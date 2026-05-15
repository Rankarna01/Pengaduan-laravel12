@extends('layouts.app')
@section('title', 'Masuk — Pengaduan Desa')

@section('content')
<div class="min-h-screen gradient-primary flex items-center justify-center p-4 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-blue-300/10 rounded-full blur-3xl"></div>

    <div class="w-full max-w-md" data-aos="fade-up">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            {{-- Header --}}
            <div class="gradient-primary px-8 py-8 text-center">
                <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-landmark text-white text-3xl"></i>
                </div>
                <h1 class="text-2xl font-extrabold text-white">Selamat Datang</h1>
                <p class="text-white/70 text-sm mt-1">Masuk ke akun Pengaduan Desa Anda</p>
            </div>

            {{-- Form --}}
            <form action="{{ route('login.post') }}" method="POST" class="px-8 py-8 space-y-5">
                @csrf

                @if($errors->any())
                <div class="bg-danger-50 border border-danger/20 rounded-xl p-4 flex gap-3">
                    <i class="fas fa-exclamation-triangle text-danger text-xl mt-0.5"></i>
                    <div class="text-sm text-danger">{{ $errors->first() }}</div>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        <i class="fas fa-envelope mr-1 text-secondary"></i> Email
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-sm @error('email') border-danger @enderror"
                           placeholder="admin@pengaduan.desa.id">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        <i class="fas fa-lock mr-1 text-secondary"></i> Password
                    </label>
                    <div class="relative" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" name="password" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-sm pr-12"
                               placeholder="••••••••">
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 text-primary">
                    <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                </div>

                <button type="submit" class="btn-primary w-full text-white font-bold py-3.5 rounded-xl text-sm tracking-wide flex items-center justify-center gap-2">
                    <i class="fas fa-sign-in-alt"></i> Masuk ke Akun
                </button>
            </form>

            <div class="px-8 pb-8 text-center">
                <p class="text-sm text-secondary">Belum punya akun?
                    <a href="{{ route('register') }}" class="text-primary font-semibold hover:underline">Daftar Sekarang</a>
                </p>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1 text-xs text-secondary hover:text-gray-600 mt-3">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>

        {{-- Demo credentials --}}
        <div class="mt-4 glass rounded-2xl p-4 text-center">
            <p class="text-white/60 text-xs mb-2 font-medium"><i class="fas fa-info-circle mr-1"></i> Akun Demo</p>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <div class="bg-white/10 rounded-lg p-2">
                    <p class="text-white font-semibold"><i class="fas fa-user-shield mr-1"></i> Admin</p>
                    <p class="text-white/70">admin@pengaduan.desa.id</p>
                    <p class="text-white/70">admin123</p>
                </div>
                <div class="bg-white/10 rounded-lg p-2">
                    <p class="text-white font-semibold"><i class="fas fa-user mr-1"></i> Masyarakat</p>
                    <p class="text-white/70">user@pengaduan.desa.id</p>
                    <p class="text-white/70">user123</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
