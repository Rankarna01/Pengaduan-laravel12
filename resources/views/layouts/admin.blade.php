@extends('layouts.app')
@section('title', 'Admin Panel — Pengaduan Desa')

@section('content')
<div class="flex min-h-screen bg-surface">
    {{-- Sidebar --}}
    @include('layouts.partials.sidebar-admin')

    {{-- Main Content --}}
    <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">
        {{-- Top Header --}}
        <header class="sticky top-0 z-30 bg-white border-b border-gray-100 shadow-sm">
            <div class="flex items-center justify-between px-6 h-16">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-bars text-gray-600"></i>
                    </button>
                    <div>
                        <h1 class="text-base font-bold text-gray-800">@yield('page_title', 'Dashboard')</h1>
                        <nav class="flex text-xs text-secondary" aria-label="Breadcrumb">
                            <a href="{{ route('admin.dashboard') }}" class="hover:text-primary">Dashboard</a>
                            @hasSection('breadcrumb')
                            <span class="mx-1">/</span>
                            @yield('breadcrumb')
                            @endif
                        </nav>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-secondary">{{ now()->format('d M Y') }}</p>
                    </div>
                    <img src="{{ auth()->user()->avatar_url }}" class="w-9 h-9 rounded-full ring-2 ring-primary/30" alt="Avatar">
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 p-6">
            @yield('admin_content')
        </main>
    </div>
</div>
@endsection
