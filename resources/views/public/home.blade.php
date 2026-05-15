@extends('layouts.app')
@section('title', 'Beranda — Sistem Pelaporan Infrastruktur Desa')
@section('meta_description', 'Laporkan kerusakan infrastruktur desa Anda dengan mudah dan pantau progres perbaikannya secara real-time.')

@section('content')
{{-- ===================== NAVBAR PUBLIC ===================== --}}
<nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" id="mainNav">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                    <i class="fa-solid fa-landmark text-white text-base"></i>
                </div>
                <div>
                    <p class="text-white font-bold text-sm leading-none">Pengaduan Desa</p>
                    <p class="text-white/70 text-xs leading-none">Infrastruktur</p>
                </div>
            </a>
            <div class="hidden md:flex items-center gap-6">
                <a href="#statistik" class="text-white/80 hover:text-white text-sm font-medium transition-colors">Statistik</a>
                <a href="#berita" class="text-white/80 hover:text-white text-sm font-medium transition-colors">Berita</a>
                <a href="{{ route('login') }}" class="text-white/80 hover:text-white text-sm font-medium transition-colors">Masuk</a>
                <a href="{{ route('register') }}" class="bg-white text-primary font-semibold text-sm px-4 py-2 rounded-xl hover:bg-primary-50 transition-all shadow">Daftar Sekarang</a>
            </div>
            <button class="md:hidden text-white p-2" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
        <div id="mobileMenu" class="hidden md:hidden pb-4">
            <div class="flex flex-col gap-2">
                <a href="{{ route('news.index') }}" class="text-white/80 hover:text-white text-sm font-medium py-2">Berita</a>
                <a href="{{ route('login') }}" class="text-white/80 hover:text-white text-sm font-medium py-2">Masuk</a>
                <a href="{{ route('register') }}" class="bg-white text-primary font-semibold text-sm px-4 py-2 rounded-xl text-center">Daftar</a>
            </div>
        </div>
    </div>
</nav>

{{-- ===================== HERO SECTION ===================== --}}
<section class="relative min-h-screen gradient-primary overflow-hidden flex items-center">
    {{-- Decorative blobs --}}
    <div class="absolute top-20 right-10 w-80 h-80 bg-white/5 rounded-full blur-3xl animate-pulse-slow"></div>
    <div class="absolute bottom-10 left-10 w-60 h-60 bg-blue-300/10 rounded-full blur-3xl animate-pulse-slow" style="animation-delay:1s"></div>
    <div class="absolute top-1/2 left-1/3 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div>
                <div class="inline-flex items-center gap-2 glass px-4 py-2 rounded-full mb-6 animate-fade-in-up" data-aos="fade-right" data-aos-delay="100">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    <span class="text-white/90 text-xs font-medium">Sistem Aktif & Beroperasi</span>
                </div>

                <h1 class="text-4xl lg:text-5xl xl:text-6xl font-extrabold text-white leading-tight mb-6" data-aos="fade-right" data-aos-delay="200">
                    Laporkan Kerusakan<br>
                    <span class="text-yellow-300">Infrastruktur</span><br>
                    Desa Anda
                </h1>

                <p class="text-white/75 text-lg leading-relaxed mb-8" data-aos="fade-right" data-aos-delay="300">
                    Platform digital untuk melaporkan, memantau, dan mengelola kerusakan infrastruktur desa secara transparan dan efisien.
                </p>

                <div class="flex flex-wrap gap-4" data-aos="fade-right" data-aos-delay="400">
                    <a href="{{ route('register') }}" class="group flex items-center gap-2 bg-white text-primary font-bold px-6 py-3.5 rounded-xl hover:bg-yellow-300 hover:text-gray-900 transition-all shadow-lg">
                        <i class="fa-solid fa-file-pen"></i>
                        <span>Buat Laporan</span>
                        <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="#statistik" class="flex items-center gap-2 glass-dark text-white font-semibold px-6 py-3.5 rounded-xl hover:bg-white/20 transition-all">
                        <i class="fa-solid fa-chart-column"></i>
                        <span>Lihat Statistik</span>
                    </a>
                </div>
            </div>

            {{-- Stats cards floating --}}
            <div class="grid grid-cols-2 gap-4" data-aos="fade-left" data-aos-delay="300">
                <div class="glass rounded-2xl p-5 card-hover">
                    <div class="flex items-start justify-between mb-2">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                            <i class="fa-solid fa-clipboard-list text-white text-lg"></i>
                        </div>
                    </div>
                    <p class="text-4xl font-extrabold text-white" id="heroTotal">{{ $stats['total'] }}</p>
                    <p class="text-white/70 text-sm mt-1">Total Laporan</p>
                </div>
                <div class="glass rounded-2xl p-5 card-hover" style="margin-top: 2rem">
                    <div class="flex items-start justify-between mb-2">
                        <div class="w-10 h-10 rounded-xl bg-yellow-400/20 flex items-center justify-center">
                            <i class="fa-solid fa-hourglass-half text-yellow-300 text-lg"></i>
                        </div>
                    </div>
                    <p class="text-4xl font-extrabold text-yellow-300" id="heroPending">{{ $stats['pending'] }}</p>
                    <p class="text-white/70 text-sm mt-1">Menunggu</p>
                </div>
                <div class="glass rounded-2xl p-5 card-hover">
                    <div class="flex items-start justify-between mb-2">
                        <div class="w-10 h-10 rounded-xl bg-blue-400/20 flex items-center justify-center">
                            <i class="fa-solid fa-screwdriver-wrench text-blue-300 text-lg"></i>
                        </div>
                    </div>
                    <p class="text-4xl font-extrabold text-blue-300" id="heroProcess">{{ $stats['process'] }}</p>
                    <p class="text-white/70 text-sm mt-1">Diproses</p>
                </div>
                <div class="glass rounded-2xl p-5 card-hover" style="margin-top: 2rem">
                    <div class="flex items-start justify-between mb-2">
                        <div class="w-10 h-10 rounded-xl bg-green-400/20 flex items-center justify-center">
                            <i class="fa-solid fa-circle-check text-green-300 text-lg"></i>
                        </div>
                    </div>
                    <p class="text-4xl font-extrabold text-green-300" id="heroCompleted">{{ $stats['completed'] }}</p>
                    <p class="text-white/70 text-sm mt-1">Selesai</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Wave divider --}}
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 80L60 69.3C120 58.7 240 37.3 360 32C480 26.7 600 37.3 720 42.7C840 48 960 48 1080 42.7C1200 37.3 1320 26.7 1380 21.3L1440 16V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z" fill="#f8fafc"/>
        </svg>
    </div>
