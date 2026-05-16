@extends('layouts.admin')
@section('page_title', 'Dashboard')

@section('admin_content')
{{-- Header (Opsional, sesuai gambar) --}}
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-extrabold text-gray-800">Dashboard</h2>
        <p class="text-sm text-gray-500">Ringkasan data laporan kerusakan infrastruktur desa.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.export.reports-pdf') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 flex items-center gap-2 shadow-sm">
            <i class="fas fa-download"></i> Export Data
        </a>
        <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 flex items-center gap-2 shadow-sm shadow-primary/30">
            <i class="fas fa-plus"></i> Kelola Laporan
        </a>
    </div>
</div>

{{-- ===================== MAIN STAT CARDS (E-KECAMATAN STYLE) ===================== --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    {{-- Card 1: Total (Solid Primary) --}}
    <div class="bg-primary rounded-xl p-6 text-white shadow-lg shadow-primary/20 relative overflow-hidden" data-aos="fade-up">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
        <p class="text-white/80 text-sm font-medium mb-1">Total Laporan</p>
        <p class="text-4xl font-extrabold mb-4">{{ $stats['total_reports'] ?? 0 }}</p>
        <div class="flex items-center gap-2 text-xs bg-white/20 w-fit px-3 py-1.5 rounded-md backdrop-blur-sm">
            <i class="fas fa-folder-open"></i> Keseluruhan Data
        </div>
    </div>

    {{-- Card 2: Menunggu (White) --}}
    <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between" data-aos="fade-up" data-aos-delay="100">
        <div class="flex justify-between items-start mb-2">
            <p class="text-gray-500 text-sm font-medium">Menunggu</p>
            <div class="w-8 h-8 rounded-full bg-orange-50 flex items-center justify-center">
                <i class="fas fa-clock text-orange-500 text-sm"></i>
            </div>
        </div>
        <p class="text-4xl font-extrabold text-gray-800 mb-4">{{ $stats['pending'] ?? 0 }}</p>
        <div class="flex items-center gap-2 text-xs text-orange-500 font-medium">
            <i class="fas fa-exclamation-circle"></i> Menunggu verifikasi
        </div>
    </div>

    {{-- Card 3: Diproses (White) --}}
    <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between" data-aos="fade-up" data-aos-delay="200">
        <div class="flex justify-between items-start mb-2">
            <p class="text-gray-500 text-sm font-medium">Sedang Diproses</p>
            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center">
                <i class="fas fa-spinner text-blue-500 text-sm"></i>
            </div>
        </div>
        <p class="text-4xl font-extrabold text-gray-800 mb-4">{{ $stats['process'] ?? 0 }}</p>
        <div class="flex items-center gap-2 text-xs text-blue-500 font-medium">
            <i class="fas fa-tools"></i> Sedang ditangani staf
        </div>
    </div>

    {{-- Card 4: Selesai (White) --}}
    <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between" data-aos="fade-up" data-aos-delay="300">
        <div class="flex justify-between items-start mb-2">
            <p class="text-gray-500 text-sm font-medium">Selesai</p>
            <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center">
                <i class="fas fa-check text-green-500 text-sm"></i>
            </div>
        </div>
        <p class="text-4xl font-extrabold text-gray-800 mb-4">{{ $stats['completed'] ?? 0 }}</p>
        <div class="flex items-center gap-2 text-xs text-green-500 font-medium">
            <i class="fas fa-arrow-up"></i> Berhasil diselesaikan
        </div>
    </div>
</div>

{{-- ===================== CHARTS ===================== --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- Line/Bar Chart --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 p-6 shadow-sm" data-aos="fade-up">
        <div class="mb-6">
            <h3 class="font-extrabold text-gray-800 text-lg">Statistik Laporan</h3>
            <p class="text-xs text-gray-500">6 Bulan Terakhir</p>
        </div>
        <div class="relative w-full h-64">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>

    {{-- Doughnut Chart --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm" data-aos="fade-up" data-aos-delay="100">
        <div class="mb-6">
            <h3 class="font-extrabold text-gray-800 text-lg">Persentase Status</h3>
        </div>
        <div class="relative w-full h-48 flex justify-center mb-4">
            <canvas id="categoryChart"></canvas>
        </div>
        <div class="mt-4 space-y-3">
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center gap-2 text-gray-600"><span class="w-3 h-3 rounded-full bg-primary"></span> Selesai</div>
                <span class="font-bold text-gray-800">{{ $stats['percent_completed'] ?? 0 }}%</span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center gap-2 text-gray-600"><span class="w-3 h-3 rounded-full bg-blue-300"></span> Diproses</div>
                <span class="font-bold text-gray-800">{{ $stats['percent_process'] ?? 0 }}%</span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center gap-2 text-gray-600"><span class="w-3 h-3 rounded-full bg-gray-200"></span> Baru/Pending</div>
                <span class="font-bold text-gray-800">{{ $stats['percent_pending'] ?? 0 }}%</span>
            </div>
        </div>
    </div>
</div>

{{-- ===================== TABLE ===================== --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden" data-aos="fade-up">
    <div class="flex items-center justify-between p-6 border-b border-gray-50">
        <h3 class="font-extrabold text-gray-800 text-lg">Laporan Terbaru</h3>
        <a href="{{ route('admin.reports.index') }}" class="text-primary text-sm font-semibold hover:text-primary/80 transition-colors">
            Lihat Semua
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50/50 text-gray-500 font-medium">
                <tr>
                    <th class="px-6 py-4 font-semibold">Kode</th>
                    <th class="px-6 py-4 font-semibold">Pelapor</th>
                    <th class="px-6 py-4 font-semibold">Laporan</th>
                    <th class="px-6 py-4 font-semibold">Kategori</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold text-right">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($latestReports ?? [] as $report)
                <tr class="hover:bg-gray-50/50 transition-colors group">
                    <td class="px-6 py-4">
                        <span class="font-mono text-primary font-semibold">{{ $report->code }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $report->user->avatar_url ?? 'https://ui-avatars.com/api/?name='.$report->user->name }}" class="w-8 h-8 rounded-full bg-gray-100">
                            <span class="text-gray-800 font-medium">{{ $report->user->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-800 font-medium truncate max-w-[200px]">{{ $report->title }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-gray-500">{{ $report->category->name }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $statusBadge = match($report->status) {
                                'pending' => 'bg-orange-50 text-orange-600',
                                'process' => 'bg-blue-50 text-blue-600',
                                'completed' => 'bg-green-50 text-green-600',
                                'rejected' => 'bg-red-50 text-red-600',
                                default => 'bg-gray-50 text-gray-600'
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusBadge }}">
                            {{ ucfirst($report->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right text-gray-500">
                        {{ $report->created_at->format('d M Y') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">Belum ada data laporan masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Chart Bulanan
const monthly = @json($monthlyChart ?? []);
const ctxMonthly = document.getElementById('monthlyChart');
if(ctxMonthly) {
    new Chart(ctxMonthly, {
        type: 'bar',
        data: {
            labels: monthly.map(d => d.month),
            datasets: [
                { 
                    label: 'Total Laporan', 
                    data: monthly.map(d => d.total), 
                    backgroundColor: '#2563eb', // Warna primary
                    borderRadius: 4,
                    barThickness: 12
                }
            ]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false } 
            }, 
            scales: { 
                y: { beginAtZero: true, grid: { color: '#f3f4f6', drawBorder: false }, ticks: { stepSize: 1, color: '#9ca3af' } }, 
                x: { grid: { display: false, drawBorder: false }, ticks: { color: '#9ca3af' } } 
            } 
        }
    });
}

// Chart Doughnut (Status)
const cats = @json($categoryChart ?? []);
const ctxCategory = document.getElementById('categoryChart');
if(ctxCategory) {
    new Chart(ctxCategory, {
        type: 'doughnut',
        data: {
            labels: cats.map(c => c.name),
            datasets: [{ 
                data: cats.map(c => c.reports_count), 
                backgroundColor: ['#2563eb', '#93c5fd', '#e5e7eb', '#10b981', '#f59e0b'], 
                borderWidth: 0 
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            cutout: '75%', 
            plugins: { 
                legend: { display: false } 
            } 
        }
    });
}
</script>
@endpush