@extends('layouts.app')
@section('title', 'Beranda — Sistem Pelaporan Infrastruktur Desa')
@section('meta_description', 'Laporkan kerusakan infrastruktur desa Anda dengan mudah dan pantau progres perbaikannya secara real-time.')

@section('content')
{{-- ===================== NAVBAR PUBLIC ===================== --}}
<nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" id="mainNav">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                    @if(isset($globalSettings['logo_url']) && $globalSettings['logo_url'])
                        <img src="{{ asset('storage/' . $globalSettings['logo_url']) }}" class="w-full h-full object-cover rounded-xl" alt="Logo">
                    @else
                        <i class="fa-solid fa-landmark text-white text-base"></i>
                    @endif
                </div>
                <div>
                    <p class="text-white font-bold text-sm leading-none">{{ $globalSettings['system_name'] ?? 'Pengaduan Desa' }}</p>
                    <p class="text-white/70 text-xs leading-none">{{ $globalSettings['system_sub_name'] ?? 'Infrastruktur' }}</p>
                </div>
            </a>
            <div class="hidden md:flex items-center gap-8">
                <a href="#statistik" class="text-white/90 hover:text-white text-sm font-semibold transition-colors">Statistik</a>
                <a href="#berita" class="text-white/90 hover:text-white text-sm font-semibold transition-colors">Berita</a>
                <div class="w-px h-5 bg-white/20"></div>
                <a href="{{ route('login') }}" class="text-white/90 hover:text-white text-sm font-semibold transition-colors">Masuk</a>
                <a href="{{ route('register') }}" class="bg-white text-primary font-bold text-sm px-5 py-2.5 rounded-xl hover:shadow-lg hover:shadow-white/20 hover:-translate-y-0.5 transition-all">
                    Daftar Sekarang
                </a>
            </div>
            <button class="md:hidden text-white p-2 rounded-lg bg-white/10 backdrop-blur-sm border border-white/10" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
        </div>
        <div id="mobileMenu" class="hidden md:hidden pb-6 pt-2">
            <div class="flex flex-col gap-3 bg-white rounded-2xl p-4 shadow-2xl">
                <a href="#statistik" class="text-gray-700 hover:text-primary text-sm font-semibold py-2 px-3 rounded-lg hover:bg-gray-50">Statistik</a>
                <a href="#berita" class="text-gray-700 hover:text-primary text-sm font-semibold py-2 px-3 rounded-lg hover:bg-gray-50">Berita</a>
                <div class="h-px bg-gray-100 my-1"></div>
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary text-sm font-semibold py-2 px-3 rounded-lg hover:bg-gray-50">Masuk</a>
                <a href="{{ route('register') }}" class="bg-primary text-white font-bold text-sm px-4 py-3 rounded-xl text-center shadow-md shadow-primary/20">Daftar Akun</a>
            </div>
        </div>
    </div>
</nav>

