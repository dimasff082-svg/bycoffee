<?php

namespace Tests\Feature;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReceiptNoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_pos_receipt_displays_note_line(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $sale = Sale::factory()->create([
            'notes' => 'Tolong pisahkan gula ya kak',
        ]);

        $response = $this->actingAs($admin)->get(route('pos.receipt', $sale->id));

        $response->assertStatus(200);
        $response->assertSee('NOTE:');
        $response->assertSee('Tolong pisahkan gula ya kak');
    }

    public function test_pos_receipt_displays_dash_when_note_empty(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $sale = Sale::factory()->create([
            'notes' => null,
        ]);

        $response = $this->actingAs($admin)->get(route('pos.receipt', $sale->id));

        $response->assertStatus(200);
        $response->assertSee('NOTE:');
    }

    public function test_kiosk_receipt_displays_note_line(): void
    {
        $sale = Sale::factory()->create([
            'notes' => 'Pesanan tanpa sedotan plastik',
        ]);

        $response = $this->get(route('kiosk.receipt', $sale->id));

        $response->assertStatus(200);
        $response->assertSee('NOTE:');
        $response->assertSee('Pesanan tanpa sedotan plastik');
    }

    public function test_pos_receipt_has_whatsapp_share_button_with_customer_phone(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $sale = Sale::factory()->create([
            'customer_phone' => '081234567890',
            'notes' => 'Catatan struk WA',
        ]);

        $response = $this->actingAs($admin)->get(route('pos.receipt', $sale->id));

        $response->assertStatus(200);
        $response->assertSee('Kirim Struk WA');
        $response->assertSee('081234567890');
        $response->assertSee('whatsappReceiptModal');
    }

    public function test_takeaway_index_has_whatsapp_receipt_buttons(): void
    {
        $cashier = User::factory()->create([
            'role' => 'cashier',
        ]);

        $sale = Sale::factory()->create([
            'table_number' => 'TAKEAWAY',
            'customer_phone' => '08987654321',
        ]);

        $response = $this->actingAs($cashier)->get(route('takeaway.index'));

        $response->assertStatus(200);
        $response->assertSee('modalWaReceiptLink');
        $response->assertSee('Kirim WA');
        $response->assertSee(route('pos.receipt', $sale->id).'?auto_wa=1');
    }
}
