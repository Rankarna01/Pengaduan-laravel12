@extends('layouts.app')
@section('title', 'Masuk — Pengaduan Desa')

@section('content')
{{-- Tambahkan script Lottie Player di sini atau pindahkan ke layout master jika diperlukan --}}
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

<div class="min-h-screen bg-surface flex items-center justify-center p-4 relative overflow-hidden">
    {{-- Dekorasi Background Minimalis --}}
    <div class="absolute top-0 right-0 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-blue-300/10 rounded-full blur-3xl"></div>

    {{-- Container Card (Split Screen) --}}
    <div class="w-full max-w-4xl bg-white rounded-xl shadow-xl overflow-hidden flex flex-col md:flex-row relative z-10" data-aos="fade-up">
        
        {{-- Kiri: Lottie Animation (Hidden di Mobile) --}}
        <div class="hidden md:flex md:w-1/2 bg-gray-50/50 items-center justify-center p-8 border-r border-gray-100">
            <div class="w-full max-w-sm">
                <lottie-player 
                    src="{{ asset('Services.json') }}" 
                    background="transparent" 
                    speed="1" 
                    style="width: 100%; height: auto;" 
                    loop 
                    autoplay>
                </lottie-player>
            </div>
        </div>

        {{-- Kanan: Form Login --}}
        <div class="w-full md:w-1/2 p-8 lg:p-12 flex flex-col justify-center">
            
            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="w-14 h-14 bg-primary/10 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-landmark text-primary text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Selamat Datang</h1>
                <p class="text-gray-500 text-sm mt-1">Masuk ke akun Pengaduan Desa Anda</p>
            </div>

            {{-- Form --}}
            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf

                @if($errors->any())
                <div class="bg-danger-50 border border-danger/20 rounded-lg p-4 flex gap-3">
                    <i class="fas fa-exclamation-triangle text-danger text-xl mt-0.5"></i>
                    <div class="text-sm text-danger">{{ $errors->first() }}</div>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        <i class="fas fa-envelope mr-1 text-secondary"></i> Email
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-sm @error('email') border-danger @enderror"
                           placeholder="admin@pengaduan.desa.id">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        <i class="fas fa-lock mr-1 text-secondary"></i> Password
                    </label>
                    <div class="relative" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" name="password" required
                               class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-sm pr-12"
                               placeholder="••••••••">
                        <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" class="rounded text-primary focus:ring-primary/30 border-gray-300">
                        <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full text-white bg-primary hover:bg-primary/90 font-semibold py-3 rounded-lg text-sm transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-sign-in-alt"></i> Masuk ke Akun
                </button>
            </form>

            {{-- Footer Links --}}
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500">Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-primary font-semibold hover:underline">Daftar Sekarang</a>
                </p>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1 text-xs text-gray-400 hover:text-gray-600 mt-4 transition-colors">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>

            </div>
            
        </div>
    </div>
</div>
@endsection