@extends('layouts.app')
@section('title', $item->title . ' — Pengaduan Desa')
@section('meta_description', $item->excerpt)

@section('content')
<div class="min-h-screen bg-surface flex flex-col">
    <nav class="bg-white border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-14">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl gradient-primary flex items-center justify-center"><span class="text-white text-sm">🏛️</span></div>
                <span class="font-bold text-gray-800 text-sm">Pengaduan Desa</span>
            </a>
            <a href="{{ route('news.index') }}" class="text-sm text-secondary hover:text-primary transition-colors">← Kembali ke Berita</a>
        </div>
    </nav>

    <main class="flex-1 max-w-4xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-10">
        <article data-aos="fade-up">
            <span class="bg-primary text-white text-xs font-semibold px-3 py-1 rounded-full">{{ $item->category }}</span>
            <h1 class="text-2xl lg:text-3xl font-extrabold text-gray-800 mt-4 mb-3 leading-tight">{{ $item->title }}</h1>
            <div class="flex items-center gap-3 text-sm text-secondary mb-6">
                <img src="{{ $item->admin->avatar_url }}" class="w-7 h-7 rounded-full">
                <span>{{ $item->admin->name }}</span>
                <span>•</span>
                <span>{{ $item->created_at->format('d F Y') }}</span>
            </div>
            @if($item->image_url)
            <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full rounded-2xl mb-7 max-h-80 object-cover shadow-md">
            @endif
            <div class="prose prose-gray max-w-none text-gray-700 leading-relaxed">
                {!! nl2br(e($item->content)) !!}
            </div>
        </article>

        {{-- Related --}}
        @if($related->count() > 0)
        <div class="mt-12 pt-8 border-t border-gray-100" data-aos="fade-up">
            <h2 class="text-lg font-bold text-gray-800 mb-5">Berita Lainnya</h2>
            <div class="grid sm:grid-cols-3 gap-4">
                @foreach($related as $r)
                <a href="{{ route('news.show', $r->slug) }}" class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md card-hover border border-gray-100">
                    <div class="h-28 bg-primary-100 overflow-hidden">
                        @if($r->image_url)
                        <img src="{{ $r->image_url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                        <div class="w-full h-full flex items-center justify-center"><span class="text-3xl opacity-20">📰</span></div>
                        @endif
                    </div>
                    <div class="p-3">
                        <p class="font-semibold text-gray-800 text-sm line-clamp-2 group-hover:text-primary transition-colors">{{ $r->title }}</p>
                        <p class="text-xs text-secondary mt-1">{{ $r->created_at->diffForHumans() }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </main>

    @include('layouts.partials.footer')
</div>
@endsection