{{-- ===================== HERO SECTION ===================== --}}
<section class="relative min-h-screen bg-primary overflow-hidden flex items-center pt-20">
    {{-- Decorative Background --}}
    <div class="absolute inset-0 bg-gradient-to-br from-primary via-blue-600 to-primary opacity-90"></div>
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-white/10 rounded-full blur-[100px] animate-pulse-slow"></div>
    <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-green-400/20 rounded-full blur-[100px] animate-pulse-slow" style="animation-delay:2s"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 w-full">
        <div class="grid lg:grid-cols-12 gap-12 lg:gap-8 items-center">
            <div class="lg:col-span-7 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2 rounded-full mb-8 animate-fade-in-up" data-aos="fade-right">
                    <span class="relative flex h-2.5 w-2.5">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                    </span>
                    <span class="text-white text-xs font-semibold tracking-wide">Sistem Aktif & Beroperasi</span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-[1.15] mb-6" data-aos="fade-right" data-aos-delay="100">
                    Laporkan Kerusakan<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-100">Infrastruktur</span><br>
                    Desa Kita
                </h1>

                <p class="text-blue-100 text-lg sm:text-xl leading-relaxed mb-10 max-w-2xl mx-auto lg:mx-0" data-aos="fade-right" data-aos-delay="200">
                    Platform digital terpadu untuk melaporkan, memantau, dan mengelola perbaikan fasilitas desa secara transparan dan efisien.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4" data-aos="fade-right" data-aos-delay="300">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto group flex items-center justify-center gap-2 bg-white text-primary font-bold px-8 py-4 rounded-xl hover:bg-gray-50 transition-all shadow-xl shadow-black/10">
                        <i class="fa-solid fa-file-pen text-lg"></i>
                        <span>Buat Laporan Sekarang</span>
                        <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="#statistik" class="w-full sm:w-auto flex items-center justify-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 text-white font-semibold px-8 py-4 rounded-xl hover:bg-white/20 transition-all">
                        <i class="fa-solid fa-chart-pie"></i>
                        <span>Lihat Statistik</span>
                    </a>
                </div>
            </div>

            {{-- Floating Stats Cards --}}
            <div class="lg:col-span-5 relative" data-aos="zoom-in" data-aos-delay="400">
                <div class="grid grid-cols-2 gap-4 sm:gap-6 relative z-10">
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl p-6 hover:-translate-y-2 transition-transform duration-300">
                        <div class="w-12 h-12 rounded-xl bg-blue-400/30 flex items-center justify-center mb-4">
                            <i class="fa-solid fa-folder-open text-blue-100 text-xl"></i>
                        </div>
                        <p class="text-4xl font-extrabold text-white mb-1">{{ $stats['total'] ?? 0 }}</p>
                        <p class="text-blue-100 text-sm font-medium">Total Laporan</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl p-6 hover:-translate-y-2 transition-transform duration-300 mt-0 sm:mt-12">
                        <div class="w-12 h-12 rounded-xl bg-orange-400/30 flex items-center justify-center mb-4">
                            <i class="fa-solid fa-clock text-orange-200 text-xl"></i>
                        </div>
                        <p class="text-4xl font-extrabold text-orange-200 mb-1">{{ $stats['pending'] ?? 0 }}</p>
                        <p class="text-blue-100 text-sm font-medium">Menunggu Verifikasi</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl p-6 hover:-translate-y-2 transition-transform duration-300">
                        <div class="w-12 h-12 rounded-xl bg-yellow-400/30 flex items-center justify-center mb-4">
                            <i class="fa-solid fa-screwdriver-wrench text-yellow-200 text-xl"></i>
                        </div>
                        <p class="text-4xl font-extrabold text-yellow-200 mb-1">{{ $stats['process'] ?? 0 }}</p>
                        <p class="text-blue-100 text-sm font-medium">Sedang Diproses</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl p-6 hover:-translate-y-2 transition-transform duration-300 mt-0 sm:mt-12">
                        <div class="w-12 h-12 rounded-xl bg-green-400/30 flex items-center justify-center mb-4">
                            <i class="fa-solid fa-check-double text-green-300 text-xl"></i>
                        </div>
                        <p class="text-4xl font-extrabold text-green-300 mb-1">{{ $stats['completed'] ?? 0 }}</p>
                        <p class="text-blue-100 text-sm font-medium">Laporan Selesai</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Wave divider --}}
    <div class="absolute bottom-0 left-0 right-0 z-20">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto text-surface">
            <path d="M0 80L60 69.3C120 58.7 240 37.3 360 32C480 26.7 600 37.3 720 42.7C840 48 960 48 1080 42.7C1200 37.3 1320 26.7 1380 21.3L1440 16V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z" fill="currentColor"/>
        </svg>
    </div>
</section>

