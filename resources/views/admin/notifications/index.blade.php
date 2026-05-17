@extends('layouts.admin')
@section('page_title', 'Notifikasi Masuk')

@section('admin_content')
{{-- Header Area --}}
<div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h2 class="text-2xl font-extrabold text-gray-800">Pusat Notifikasi</h2>
        <p class="text-sm text-gray-500 mt-1">Dashboard / Notifikasi</p>
    </div>
</div>

{{-- Container Card Utama --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden" data-aos="fade-up">
    
    {{-- Toolbar --}}
    <div class="flex items-center justify-between p-6 border-b border-gray-50">
        <div>
            <h3 class="font-extrabold text-gray-800 text-lg">Daftar Notifikasi</h3>
            <p class="text-xs text-gray-500">{{ auth()->user()->unreadNotifications->count() }} notifikasi belum dibaca</p>
        </div>
        
        @if(auth()->user()->unreadNotifications->count() > 0)
        <form action="{{ route('admin.notifications.read-all') }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 bg-blue-50 text-primary border border-blue-100 rounded-lg text-sm font-semibold hover:bg-blue-100 transition-colors flex items-center gap-2">
                <i class="fas fa-check-double"></i> Tandai Semua Dibaca
            </button>
        </form>
        @endif
    </div>

    {{-- List Notifikasi --}}
    <div class="divide-y divide-gray-50">
        @forelse($notifications as $notification)
        <div class="p-6 flex items-start gap-4 transition-colors {{ $notification->unread() ? 'bg-primary/5 hover:bg-primary/10' : 'hover:bg-gray-50' }}">
            
            {{-- Icon --}}
            <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 {{ $notification->unread() ? 'bg-primary text-white shadow-md' : 'bg-gray-100 text-gray-400' }}">
                <i class="fas fa-bullhorn text-lg"></i>
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0 pt-1">
                <p class="text-sm text-gray-800 leading-relaxed">
                    <span class="font-bold text-primary">{{ $notification->data['user_name'] }}</span> 
                    {{ $notification->data['message'] }} 
                    <span class="font-semibold">"{{ $notification->data['title'] }}"</span>
                </p>
                <div class="flex items-center gap-3 mt-2 text-xs">
                    <span class="text-gray-500 font-medium">
                        <i class="far fa-clock mr-1"></i> {{ $notification->created_at->diffForHumans() }}
                    </span>
                    <span class="text-gray-300">•</span>
                    <span class="font-mono text-primary font-semibold">{{ $notification->data['report_code'] }}</span>
                </div>
            </div>

            {{-- Action Button --}}
            <div class="shrink-0 pt-1">
                <a href="{{ route('admin.notifications.read', $notification->id) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-lg text-primary hover:bg-primary hover:text-white border border-primary/20 transition-all" title="Lihat Laporan">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="p-16 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                <i class="far fa-bell-slash text-3xl text-gray-300"></i>
            </div>
            <h3 class="text-gray-800 font-bold mb-1">Tidak ada notifikasi</h3>
            <p class="text-gray-500 text-sm">Anda telah membaca semua pemberitahuan.</p>
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
@endsection