<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Tampilan formulir pengaturan toko dan sistem untuk Admin.
     */
    public function index(): View
    {
        $settings = Setting::allMapped();

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Menyimpan perubahan pengaturan toko.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'store_name' => ['required', 'string', 'max:150'],
            'store_phone' => ['required', 'string', 'max:30'],
            'store_email' => ['nullable', 'email', 'max:150'],
            'store_address' => ['required', 'string', 'max:500'],
            'store_hours' => ['required', 'string', 'max:100'],
            'online_welcome_title' => ['required', 'string', 'max:200'],
            'online_welcome_description' => ['required', 'string', 'max:1000'],
            'attendance_max_distance' => ['required', 'numeric', 'min:1', 'max:5000'],
            'attendance_office_name' => ['nullable', 'string', 'max:150'],
            'attendance_office_latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'attendance_office_longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'order_notification_whatsapp' => ['nullable', 'string', 'max:30'],
            'app_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'],
            'remove_logo' => ['nullable', 'boolean'],
            'payment_qris_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'],
            'remove_qris_image' => ['nullable', 'boolean'],
            'payment_qris_name' => ['nullable', 'string', 'max:150'],
            'payment_qris_notes' => ['nullable', 'string', 'max:1000'],
            'payment_bank_name' => ['nullable', 'string', 'max:100'],
            'payment_bank_account_number' => ['nullable', 'string', 'max:50'],
            'payment_bank_account_holder' => ['nullable', 'string', 'max:150'],
            'payment_bank_notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'store_name.required' => 'Nama toko wajib diisi.',
            'store_phone.required' => 'Nomor kontak telepon / WhatsApp toko wajib diisi.',
            'store_address.required' => 'Alamat lengkap toko wajib diisi.',
            'store_hours.required' => 'Jam operasional toko wajib diisi.',
            'online_welcome_title.required' => 'Judul ucapan selamat datang online order wajib diisi.',
            'online_welcome_description.required' => 'Deskripsi singkat toko wajib diisi.',
            'attendance_max_distance.required' => 'Maksimal jarak radius absensi wajib diisi.',
            'attendance_max_distance.numeric' => 'Maksimal jarak radius absensi harus berupa angka.',
            'attendance_max_distance.min' => 'Maksimal jarak radius absensi minimal 1 meter.',
            'attendance_office_latitude.required' => 'Titik koordinat Latitude kantor wajib diisi.',
            'attendance_office_latitude.numeric' => 'Titik koordinat Latitude kantor harus berupa angka desimal.',
            'attendance_office_latitude.between' => 'Titik koordinat Latitude harus berada di antara -90 dan 90.',
            'attendance_office_longitude.required' => 'Titik koordinat Longitude kantor wajib diisi.',
            'attendance_office_longitude.numeric' => 'Titik koordinat Longitude kantor harus berupa angka desimal.',
            'attendance_office_longitude.between' => 'Titik koordinat Longitude harus berada di antara -180 dan 180.',
            'app_logo.image' => 'File logo harus berupa gambar yang valid (PNG, JPG, WEBP, SVG).',
            'app_logo.max' => 'Ukuran file logo maksimal 2 MB.',
        ]);

        // Simpan setting berbasis teks
        Setting::set('store_name', $validated['store_name'], 'text', 'store');
        Setting::set('store_phone', $validated['store_phone'], 'text', 'store');
        Setting::set('store_email', $validated['store_email'] ?? '', 'text', 'store');
        Setting::set('store_address', $validated['store_address'], 'textarea', 'store');
        Setting::set('store_hours', $validated['store_hours'], 'text', 'store');
        Setting::set('online_welcome_title', $validated['online_welcome_title'], 'text', 'online_order');
        Setting::set('online_welcome_description', $validated['online_welcome_description'], 'textarea', 'online_order');
        Setting::set('attendance_max_distance', (string) $validated['attendance_max_distance'], 'number', 'attendance');
        if (isset($validated['attendance_office_name'])) {
            Setting::set('attendance_office_name', $validated['attendance_office_name'], 'text', 'attendance');
        }
        if (isset($validated['attendance_office_latitude'])) {
            Setting::set('attendance_office_latitude', (string) $validated['attendance_office_latitude'], 'text', 'attendance');
        }
        if (isset($validated['attendance_office_longitude'])) {
            Setting::set('attendance_office_longitude', (string) $validated['attendance_office_longitude'], 'text', 'attendance');
        }
        Setting::set('order_notification_whatsapp', $validated['order_notification_whatsapp'] ?? '', 'text', 'online_order');

        // Pengaturan Metode Pembayaran Meja (QRIS & Transfer Bank)
        Setting::set('payment_qris_name', $validated['payment_qris_name'] ?? 'Fira Cake QRIS', 'text', 'payment');
        Setting::set('payment_qris_notes', $validated['payment_qris_notes'] ?? '', 'textarea', 'payment');
        Setting::set('payment_bank_name', $validated['payment_bank_name'] ?? 'BCA', 'text', 'payment');
        Setting::set('payment_bank_account_number', $validated['payment_bank_account_number'] ?? '', 'text', 'payment');
        Setting::set('payment_bank_account_holder', $validated['payment_bank_account_holder'] ?? '', 'text', 'payment');
        Setting::set('payment_bank_notes', $validated['payment_bank_notes'] ?? '', 'textarea', 'payment');

        // Kelola file gambar QRIS
        if ($request->boolean('remove_qris_image') || $request->boolean('remove_payment_qris_image')) {
            $oldQris = Setting::get('payment_qris_image');
            if ($oldQris && Storage::disk('public')->exists($oldQris)) {
                Storage::disk('public')->delete($oldQris);
            }
            Setting::set('payment_qris_image', null, 'image', 'payment');
        } elseif ($request->hasFile('payment_qris_image')) {
            $oldQris = Setting::get('payment_qris_image');
            if ($oldQris && Storage::disk('public')->exists($oldQris)) {
                Storage::disk('public')->delete($oldQris);
            }
            $qrisPath = $request->file('payment_qris_image')->store('settings', 'public');
            Setting::set('payment_qris_image', $qrisPath, 'image', 'payment');
        }

        // Kelola file logo toko: hapus logo lama jika diminta atau jika mengunggah file baru
        if ($request->boolean('remove_logo')) {
            $oldLogo = Setting::get('app_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            Setting::set('app_logo', null, 'image', 'appearance');
        } elseif ($request->hasFile('app_logo')) {
            $oldLogo = Setting::get('app_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            $logoPath = $request->file('app_logo')->store('settings', 'public');
            Setting::set('app_logo', $logoPath, 'image', 'appearance');
        }

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan toko dan pesan online berhasil disimpan.');
    }
}
