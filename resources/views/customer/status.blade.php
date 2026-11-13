<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Status Pesanan - Meja {{ $tableNumber }} ({{ $sale->transaction_number }}) | {{ $storeName }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,800;1,600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif-resto { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="h-full flex flex-col justify-between text-slate-800 antialiased p-4 sm:p-6">

    <div class="max-w-md mx-auto w-full space-y-5">
        <!-- Top Nav Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <img src="{{ $storeLogo }}" alt="{{ $storeName }}" class="w-8 h-8 rounded-xl object-contain shadow-xs border border-amber-200 p-0.5 bg-white shrink-0" onerror="this.src='{{ asset('images/default-cake.png') }}'">
                <div class="leading-tight">
                    <span class="font-extrabold text-xs tracking-wider text-slate-900 block truncate">{{ $storeNameLine1 }}</span>
                    @if(!empty($storeNameLine2))
                        <span class="text-[10px] font-bold text-[#c59b27] block truncate">{{ $storeNameLine2 }}</span>
                    @endif
                </div>
            </div>
            <span class="px-3 py-1 rounded-xl text-xs font-black bg-amber-100 text-amber-900 border border-amber-300">
                Meja {{ $tableNumber }}
            </span>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl text-xs flex items-center gap-3 shadow-2xs">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Order Card Status -->
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-xl text-center space-y-5">
            @if($sale->isPaid())
                <div class="w-16 h-16 rounded-3xl bg-emerald-100 text-emerald-600 mx-auto flex items-center justify-center shadow-md shadow-emerald-500/10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            @else
                <div class="w-16 h-16 rounded-3xl bg-amber-100 text-amber-600 mx-auto flex items-center justify-center shadow-md shadow-amber-500/10 animate-pulse">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            @endif

            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">
                    {{ $sale->isPaid() ? 'Pesanan Terkonfirmasi & Masuk Dapur' : 'Pesanan Masuk ke Kasir' }}
                </span>
                <h2 class="text-2xl font-black text-slate-900 font-mono mt-0.5">{{ $sale->transaction_number }}</h2>
                <p class="text-xs text-slate-500 mt-1">
                    {{ $sale->transaction_date->format('d/m/Y H:i') }} WIB &bull; Meja {{ $tableNumber }}
                </p>
            </div>

            <!-- Two Statuses: Dapur & Kasir -->
            <div class="grid grid-cols-2 gap-3 pt-2 text-left">
                <!-- Status Dapur -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Status Dapur</span>
                    @if($sale->isPaid())
                        @if($sale->order_status === 'completed')
                            <span class="inline-flex items-center gap-1 font-extrabold text-xs text-emerald-700">
                                &check; Selesai Dimasak
                            </span>
                        @elseif($sale->order_status === 'processing')
                            <span class="inline-flex items-center gap-1 font-extrabold text-xs text-blue-700 animate-pulse">
                                &bull; Sedang Dimasak
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 font-extrabold text-xs text-amber-700">
                                &bull; Antrean Dapur
                            </span>
                        @endif
                    @else
                        <span class="inline-flex items-center gap-1 font-extrabold text-xs text-stone-500">
                            &bull; Menunggu Kasir
                        </span>
                        <span class="text-[9px] text-slate-400 block mt-0.5">Masuk setelah dikonfirmasi</span>
                    @endif
                </div>

                <!-- Status Pembayaran -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Status Kasir</span>
                    @if($sale->isPaid())
                        <span class="inline-flex items-center gap-1 font-extrabold text-xs text-emerald-700">
                            &check; Pembayaran Lunas
                        </span>
                        <span class="text-[10px] text-emerald-600 block mt-0.5">({{ $sale->payment?->methodLabel() ?? 'Lunas' }})</span>
                    @elseif($sale->open_bill_id)
                        <span class="inline-flex items-center gap-1 font-extrabold text-xs text-emerald-800">
                            &bull; Open Bill Meja
                        </span>
                        <span class="text-[10px] text-slate-500 block mt-0.5">Bayar di Kasir Nanti</span>
                    @else
                        <span class="inline-flex items-center gap-1 font-extrabold text-xs text-amber-700 animate-pulse">
                            &bull; Menunggu Verifikasi
                        </span>
                        <span class="text-[10px] text-slate-500 block mt-0.5">{{ $sale->payment?->methodLabel() ?? 'QRIS/Transfer' }}</span>
                    @endif
                </div>
            </div>

            <!-- TAMPILAN BUKTI PEMBAYARAN YANG DIUNGGAH PELANGGAN -->
            @if($sale->hasPaymentProof())
                <div class="p-4 rounded-2xl bg-[#faf8f4] border border-[#ebdcc4] text-left space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold text-amber-900 uppercase tracking-wider">Bukti Pembayaran Anda</span>
                        <span class="text-[10px] text-emerald-800 bg-emerald-100/80 font-bold px-2 py-0.5 rounded-md">
                            Sudah Diunggah &check;
                        </span>
                    </div>

                    <div class="flex items-center gap-3 bg-white p-2.5 rounded-xl border border-amber-200/80 shadow-2xs">
                        <img src="{{ $sale->payment_proof_url }}" alt="Bukti Pembayaran"
                            onclick="openImageModal('{{ $sale->payment_proof_url }}')"
                            class="w-14 h-14 object-cover rounded-xl border border-slate-200 shrink-0 cursor-pointer hover:opacity-90 transition">
                        <div class="flex-1 truncate">
                            <span class="text-xs font-bold text-slate-800 block truncate">Metode: {{ $sale->payment?->methodLabel() ?? 'QRIS / Transfer' }}</span>
                            <span class="text-[11px] text-slate-500 block mt-0.5">Bukti sedang diperiksa oleh kasir</span>
                        </div>
                        <button type="button" onclick="openImageModal('{{ $sale->payment_proof_url }}')"
                            class="shrink-0 px-3 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200 text-xs font-bold transition">
                            Lihat
                        </button>
                    </div>
                </div>
            @endif

            <!-- Rincian Item Pesanan -->
            <div class="pt-4 border-t border-slate-100 text-left space-y-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Rincian Menu:</span>
                <div class="divide-y divide-slate-100 max-h-48 overflow-y-auto text-xs">
                    @foreach($sale->items as $item)
                        <div class="py-2 flex items-center justify-between">
                            <span class="text-slate-800 font-medium">{{ $item->quantity }}x {{ $item->product_name_snapshot }}</span>
                            <span class="font-bold text-slate-900">Rp {{ number_format($item->line_total, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="pt-3 border-t border-slate-200 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase">Total Tagihan:</span>
                    <span class="text-xl font-black text-slate-900">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Petunjuk & Pesan Status -->
            @if($sale->isPaid())
                <div class="bg-emerald-50 border border-emerald-200/80 p-4 rounded-2xl text-xs text-emerald-900 text-left leading-relaxed space-y-1">
                    <div class="flex items-center gap-2 font-black text-emerald-800">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Pembayaran Berhasil Dikonfirmasi Kasir
                    </div>
                    <p class="text-[11px] text-emerald-800">
                        Terima kasih! Bukti pembayaran telah diverifikasi dan pesanan Anda telah <strong>diteruskan ke Dapur</strong>. Tim dapur kami sedang menyiapkan sajian lezat untuk Anda.
                    </p>
                </div>
            @elseif($sale->open_bill_id)
                <div class="bg-emerald-50/60 border border-emerald-300 p-4 rounded-2xl text-xs text-emerald-950 text-left leading-relaxed">
                    <span class="font-extrabold text-emerald-900 block mb-1">Tagihan Tergabung (Open Bill):</span>
                    Pesanan ini secara otomatis dimasukkan ke tagihan <strong>Open Bill Meja {{ $tableNumber }}</strong>. Anda dapat menikmati pesanan terlebih dahulu, dan melunasi seluruh tagihan sekaligus di kasir sebelum pulang.
                </div>
            @else
                <div class="bg-amber-50 border border-amber-200 p-4 rounded-2xl text-xs text-amber-950 text-left leading-relaxed space-y-1.5">
                    <div class="flex items-center gap-2 font-black text-amber-900">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping"></span>
                        <span>Menunggu Konfirmasi Kasir</span>
                    </div>
                    <p class="text-[11px] text-amber-800 leading-relaxed">
                        Bukti pembayaran Anda sudah diterima oleh sistem. Kasir kami sedang memeriksa bukti tersebut. <strong>Setelah kasir mengonfirmasi, pesanan Anda akan langsung masuk ke layar Dapur.</strong> Halaman ini akan otomatis diperbarui saat kasir menyetujui pesanan Anda.
                    </p>
                </div>
            @endif

            <!-- Tombol Navigasi Bawah -->
            <div class="space-y-2 pt-2">
                <button type="button" onclick="window.location.reload()"
                    class="w-full py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Segarkan Status (Refresh)
                </button>

                <a href="{{ route('order.index', $tableNumber) }}"
                    class="w-full block py-3 rounded-2xl bg-slate-900 hover:bg-black text-white text-xs font-black shadow-md transition transform hover:scale-105 active:scale-95">
                    + Pesan Menu Tambahan
                </a>
            </div>
        </div>
    </div>

    <!-- Modal Pratinjau Gambar Bukti Pembayaran -->
    <div id="imagePreviewModal" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-sm flex items-center justify-center p-4" onclick="closeImageModal()">
        <div class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl p-4 space-y-3" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <span class="text-xs font-bold text-slate-800">Bukti Pembayaran ({{ $sale->transaction_number }})</span>
                <button type="button" onclick="closeImageModal()" class="text-slate-400 hover:text-slate-600 p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="max-h-[70vh] overflow-auto flex items-center justify-center bg-slate-100 rounded-2xl p-2">
                <img id="modalPreviewImg" src="" alt="Bukti Pembayaran Full" class="max-w-full max-h-[65vh] object-contain rounded-xl">
            </div>
            <div class="flex justify-end gap-2 pt-1">
                <a id="modalOpenTabLink" href="" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition">
                    Buka di Tab Baru
                </a>
                <button type="button" onclick="closeImageModal()" class="px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        function openImageModal(url) {
            document.getElementById('modalPreviewImg').src = url;
            document.getElementById('modalOpenTabLink').href = url;
            document.getElementById('imagePreviewModal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('imagePreviewModal').classList.add('hidden');
        }

        // Auto Polling: Cek apakah Kasir sudah mengonfirmasi pesanan meja ini
        @if($sale->isUnpaid())
        setInterval(async () => {
            try {
                const res = await fetch("{{ route('order.check_status', [$tableNumber, $sale->id]) }}", {
                    headers: { 'Accept': 'application/json' }
                });
                if (res.ok) {
                    const data = await res.json();
                    if (data.is_paid) {
                        window.location.reload();
                    }
                }
            } catch (err) {
                console.log('Polling check:', err);
            }
        }, 4000);
        @endif
    </script>

</body>
</html>
