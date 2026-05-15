@extends('layouts.app')
@section('title', 'Daftar — Pengaduan Desa')

@section('content')
<div class="min-h-screen gradient-primary flex items-center justify-center p-4 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-blue-300/10 rounded-full blur-3xl"></div>

    <div class="w-full max-w-md" data-aos="fade-up">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="gradient-primary px-8 py-7 text-center">
                <div class="w-14 h-14 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-user-plus text-white text-2xl"></i>
                </div>
                <h1 class="text-2xl font-extrabold text-white">Buat Akun Baru</h1>
                <p class="text-white/70 text-sm mt-1">Bergabung dan mulai lapor kerusakan infrastruktur</p>
            </div>

            <form action="{{ route('register.post') }}" method="POST" class="px-8 py-7 space-y-4">
                @csrf

                @if($errors->any())
                <div class="bg-danger-50 border border-danger/20 rounded-xl p-4">
                    <ul class="text-sm text-danger space-y-1 list-disc list-inside">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fas fa-user mr-1 text-secondary"></i> Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-sm @error('name') border-danger @enderror"
                           placeholder="Budi Santoso">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fas fa-envelope mr-1 text-secondary"></i> Alamat Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-sm @error('email') border-danger @enderror"
                           placeholder="email@example.com">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fas fa-phone mr-1 text-secondary"></i> Nomor HP</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-sm"
                           placeholder="08xxxxxxxxxx">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fas fa-lock mr-1 text-secondary"></i> Password <span class="text-danger">*</span></label>
                    <div class="relative" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" name="password" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-sm pr-12"
                               placeholder="Minimal 8 karakter">
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fas fa-lock mr-1 text-secondary"></i> Konfirmasi Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-sm"
                           placeholder="Ulangi password">
                </div>

                <button type="submit" class="btn-primary w-full text-white font-bold py-3.5 rounded-xl text-sm tracking-wide flex items-center justify-center gap-2 mt-2">
                    <i class="fas fa-rocket"></i> Buat Akun Sekarang
                </button>
            </form>

            <div class="px-8 pb-7 text-center">
                <p class="text-sm text-secondary">Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">Masuk di sini</a>
                </p>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1 text-xs text-secondary hover:text-gray-600 mt-3">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
