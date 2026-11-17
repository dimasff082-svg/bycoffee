<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\RawMaterial;
use App\Models\RawMaterialMutation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RawMaterialInventoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /**
     * Staf Warehouse, Admin, dan Owner dapat mengakses halaman katalog inventori bahan baku.
     */
    public function test_authorized_users_can_view_raw_materials_index(): void
    {
        $admin = User::factory()->admin()->create();
        $warehouse = User::factory()->warehouse()->create();
        $owner = User::factory()->owner()->create();

        RawMaterial::create([
            'code' => 'BHN-001',
            'name' => 'Biji Kopi Arabika',
            'category' => 'Biji Kopi',
            'unit' => 'gram',
            'current_stock' => 5000,
            'min_stock' => 1000,
            'unit_price' => 250,
        ]);

        $this->actingAs($warehouse)
            ->get(route('warehouse.raw_materials.index'))
            ->assertOk()
            ->assertSee('Biji Kopi Arabika')
            ->assertSee('BHN-001');

        $this->actingAs($admin)
            ->get(route('warehouse.raw_materials.index'))
            ->assertOk();

        $this->actingAs($owner)
            ->get(route('warehouse.raw_materials.index'))
            ->assertOk();
    }

    /**
     * Pengguna tanpa role warehouse/admin/owner ditolak aksesnya (403 atau redirect).
     */
    public function test_unauthorized_user_cannot_view_raw_materials_index(): void
    {
        $cashier = User::factory()->cashier()->create();

        $this->actingAs($cashier)
            ->get(route('warehouse.raw_materials.index'))
            ->assertForbidden();
    }

    /**
     * Menambahkan bahan baku baru dengan saldo awal otomatis mencatat mutasi 'in'.
     */
    public function test_can_create_raw_material_with_initial_stock(): void
    {
        $warehouse = User::factory()->warehouse()->create();

        $response = $this->actingAs($warehouse)
            ->post(route('warehouse.raw_materials.store'), [
                'name' => 'Susu Segar Greenfield',
                'category' => 'Susu & Dairy',
                'unit' => 'liter',
                'initial_stock' => 20,
                'min_stock' => 5,
                'unit_price' => 21000,
                'notes' => 'Penyimpanan chiller bar',
            ]);

        $response->assertRedirect(route('warehouse.raw_materials.index'));
        $response->assertSessionHas('success');

        $material = RawMaterial::where('name', 'Susu Segar Greenfield')->first();
        $this->assertNotNull($material);
        $this->assertEquals('BHN-001', $material->code);
        $this->assertEquals(20, (float) $material->current_stock);
        $this->assertEquals(21000, (float) $material->unit_price);

        // Verifikasi mutasi kartu stok awal
        $mutation = RawMaterialMutation::where('raw_material_id', $material->id)->first();
        $this->assertNotNull($mutation);
        $this->assertEquals('in', $mutation->type);
        $this->assertEquals(20, (float) $mutation->quantity);
        $this->assertEquals('SALDO-AWAL', $mutation->reference_number);
    }

    /**
     * Restock cepat menambah stok fisik dan mencatat mutasi 'in'.
     */
    public function test_can_restock_raw_material(): void
    {
        $warehouse = User::factory()->warehouse()->create();
        $material = RawMaterial::create([
            'code' => 'BHN-001',
            'name' => 'Sirup Vanilla',
            'category' => 'Sirup & Pemanis',
            'unit' => 'botol',
            'current_stock' => 3,
            'min_stock' => 2,
            'unit_price' => 140000,
        ]);

        $response = $this->actingAs($warehouse)
            ->post(route('warehouse.raw_materials.restock', $material->id), [
                'quantity' => 5,
                'unit_price' => 145000,
                'reference_number' => 'NOTA-SUPPLIER-01',
                'notes' => 'Restock mingguan supplier',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $material->refresh();
        $this->assertEquals(8, (float) $material->current_stock);
        $this->assertEquals(145000, (float) $material->unit_price);

        $this->assertDatabaseHas('raw_material_mutations', [
            'raw_material_id' => $material->id,
            'type' => 'in',
            'quantity' => 5,
            'stock_before' => 3,
            'stock_after' => 8,
            'reference_number' => 'NOTA-SUPPLIER-01',
        ]);
    }

    /**
     * Catat pemakaian bar/dapur mengurangi stok fisik dan mencatat mutasi 'out'.
     */
    public function test_can_record_usage(): void
    {
        $warehouse = User::factory()->warehouse()->create();
        $material = RawMaterial::create([
            'code' => 'BHN-001',
            'name' => 'Biji Kopi Robusta',
            'category' => 'Biji Kopi',
            'unit' => 'gram',
            'current_stock' => 1000,
            'min_stock' => 200,
            'unit_price' => 120,
        ]);

        $response = $this->actingAs($warehouse)
            ->post(route('warehouse.raw_materials.usage', $material->id), [
                'quantity' => 300,
                'usage_type' => 'bar_usage',
                'notes' => 'Pembuatan cold brew batch pagi',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $material->refresh();
        $this->assertEquals(700, (float) $material->current_stock);

        $this->assertDatabaseHas('raw_material_mutations', [
            'raw_material_id' => $material->id,
            'type' => 'out',
            'quantity' => 300,
            'stock_before' => 1000,
            'stock_after' => 700,
        ]);
    }

    /**
     * Pemakaian melebihi sisa stok fisik ditolak sistem.
     */
    public function test_usage_exceeding_current_stock_is_rejected(): void
    {
        $warehouse = User::factory()->warehouse()->create();
        $material = RawMaterial::create([
            'code' => 'BHN-001',
            'name' => 'Gula Aren Cair',
            'category' => 'Sirup & Pemanis',
            'unit' => 'liter',
            'current_stock' => 2,
            'min_stock' => 1,
            'unit_price' => 35000,
        ]);

        $response = $this->actingAs($warehouse)
            ->post(route('warehouse.raw_materials.usage', $material->id), [
                'quantity' => 5, // melebihi stok yang hanya 2
                'usage_type' => 'bar_usage',
                'notes' => 'Tes pemakaian lebih dari stok',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('quantity');

        $material->refresh();
        $this->assertEquals(2, (float) $material->current_stock);
    }

    /**
     * Stock opname fisik menyesuaikan saldo fisik aktual dan mencatat mutasi 'adjustment'.
     */
    public function test_stock_opname_adjusts_physical_stock(): void
    {
        $warehouse = User::factory()->warehouse()->create();
        $material = RawMaterial::create([
            'code' => 'BHN-001',
            'name' => 'Cup Plastik 16oz',
            'category' => 'Packaging & Cup',
            'unit' => 'pcs',
            'current_stock' => 150,
            'min_stock' => 50,
            'unit_price' => 600,
        ]);

        $response = $this->actingAs($warehouse)
            ->post(route('warehouse.raw_materials.opname', $material->id), [
                'actual_stock' => 140, // selisih -10
                'notes' => 'Hasil audit akhir pekan, 10 cup penyok',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $material->refresh();
        $this->assertEquals(140, (float) $material->current_stock);

        $this->assertDatabaseHas('raw_material_mutations', [
            'raw_material_id' => $material->id,
            'type' => 'adjustment',
            'quantity' => -10,
            'stock_before' => 150,
            'stock_after' => 140,
        ]);
    }

    /**
     * Integrasi nota belanja pengeluaran gudang otomatis menambah stok bahan baku.
     */
    public function test_recording_expense_with_raw_material_auto_restocks_inventory(): void
    {
        $warehouse = User::factory()->warehouse()->create();
        $material = RawMaterial::create([
            'code' => 'BHN-001',
            'name' => 'Biji Kopi Arabika House Blend',
            'category' => 'Biji Kopi',
            'unit' => 'gram',
            'current_stock' => 2000,
            'min_stock' => 1000,
            'unit_price' => 200,
        ]);

        // Catat pengeluaran pembelian bahan baku 1000 gram seharga Rp 250.000
        $response = $this->actingAs($warehouse)
            ->post(route('warehouse.expenses.store'), [
                'expense_type' => 'raw_material',
                'raw_material_id' => $material->id,
                'raw_material_quantity' => 1000,
                'title' => 'Pembelian Biji Kopi Arabika Gayo',
                'category' => 'Bahan Baku Minuman & Kopi',
                'amount' => 250000,
                'expense_date' => now()->toDateString(),
                'payment_method' => 'cash',
                'notes' => 'Beli di roastery lokal',
            ]);

        $response->assertRedirect(route('warehouse.expenses.index'));
        $response->assertSessionHas('success');

        $material->refresh();
        // Stok harus bertambah dari 2000 menjadi 3000
        $this->assertEquals(3000, (float) $material->current_stock);
        // HPP unit_price harus terupdate: 250.000 / 1000 = 250
        $this->assertEquals(250, (float) $material->unit_price);

        $expense = Expense::where('raw_material_id', $material->id)->first();
        $this->assertNotNull($expense);

        // Verifikasi mutasi kartu stok otomatis dibuat
        $this->assertDatabaseHas('raw_material_mutations', [
            'raw_material_id' => $material->id,
            'type' => 'in',
            'quantity' => 1000,
            'stock_before' => 2000,
            'stock_after' => 3000,
            'reference_number' => $expense->expense_number,
        ]);

        // Hapus nota belanja (misal salah input) -> stok harus otomatis di-revert kembali ke 2000
        $delResponse = $this->actingAs($warehouse)
            ->delete(route('warehouse.expenses.destroy', $expense->id));

        $delResponse->assertRedirect(route('warehouse.expenses.index'));
        $material->refresh();
        $this->assertEquals(2000, (float) $material->current_stock);
    }
}
