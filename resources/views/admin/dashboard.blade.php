@extends('layouts.admin')
@section('page_title', 'Dashboard')

@section('admin_content')
{{-- ===================== STAT CARDS ===================== --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
    $cards = [
        ['label'=>'Total Laporan',  'value'=>$stats['total_reports'],  'icon'=>'fa-clipboard-list', 'color'=>'from-blue-500 to-blue-600',    'sub'=>'Semua laporan',     'delay'=>0],
        ['label'=>'Menunggu',       'value'=>$stats['pending'],         'icon'=>'fa-clock',          'color'=>'from-yellow-400 to-yellow-500', 'sub'=>'Perlu ditangani',   'delay'=>100],
        ['label'=>'Diproses',       'value'=>$stats['process'],         'icon'=>'fa-wrench',         'color'=>'from-purple-500 to-purple-600', 'sub'=>'Sedang dikerjakan', 'delay'=>200],
        ['label'=>'Selesai',        'value'=>$stats['completed'],       'icon'=>'fa-check-circle',   'color'=>'from-emerald-500 to-emerald-600','sub'=>'Berhasil selesai', 'delay'=>300],
    ];
    @endphp

    @foreach($cards as $card)
    <div class="bg-gradient-to-br {{ $card['color'] }} rounded-2xl p-5 text-white shadow-lg card-hover" data-aos="fade-up" data-aos-delay="{{ $card['delay'] }}">
        <div class="flex items-center justify-between mb-3">
            <i class="fas {{ $card['icon'] }} text-2xl text-white/80"></i>
            <span class="text-white/50 text-xs font-medium">{{ $card['sub'] }}</span>
        </div>
        <p class="text-3xl font-extrabold">{{ $card['value'] }}</p>
        <p class="text-white/80 text-sm font-medium mt-1">{{ $card['label'] }}</p>
    </div>
    @endforeach
</div>

{{-- Secondary stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-3 card-hover" data-aos="fade-up">
        <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
            <i class="fas fa-times-circle text-red-500"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['rejected'] }}</p>
            <p class="text-xs text-secondary">Ditolak</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-3 card-hover" data-aos="fade-up" data-aos-delay="100">
        <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center">
            <i class="fas fa-users text-primary"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_users'] }}</p>
            <p class="text-xs text-secondary">Pengguna</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-3 card-hover" data-aos="fade-up" data-aos-delay="200">
        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
            <i class="fas fa-tags text-purple-600"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_categories'] }}</p>
            <p class="text-xs text-secondary">Kategori</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-3 card-hover" data-aos="fade-up" data-aos-delay="300">
        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
            <i class="fas fa-newspaper text-green-600"></i>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total_news'] }}</p>
            <p class="text-xs text-secondary">Berita</p>
        </div>
    </div>
</div>

{{-- Charts --}}
<div class="grid lg:grid-cols-5 gap-6 mb-8">
    <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100 p-6" data-aos="fade-right">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-bold text-gray-800">Tren Laporan Bulanan</h3>
                <p class="text-xs text-secondary">6 bulan terakhir</p>
            </div>
            <span class="bg-primary-100 text-primary text-xs font-semibold px-3 py-1 rounded-full">{{ $stats['percent_completed'] }}% selesai</span>
        </div>
        <canvas id="monthlyChart" height="120"></canvas>
    </div>
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6" data-aos="fade-left">
        <div class="mb-4">
            <h3 class="font-bold text-gray-800">Per Kategori</h3>
            <p class="text-xs text-secondary">Distribusi laporan</p>
        </div>
        <canvas id="categoryChart" height="160"></canvas>
    </div>
</div>

{{-- Latest Reports Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100" data-aos="fade-up">
    <div class="flex items-center justify-between p-6 border-b border-gray-100">
        <div>
            <h3 class="font-bold text-gray-800">Laporan Terbaru</h3>
            <p class="text-xs text-secondary">8 laporan terakhir masuk</p>
        </div>
        <a href="{{ route('admin.reports.index') }}" class="text-primary text-sm font-semibold hover:underline flex items-center gap-1">
            Lihat Semua <i class="fas fa-arrow-right text-xs"></i>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-50 text-left">
                    <th class="px-6 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Kode</th>
                    <th class="px-6 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Laporan</th>
                    <th class="px-6 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Pelapor</th>
                    <th class="px-6 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($latestReports as $report)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-3.5 font-mono text-xs font-semibold text-primary">{{ $report->code }}</td>
                    <td class="px-6 py-3.5">
                        <p class="font-semibold text-gray-800 truncate max-w-xs">{{ $report->title }}</p>
                        <p class="text-xs text-secondary truncate">{{ Str::limit($report->location, 40) }}</p>
                    </td>
                    <td class="px-6 py-3.5">
                        <div class="flex items-center gap-2">
                            <img src="{{ $report->user->avatar_url }}" class="w-6 h-6 rounded-full">
                            <span class="text-gray-700 font-medium">{{ $report->user->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-3.5 text-gray-700">{{ $report->category->name }}</td>
                    <td class="px-6 py-3.5">
                        @php
                        $statusClasses = [
                            'pending'   => 'bg-yellow-100 text-yellow-700',
                            'process'   => 'bg-blue-100 text-blue-700',
                            'completed' => 'bg-green-100 text-green-700',
                            'rejected'  => 'bg-red-100 text-red-700',
                        ];
                        @endphp
                        <span class="status-badge {{ $statusClasses[$report->status] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $report->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-3.5 text-xs text-secondary">{{ $report->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-secondary">Belum ada laporan masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
const monthly = @json($monthlyChart);
new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
        labels: monthly.map(d => d.month),
        datasets: [
            { label: 'Total', data: monthly.map(d => d.total), backgroundColor: 'rgba(37,99,235,0.15)', borderColor: '#2563eb', borderWidth: 2, borderRadius: 6, borderSkipped: false },
            { label: 'Selesai', data: monthly.map(d => d.completed), backgroundColor: 'rgba(34,197,94,0.15)', borderColor: '#22c55e', borderWidth: 2, borderRadius: 6, borderSkipped: false }
        ]
    },
    options: { responsive: true, plugins: { legend: { labels: { font: { family: 'Poppins', size: 11 } } } }, scales: { y: { beginAtZero: true, grid: { color: '#f8fafc' }, ticks: { stepSize: 1 } }, x: { grid: { display: false } } } }
});

const cats = @json($categoryChart);
new Chart(document.getElementById('categoryChart'), {
    type: 'doughnut',
    data: {
        labels: cats.map(c => c.name),
        datasets: [{ data: cats.map(c => c.reports_count), backgroundColor: ['#2563eb','#22c55e','#f59e0b','#ef4444','#8b5cf6','#06b6d4','#ec4899','#64748b'], borderWidth: 0 }]
    },
    options: { responsive: true, cutout: '60%', plugins: { legend: { position: 'bottom', labels: { font: { family: 'Poppins', size: 10 }, boxWidth: 10 } } } }
});
</script>
@endpush