</section>

{{-- ===================== STATISTIK SECTION ===================== --}}
<section id="statistik" class="py-20 bg-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14" data-aos="fade-up">
            <span class="text-primary font-semibold text-sm uppercase tracking-widest">Data Real-time</span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-800 mt-2">Statistik Laporan</h2>
            <p class="text-secondary mt-3 max-w-xl mx-auto">Transparansi data kerusakan infrastruktur desa yang diperbarui secara berkala.</p>
        </div>

        {{-- Progress bar completion rate --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-10 border border-gray-100" data-aos="fade-up" data-aos-delay="100">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-sm font-semibold text-gray-600">Tingkat Penyelesaian Laporan</p>
                    <p class="text-3xl font-extrabold text-success">{{ $stats['percent_completed'] }}%</p>
                </div>
                <div class="w-20 h-20 relative">
                    <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="#e2e8f0" stroke-width="3"/>
                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="#22c55e" stroke-width="3"
                                stroke-dasharray="{{ $stats['percent_completed'] }}, 100" stroke-linecap="round"/>
                    </svg>
                    <span class="absolute inset-0 flex items-center justify-center text-xs font-bold text-success">{{ $stats['percent_completed'] }}%</span>
                </div>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2.5">
                <div class="bg-gradient-to-r from-success-dark to-success rounded-full h-2.5 transition-all duration-1000"
                     style="width: {{ $stats['percent_completed'] }}%"></div>
            </div>
        </div>

        {{-- Chart section --}}
        <div class="grid lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100" data-aos="fade-right" data-aos-delay="200">
                <h3 class="font-bold text-gray-800 mb-1">Tren Laporan Bulanan</h3>
                <p class="text-secondary text-xs mb-4">6 bulan terakhir</p>
                <canvas id="monthlyChart" height="200"></canvas>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100" data-aos="fade-left" data-aos-delay="200">
                <h3 class="font-bold text-gray-800 mb-1">Laporan per Kategori</h3>
                <p class="text-secondary text-xs mb-4">Distribusi kerusakan</p>
                <canvas id="categoryChart" height="200"></canvas>
            </div>
        </div>
    </div>
</section>

{{-- ===================== KATEGORI SECTION ===================== --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="text-primary font-semibold text-sm uppercase tracking-widest">Kategori</span>
            <h2 class="text-3xl font-extrabold text-gray-800 mt-2">Jenis Infrastruktur</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @foreach($categories as $i => $cat)
            <div class="bg-surface rounded-2xl p-5 text-center card-hover border border-gray-100 cursor-pointer" data-aos="fade-up" data-aos-delay="{{ $i * 80 }}">
                <div class="text-4xl mb-3">{{ $cat->icon }}</div>
                <p class="font-semibold text-gray-800 text-sm">{{ $cat->name }}</p>
                <p class="text-xs text-secondary mt-1">{{ $cat->reports->count() }} laporan</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================== BERITA SECTION ===================== --}}
<section id="berita" class="py-20 bg-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-12" data-aos="fade-up">
            <div>
                <span class="text-primary font-semibold text-sm uppercase tracking-widest">Informasi Terkini</span>
                <h2 class="text-3xl font-extrabold text-gray-800 mt-1">Berita Desa</h2>
            </div>
            <a href="{{ route('news.index') }}" class="hidden sm:flex items-center gap-1 text-primary font-semibold text-sm hover:underline">
                Lihat Semua <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        @if($latestNews->isEmpty())
            <div class="text-center py-16 text-secondary">
                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-newspaper text-4xl text-gray-300"></i>
                </div>
                <p>Belum ada berita yang dipublikasikan.</p>
            </div>
        @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($latestNews as $i => $item)
            <a href="{{ route('news.show', $item->slug) }}" class="group bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl card-hover border border-gray-100" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                <div class="h-48 bg-gradient-to-br from-primary-100 to-primary/20 overflow-hidden relative">
                    @if($item->image_url)
                        <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fa-solid fa-newspaper text-6xl opacity-20 text-primary"></i>
                        </div>
                    @endif
                    <span class="absolute top-3 left-3 bg-primary text-white text-xs font-semibold px-2.5 py-1 rounded-full">{{ $item->category }}</span>
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-gray-800 text-base line-clamp-2 group-hover:text-primary transition-colors mb-2">{{ $item->title }}</h3>
                    <p class="text-secondary text-sm line-clamp-2 mb-4">{{ $item->excerpt }}</p>
                    <div class="flex items-center gap-2 text-xs text-secondary">
                        <img src="{{ $item->admin->avatar_url }}" class="w-5 h-5 rounded-full">
                        <span>{{ $item->admin->name }}</span>
                        <span>•</span>
                        <span>{{ $item->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</section>

{{-- ===================== CTA SECTION ===================== --}}
<section class="gradient-primary py-20">
    <div class="max-w-4xl mx-auto px-4 text-center" data-aos="zoom-in">
        <h2 class="text-3xl lg:text-4xl font-extrabold text-white mb-4">Temukan Masalah di Sekitar Anda?</h2>
        <p class="text-white/75 text-lg mb-8">Laporkan sekarang dan bantu kami membangun desa yang lebih baik untuk semua.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-white text-primary font-bold px-8 py-4 rounded-xl hover:bg-yellow-300 hover:text-gray-900 transition-all shadow-lg">
                <i class="fa-solid fa-rocket"></i>
                <span>Mulai Lapor Sekarang</span>
            </a>
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 glass-dark text-white font-semibold px-8 py-4 rounded-xl hover:bg-white/20 transition-all">
                <i class="fa-solid fa-lock"></i>
                <span>Sudah Punya Akun?</span>
            </a>
        </div>
    </div>
</section>

@include('layouts.partials.footer')
@endsection

@push('scripts')
<script>
// Sticky nav background
window.addEventListener('scroll', () => {
    const nav = document.getElementById('mainNav');
    if (window.scrollY > 50) {
        nav.classList.add('bg-gray-900/95', 'backdrop-blur-md', 'shadow-lg');
        nav.classList.remove('bg-transparent');
    } else {
        nav.classList.remove('bg-gray-900/95', 'backdrop-blur-md', 'shadow-lg');
    }
});

// Monthly Chart
const monthlyData = @json($monthlyChart);
new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
        labels: monthlyData.map(d => d.month),
        datasets: [{
            label: 'Total Laporan',
            data: monthlyData.map(d => d.count),
            backgroundColor: 'rgba(37,99,235,0.15)',
            borderColor: '#2563eb',
            borderWidth: 2,
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { stepSize: 1 } },
            x: { grid: { display: false } }
        }
    }
});

// Category Doughnut Chart
const catData = @json($categoriesChart);
new Chart(document.getElementById('categoryChart'), {
    type: 'doughnut',
    data: {
        labels: catData.map(d => d.label),
        datasets: [{
            data: catData.map(d => d.count),
            backgroundColor: ['#2563eb','#22c55e','#f59e0b','#ef4444','#8b5cf6','#06b6d4','#ec4899','#64748b'],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: {
            legend: { position: 'right', labels: { font: { family: 'Poppins', size: 11 }, boxWidth: 12 } }
        }
    }
});
</script>
@endpush
