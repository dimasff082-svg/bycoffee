<?php

namespace Database\Seeders;

use App\Models\RawMaterial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RawMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = User::where('role', 'admin')->value('id') ?? User::first()?->id;

        $materials = [
            [
                'code' => 'BHN-001',
                'name' => 'Biji Kopi Arabika House Blend',
                'category' => 'Biji Kopi',
                'unit' => 'gram',
                'current_stock' => 15000,
                'min_stock' => 3000,
                'unit_price' => 250,
                'notes' => 'Blend Arabika Gayo & Mandheling roast medium dark',
            ],
            [
                'code' => 'BHN-002',
                'name' => 'Biji Kopi Robusta Premium',
                'category' => 'Biji Kopi',
                'unit' => 'gram',
                'current_stock' => 10000,
                'min_stock' => 2000,
                'unit_price' => 120,
                'notes' => 'Robusta Dampit / Temanggung body tebal',
            ],
            [
                'code' => 'BHN-003',
                'name' => 'Fresh Milk Diamond / Greenfields',
                'category' => 'Susu & Dairy',
                'unit' => 'liter',
                'current_stock' => 45,
                'min_stock' => 12,
                'unit_price' => 21000,
                'notes' => 'Susu segar pasteurisasi untuk cappuccino & latte',
            ],
            [
                'code' => 'BHN-004',
                'name' => 'Susu Evaporasi F&N',
                'category' => 'Susu & Dairy',
                'unit' => 'kaleng',
                'current_stock' => 24,
                'min_stock' => 6,
                'unit_price' => 15500,
                'notes' => 'Campuran kopi susu aren & thai tea gurih',
            ],
            [
                'code' => 'BHN-005',
                'name' => 'Gula Aren Cair Organik',
                'category' => 'Sirup & Pemanis',
                'unit' => 'liter',
                'current_stock' => 18,
                'min_stock' => 5,
                'unit_price' => 38000,
                'notes' => 'Gula aren nira murni tanpa pengawet',
            ],
            [
                'code' => 'BHN-006',
                'name' => 'Sirup Karamel Monin / Toffin',
                'category' => 'Sirup & Pemanis',
                'unit' => 'botol',
                'current_stock' => 6,
                'min_stock' => 2,
                'unit_price' => 145000,
                'notes' => 'Botol 750ml untuk Caramel Macchiato',
            ],
            [
                'code' => 'BHN-007',
                'name' => 'Sirup Vanilla Toffin',
                'category' => 'Sirup & Pemanis',
                'unit' => 'botol',
                'current_stock' => 5,
                'min_stock' => 2,
                'unit_price' => 140000,
                'notes' => 'Botol 750ml untuk Vanilla Latte',
            ],
            [
                'code' => 'BHN-008',
                'name' => 'Matcha Green Tea Powder',
                'category' => 'Bubuk Minuman',
                'unit' => 'gram',
                'current_stock' => 2500,
                'min_stock' => 500,
                'unit_price' => 180,
                'notes' => 'Pure Japanese Uji Matcha powder tanpa gula',
            ],
            [
                'code' => 'BHN-009',
                'name' => 'Cup Plastik Sablon BY Coffee 16oz',
                'category' => 'Packaging & Cup',
                'unit' => 'pcs',
                'current_stock' => 850,
                'min_stock' => 200,
                'unit_price' => 650,
                'notes' => 'Cup ice takeaway logo resmi BY Coffee',
            ],
            [
                'code' => 'BHN-010',
                'name' => 'Paper Cup Hot 8oz',
                'category' => 'Packaging & Cup',
                'unit' => 'pcs',
                'current_stock' => 300,
                'min_stock' => 100,
                'unit_price' => 550,
                'notes' => 'Cup panas espresso / piccolo / hot latte',
            ],
            [
                'code' => 'BHN-011',
                'name' => 'Sedotan Pipih Steril Hitam',
                'category' => 'Packaging & Cup',
                'unit' => 'pcs',
                'current_stock' => 1200,
                'min_stock' => 300,
                'unit_price' => 120,
                'notes' => 'Sedotan steril bungkus kertas higienis',
            ],
            [
                'code' => 'BHN-012',
                'name' => 'Kantong Plastik Takeaway 1 Cup',
                'category' => 'Packaging & Cup',
                'unit' => 'pcs',
                'current_stock' => 500,
                'min_stock' => 150,
                'unit_price' => 200,
                'notes' => 'Kresek take-away bening ramah lingkungan',
            ],
        ];

        DB::beginTransaction();
        try {
            foreach ($materials as $item) {
                $material = RawMaterial::updateOrCreate(
                    ['code' => $item['code']],
                    [
                        'name' => $item['name'],
                        'category' => $item['category'],
                        'unit' => $item['unit'],
                        'current_stock' => $item['current_stock'],
                        'min_stock' => $item['min_stock'],
                        'unit_price' => $item['unit_price'],
                        'notes' => $item['notes'],
                    ]
                );

                // Tambahkan mutasi saldo awal jika belum pernah ada mutasi
                if ($material->mutations()->count() === 0) {
                    $material->mutations()->create([
                        'user_id' => $userId,
                        'type' => 'in',
                        'quantity' => $item['current_stock'],
                        'stock_before' => 0,
                        'stock_after' => $item['current_stock'],
                        'reference_number' => 'SALDO-AWAL',
                        'notes' => 'Saldo Awal Stok Master Bahan Baku BY Coffee',
                    ]);
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
