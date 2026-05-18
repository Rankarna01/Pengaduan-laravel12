@extends('layouts.app')
@section('title', 'Notifikasi Saya — Pengaduan Desa')

@section('content')
<div class="min-h-screen bg-surface flex flex-col">
    @include('layouts.partials.navbar-member')

    <main class="flex-1 max-w-4xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- Header & Actions --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-800">Notifikasi Saya</h1>
                <p class="text-sm text-secondary mt-1">Pemberitahuan pembaruan status laporan Anda</p>
            </div>
            
            @if(auth()->user()->unreadNotifications->count() > 0)
            <form action="{{ route('member.notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-blue-50 text-primary border border-blue-100 rounded-lg text-sm font-semibold hover:bg-blue-100 transition-colors flex items-center justify-center gap-2 shadow-sm">
                    <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                </button>
            </form>
            @endif
        </div>

        {{-- List Notifikasi --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up">
            <div class="divide-y divide-gray-50">
                @forelse($notifications as $notification)
                <div class="p-6 flex items-start gap-4 transition-all duration-300 {{ $notification->unread() ? 'bg-primary/5 border-l-4 border-l-primary' : 'hover:bg-gray-50 border-l-4 border-l-transparent' }}">
                    
                    {{-- Icon Berdasarkan Status --}}
                    @php
                        $isCompleted = str_contains(strtolower($notification->data['status']), 'selesai');
                        $iconClass = $isCompleted ? 'fa-check-circle text-green-500 bg-green-50' : 'fa-bell text-primary bg-primary-50';
                        $iconClass = str_contains(strtolower($notification->data['status']), 'tolak') ? 'fa-times-circle text-red-500 bg-red-50' : $iconClass;
                    @endphp
                    
                    <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 {{ $iconClass }}">
                        <i class="fas {{ str_replace(strstr($iconClass, ' text-'), '', $iconClass) }} text-xl"></i>
                    </div>

                    {{-- Isi Pesan --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-800 leading-relaxed font-medium">
                            {{ $notification->data['message'] }} 
                            <span class="font-bold text-primary block mt-0.5">{{ $notification->data['title'] }}</span>
                        </p>
                        
                        @if(isset($notification->data['response']) && $notification->data['response'])
                        <div class="mt-3 bg-gray-50 border border-gray-100 rounded-lg p-3 text-sm text-gray-600 italic">
                            <span class="font-semibold text-gray-700 not-italic block mb-1">Pesan dari Admin:</span>
                            "{{ $notification->data['response'] }}"
                        </div>
                        @endif

                        <div class="flex items-center gap-3 mt-3 text-xs">
                            <span class="text-gray-500 font-medium">
                                <i class="far fa-clock mr-1"></i> {{ $notification->created_at->diffForHumans() }}
                            </span>
                            <span class="text-gray-300">•</span>
                            <span class="font-mono text-primary font-semibold bg-primary/10 px-2 py-0.5 rounded">{{ $notification->data['report_code'] }}</span>
                        </div>
                    </div>

                    {{-- Action Button --}}
                    <div class="shrink-0 pt-2 hidden sm:block">
                        <a href="{{ route('member.notifications.read', $notification->id) }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-primary text-sm font-semibold hover:bg-primary hover:text-white border border-primary/20 transition-all">
                            Lihat Laporan
                        </a>
                    </div>
                </div>
                @empty
                <div class="p-16 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                        <i class="far fa-bell-slash text-3xl text-gray-300"></i>
                    </div>
                    <h3 class="text-gray-800 font-bold mb-1 text-lg">Belum Ada Notifikasi</h3>
                    <p class="text-gray-500 text-sm max-w-xs mx-auto">Anda akan menerima pemberitahuan di sini saat admin menanggapi laporan Anda.</p>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($notifications->hasPages())
            <div class="p-4 border-t border-gray-50 bg-gray-50/50">
                {{ $notifications->links() }}
            </div>
            @endif
        </div>
    </main>

    @include('layouts.partials.footer')
</div>
@endsection