{{-- ===================== STATISTIK & GRAFIK SECTION ===================== --}}
<section id="statistik" class="py-24 bg-surface relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16" data-aos="fade-up">
            <span class="text-primary font-extrabold tracking-wider uppercase text-xs bg-primary/10 px-4 py-2 rounded-full">
                Transparansi Data
            </span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800 mt-6 mb-4">Perkembangan Laporan</h2>
            <p class="text-gray-500 text-sm md:text-base leading-relaxed">Pantau distribusi dan penyelesaian laporan kerusakan infrastruktur di desa secara terbuka.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            {{-- Grafik Batang (Bulanan) --}}
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-xl shadow-gray-200/40" data-aos="fade-right">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg">Tren Pengaduan</h3>
                        <p class="text-xs text-gray-400 mt-1">Laporan masuk 6 bulan terakhir</p>
                    </div>
                    <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-column text-primary"></i>
                    </div>
                </div>
                <div class="relative w-full h-64">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>

            {{-- Grafik Doughnut (Kategori) --}}
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-xl shadow-gray-200/40" data-aos="fade-left">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg">Distribusi Kategori</h3>
                        <p class="text-xs text-gray-400 mt-1">Berdasarkan jenis infrastruktur</p>
                    </div>
                    <div class="w-10 h-10 bg-green-500/10 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-pie text-green-500"></i>
                    </div>
                </div>
                <div class="relative w-full h-64 flex justify-center">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===================== KATEGORI SECTION ===================== --}}
<section class="py-24 bg-white relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-3xl font-extrabold text-gray-800 mb-4">Fasilitas yang Dilayani</h2>
            <p class="text-gray-500 text-sm max-w-2xl mx-auto">Kami mengkategorikan laporan untuk mempercepat proses identifikasi dan perbaikan oleh dinas terkait.</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
            @foreach($categories ?? [] as $i => $cat)
            <div class="group bg-surface rounded-3xl p-6 sm:p-8 text-center border border-gray-100 hover:border-primary hover:shadow-xl hover:shadow-primary/10 transition-all duration-300" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                <div class="w-16 h-16 mx-auto bg-white rounded-2xl flex items-center justify-center text-3xl text-gray-400 group-hover:text-primary group-hover:scale-110 transition-all duration-300 shadow-sm mb-5">
                    <i class="{{ $cat->icon }}"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-1 group-hover:text-primary transition-colors">{{ $cat->name }}</h3>
                <p class="text-xs text-gray-400 font-medium bg-white px-3 py-1 rounded-full inline-block border border-gray-50">{{ $cat->reports->count() ?? 0 }} Laporan</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================== BERITA SECTION ===================== --}}
<section id="berita" class="py-24 bg-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between mb-12 gap-4" data-aos="fade-up">
            <div>
                <span class="text-primary font-extrabold tracking-wider uppercase text-xs bg-primary/10 px-4 py-2 rounded-full mb-4 inline-block">
                    Informasi Terkini
                </span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800 mt-2">Kabar Desa</h2>
            </div>
            <a href="{{ route('news.index') }}" class="group flex items-center gap-2 bg-white border border-gray-200 text-gray-700 font-bold px-6 py-3 rounded-xl hover:bg-gray-50 transition-all shadow-sm">
                Lihat Semua Berita <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        @if(isset($latestNews) && $latestNews->isEmpty())
            <div class="bg-white rounded-3xl border border-gray-100 p-16 text-center shadow-sm">
                <div class="w-20 h-20 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-4 border border-gray-100">
                    <i class="fa-solid fa-newspaper text-3xl text-gray-300"></i>
                </div>
                <p class="text-gray-500 font-medium">Belum ada berita yang dipublikasikan saat ini.</p>
            </div>
        @else
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($latestNews ?? [] as $i => $item)
            <a href="{{ route('news.show', $item->slug) }}" class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl border border-gray-100 transition-all duration-300 flex flex-col" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                <div class="h-56 bg-gray-100 overflow-hidden relative">
                    @if($item->image_url)
                        <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100">
                            <i class="fa-solid fa-image text-4xl text-gray-300"></i>
                        </div>
                    @endif
                    <div class="absolute top-4 left-4">
                        <span class="bg-white/90 backdrop-blur text-gray-800 text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm">
                            {{ $item->category ?? 'Informasi' }}
                        </span>
                    </div>
                </div>
                <div class="p-6 sm:p-8 flex-1 flex flex-col">
                    <div class="flex items-center gap-2 text-xs text-gray-400 font-medium mb-3">
                        <i class="fa-regular fa-calendar"></i>
                        <span>{{ $item->created_at->format('d M Y') }}</span>
                    </div>
                    <h3 class="font-extrabold text-gray-800 text-lg line-clamp-2 group-hover:text-primary transition-colors mb-3">{{ $item->title }}</h3>
                    <p class="text-gray-500 text-sm line-clamp-2 mb-6 flex-1">{{ $item->excerpt }}</p>
                    <div class="flex items-center gap-3 pt-4 border-t border-gray-50 mt-auto">
                        <img src="{{ $item->admin->avatar_url ?? 'https://ui-avatars.com/api/?name=Admin' }}" class="w-8 h-8 rounded-full bg-gray-100">
                        <div>
                            <p class="text-xs font-bold text-gray-800">{{ $item->admin->name ?? 'Admin Desa' }}</p>
                            <p class="text-[10px] text-gray-400 uppercase tracking-wide">Penulis</p>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</section>

