@extends('layouts.app')
@section('title', 'Dashboard Saya — Pengaduan Desa')

@section('content')
<div class="min-h-screen bg-surface flex flex-col">
    @include('layouts.partials.navbar-member')

    <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8 mt-16 md:mt-0">
        
        {{-- Welcome Banner --}}
        <div class="bg-primary rounded-3xl p-8 text-white mb-8 shadow-lg shadow-primary/20 relative overflow-hidden" data-aos="fade-up">
            {{-- Efek dekorasi di dalam banner --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
            <div class="absolute bottom-0 left-0 w-40 h-40 bg-blue-400/20 rounded-full blur-2xl translate-y-1/3 -translate-x-1/4"></div>
            
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <p class="text-white/80 text-sm font-medium mb-1">Selamat datang kembali,</p>
                    <h1 class="text-2xl sm:text-3xl font-extrabold">{{ auth()->user()->name ?? 'Pengguna' }} <i class="fas fa-hand-wave ml-1 animate-pulse"></i></h1>
                    <p class="text-white/80 text-sm mt-2">Pantau dan kelola semua status laporan kerusakan Anda di sini.</p>
                </div>
                <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.(auth()->user()->name ?? 'User') }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl border-4 border-white/20 shadow-sm hidden sm:block object-cover" alt="Avatar">
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
            @php
            $statCards = [
                ['label'=>'Total Laporan','val'=>$stats['total'] ?? 0,     'icon'=>'fa-folder-open',   'bg'=>'bg-blue-50',    'text'=>'text-blue-600', 'border'=>'border-blue-100',   'status'=>'all'],
                ['label'=>'Menunggu',     'val'=>$stats['pending'] ?? 0,   'icon'=>'fa-clock',         'bg'=>'bg-orange-50',  'text'=>'text-orange-500','border'=>'border-orange-100', 'status'=>'pending'],
                ['label'=>'Diproses',     'val'=>$stats['process'] ?? 0,   'icon'=>'fa-tools',         'bg'=>'bg-purple-50',  'text'=>'text-purple-500','border'=>'border-purple-100', 'status'=>'process'],
                ['label'=>'Selesai',      'val'=>$stats['completed'] ?? 0, 'icon'=>'fa-check-circle',  'bg'=>'bg-green-50',   'text'=>'text-green-500', 'border'=>'border-green-100',  'status'=>'completed'],
            ];
            @endphp
            
            @foreach($statCards as $i => $c)
            <a href="{{ route('member.dashboard') }}?status={{ $c['status'] }}"
               class="bg-white rounded-2xl border shadow-sm p-6 hover:-translate-y-1 transition-transform duration-300 block
                      {{ $activeStatus === $c['status'] ? 'border-primary ring-2 ring-primary/20' : 'border-gray-100' }}"
               data-aos="fade-up" data-aos-delay="{{ $i * 80 }}">
                <div class="w-12 h-12 {{ $c['bg'] }} border {{ $c['border'] }} rounded-xl flex items-center justify-center mb-4">
                    <i class="fas {{ $c['icon'] }} {{ $c['text'] }} text-lg"></i>
                </div>
                <p class="text-3xl font-extrabold text-gray-800 mb-1">{{ $c['val'] }}</p>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ $c['label'] }}</p>
            </a>
            @endforeach
        </div>

        {{-- Recent Reports --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="flex items-center justify-between p-6 border-b border-gray-50">
                <div>
                <h2 class="font-extrabold text-gray-800 text-lg">
                    @if($activeStatus === 'all') Laporan Terbaru
                    @elseif($activeStatus === 'pending') Laporan Menunggu
                    @elseif($activeStatus === 'process') Laporan Diproses
                    @elseif($activeStatus === 'completed') Laporan Selesai
                    @elseif($activeStatus === 'rejected') Laporan Ditolak
                    @endif
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">
                    {{ $activeStatus === 'all' ? '5 laporan terakhir yang Anda buat' : 'Laporan berdasarkan status yang dipilih' }}
                </p>
            </div>
            <a href="{{ route('member.reports.index') }}" class="text-primary text-sm font-bold hover:text-primary/80 flex items-center gap-2 transition-colors">
                Lihat Semua <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>

        {{-- Filter Tabs --}}
        <div class="flex flex-wrap gap-2 px-6 pb-4 border-b border-gray-50">
            @php
            $tabs = [
                'all'       => ['label' => 'Semua',    'color' => 'text-gray-600',   'bg' => 'bg-gray-100',   'active_bg' => 'bg-gray-800 text-white'],
                'pending'   => ['label' => 'Menunggu', 'color' => 'text-orange-600', 'bg' => 'bg-orange-50',  'active_bg' => 'bg-orange-500 text-white'],
                'process'   => ['label' => 'Diproses', 'color' => 'text-blue-600',   'bg' => 'bg-blue-50',    'active_bg' => 'bg-blue-600 text-white'],
                'completed' => ['label' => 'Selesai',  'color' => 'text-green-600',  'bg' => 'bg-green-50',   'active_bg' => 'bg-green-600 text-white'],
                'rejected'  => ['label' => 'Ditolak',  'color' => 'text-red-600',    'bg' => 'bg-red-50',     'active_bg' => 'bg-red-500 text-white'],
            ];
            @endphp
            @foreach($tabs as $key => $tab)
            <a href="{{ route('member.dashboard') }}?status={{ $key }}"
               class="px-4 py-1.5 rounded-full text-xs font-bold transition-all
                      {{ $activeStatus === $key ? $tab['active_bg'] : $tab['color'] . ' ' . $tab['bg'] . ' hover:opacity-80' }}">
                {{ $tab['label'] }}
                @if($key !== 'all')
                <span class="ml-1 opacity-70">({{ $stats[$key] ?? 0 }})</span>
                @endif
            </a>
            @endforeach
            </div>
            
            <div class="divide-y divide-gray-50">
                @forelse($latestReports ?? [] as $report)
                @php 
                    $statusColors = [
                        'pending'   => 'bg-orange-50 text-orange-600',
                        'process'   => 'bg-blue-50 text-blue-600',
                        'completed' => 'bg-green-50 text-green-600',
                        'rejected'  => 'bg-red-50 text-red-600'
                    ]; 
                @endphp
                <div class="flex items-center gap-4 p-5 hover:bg-gray-50/50 transition-colors">
                    {{-- FIX: Icon dibungkus tag <i> agar merender FontAwesome dengan benar --}}
                    <div class="w-12 h-12 bg-gray-50 border border-gray-100 rounded-xl flex items-center justify-center text-lg shrink-0 text-gray-600">
                        <i class="{{ $report->category->icon }}"></i>
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-800 truncate">{{ $report->title }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs font-mono font-semibold text-primary bg-primary/10 px-2 py-0.5 rounded-md">{{ $report->code }}</span>
                            <span class="text-xs text-gray-400">&bull; {{ $report->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    
                    <span class="px-3 py-1 rounded-full text-xs font-bold shrink-0 {{ $statusColors[$report->status] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ $report->status_label }}
                    </span>
                </div>
                @empty
                <div class="p-12 text-center text-gray-400">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                        <i class="fas fa-clipboard-list text-2xl text-gray-300"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-500 mb-2">Anda belum membuat laporan apapun.</p>
                    <a href="{{ route('member.reports.index') }}" class="text-primary font-bold hover:underline text-sm">Buat Laporan Pertama</a>
                </div>
                @endforelse
            </div>
        </div>
    </main>

    @include('layouts.partials.footer')
</div>
@endsection