@extends('layouts.app')

@section('title', 'Pengaturan Toko & Pemesanan Online')

@section('content')
<div class="space-y-6 pb-12">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-[#c59b27]">Konfigurasi Sistem</span>
                <span class="text-stone-300">•</span>
                <span class="text-xs font-medium text-[#786e60]">{{ $settings['store_name'] ?? 'Toko' }}</span>
            </div>
            <h1 class="font-serif-classic text-2xl sm:text-3xl font-bold text-[#262017] mt-1">
                Pengaturan Toko & Pemesanan Online
            </h1>
            <p class="text-xs sm:text-sm text-[#786e60] mt-1">
                Atur informasi toko, nomor kontak WhatsApp, ucapan sambutan katalog online, logo aplikasi, serta jam operasional toko.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('online_order.index') }}" target="_blank"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-[#d6cfc2] hover:bg-[#faf8f5] text-[#262017] text-xs font-bold shadow-xs transition hover:scale-105 active:scale-95">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                <span>Lihat Halaman Pesan Online</span>
            </a>
        </div>
    </div>

    <!-- Form Pengaturan -->
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom Kiri: Informasi Profil & Kontak Toko -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Card 1: Informasi Toko & Kontak -->
                <div class="bg-white rounded-2xl border border-[#e5dfd3] p-6 shadow-[0_4px_20px_-4px_rgba(38,32,23,0.05)] space-y-5">
                    <div class="flex items-center gap-3 pb-3 border-b border-[#f0ece1]">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 flex items-center justify-center font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-serif-classic text-base font-bold text-[#262017]">Identitas & Kontak Resmi Toko</h2>
                            <p class="text-xs text-[#786e60]">Informasi nama resto/bakery, kontak resmi, dan lokasi gerai.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nama Toko -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold text-[#262017]">
                                    Nama Toko / Bakery <span class="text-rose-600">*</span>
                                </label>
                                <span class="text-[10px] text-[#786e60] bg-amber-50 border border-amber-200/60 px-2 py-0.5 rounded-md font-medium">
                                    Mendukung 2 Baris (Tekan Enter)
                                </span>
                            </div>
                            <textarea name="store_name" rows="2" required
                                placeholder="Contoh:&#10;Fira Cake&#10;Bakery & Pastry"
                                class="w-full text-xs px-3.5 py-2 rounded-xl border border-[#d6cfc2] bg-[#faf8f5] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#c59b27] resize-none leading-relaxed">{{ old('store_name', $settings['store_name'] ?? "Fira Cake\nBakery & Pastry") }}</textarea>
                            <p class="text-[11px] text-[#786e60] mt-1">Gunakan tombol Enter untuk memecah baris 1 dan baris 2 secara presisi pada sidebar, struk, dan header.</p>
                        </div>

                        <!-- Nomor Kontak Toko (WA) -->
                        <div>
                            <label class="block text-xs font-bold text-[#262017] mb-1.5">
                                Nomor WhatsApp / Telepon Toko <span class="text-rose-600">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" name="store_phone" required
                                    value="{{ old('store_phone', $settings['store_phone'] ?? '082131117996') }}"
                                    placeholder="Contoh: 082131117996"
                                    class="w-full text-xs pl-9 pr-3.5 py-2.5 rounded-xl border border-[#d6cfc2] bg-[#faf8f5] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#c59b27]">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-emerald-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-[11px] text-[#786e60] mt-1">Ditampilkan pada header katalog pesanan online & tombol bantuan pelanggan.</p>
                        </div>

                        <!-- Email Toko -->
                        <div>
                            <label class="block text-xs font-bold text-[#262017] mb-1.5">
                                Alamat Email Toko (Opsional)
                            </label>
                            <input type="email" name="store_email"
                                value="{{ old('store_email', $settings['store_email'] ?? '') }}"
                                placeholder="Contoh: info@firacake.com"
                                class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-[#d6cfc2] bg-[#faf8f5] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#c59b27]">
                        </div>

                        <!-- Jam Operasional -->
                        <div>
                            <label class="block text-xs font-bold text-[#262017] mb-1.5">
                                Jam Operasional Toko <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" name="store_hours" required
                                value="{{ old('store_hours', $settings['store_hours'] ?? '08:00 - 21:00 WITA') }}"
                                placeholder="Contoh: 08:00 - 21:00 WITA"
                                class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-[#d6cfc2] bg-[#faf8f5] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#c59b27]">
                        </div>
                    </div>

                    <!-- Alamat Lengkap Toko -->
                    <div>
                        <label class="block text-xs font-bold text-[#262017] mb-1.5">
                            Alamat Lengkap Toko / Gerai <span class="text-rose-600">*</span>
                        </label>
                        <textarea name="store_address" rows="2" required
                            placeholder="Alamat lengkap gerai toko..."
                            class="w-full text-xs p-3.5 rounded-xl border border-[#d6cfc2] bg-[#faf8f5] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#c59b27]">{{ old('store_address', $settings['store_address'] ?? 'BISOLPIN, Ruko Ruby, Jl. Boulevard Rubi II, Masale, Panakkukang, Makassar') }}</textarea>
                    </div>
                </div>

                <!-- Card 2: Pengaturan Tampilan & Sambutan Halaman Pesan Online -->
                <div class="bg-white rounded-2xl border border-[#e5dfd3] p-6 shadow-[0_4px_20px_-4px_rgba(38,32,23,0.05)] space-y-5">
                    <div class="flex items-center gap-3 pb-3 border-b border-[#f0ece1]">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-200 text-blue-700 flex items-center justify-center font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-serif-classic text-base font-bold text-[#262017]">Tampilan Sambutan Katalog Pemesanan Online</h2>
                            <p class="text-xs text-[#786e60]">Teks ucapan selamat datang dan deskripsi singkat yang dibaca pelanggan saat membuka tautan pesanan online.</p>
                        </div>
                    </div>

                    <!-- Judul Sambutan -->
                    <div>
                        <label class="block text-xs font-bold text-[#262017] mb-1.5">
                            Judul Ucapan Selamat Datang <span class="text-rose-600">*</span>
                        </label>
                        <input type="text" name="online_welcome_title" required
                            value="{{ old('online_welcome_title', $settings['online_welcome_title'] ?? 'Selamat Datang di Fira Cake!') }}"
                            placeholder="Contoh: Selamat Datang di Fira Cake!"
                            class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-[#d6cfc2] bg-[#faf8f5] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#c59b27]">
                    </div>

                    <!-- Deskripsi Singkat Toko -->
                    <div>
                        <label class="block text-xs font-bold text-[#262017] mb-1.5">
                            Deskripsi Singkat Toko & Sajian <span class="text-rose-600">*</span>
                        </label>
                        <textarea name="online_welcome_description" rows="3" required
                            placeholder="Deskripsi singkat mengenai sajian dan kualitas kue..."
                            class="w-full text-xs p-3.5 rounded-xl border border-[#d6cfc2] bg-[#faf8f5] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#c59b27]">{{ old('online_welcome_description', $settings['online_welcome_description'] ?? 'Nikmati aneka pilihan roti, bolu fresh, kue tart, dan pastry premium kualitas terbaik kami langsung dibuat segar setiap hari.') }}</textarea>
                        <p class="text-[11px] text-[#786e60] mt-1">Deskripsi ini tampil di banner sambutan bagian atas katalog pemesanan online.</p>
                    </div>

                    <!-- Nomor WhatsApp Notifikasi Admin / Kasir -->
                    <div>
                        <label class="block text-xs font-bold text-[#262017] mb-1.5">
                            Nomor WhatsApp Notifikasi Internal Pesanan (Opsional)
                        </label>
                        <input type="text" name="order_notification_whatsapp"
                            value="{{ old('order_notification_whatsapp', $settings['order_notification_whatsapp'] ?? '') }}"
                            placeholder="Contoh: 082131117996"
                            class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-[#d6cfc2] bg-[#faf8f5] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#c59b27]">
                        <p class="text-[11px] text-[#786e60] mt-1">Nomor penerima ringkasan transaksi pesanan online masuk.</p>
                    </div>
                </div>

                <!-- Card 3: Pengaturan Metode Pembayaran Meja (QRIS & Transfer Bank) -->
                <div class="bg-white rounded-2xl border border-[#e5dfd3] p-6 shadow-[0_4px_20px_-4px_rgba(38,32,23,0.05)] space-y-6">
                    <div class="flex items-center gap-3 pb-3 border-b border-[#f0ece1]">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 flex items-center justify-center font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-serif-classic text-base font-bold text-[#262017]">Metode Pembayaran Pesanan Meja (QRIS & Transfer Bank)</h2>
                            <p class="text-xs text-[#786e60]">Konfigurasi kode QRIS barcode dan rekening bank yang digunakan pelanggan saat memesan dari meja.</p>
                        </div>
                    </div>

                    <!-- 1. Bagian QRIS -->
                    <div class="p-4 rounded-xl bg-[#faf8f5] border border-[#e5dfd3] space-y-4">
                        <div class="flex items-center justify-between border-b border-[#ebdcc4] pb-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                <h3 class="text-xs font-bold text-[#262017] uppercase tracking-wider">1. Pembayaran QRIS</h3>
                            </div>
                            <span class="text-[10px] font-semibold text-emerald-800 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md">
                                Mendukung Scan All E-Wallet & M-Banking
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Foto Barcode QRIS -->
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-[#262017]">
                                    Gambar / Barcode QRIS Toko
                                </label>
                                <div class="flex items-start gap-4 p-3 bg-white rounded-xl border border-dashed border-[#d6cfc2]">
                                    <div class="w-24 h-24 shrink-0 rounded-lg bg-[#faf8f5] border border-slate-200 overflow-hidden flex items-center justify-center">
                                        @if(!empty($settings['payment_qris_image']))
                                            <img src="{{ asset('storage/'.$settings['payment_qris_image']) }}" alt="QRIS Toko" class="w-full h-full object-contain">
                                        @else
                                            <div class="text-center p-2 text-[10px] text-slate-400">
                                                <svg class="w-6 h-6 mx-auto mb-1 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                                Belum ada foto QRIS
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 space-y-2">
                                        <input type="file" name="payment_qris_image" accept="image/*"
                                            class="w-full text-xs text-[#786e60] file:mr-2 file:py-1.5 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-[#c59b27] file:text-stone-950 hover:file:bg-[#a98119] file:cursor-pointer">
                                        <p class="text-[10px] text-[#786e60]">Format: PNG, JPG, JPEG, WEBP. Maks: 2MB.</p>
                                        @if(!empty($settings['payment_qris_image']))
                                            <label class="flex items-center gap-1.5 text-[11px] text-rose-700 font-semibold cursor-pointer pt-1">
                                                <input type="checkbox" name="remove_qris_image" value="1" class="rounded text-rose-600 border-rose-300">
                                                <span>Hapus barcode QRIS saat ini</span>
                                            </label>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Nama Akun QRIS & Catatan -->
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-bold text-[#262017] mb-1">
                                        Nama Merchant / Akun QRIS
                                    </label>
                                    <input type="text" name="payment_qris_name"
                                        value="{{ old('payment_qris_name', $settings['payment_qris_name'] ?? 'Fira Cake QRIS') }}"
                                        placeholder="Contoh: Fira Cake Bakery & Pastry"
                                        class="w-full text-xs px-3.5 py-2 rounded-xl border border-[#d6cfc2] bg-white focus:outline-none focus:ring-1 focus:ring-[#c59b27]">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-[#262017] mb-1">
                                        Petunjuk / Catatan Pembayaran QRIS
                                    </label>
                                    <textarea name="payment_qris_notes" rows="2"
                                        placeholder="Contoh: Buka aplikasi mobile banking atau e-wallet Anda, scan barcode di atas lalu unggah bukti pembayaran..."
                                        class="w-full text-xs p-2.5 rounded-xl border border-[#d6cfc2] bg-white focus:outline-none focus:ring-1 focus:ring-[#c59b27]">{{ old('payment_qris_notes', $settings['payment_qris_notes'] ?? 'Scan kode QRIS di atas untuk melakukan pembayaran langsung via GoPay, OVO, ShopeePay, BCA, atau M-Banking lainnya.') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Bagian Transfer Bank -->
                    <div class="p-4 rounded-xl bg-[#faf8f5] border border-[#e5dfd3] space-y-4">
                        <div class="flex items-center justify-between border-b border-[#ebdcc4] pb-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                <h3 class="text-xs font-bold text-[#262017] uppercase tracking-wider">2. Pembayaran Transfer Bank</h3>
                            </div>
                            <span class="text-[10px] font-semibold text-blue-800 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-md">
                                Transfer Manual ke Rekening Toko
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- Nama Bank -->
                            <div>
                                <label class="block text-xs font-bold text-[#262017] mb-1">
                                    Nama Bank <span class="text-rose-600">*</span>
                                </label>
                                <input type="text" name="payment_bank_name"
                                    value="{{ old('payment_bank_name', $settings['payment_bank_name'] ?? 'BCA') }}"
                                    placeholder="Contoh: BCA / Mandiri / BNI / BRI"
                                    class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-[#d6cfc2] bg-white focus:outline-none focus:ring-1 focus:ring-[#c59b27]">
                            </div>

                            <!-- Nomor Rekening -->
                            <div>
                                <label class="block text-xs font-bold text-[#262017] mb-1">
                                    Nomor Rekening Bank <span class="text-rose-600">*</span>
                                </label>
                                <input type="text" name="payment_bank_account_number"
                                    value="{{ old('payment_bank_account_number', $settings['payment_bank_account_number'] ?? '1234567890') }}"
                                    placeholder="Contoh: 1234567890"
                                    class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-[#d6cfc2] bg-white font-mono font-bold text-[#262017] focus:outline-none focus:ring-1 focus:ring-[#c59b27]">
                            </div>

                            <!-- Atas Nama Rekening -->
                            <div>
                                <label class="block text-xs font-bold text-[#262017] mb-1">
                                    Atas Nama Pemilik Rekening <span class="text-rose-600">*</span>
                                </label>
                                <input type="text" name="payment_bank_account_holder"
                                    value="{{ old('payment_bank_account_holder', $settings['payment_bank_account_holder'] ?? 'Fira Cake Bakery') }}"
                                    placeholder="Contoh: Fira Cake Bakery"
                                    class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-[#d6cfc2] bg-white focus:outline-none focus:ring-1 focus:ring-[#c59b27]">
                            </div>
                        </div>

                        <!-- Petunjuk Transfer -->
                        <div>
                            <label class="block text-xs font-bold text-[#262017] mb-1">
                                Petunjuk / Instruksi Transfer Bank
                            </label>
                            <textarea name="payment_bank_notes" rows="2"
                                placeholder="Contoh: Silakan transfer sesuai total pesanan. Simpan bukti transfer dan unggah pada halaman pemesanan meja..."
                                class="w-full text-xs p-2.5 rounded-xl border border-[#d6cfc2] bg-white focus:outline-none focus:ring-1 focus:ring-[#c59b27]">{{ old('payment_bank_notes', $settings['payment_bank_notes'] ?? 'Transfer sesuai nominal tagihan ke nomor rekening di atas. Simpan struk/bukti transfer untuk diunggah sebelum pesanan dikirim ke kasir.') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Pengaturan Presensi & Radius Kehadiran Karyawan -->
                <div class="bg-white rounded-2xl border border-[#e5dfd3] p-6 shadow-[0_4px_20px_-4px_rgba(38,32,23,0.05)] space-y-5">
                    <div class="flex items-center gap-3 pb-3 border-b border-[#f0ece1]">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-center font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-serif-classic text-base font-bold text-[#262017]">Radius Presensi Kehadiran Karyawan</h2>
                            <p class="text-xs text-[#786e60]">Atur toleransi batas maksimal jarak GPS untuk absensi mandiri staf kasir dan warehouse.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
                        <div>
                            <label class="block text-xs font-bold text-[#262017] mb-1.5">
                                Maksimal Jarak Radius Absensi (Meter) <span class="text-rose-600">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="attendance_max_distance" required min="1" max="5000" step="1"
                                    value="{{ old('attendance_max_distance', $settings['attendance_max_distance'] ?? '15') }}"
                                    placeholder="Contoh: 15"
                                    class="w-full text-xs pl-3.5 pr-14 py-2.5 rounded-xl border border-[#d6cfc2] bg-[#faf8f5] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#c59b27] font-mono font-bold text-[#262017]">
                                <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-xs font-bold text-[#786e60] pointer-events-none">
                                    Meter
                                </span>
                            </div>
                            <p class="text-[11px] text-[#786e60] mt-1">
                                Batas toleransi jarak GPS karyawan dari titik kantor toko {{ $settings['store_name'] ?? 'Toko' }} ({{ $settings['store_address'] ?? 'Alamat Toko' }}) agar absensi dapat dikonfirmasi.
                            </p>
                        </div>

                        <div class="p-3.5 rounded-xl bg-amber-50/70 border border-amber-200 text-xs text-amber-900 space-y-1">
                            <span class="font-bold flex items-center gap-1.5 text-[#8a681c]">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Dampak Pengaturan Radius
                            </span>
                            <p class="text-[11px] text-amber-800 leading-relaxed">
                                Jika karyawan berada melebihi jarak ini dari toko, tombol ambil foto kamera dan tombol kirim absensi otomatis terkunci dan ditolak oleh sistem.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Logo & Ringkasan -->
            <div class="space-y-6">
                <!-- Card 3: Logo Aplikasi & Toko -->
                <div class="bg-white rounded-2xl border border-[#e5dfd3] p-6 shadow-[0_4px_20px_-4px_rgba(38,32,23,0.05)] space-y-4">
                    <div class="flex items-center gap-3 pb-3 border-b border-[#f0ece1]">
                        <div class="w-9 h-9 rounded-xl bg-purple-50 border border-purple-200 text-purple-700 flex items-center justify-center font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-serif-classic text-base font-bold text-[#262017]">Logo Toko</h2>
                            <p class="text-xs text-[#786e60]">Branding visual pada katalog dan cetak.</p>
                        </div>
                    </div>

                    <div class="flex flex-col items-center justify-center p-4 bg-[#faf8f5] rounded-xl border border-dashed border-[#d6cfc2] text-center">
                        <img src="{{ $storeLogo }}" alt="Logo Toko" class="w-24 h-24 object-contain rounded-xl shadow-xs border border-white mb-3" onerror="this.src='{{ asset('images/default-cake.png') }}'">
                        <span class="text-xs font-semibold text-[#262017]">Logo Saat Ini</span>
                        <span class="text-[11px] text-[#786e60]">Maksimal 2 MB (PNG, JPG, SVG, WEBP)</span>
                    </div>

                    @if(!empty($settings['app_logo']))
                        <div class="flex items-center gap-2 p-2.5 rounded-xl bg-rose-50 border border-rose-200">
                            <input type="checkbox" id="remove_logo" name="remove_logo" value="1" class="rounded border-rose-300 text-rose-600 focus:ring-rose-500">
                            <label for="remove_logo" class="text-xs text-rose-800 font-semibold cursor-pointer">
                                Hapus logo kustom & kembalikan ke logo default
                            </label>
                        </div>
                    @endif

                    <div>
                        <label class="block text-xs font-bold text-[#262017] mb-1.5">Ganti Logo Baru</label>
                        <input type="file" name="app_logo" accept="image/*"
                            class="w-full text-xs text-[#786e60] file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#c59b27] file:text-stone-950 hover:file:bg-[#a98119] file:cursor-pointer">
                    </div>
                </div>

                <!-- Preview Kartu Kontak Online -->
                <div class="bg-[#faf8f5] rounded-2xl border border-[#e5dfd3] p-5 space-y-3">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-[#786e60] flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Informasi Integrasi</span>
                    </div>
                    <p class="text-xs text-[#4d4437] leading-relaxed">
                        Data nomor telepon toko <strong class="text-[#262017]">{{ $settings['store_phone'] ?? '082131117996' }}</strong> akan otomatis terhubung ke:
                    </p>
                    <ul class="text-xs text-[#4d4437] space-y-1.5 list-disc list-inside">
                        <li>Tombol "Hubungi Toko via WA" di halaman pesan online pelanggan.</li>
                        <li>Header katalog mobile dan informasi jam operasional.</li>
                        <li>Radius maksimal presensi karyawan pada peta GPS absensi mandiri.</li>
                        <li>Struk transaksi dan identitas toko.</li>
                    </ul>
                </div>

                <!-- Action Button -->
                <div class="bg-white rounded-2xl border border-[#e5dfd3] p-5 shadow-[0_4px_20px_-4px_rgba(38,32,23,0.05)]">
                    <button type="submit"
                        class="w-full py-3 px-4 rounded-xl bg-[#c59b27] hover:bg-[#a98119] active:scale-98 text-stone-950 text-xs font-bold shadow-md transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Simpan Seluruh Pengaturan</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

