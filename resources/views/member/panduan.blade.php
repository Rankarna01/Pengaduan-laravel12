@extends('layouts.app')
@section('title', 'Panduan Pelaporan — Pengaduan Desa')

@section('content')
<div class="min-h-screen bg-surface flex flex-col">
    @include('layouts.partials.navbar-member')

    <main class="flex-1 max-w-4xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-10">
        
        {{-- Header Panduan --}}
        <div class="text-center mb-12" data-aos="fade-up">
            <div class="w-16 h-16 bg-blue-100 text-primary rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4 shadow-sm border border-blue-200">
                <i class="fas fa-book-reader"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-800">Panduan Pelaporan</h1>
            <p class="text-gray-500 mt-2 max-w-xl mx-auto">Ikuti langkah-langkah di bawah ini untuk membuat laporan kerusakan infrastruktur yang baik, benar, dan cepat diproses oleh admin.</p>
        </div>

        {{-- Timeline / Steps --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-10" data-aos="fade-up" data-aos-delay="100">
            <div class="relative border-l-4 border-primary/20 ml-4 sm:ml-6 space-y-10 py-2">
                
                {{-- Step 1 --}}
                <div class="relative pl-8 sm:pl-10">
                    <div class="absolute -left-5 sm:-left-6 top-0 w-10 h-10 sm:w-12 sm:h-12 bg-primary text-white rounded-full flex items-center justify-center font-bold text-lg shadow-lg shadow-primary/30 border-4 border-white">1</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Pilih Kategori yang Tepat</h3>
                    <p class="text-gray-600 text-sm leading-relaxed mb-4">Pastikan Anda memilih kategori infrastruktur yang sesuai dengan kerusakan (Misal: Jalan, Jembatan, atau Irigasi). Hal ini membantu kami meneruskan laporan ke divisi yang tepat.</p>
                </div>

                {{-- Step 2 --}}
                <div class="relative pl-8 sm:pl-10">
                    <div class="absolute -left-5 sm:-left-6 top-0 w-10 h-10 sm:w-12 sm:h-12 bg-primary text-white rounded-full flex items-center justify-center font-bold text-lg shadow-lg shadow-primary/30 border-4 border-white">2</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Isi Detail Kerusakan</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start gap-2"><i class="fas fa-check text-green-500 mt-1"></i> <b>Judul:</b> Buat judul yang singkat dan jelas (Contoh: "Jalan Berlubang di RT 03").</li>
                        <li class="flex items-start gap-2"><i class="fas fa-check text-green-500 mt-1"></i> <b>Lokasi:</b> Tuliskan alamat lengkap atau patokan lokasi agar petugas mudah menemukannya.</li>
                        <li class="flex items-start gap-2"><i class="fas fa-check text-green-500 mt-1"></i> <b>Deskripsi:</b> Ceritakan seberapa parah kerusakan dan dampaknya bagi warga sekitar.</li>
                    </ul>
                </div>

                {{-- Step 3 --}}
                <div class="relative pl-8 sm:pl-10">
                    <div class="absolute -left-5 sm:-left-6 top-0 w-10 h-10 sm:w-12 sm:h-12 bg-primary text-white rounded-full flex items-center justify-center font-bold text-lg shadow-lg shadow-primary/30 border-4 border-white">3</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Lampirkan Foto Bukti (Sangat Disarankan)</h3>
                    <p class="text-gray-600 text-sm leading-relaxed mb-4">Meski opsional, melampirkan foto kondisi nyata sangat membantu admin dalam memvalidasi dan memprioritaskan perbaikan. Pastikan foto terang dan jelas.</p>
                    <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 flex gap-4">
                        <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                        <p class="text-xs text-gray-500">Maksimal ukuran file foto adalah 5MB. Format yang didukung: JPG, PNG, WEBP.</p>
                    </div>
                </div>

                {{-- Step 4 --}}
                <div class="relative pl-8 sm:pl-10">
                    <div class="absolute -left-5 sm:-left-6 top-0 w-10 h-10 sm:w-12 sm:h-12 bg-green-500 text-white rounded-full flex items-center justify-center font-bold text-lg shadow-lg shadow-green-500/30 border-4 border-white"><i class="fas fa-flag-checkered"></i></div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Pantau Status Laporan</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Setelah laporan dikirim, status awalnya adalah <span class="bg-orange-100 text-orange-600 px-2 py-0.5 rounded font-bold text-xs">Menunggu</span>. Anda bisa memantau perkembangan laporan dan membaca tanggapan dari Admin melalui menu <b>"Laporan Saya"</b> atau <b>"Notifikasi"</b>.</p>
                </div>

            </div>
        </div>

        {{-- CTA Button --}}
        <div class="mt-10 text-center">
            <a href="{{ route('member.reports.index') }}" class="inline-flex items-center gap-2 bg-primary text-white font-bold px-8 py-3.5 rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-primary/30">
                <i class="fas fa-rocket"></i> Saya Mengerti, Buat Laporan Sekarang
            </a>
        </div>

    </main>

    @include('layouts.partials.footer')
</div>
@endsection