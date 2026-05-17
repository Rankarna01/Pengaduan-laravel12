@extends('layouts.app')
@section('title', 'Berita Desa — ' . ($globalSettings['system_name'] ?? 'Pengaduan Desa'))

@section('content')
<div class="min-h-screen bg-surface flex flex-col">
    {{-- Simple public navbar --}}
    <nav class="bg-white border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-14">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl gradient-primary flex items-center justify-center"><span class="text-white text-sm">🏛️</span></div>
                <span class="font-bold text-gray-800 text-sm">Pengaduan Desa</span>
            </a>
            <div class="flex items-center gap-4 text-sm">
                <a href="{{ route('home') }}" class="text-secondary hover:text-primary transition-colors">Beranda</a>
                @auth
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('member.dashboard') }}" class="btn-primary text-white px-4 py-1.5 rounded-lg text-xs font-semibold">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-secondary hover:text-primary">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-primary text-white px-4 py-1.5 rounded-lg text-xs font-semibold">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-10">
        <div class="text-center mb-10" data-aos="fade-up">
            <span class="text-primary font-semibold text-sm uppercase tracking-widest">Informasi</span>
            <h1 class="text-3xl font-extrabold text-gray-800 mt-1">Berita Desa</h1>
        </div>

        @if($news->isEmpty())
        <div class="text-center py-20 text-secondary"><p class="text-5xl mb-4">📰</p><p>Belum ada berita.</p></div>
        @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($news as $i => $item)
            <a href="{{ route('news.show', $item->slug) }}" class="group bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl card-hover border border-gray-100" data-aos="fade-up" data-aos-delay="{{ ($i % 3) * 100 }}">
                <div class="h-44 bg-primary-100 overflow-hidden relative">
                    @if($item->image_url)
                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                    <div class="w-full h-full flex items-center justify-center"><span class="text-5xl opacity-20">📰</span></div>
                    @endif
                    <span class="absolute top-3 left-3 bg-primary text-white text-xs font-semibold px-2.5 py-1 rounded-full">{{ $item->category }}</span>
                </div>
                <div class="p-5">
                    <h2 class="font-bold text-gray-800 line-clamp-2 group-hover:text-primary transition-colors mb-2">{{ $item->title }}</h2>
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
        <div class="mt-8">{{ $news->links() }}</div>
        @endif
    </main>

    @include('layouts.partials.footer')
</div>
@endsection
