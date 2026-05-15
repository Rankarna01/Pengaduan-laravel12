@extends('layouts.app')
@section('title', 'Dashboard Saya — Pengaduan Desa')

@section('content')
<div class="min-h-screen bg-surface flex flex-col">
    @include('layouts.partials.navbar-member')

    <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">
        {{-- Welcome --}}
        <div class="gradient-primary rounded-3xl p-7 text-white mb-8" data-aos="fade-up">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/70 text-sm">Selamat datang kembali,</p>
                    <h1 class="text-2xl font-extrabold mt-0.5">{{ auth()->user()->name }} <i class="fas fa-hand-wave"></i></h1>
                    <p class="text-white/70 text-sm mt-1">Pantau status laporan Anda di sini</p>
                </div>
                <img src="{{ auth()->user()->avatar_url }}" class="w-16 h-16 rounded-2xl ring-4 ring-white/30 hidden sm:block" alt="Avatar">
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            @php
            $statCards = [
                ['label'=>'Total Laporan','val'=>$stats['total'],     'icon'=>'fa-clipboard-list','bg'=>'bg-primary-50',  'text'=>'text-primary'],
                ['label'=>'Menunggu',     'val'=>$stats['pending'],   'icon'=>'fa-clock',          'bg'=>'bg-yellow-50',  'text'=>'text-yellow-600'],
                ['label'=>'Diproses',     'val'=>$stats['process'],   'icon'=>'fa-wrench',         'bg'=>'bg-purple-50',  'text'=>'text-purple-600'],
                ['label'=>'Selesai',      'val'=>$stats['completed'], 'icon'=>'fa-check-circle',   'bg'=>'bg-success-50', 'text'=>'text-success-dark'],
            ];
            @endphp
            @foreach($statCards as $i => $c)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 card-hover" data-aos="fade-up" data-aos-delay="{{ $i * 80 }}">
                <div class="w-10 h-10 {{ $c['bg'] }} rounded-xl flex items-center justify-center mb-3">
                    <i class="fas {{ $c['icon'] }} {{ $c['text'] }}"></i>
                </div>
                <p class="text-2xl font-extrabold {{ $c['text'] }}">{{ $c['val'] }}</p>
                <p class="text-xs text-secondary mt-0.5">{{ $c['label'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Recent Reports --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100" data-aos="fade-up">
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <div>
                    <h2 class="font-bold text-gray-800">Laporan Terbaru</h2>
                    <p class="text-xs text-secondary">5 laporan terakhir</p>
                </div>
                <a href="{{ route('member.reports.index') }}" class="text-primary text-sm font-semibold hover:underline flex items-center gap-1">
                    Lihat Semua <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($latestReports as $report)
                @php $statusColors = ['pending'=>'bg-yellow-100 text-yellow-700','process'=>'bg-blue-100 text-blue-700','completed'=>'bg-green-100 text-green-700','rejected'=>'bg-red-100 text-red-700']; @endphp
                <div class="flex items-center gap-4 p-5">
                    <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center text-lg shrink-0">{{ $report->category->icon }}</div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 truncate">{{ $report->title }}</p>
                        <p class="text-xs text-secondary"><span class="font-mono">{{ $report->code }}</span> · {{ $report->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="status-badge {{ $statusColors[$report->status] ?? 'bg-gray-100 text-gray-700' }} shrink-0">{{ $report->status_label }}</span>
                </div>
                @empty
                <div class="p-10 text-center text-secondary">
                    <i class="fas fa-clipboard-list text-4xl text-gray-200 mb-3 block"></i>
                    <p class="text-sm">Belum ada laporan. <a href="{{ route('member.reports.index') }}" class="text-primary font-semibold">Buat sekarang</a></p>
                </div>
                @endforelse
            </div>
        </div>
    </main>

    @include('layouts.partials.footer')
</div>
@endsection