{{-- ===================== CTA SECTION ===================== --}}
<section class="bg-gray-900 py-24 relative overflow-hidden">
    <div class="absolute inset-0 bg-primary/10"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-primary/20 rounded-full blur-[100px]"></div>
    <div class="relative max-w-4xl mx-auto px-4 text-center" data-aos="zoom-in">
        <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-6">Mulai Laporkan Masalah di Sekitar Anda</h2>
        <p class="text-gray-400 text-lg mb-10 max-w-2xl mx-auto">Partisipasi Anda sangat berarti. Laporkan kerusakan sekarang dan bantu kami mempercepat proses pembangunan desa.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 bg-primary text-white font-bold px-8 py-4 rounded-xl hover:bg-primary/90 transition-all shadow-lg shadow-primary/30">
                <i class="fa-solid fa-rocket"></i>
                <span>Daftar & Buat Laporan</span>
            </a>
        </div>
    </div>
</section>

@include('layouts.partials.footer')
@endsection

@push('scripts')
{{-- Load Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Sticky Navbar Logic
window.addEventListener('scroll', () => {
    const nav = document.getElementById('mainNav');
    if (window.scrollY > 50) {
        nav.classList.add('bg-primary', 'shadow-lg');
        nav.classList.remove('bg-transparent', 'py-2');
    } else {
        nav.classList.remove('bg-primary', 'shadow-lg');
        nav.classList.add('bg-transparent', 'py-2');
    }
});

// ===================== CHARTS CONFIGURATION =====================
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Monthly Bar Chart
    const ctxMonthly = document.getElementById('monthlyChart');
    if(ctxMonthly) {
        const monthlyData = @json($monthlyChart ?? []);
        new Chart(ctxMonthly, {
            type: 'bar',
            data: {
                labels: monthlyData.map(d => d.month),
                datasets: [{
                    label: 'Total Laporan Masuk',
                    data: monthlyData.map(d => d.count),
                    backgroundColor: '#2563eb', // Warna Biru Primary
                    borderRadius: 6,
                    barThickness: 20
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: '#f3f4f6', drawBorder: false }, 
                        ticks: { stepSize: 1, color: '#9ca3af', font: {family: "'Poppins', sans-serif"} } 
                    },
                    x: { 
                        grid: { display: false, drawBorder: false }, 
                        ticks: { color: '#6b7280', font: {family: "'Poppins', sans-serif"} } 
                    }
                }
            }
        });
    }

    // 2. Category Doughnut Chart
    const ctxCategory = document.getElementById('categoryChart');
    if(ctxCategory) {
        const catData = @json($categoriesChart ?? []);
        new Chart(ctxCategory, {
            type: 'doughnut',
            data: {
                labels: catData.map(d => d.label),
                datasets: [{
                    data: catData.map(d => d.count),
                    // Palette warna modern
                    backgroundColor: ['#2563eb', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { 
                        position: 'right', 
                        labels: { 
                            usePointStyle: true,
                            padding: 20,
                            font: { family: "'Poppins', sans-serif", size: 12 } 
                        } 
                    }
                }
            }
        });
    }
});
</script>
@endpush