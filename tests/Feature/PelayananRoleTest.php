<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Salary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PelayananRoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /**
     * Staf Pelayanan dapat mengakses halaman daftar menu dalam mode pure read-only.
     */
    public function test_pelayanan_can_access_read_only_menu_page(): void
    {
        $pelayanan = User::factory()->pelayanan()->create();
        $category = Category::factory()->create(['name' => 'Minuman Kopi']);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Caramel Macchiato',
            'sku' => 'KOP-001',
            'selling_price' => 28000,
            'is_active' => true,
        ]);

        $response = $this->actingAs($pelayanan)
            ->get(route('pelayanan.menu.index'));

        $response->assertOk()
            ->assertSee('Caramel Macchiato')
            ->assertSee('KOP-001')
            ->assertSee('Minuman Kopi')
            ->assertSee('Rp 28.000')
            ->assertSee('Tersedia')
            // Memastikan murni read-only: TIDAK ada aksi tambah, edit, atau hapus menu
            ->assertDontSee('Tambah Menu Baru')
            ->assertDontSee('Kelola Open Bill')
            ->assertDontSee('Transaksi Takeaway');
    }

    /**
     * Staf Pelayanan DITOLAK (403) jika mencoba mengakses manajemen produk/menu admin (CRUD).
     */
    public function test_pelayanan_cannot_access_admin_product_management(): void
    {
        $pelayanan = User::factory()->pelayanan()->create();

        $this->actingAs($pelayanan)
            ->get(route('admin.products.index'))
            ->assertForbidden();

        $this->actingAs($pelayanan)
            ->get(route('admin.products.create'))
            ->assertForbidden();

        $this->actingAs($pelayanan)
            ->post(route('admin.products.store'), [
                'name' => 'Unauthorized Drink',
                'selling_price' => 20000,
            ])
            ->assertForbidden();
    }

    /**
     * Staf Pelayanan DITOLAK (403) jika mencoba mengakses modul kasir (POS, Takeaway, Open Bill).
     */
    public function test_pelayanan_cannot_access_cashier_features(): void
    {
        $pelayanan = User::factory()->pelayanan()->create();

        $this->actingAs($pelayanan)
            ->get(route('cashier.dashboard'))
            ->assertForbidden();

        $this->actingAs($pelayanan)
            ->get(route('takeaway.index'))
            ->assertForbidden();

        $this->actingAs($pelayanan)
            ->get(route('cashier.open_bills.index'))
            ->assertForbidden();
    }

    /**
     * Staf Pelayanan DITOLAK (403) jika mencoba mengakses modul gudang/pengeluaran.
     */
    public function test_pelayanan_cannot_access_warehouse_expenses(): void
    {
        $pelayanan = User::factory()->pelayanan()->create();

        $this->actingAs($pelayanan)
            ->get(route('warehouse.expenses.index'))
            ->assertForbidden();
    }

    /**
     * Staf Pelayanan dapat mengakses halaman presensi mandiri dan melakukan absensi.
     */
    public function test_pelayanan_can_access_and_submit_attendance(): void
    {
        $pelayanan = User::factory()->pelayanan()->create();

        // 1. Buka halaman absensi
        $this->actingAs($pelayanan)
            ->get(route('attendance.index'))
            ->assertOk()
            ->assertSee('Presensi Kehadiran Karyawan');

        // 2. Kirim presensi masuk dengan koordinat valid kantor dan snapshot kamera
        $fakeBase64Image = 'data:image/jpeg;base64,'.base64_encode('fake-camera-snapshot-content');

        $response = $this->actingAs($pelayanan)
            ->post(route('attendance.store'), [
                'type' => 'check_in',
                'latitude' => -5.155325,
                'longitude' => 119.441929,
                'photo_camera' => $fakeBase64Image,
                'notes' => 'Shift pagi staf pelayanan',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('attendances', [
            'user_id' => $pelayanan->id,
            'type' => 'check_in',
            'notes' => 'Shift pagi staf pelayanan',
        ]);
    }

    /**
     * Staf Pelayanan dapat mengakses halaman slip gaji mandiri.
     */
    public function test_pelayanan_can_access_my_salaries(): void
    {
        $pelayanan = User::factory()->pelayanan()->create();

        Salary::create([
            'payroll_number' => 'SLIP-PEL-001',
            'user_id' => $pelayanan->id,
            'period_month' => 'November 2026',
            'basic_salary' => 3000000,
            'allowance' => 200000,
            'incentive' => 150000,
            'deductions' => 0,
            'net_salary' => 3350000,
            'payment_date' => now()->toDateString(),
            'payment_status' => 'paid',
            'payment_method' => 'transfer',
            'notes' => 'Gaji pokok pramusaji',
        ]);

        $response = $this->actingAs($pelayanan)
            ->get(route('my_salaries.index'));

        $response->assertOk()
            ->assertSee('SLIP-PEL-001')
            ->assertSee('November 2026')
            ->assertSee('Rp 3.350.000');
    }

    /**
     * Staf Pelayanan dialihkan ke halaman menu saat login atau mengakses root '/'.
     */
    public function test_pelayanan_redirected_to_menu_page(): void
    {
        $pelayanan = User::factory()->pelayanan()->create([
            'password' => bcrypt('password123'),
        ]);

        // Login flow
        $response = $this->post(route('login'), [
            'email' => $pelayanan->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('pelayanan.menu.index'));

        // Root '/' access flow
        $this->actingAs($pelayanan)
            ->get('/')
            ->assertRedirect(route('pelayanan.menu.index'));
    }

    /**
     * Administrator dapat mendaftarkan dan memperbarui user dengan role pelayanan.
     */
    public function test_admin_can_manage_user_with_pelayanan_role(): void
    {
        $admin = User::factory()->admin()->create();

        // 1. Admin mendaftarkan staf pelayanan baru
        $response = $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Dimas Pramusaji',
                'email' => 'dimas.waiter@pos.test',
                'role' => 'pelayanan',
                'password' => 'password123',
            ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'Dimas Pramusaji',
            'email' => 'dimas.waiter@pos.test',
            'role' => 'pelayanan',
        ]);

        $user = User::where('email', 'dimas.waiter@pos.test')->first();
        $this->assertTrue($user->isPelayanan());
        $this->assertEquals('PELAYANAN', $user->roleLabel());

        // 2. Admin mengedit user
        $updateResponse = $this->actingAs($admin)
            ->put(route('admin.users.update', $user->id), [
                'name' => 'Dimas Senior Server',
                'email' => 'dimas.waiter@pos.test',
                'role' => 'pelayanan',
            ]);

        $updateResponse->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Dimas Senior Server',
            'role' => 'pelayanan',
        ]);
    }
}
