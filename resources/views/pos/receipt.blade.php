@extends('layouts.app')

@push('styles')
<style>
@page {
    size: 58mm auto;
    margin: 0;
}
@media print {
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    html, body {
        width: 58mm !important;
        min-width: 58mm !important;
        max-width: 58mm !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #ffffff !important;
    }
    body * {
        visibility: hidden;
    }
    #printable-receipt, #printable-receipt * {
        visibility: visible;
    }
    #printable-receipt {
        position: absolute;
        left: 0;
        top: 0;
        width: 58mm !important;
        max-width: 58mm !important;
        min-width: 58mm !important;
        margin: 0 !important;
        padding: 1.5mm 1.5mm !important; /* Margin sangat sedikit sesuai ukuran thermal 58mm */
        box-shadow: none !important;
        border: none !important;
        border-radius: 0 !important;
        background: #ffffff !important;
        color: #000000 !important;
        box-sizing: border-box !important;
    }
    .no-print {
        display: none !important;
    }
}
</style>
@endpush

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <!-- Top Actions / Nav -->
    <div class="flex items-center justify-between no-print">
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </span>
            <div>
                <h2 class="text-xl font-black text-slate-800">Transaksi Berhasil!</h2>
                <p class="text-xs text-slate-500">Struk digital 58mm telah dibuat dan tersimpan aman di sistem.</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <div class="flex items-center gap-1.5">
                <button type="button" onclick="handlePrint()"
                    class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-bold px-4 py-2.5 rounded-xl shadow-xs transition cursor-pointer">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak Struk</span>
                </button>
                <button type="button" onclick="openPrintModeModal()" title="Pilihan Tipe Printer (Bluetooth / USB)"
                    class="p-2.5 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-400 hover:text-slate-600 shadow-xs transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>
            @if(auth()->user()->isCashier() || auth()->user()->isAdmin())
                <a href="{{ route('takeaway.index') }}"
                    class="inline-flex items-center gap-2 bg-[#c59b27] hover:bg-[#a87f1c] text-stone-950 text-xs font-extrabold px-4 py-2.5 rounded-xl shadow-md transition transform active:scale-95">
                    <svg class="w-4 h-4 text-stone-950" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span>Pesanan Bawa Pulang Baru</span>
                </a>
            @else
                <a href="{{ route('pos.index') }}"
                    class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md shadow-emerald-600/20 transition transform active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Transaksi Baru
                </a>
            @endif
        </div>
    </div>

    <!-- Digital Receipt Card (Ukuran 58mm dengan margin minimal) -->
    <div id="printable-receipt" class="bg-white rounded-2xl border border-slate-200/90 shadow-xl p-3.5 sm:p-4 font-sans w-full max-w-[340px] mx-auto text-slate-800">
        <!-- Store Header -->
        <div class="text-center pb-3 border-b border-dashed border-slate-300">
            <img src="{{ $storeLogo }}" alt="{{ $storeName }}" class="w-11 h-11 mx-auto object-contain mb-1.5 drop-shadow-xs" onerror="this.src='{{ asset('images/default-cake.png') }}'">
            <h3 class="font-extrabold text-sm text-slate-900 tracking-tight uppercase leading-tight">
                <span>{{ $storeNameLine1 }}</span>
                @if(!empty($storeNameLine2))
                    <span class="block text-xs font-bold text-slate-700 mt-0.5">{{ $storeNameLine2 }}</span>
                @endif
            </h3>
            <p class="text-[9.5px] text-slate-500 mt-0.5 leading-snug">{{ $storeAddress }}</p>
            <p class="text-[9.5px] font-semibold text-slate-700 mt-0.5">Telp / WA: {{ $storePhone }}</p>
            <p class="text-[9px] text-slate-400 mt-0.5">Struk Resmi Pembelian &bull; Jam: {{ $storeHours }}</p>
        </div>

        <!-- Meta Info -->
        @php
            $isOpenBill = (bool) ($sale->open_bill_id && $sale->openBill);
            $receiptSales = $isOpenBill ? $sale->openBill->sales : collect([$sale]);
            $receiptItems = $isOpenBill ? $sale->openBill->sales->flatMap->items : $sale->items;
            $receiptTotal = $isOpenBill ? ($sale->openBill->total_amount > 0 ? $sale->openBill->total_amount : $receiptSales->sum('total_amount')) : $sale->total_amount;
            $receiptSubtotal = $isOpenBill ? $receiptSales->sum('subtotal') : $sale->subtotal;
            $receiptPayment = $sale->payment ?? $sale->openBill?->sales->pluck('payment')->filter()->first();
            $receiptCashier = $sale->openBill?->closer?->name ?? ($sale->openBill?->opener?->name ?? ($sale->confirmer?->name ?? ($sale->user?->name ?? 'Kasir Resto')));
            $receiptNotes = $isOpenBill ? ($sale->openBill->notes ?: $receiptSales->pluck('notes')->filter()->implode(', ')) : $sale->notes;
        @endphp

        @if($isOpenBill)
            <div class="my-2 px-2.5 py-1.5 rounded-xl bg-amber-50 border border-amber-200 text-center font-mono text-[10px] text-amber-900">
                <span class="font-bold uppercase tracking-wider block">Nota Tagihan Open Bill</span>
                <span>Meja: <strong>{{ $sale->openBill->table_number }}</strong> &bull; {{ $receiptSales->count() }} Order Tergabung</span>
            </div>
        @endif

        <div class="py-2.5 border-b border-dashed border-slate-300 text-[10px] space-y-1 font-mono">
            <div class="flex justify-between">
                <span class="text-slate-400">No. {{ $isOpenBill ? 'Bill / Sesi' : 'Transaksi' }}</span>
                <span class="font-bold text-slate-900">{{ $isOpenBill ? $sale->openBill->bill_number : $sale->transaction_number }}</span>
            </div>
            @if($isOpenBill)
                <div class="flex justify-between">
                    <span class="text-slate-400">Daftar Order</span>
                    <span class="text-slate-700 font-bold truncate max-w-[170px] text-right">{{ $receiptSales->pluck('transaction_number')->implode(', ') }}</span>
                </div>
            @endif
            <div class="flex justify-between">
                <span class="text-slate-400">Tipe / Meja</span>
                <span class="text-slate-700 font-bold">
                    @if($sale->table_number === 'TAKEAWAY')
                        <span class="text-purple-700 font-black">Bawa Pulang (Takeaway)</span>
                    @elseif($sale->table_number === 'ONLINE')
                        <span class="text-blue-700 font-black">Pesanan Online</span>
                    @elseif($sale->table_number === 'KIOSK')
                        <span class="text-indigo-700 font-black">Kiosk Mandiri</span>
                    @elseif($sale->table_number)
                        Meja {{ $sale->table_number }}
                    @else
                        Non-Meja
                    @endif
                </span>
            </div>
            @if($sale->customer_name || $sale->openBill?->customer_name)
                <div class="flex justify-between">
                    <span class="text-slate-400">Pelanggan</span>
                    <span class="text-slate-700 font-semibold truncate max-w-[170px] text-right">{{ $sale->customer_name ?: $sale->openBill->customer_name }}</span>
                </div>
            @endif
            @if($sale->customer_phone)
                <div class="flex justify-between">
                    <span class="text-slate-400">No. HP Pelanggan</span>
                    <span class="text-slate-700 font-semibold">{{ $sale->customer_phone }}</span>
                </div>
            @endif
            <div class="flex justify-between">
                <span class="text-slate-400">Waktu</span>
                <span class="text-slate-700">{{ ($sale->openBill?->closed_at ?? $sale->transaction_date)->format('d/m/Y H:i:s') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Kasir</span>
                <span class="text-slate-700 font-semibold truncate max-w-[170px] text-right">{{ $receiptCashier }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Status</span>
                <span class="text-emerald-600 font-bold uppercase tracking-wider">Lunas</span>
            </div>
        </div>

        <!-- Items Table (Format Ringkas 58mm) -->
        <div class="py-2.5 border-b border-dashed border-slate-300">
            <table class="w-full text-[10px] leading-tight">
                <thead>
                    <tr class="text-slate-400 text-left font-mono border-b border-slate-100 pb-1">
                        <th class="pb-1 text-left font-bold">Item</th>
                        <th class="pb-1 text-center font-bold w-6">Qty</th>
                        <th class="pb-1 text-right font-bold w-12">Harga</th>
                        <th class="pb-1 text-right font-bold w-14">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-mono">
                    @foreach($receiptItems as $item)
                        <tr>
                            <td class="py-1.5 pr-1">
                                <div class="font-bold text-slate-900 leading-tight">{{ $item->product_name_snapshot }}</div>
                                <div class="text-[8.5px] text-slate-400">{{ $item->sku_snapshot }}</div>
                            </td>
                            <td class="py-1.5 text-center text-slate-700 align-top">{{ $item->quantity }}</td>
                            <td class="py-1.5 text-right text-slate-600 align-top">{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="py-1.5 text-right font-bold text-slate-900 align-top">{{ number_format($item->line_total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary Totals (58mm) -->
        <div class="py-2.5 border-b border-dashed border-slate-300 text-[10px] font-mono space-y-1.5">
            <div class="flex justify-between text-slate-600">
                <span>Subtotal Item</span>
                <span>Rp {{ number_format($receiptSubtotal, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between text-xs font-black text-slate-900 pt-1.5 border-t border-slate-100">
                <span>TOTAL TAGIHAN</span>
                <span class="text-emerald-700 text-sm">Rp {{ number_format($receiptTotal, 0, ',', '.') }}</span>
            </div>

            <div class="pt-1.5 border-t border-slate-100 space-y-1">
                <div class="flex justify-between text-slate-600">
                    <span>Metode Bayar</span>
                    <span class="font-bold uppercase">{{ $receiptPayment?->methodLabel() ?? 'TUNAI' }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Uang Diterima</span>
                    <span>Rp {{ number_format($receiptPayment->amount_tendered ?? $receiptTotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-bold text-slate-900">
                    <span>Uang Kembalian</span>
                    <span>Rp {{ number_format($receiptPayment->change_amount ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-start justify-between gap-2 pt-1.5 border-t border-slate-100 text-slate-800">
                    <span class="font-bold text-slate-900 shrink-0">NOTE:</span>
                    <span class="text-right font-semibold break-words max-w-[200px]">{{ $receiptNotes ?: '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Footer Thank You Note -->
        <div class="text-center pt-3.5 space-y-0.5 text-[9px] text-slate-400">
            <p class="font-bold text-slate-700">Terima kasih atas kunjungan Anda!</p>
            <p class="text-[8.5px] font-semibold text-slate-600">Hubungi Toko: {{ $storePhone }}</p>
            <p class="text-[8px] leading-tight text-slate-400">Barang yang sudah dibeli tidak dapat ditukar/dikembalikan tanpa bukti struk ini.</p>
        </div>
    </div>
</div>

<!-- Modal Pilihan Metode Cetak (Bluetooth vs USB/Sistem) -->
<div id="printModeModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs hidden no-print">
    <div class="bg-white rounded-2xl max-w-sm w-full p-5 shadow-2xl border border-slate-100 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div class="flex items-center gap-2">
                <span class="p-2 rounded-xl bg-amber-50 text-amber-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                </span>
                <div>
                    <h4 class="font-bold text-slate-800 text-sm">Metode Cetak Struk</h4>
                    <p class="text-[11px] text-slate-500">Pilih koneksi printer thermal 58mm Anda</p>
                </div>
            </div>
            <button type="button" onclick="closePrintModeModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="space-y-2.5">
            <button type="button" onclick="selectPrintMode('bluetooth')"
                class="w-full text-left p-3.5 rounded-xl border border-slate-200 hover:border-amber-500 hover:bg-amber-50/40 transition group flex items-start gap-3 cursor-pointer">
                <div class="p-2 rounded-lg bg-blue-50 text-blue-600 group-hover:bg-amber-500 group-hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v18l7-5-7-5 7-5-7-3z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-xs text-slate-800">Bluetooth Thermal</span>
                        <span class="text-[9.5px] px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold">Langsung Web</span>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">
                        Langsung cetak via Bluetooth dari Chrome / Edge tanpa install aplikasi atau driver (Laptop & HP).
                    </p>
                </div>
            </button>

            <button type="button" onclick="selectPrintMode('system')"
                class="w-full text-left p-3.5 rounded-xl border border-slate-200 hover:border-amber-500 hover:bg-amber-50/40 transition group flex items-start gap-3 cursor-pointer">
                <div class="p-2 rounded-lg bg-slate-100 text-slate-700 group-hover:bg-amber-500 group-hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-xs text-slate-800">Kabel USB / Driver Sistem</span>
                        <span class="text-[9.5px] px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 font-bold">Dialog Windows</span>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">
                        Cetak lewat kabel USB atau driver printer thermal yang terpasang di sistem operasi laptop/PC.
                    </p>
                </div>
            </button>
        </div>

        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <label class="inline-flex items-center gap-1.5 cursor-pointer text-[11px]">
                <input type="checkbox" id="rememberPrintMode" checked class="rounded text-amber-600 focus:ring-amber-500">
                <span>Ingat pilihan saya</span>
            </label>
            <button type="button" onclick="closePrintModeModal()" class="text-[11px] font-bold text-slate-500 hover:text-slate-800 cursor-pointer">
                Batal
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const receiptData = {
        storeNameLine1: @json($storeNameLine1),
        storeNameLine2: @json($storeNameLine2 ?? ''),
        storeAddress: @json($storeAddress ?? ''),
        storePhone: @json($storePhone ?? ''),
        storeHours: @json($storeHours ?? ''),
        trxNumber: @json($isOpenBill ? $sale->openBill->bill_number : $sale->transaction_number),
        tableNumber: @json($sale->table_number ?? 'M001'),
        customerName: @json($sale->customer_name ?: ($sale->openBill?->customer_name ?? '')),
        customerPhone: @json($sale->customer_phone ?? ''),
        trxTime: @json(($sale->openBill?->closed_at ?? $sale->transaction_date)->format('d/m/Y H:i:s')),
        cashierName: @json($receiptCashier),
        isPaid: @json($sale->isPaid()),
        paymentMethod: @json($receiptPayment?->methodLabel() ?? 'TUNAI'),
        subtotal: @json((float)$receiptSubtotal),
        total: @json((float)$receiptTotal),
        amountTendered: @json((float)($receiptPayment->amount_tendered ?? $receiptTotal)),
        changeAmount: @json((float)($receiptPayment->change_amount ?? 0)),
        note: @json($receiptNotes ?: '-'),
        items: [
            @foreach($receiptItems as $item)
            {
                name: @json($item->product_name_snapshot),
                sku: @json($item->sku_snapshot ?? ''),
                quantity: @json((int)$item->quantity),
                price: @json((float)$item->unit_price),
                total: @json((float)$item->line_total)
            },
            @endforeach
        ]
    };

    function formatRupiah(num) {
        return new Intl.NumberFormat('id-ID').format(Math.round(num || 0));
    }

    function textToBytes(str) {
        const encoder = new TextEncoder();
        return Array.from(encoder.encode(str));
    }

    function padLine(left, right, width = 32) {
        const l = String(left || '');
        const r = String(right || '');
        const spaceCount = Math.max(1, width - l.length - r.length);
        return l + ' '.repeat(spaceCount) + r;
    }

    function buildEscPosBytes(data) {
        const ESC = 0x1B;
        const GS = 0x1D;
        const LINE_WIDTH = 32;
        let bytes = [];

        // 1. Inisialisasi printer ESC/POS
        bytes.push(ESC, 0x40);

        // 2. Header Toko (Center Alignment)
        bytes.push(ESC, 0x61, 0x01);
        bytes.push(GS, 0x21, 0x11); // Double height & width
        bytes.push(...textToBytes((data.storeNameLine1 || 'KASIR') + "\n"));
        bytes.push(GS, 0x21, 0x00); // Normal size

        if (data.storeNameLine2) {
            bytes.push(ESC, 0x45, 0x01); // Bold on
            bytes.push(...textToBytes(data.storeNameLine2 + "\n"));
            bytes.push(ESC, 0x45, 0x00);
        }

        if (data.storeAddress) {
            bytes.push(...textToBytes(data.storeAddress + "\n"));
        }
        if (data.storePhone) {
            bytes.push(...textToBytes("Telp / WA: " + data.storePhone + "\n"));
        }
        if (data.storeHours) {
            bytes.push(...textToBytes("Jam: " + data.storeHours + "\n"));
        }

        // Pembatas garis 58mm (32 kolom)
        bytes.push(ESC, 0x61, 0x00); // Left align
        bytes.push(...textToBytes("-".repeat(LINE_WIDTH) + "\n"));

        // Metadata Transaksi
        bytes.push(...textToBytes(padLine("No. Transaksi", data.trxNumber, LINE_WIDTH) + "\n"));
        let tableLabel = data.tableNumber === 'ONLINE' ? 'Pesanan Online' : (data.tableNumber === 'TAKEAWAY' ? 'Bawa Pulang' : (data.tableNumber === 'KIOSK' ? 'Kiosk Mandiri' : 'Meja ' + data.tableNumber));
        bytes.push(...textToBytes(padLine("Tipe / Meja", tableLabel, LINE_WIDTH) + "\n"));
        if (data.customerName) {
            bytes.push(...textToBytes(padLine("Pelanggan", data.customerName, LINE_WIDTH) + "\n"));
        }
        if (data.customerPhone) {
            bytes.push(...textToBytes(padLine("No. HP", data.customerPhone, LINE_WIDTH) + "\n"));
        }
        bytes.push(...textToBytes(padLine("Waktu", data.trxTime, LINE_WIDTH) + "\n"));
        bytes.push(...textToBytes(padLine("Kasir", data.cashierName, LINE_WIDTH) + "\n"));
        bytes.push(...textToBytes(padLine("Status", data.isPaid ? "LUNAS" : "BELUM LUNAS", LINE_WIDTH) + "\n"));

        // Pembatas Menu
        bytes.push(...textToBytes("-".repeat(LINE_WIDTH) + "\n"));

        // Rincian Menu Pesanan
        (data.items || []).forEach(item => {
            bytes.push(ESC, 0x45, 0x01); // Nama item cetak tebal
            bytes.push(...textToBytes(item.name + "\n"));
            bytes.push(ESC, 0x45, 0x00);
            const qtyPrice = `  ${item.quantity} x ${formatRupiah(item.price)}`;
            const lineTotal = formatRupiah(item.total);
            bytes.push(...textToBytes(padLine(qtyPrice, lineTotal, LINE_WIDTH) + "\n"));
        });

        // Pembatas Total
        bytes.push(...textToBytes("-".repeat(LINE_WIDTH) + "\n"));

        // Subtotal & Total
        bytes.push(...textToBytes(padLine("Subtotal Item", "Rp " + formatRupiah(data.subtotal), LINE_WIDTH) + "\n"));
        bytes.push(ESC, 0x45, 0x01); // Bold
        bytes.push(GS, 0x21, 0x01);  // Double height
        bytes.push(...textToBytes(padLine("TOTAL TAGIHAN", "Rp " + formatRupiah(data.total), LINE_WIDTH) + "\n"));
        bytes.push(GS, 0x21, 0x00);  // Normal size
        bytes.push(ESC, 0x45, 0x00);

        // Informasi Pembayaran
        bytes.push(...textToBytes(padLine("Metode Bayar", data.paymentMethod, LINE_WIDTH) + "\n"));
        if (data.amountTendered > 0) {
            bytes.push(...textToBytes(padLine("Uang Diterima", "Rp " + formatRupiah(data.amountTendered), LINE_WIDTH) + "\n"));
            bytes.push(...textToBytes(padLine("Uang Kembalian", "Rp " + formatRupiah(data.changeAmount), LINE_WIDTH) + "\n"));
        }

        // Baris NOTE Struk
        const noteStr = String(data.note || "-");
        if (noteStr.length <= (LINE_WIDTH - 6)) {
            bytes.push(...textToBytes(padLine("NOTE:", noteStr, LINE_WIDTH) + "\n"));
        } else {
            bytes.push(...textToBytes("NOTE:\n" + noteStr + "\n"));
        }

        // Footer Struk
        bytes.push(ESC, 0x61, 0x01); // Center align
        bytes.push(...textToBytes("-".repeat(LINE_WIDTH) + "\n"));
        bytes.push(...textToBytes("Terima kasih atas kunjungan Anda!\n"));
        if (data.storePhone) {
            bytes.push(...textToBytes("Hubungi Toko: " + data.storePhone + "\n"));
        }
        bytes.push(...textToBytes("Barang yg sudah dibeli tdk dpt\n"));
        bytes.push(...textToBytes("ditukar tanpa bukti struk ini.\n"));

        // Feed kertas & potong kertas (cut)
        bytes.push(...textToBytes("\n\n\n\n"));
        bytes.push(GS, 0x56, 0x41, 0x03);

        return new Uint8Array(bytes);
    }

    let bluetoothDevice = null;
    let printCharacteristic = null;

    async function getBluetoothPrintCharacteristic() {
        if (bluetoothDevice && bluetoothDevice.gatt && bluetoothDevice.gatt.connected && printCharacteristic) {
            return printCharacteristic;
        }

        if (!navigator.bluetooth) {
            throw new Error("Web Bluetooth tidak didukung pada browser ini. Gunakan Google Chrome di Android.");
        }

        const knownServices = [
            '000018f0-0000-1000-8000-00805f9b34fb', // Standard Printer ESC/POS
            '49535343-fe7d-4ae5-8fa9-9fafd205e455', // ISSC Transparent Serial
            '0000ff00-0000-1000-8000-00805f9b34fb',
            '0000ae00-0000-1000-8000-00805f9b34fb',
            '0000ff01-0000-1000-8000-00805f9b34fb',
            'e7810a71-73ae-499d-8c15-faa9aef0c3f2'
        ];

        bluetoothDevice = await navigator.bluetooth.requestDevice({
            acceptAllDevices: true,
            optionalServices: knownServices
        });

        if (!bluetoothDevice) {
            throw new Error("Tidak ada perangkat Bluetooth yang dipilih.");
        }

        const server = await bluetoothDevice.gatt.connect();

        // Cari characteristic yang mendukung fungsi write
        for (const serviceUuid of knownServices) {
            try {
                const service = await server.getPrimaryService(serviceUuid);
                const chars = await service.getCharacteristics();
                for (const ch of chars) {
                    if (ch.properties.write || ch.properties.writeWithoutResponse) {
                        printCharacteristic = ch;
                        return printCharacteristic;
                    }
                }
            } catch (e) {}
        }

        // Cek seluruh primary service pada printer
        try {
            const services = await server.getPrimaryServices();
            for (const service of services) {
                const chars = await service.getCharacteristics();
                for (const ch of chars) {
                    if (ch.properties.write || ch.properties.writeWithoutResponse) {
                        printCharacteristic = ch;
                        return printCharacteristic;
                    }
                }
            }
        } catch (e) {}

        throw new Error("Layanan cetak ESC/POS tidak ditemukan pada perangkat Bluetooth ini.");
    }

    async function sendBytesToPrinter(char, bytes) {
        const CHUNK_SIZE = 64;
        for (let i = 0; i < bytes.length; i += CHUNK_SIZE) {
            const chunk = bytes.slice(i, i + CHUNK_SIZE);
            if (char.properties.writeWithoutResponse) {
                await char.writeValueWithoutResponse(chunk);
            } else {
                await char.writeValue(chunk);
            }
            await new Promise(res => setTimeout(res, 25));
        }
    }

    function openPrintModeModal() {
        const modal = document.getElementById('printModeModal');
        if (modal) modal.classList.remove('hidden');
    }

    function closePrintModeModal() {
        const modal = document.getElementById('printModeModal');
        if (modal) modal.classList.add('hidden');
    }

    async function selectPrintMode(mode) {
        const remember = document.getElementById('rememberPrintMode')?.checked ?? true;
        if (remember) {
            localStorage.setItem('receipt_print_mode', mode);
        }
        closePrintModeModal();

        if (mode === 'bluetooth') {
            await printViaBluetooth();
        } else {
            window.print();
        }
    }

    async function printViaBluetooth() {
        if (!navigator.bluetooth) {
            alert("Browser ini belum mendukung Web Bluetooth. Silakan gunakan Google Chrome atau Microsoft Edge dengan koneksi HTTPS pada Laptop atau HP/Tablet Anda.");
            window.print();
            return;
        }

        try {
            const char = await getBluetoothPrintCharacteristic();
            const bytes = buildEscPosBytes(receiptData);
            await sendBytesToPrinter(char, bytes);
        } catch (err) {
            console.warn("Bluetooth Print notice:", err);
            if (err.name === 'NotFoundError' || (err.message && err.message.toLowerCase().includes('user cancelled'))) {
                return;
            }
            if (confirm("Koneksi Bluetooth tidak merespons: " + (err.message || "Gagal menghubungkan") + ".\n\nBuka dialog cetak sistem biasa?")) {
                window.print();
            }
        }
    }

    async function handlePrint() {
        const isMobile = /Android|iPhone|iPad|iPod|webOS|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        const savedMode = localStorage.getItem('receipt_print_mode');

        // Jika mode tersimpan sudah ditentukan pengguna
        if (savedMode === 'bluetooth') {
            await printViaBluetooth();
            return;
        } else if (savedMode === 'system') {
            window.print();
            return;
        }

        // Jika belum ada preferensi tersimpan:
        if (isMobile) {
            // Pada HP / Tablet Android default langsung coba Bluetooth
            if (navigator.bluetooth) {
                await printViaBluetooth();
            } else {
                window.print();
            }
        } else {
            // Pada Laptop / Komputer Desktop:
            // Jika browser mendukung Web Bluetooth (Chrome / Edge), tampilkan pilihan agar pengguna tahu bisa langsung Bluetooth tanpa driver atau pakai USB
            if (navigator.bluetooth) {
                openPrintModeModal();
            } else {
                window.print();
            }
        }
    }
</script>
@endpush
