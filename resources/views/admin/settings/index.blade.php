@extends('layouts.admin')
@section('page_title', 'Pengaturan Sistem')

@section('admin_content')
<div class="mb-6">
    <h2 class="text-2xl font-extrabold text-gray-800">Pengaturan Sistem</h2>
    <p class="text-sm text-gray-500">Kelola informasi global, logo, dan kontak sistem aplikasi Anda.</p>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')

    {{-- Informasi Umum --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden" data-aos="fade-up">
        <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30">
            <h3 class="font-bold text-gray-800"><i class="fas fa-info-circle text-primary mr-2"></i> Informasi Umum</h3>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Sistem Utama</label>
                <input type="text" name="system_name" value="{{ $settings['system_name']->value ?? '' }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none" placeholder="Pengaduan Desa">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Sub Nama Sistem</label>
                <input type="text" name="system_sub_name" value="{{ $settings['system_sub_name']->value ?? '' }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none" placeholder="Infrastruktur">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi Footer</label>
                <textarea name="footer_text" rows="2" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none resize-none">{{ $settings['footer_text']->value ?? '' }}</textarea>
            </div>
        </div>
    </div>

    {{-- Logo --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30">
            <h3 class="font-bold text-gray-800"><i class="fas fa-image text-primary mr-2"></i> Logo Sistem</h3>
        </div>
        <div class="p-6 flex flex-col sm:flex-row gap-6 items-center">
            <div class="w-24 h-24 shrink-0 rounded-xl bg-gray-100 flex items-center justify-center border border-gray-200 overflow-hidden shadow-sm">
                @if(isset($settings['logo_url']) && $settings['logo_url']->value)
                    <img src="{{ asset('storage/' . $settings['logo_url']->value) }}" alt="Logo" class="w-full h-full object-cover">
                @else
                    <i class="fas fa-landmark text-3xl text-gray-400"></i>
                @endif
            </div>
            <div class="flex-1">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Upload Logo Baru</label>
                <input type="file" name="logo_url" accept="image/*" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors">
                <p class="text-xs text-gray-500 mt-2"><i class="fas fa-info-circle text-blue-500"></i> Kosongkan jika tidak ingin mengubah logo. Format disarankan: PNG transparan (Maks 2MB).</p>
            </div>
        </div>
    </div>

    {{-- Kontak --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="200">
        <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30">
            <h3 class="font-bold text-gray-800"><i class="fas fa-address-book text-primary mr-2"></i> Informasi Kontak</h3>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"><i class="fas fa-envelope"></i></div>
                    <input type="email" name="contact_email" value="{{ $settings['contact_email']->value ?? '' }}" class="w-full pl-10 px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor Telepon / WhatsApp</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"><i class="fas fa-phone"></i></div>
                    <input type="text" name="contact_phone" value="{{ $settings['contact_phone']->value ?? '' }}" class="w-full pl-10 px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
                </div>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Lengkap</label>
                <div class="relative">
                    <div class="absolute top-3 left-0 pl-3 pointer-events-none text-gray-400"><i class="fas fa-map-marker-alt"></i></div>
                    <textarea name="contact_address" rows="2" class="w-full pl-10 px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none resize-none">{{ $settings['contact_address']->value ?? '' }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end" data-aos="fade-up" data-aos-delay="300">
        <button type="submit" class="bg-primary text-white font-bold px-6 py-3 rounded-lg shadow-md shadow-primary/30 hover:bg-primary/90 transition-all flex items-center gap-2">
            <i class="fas fa-save"></i> Simpan Pengaturan
        </button>
    </div>
</form>
@endsection
