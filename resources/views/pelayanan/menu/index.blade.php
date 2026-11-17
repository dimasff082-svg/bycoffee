@extends('layouts.app')

@section('title', 'Daftar Menu - BY Coffee')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-12">
    <!-- Header Page & Waitstaff Guide -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gradient-to-r from-[#211d17] via-[#2c261e] to-[#1a1712] p-6 rounded-3xl text-white shadow-xl border border-[#3d3529]">
        <div class="space-y-1.5">
            <div class="flex items-center gap-2.5 flex-wrap">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-teal-500/20 text-teal-300 border border-teal-500/30">
                    <svg class="w-3.5 h-3.5 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Staf Pelayanan • Mode Panduan Menu (Read Only)
                </span>
                <span class="text-xs text-stone-400">Total: {{ $products->total() }} Menu Terdaftar</span>
            </div>
            <h1 class="text-2xl font-serif-classic font-bold tracking-tight text-[#f7f2e8]">Katalog & Daftar Menu BY Coffee</h1>
            <p class="text-xs text-[#b8ab97] max-w-2xl leading-relaxed">
                Halaman panduan resmi bagi staf pelayanan (pramusaji/waiter) untuk mengecek ketersediaan hidangan, harga, komposisi rasa, dan deskripsi menu guna melayani pelanggan di meja resto/cafe.
            </p>
        </div>
        <div class="shrink-0 flex items-center gap-3">
            <a href="{{ route('attendance.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-[#c59b27] hover:bg-[#b0881e] text-stone-950 font-bold text-xs transition shadow-md hover:scale-105 active:scale-95">
                <svg class="w-4 h-4 text-stone-950" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Presensi Kehadiran
            </a>
            <a href="{{ route('my_salaries.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/15 text-stone-200 font-semibold text-xs transition border border-white/10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Slip Gaji Saya
            </a>
        </div>
    </div>

    <!-- 4 Summary KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-[#e5dfd3] p-4 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-[#8f8573] uppercase tracking-wider block">Total Menu</span>
                <span class="text-xl font-black text-[#262017] mt-0.5 block font-serif-classic">{{ $summary['total_menu'] }} <span class="text-xs font-semibold text-stone-500">Item</span></span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-stone-100 flex items-center justify-center text-stone-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-[#e5dfd3] p-4 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider block">Menu Tersedia</span>
                <span class="text-xl font-black text-emerald-700 mt-0.5 block font-serif-classic">{{ $summary['available_menu'] }} <span class="text-xs font-semibold text-emerald-600">Siap Pesan</span></span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-[#e5dfd3] p-4 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-rose-700 uppercase tracking-wider block">Menu Habis / Kosong</span>
                <span class="text-xl font-black text-rose-700 mt-0.5 block font-serif-classic">{{ $summary['out_of_stock_menu'] }} <span class="text-xs font-semibold text-rose-600">Sold Out</span></span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-rose-50 border border-rose-200 flex items-center justify-center text-rose-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-[#e5dfd3] p-4 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-amber-700 uppercase tracking-wider block">Kategori Menu</span>
                <span class="text-xl font-black text-amber-800 mt-0.5 block font-serif-classic">{{ $summary['total_categories'] }} <span class="text-xs font-semibold text-amber-700">Kategori</span></span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-center text-[#c59b27]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white rounded-3xl p-5 border border-[#e5dfd3] shadow-xs">
        <form method="GET" action="{{ route('pelayanan.menu.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            <!-- Search Input -->
            <div class="md:col-span-5 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama menu, kode SKU, atau kata kunci rasa..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-[#faf7f0] border border-[#e5dfd3] text-xs text-[#26211a] placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-[#c59b27] focus:bg-white transition">
            </div>

            <!-- Category Selector -->
            <div class="md:col-span-3">
                <select name="category_id" class="w-full px-3.5 py-2.5 rounded-2xl bg-[#faf7f0] border border-[#e5dfd3] text-xs text-[#26211a] focus:outline-none focus:ring-2 focus:ring-[#c59b27] focus:bg-white transition cursor-pointer">
                    <option value="">Semua Kategori Menu</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Selector -->
            <div class="md:col-span-2">
                <select name="status" class="w-full px-3.5 py-2.5 rounded-2xl bg-[#faf7f0] border border-[#e5dfd3] text-xs text-[#26211a] focus:outline-none focus:ring-2 focus:ring-[#c59b27] focus:bg-white transition cursor-pointer">
                    <option value="">Semua Ketersediaan</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Tersedia (Ready)</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Habis (Sold Out)</option>
                </select>
            </div>

            <!-- Submit & Reset Buttons -->
            <div class="md:col-span-2 flex items-center gap-2">
                <button type="submit" class="flex-1 px-4 py-2.5 rounded-2xl bg-[#262017] hover:bg-stone-800 text-white font-bold text-xs transition shadow-xs">
                    Saring
                </button>
                @if(request()->hasAny(['search', 'category_id', 'status']))
                    <a href="{{ route('pelayanan.menu.index') }}" class="px-3 py-2.5 rounded-2xl bg-[#f0ebd9] hover:bg-[#e4ddc8] text-[#5c5346] font-semibold text-xs transition flex items-center justify-center" title="Reset Saringan">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Product Grid Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
        @forelse($products as $product)
            @php
                $detailPayload = json_encode([
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'category' => $product->category?->name ?? 'Menu',
                    'price' => 'Rp ' . number_format($product->selling_price, 0, ',', '.'),
                    'unit' => $product->unit ?? 'porsi',
                    'image' => $product->image_url,
                    'is_active' => $product->is_active,
                    'description' => $product->description ?: 'Belum ada deskripsi rasa/komposisi detail untuk menu ini.'
                ]);
            @endphp
            <div class="group bg-white rounded-3xl border border-[#e5dfd3] shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col overflow-hidden relative {{ ! $product->is_active ? 'opacity-75 bg-stone-50/70' : '' }}">
                <!-- Menu Image -->
                <div class="relative h-44 w-full bg-[#f4efe4] overflow-hidden">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}"
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            onerror="this.onerror=null; this.src='{{ asset('images/default-cake.png') }}'">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-stone-400 bg-gradient-to-b from-[#faf7f0] to-[#eee8db]">
                            <svg class="w-12 h-12 mb-1.5 text-[#c59b27]/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span class="text-[10px] font-serif-classic font-bold text-[#8a681c] tracking-widest uppercase">BY COFFEE</span>
                        </div>
                    @endif

                    <!-- Category Badge -->
                    <div class="absolute top-3 left-3">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-black/60 backdrop-blur-md text-[#f7f2e8] border border-white/20 shadow-sm">
                            {{ $product->category?->name ?? 'Menu' }}
                        </span>
                    </div>

                    <!-- Status Availability Badge -->
                    <div class="absolute top-3 right-3">
                        @if($product->is_active)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/90 text-white backdrop-blur-md shadow-sm border border-emerald-400/40">
                                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                Tersedia
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-600/90 text-white backdrop-blur-md shadow-sm border border-rose-400/40">
                                <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                Habis
                            </span>
                        @endif
                    </div>

                    <!-- SKU Tag -->
                    <div class="absolute bottom-2 left-3">
                        <span class="font-mono text-[10px] font-bold text-white/90 bg-black/50 px-2 py-0.5 rounded-md backdrop-blur-xs">
                            {{ $product->sku }}
                        </span>
                    </div>
                </div>

                <!-- Menu Content -->
                <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                    <div class="space-y-1.5">
                        <h3 class="font-serif-classic font-bold text-sm text-[#262017] group-hover:text-[#c59b27] transition line-clamp-1">
                            {{ $product->name }}
                        </h3>

                        <!-- Deskripsi / Rasa -->
                        <p class="text-xs text-[#786e60] line-clamp-2 leading-relaxed">
                            {{ $product->description ?: 'Menu signature BY Coffee disajikan segar dengan standar rasa terbaik.' }}
                        </p>
                    </div>

                    <div class="pt-2 border-t border-[#f0ebd9] flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-[#8f8573] block uppercase tracking-wider font-semibold">Harga</span>
                            <span class="font-black text-sm text-[#262017] font-mono">
                                Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                            </span>
                            <span class="text-[10px] text-[#8f8573]">/ {{ $product->unit ?? 'porsi' }}</span>
                        </div>

                        <!-- Tombol Pratinjau Detail (Pure Read-Only) -->
                        <button type="button"
                            onclick='openMenuDetailModal({!! $detailPayload !!})'
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#f0ebd9] hover:bg-[#c59b27] hover:text-stone-950 text-[#5c5346] text-xs font-bold transition shadow-2xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span>Rincian</span>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-3xl p-12 border border-[#e5dfd3] text-center space-y-3">
                <div class="w-16 h-16 rounded-full bg-[#fdfaf3] border border-[#e5dfd3] flex items-center justify-center mx-auto text-stone-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h4 class="font-serif-classic font-bold text-base text-[#262017]">Tidak Ada Menu Ditemukan</h4>
                <p class="text-xs text-[#786e60] max-w-sm mx-auto">
                    Kriteria pencarian atau filter yang Anda gunakan tidak cocok dengan menu manapun. Silakan reset filter.
                </p>
                <div class="pt-2">
                    <a href="{{ route('pelayanan.menu.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#262017] text-white text-xs font-bold hover:bg-stone-800 transition">
                        Reset Saringan
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="bg-white rounded-2xl border border-[#e5dfd3] p-4 shadow-xs">
            {{ $products->links() }}
        </div>
    @endif
</div>

<!-- Modal Pratinjau Detail Menu (Pure Read-Only) -->
<div id="modal-menu-detail" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-stone-900/60 backdrop-blur-xs p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl shadow-2xl border border-[#e5dfd3] w-full max-w-lg p-6 space-y-5 my-8">
        <div class="flex items-center justify-between pb-3 border-b border-[#f0ebd9]">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-teal-800 border border-teal-200">
                    Panduan Pelayanan
                </span>
                <span class="font-mono text-xs font-bold text-[#8a681c]" id="detailSku">SKU</span>
            </div>
            <button type="button" onclick="closeMenuDetailModal()" class="text-stone-400 hover:text-stone-600 text-xl font-bold">&times;</button>
        </div>

        <!-- Foto & Header Modal -->
        <div class="space-y-3">
            <div class="w-full h-52 rounded-2xl overflow-hidden bg-[#faf7f0] border border-[#e5dfd3] relative">
                <img id="detailImage" src="" alt="Menu" class="w-full h-full object-cover hidden">
                <div id="detailFallback" class="w-full h-full flex flex-col items-center justify-center text-stone-400 bg-gradient-to-b from-[#faf7f0] to-[#eee8db]">
                    <svg class="w-16 h-16 mb-2 text-[#c59b27]/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="text-xs font-serif-classic font-bold text-[#8a681c] tracking-widest uppercase">BY COFFEE</span>
                </div>
            </div>

            <div class="flex items-start justify-between gap-3">
                <div>
                    <h3 class="font-serif-classic font-bold text-lg text-[#262017]" id="detailName">Nama Menu</h3>
                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-[#f0ebd9] text-[#5c5346] border border-[#dcd4c5] mt-1" id="detailCategory">Kategori</span>
                </div>
                <div class="text-right">
                    <span class="text-[10px] text-[#8f8573] uppercase font-bold block">Harga Jual</span>
                    <span class="font-black text-lg text-[#262017] font-mono" id="detailPrice">Rp 0</span>
                </div>
            </div>
        </div>

        <!-- Status Ketersediaan -->
        <div class="p-3.5 rounded-2xl bg-[#faf7f0] border border-[#e5dfd3] flex items-center justify-between text-xs">
            <span class="font-semibold text-[#5c5346]">Status Ketersediaan:</span>
            <span id="detailStatusBadge" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold"></span>
        </div>

        <!-- Deskripsi / Rasa / Informasi Penyajian -->
        <div class="space-y-1.5">
            <label class="block text-xs font-bold text-[#5c5346] font-serif-classic">Deskripsi & Catatan Rasa</label>
            <div class="p-4 rounded-2xl bg-[#fcfbf9] border border-[#dcd4c5] text-xs text-[#262017] leading-relaxed max-h-48 overflow-y-auto" id="detailDescription">
                Deskripsi
            </div>
            <p class="text-[10px] text-stone-400 italic">Gunakan informasi ini untuk menjelaskan karakter rasa, suhu penyajian, atau bahan utama jika ditanyakan oleh pelanggan.</p>
        </div>

        <div class="flex justify-end pt-3 border-t border-[#f0ebd9]">
            <button type="button" onclick="closeMenuDetailModal()"
                class="px-5 py-2.5 rounded-xl text-xs font-bold bg-[#262017] hover:bg-stone-800 text-white shadow-xs transition">
                Tutup Panduan
            </button>
        </div>
    </div>
</div>

<script>
    function openMenuDetailModal(data) {
        document.getElementById('detailSku').textContent = data.sku || '-';
        document.getElementById('detailName').textContent = data.name || '-';
        document.getElementById('detailCategory').textContent = data.category || 'Menu';
        document.getElementById('detailPrice').textContent = `${data.price} / ${data.unit}`;
        document.getElementById('detailDescription').textContent = data.description;

        const imgEl = document.getElementById('detailImage');
        const fallbackEl = document.getElementById('detailFallback');

        if (data.image) {
            imgEl.src = data.image;
            imgEl.classList.remove('hidden');
            if (fallbackEl) fallbackEl.classList.add('hidden');
        } else {
            imgEl.removeAttribute('src');
            imgEl.classList.add('hidden');
            if (fallbackEl) fallbackEl.classList.remove('hidden');
        }

        const badge = document.getElementById('detailStatusBadge');
        if (data.is_active) {
            badge.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300';
            badge.innerHTML = '<span class="w-2 h-2 rounded-full bg-emerald-500"></span> Siap Dipesan (Tersedia)';
        } else {
            badge.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-300';
            badge.innerHTML = '<span class="w-2 h-2 rounded-full bg-rose-500"></span> Tidak Tersedia (Sold Out)';
        }

        document.getElementById('modal-menu-detail').classList.remove('hidden');
    }

    function closeMenuDetailModal() {
        document.getElementById('modal-menu-detail').classList.add('hidden');
    }
</script>
@endsection
