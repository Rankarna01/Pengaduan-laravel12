@extends('layouts.app')
@section('title', 'Profil Saya — Pengaduan Desa')

@section('content')
<div class="min-h-screen bg-surface flex flex-col">
    @include('layouts.partials.navbar-member')

    <main class="flex-1 max-w-4xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-10">
        
        <div class="mb-8" data-aos="fade-up">
            <h1 class="text-2xl font-extrabold text-gray-800">Pengaturan Profil</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola informasi pribadi dan keamanan akun Anda.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            
            {{-- Kolom Kiri: Info Profil --}}
            <div class="md:col-span-1" data-aos="fade-up" data-aos-delay="100">
                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm text-center">
                    <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-gray-50 object-cover" alt="Avatar">
                    <h3 class="text-lg font-bold text-gray-800">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    <span class="inline-block mt-3 bg-primary/10 text-primary text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Masyarakat</span>
                </div>
            </div>

            {{-- Kolom Kanan: Form Update --}}
            <div class="md:col-span-2 space-y-8" data-aos="fade-up" data-aos-delay="200">
                
                {{-- Form Info Dasar --}}
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 border-b border-gray-100 pb-4"><i class="fas fa-user-edit text-primary mr-2"></i> Informasi Pribadi</h3>
                    
                    <form action="{{ route('member.profile.update') }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">NIK (Nomor Induk Kependudukan)</label>
                            <input type="text" name="nik" value="{{ old('nik', $user->nik) }}" required minlength="16" maxlength="16"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-gray-50 focus:bg-white transition-all">
                            @error('nik') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-gray-50 focus:bg-white transition-all">
                            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-gray-50 focus:bg-white transition-all">
                            @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nomor Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 08123456789"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-gray-50 focus:bg-white transition-all">
                            @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                            <textarea name="alamat" required rows="2"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-gray-50 focus:bg-white transition-all">{{ old('alamat', $user->alamat) }}</textarea>
                            @error('alamat') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Dusun</label>
                            <select name="dusun" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-gray-50 focus:bg-white transition-all">
                                <option value="" disabled {{ old('dusun', $user->dusun) ? '' : 'selected' }}>-- Pilih Dusun --</option>
                                @foreach(['Penam Raya', 'Buin Gali', 'Kabuyit Timur', 'Langam', 'Bringin Dalam', 'Lagenang', 'Sigar Mandang', 'Kabuyit Barat', 'Buin Panan'] as $d)
                                    <option value="{{ $d }}" {{ old('dusun', $user->dusun) == $d ? 'selected' : '' }}>{{ $d }}</option>
                                @endforeach
                            </select>
                            @error('dusun') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="bg-primary hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl text-sm transition-colors shadow-lg shadow-primary/30">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Form Ubah Password --}}
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 border-b border-gray-100 pb-4"><i class="fas fa-lock text-primary mr-2"></i> Keamanan (Ubah Password)</h3>
                    
                    <form action="{{ route('member.profile.password') }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password Saat Ini</label>
                            <input type="password" name="current_password" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-gray-50 focus:bg-white transition-all">
                            @error('current_password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password Baru</label>
                                <input type="password" name="password" required
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-gray-50 focus:bg-white transition-all">
                                @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Ulangi Password Baru</label>
                                <input type="password" name="password_confirmation" required
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-gray-50 focus:bg-white transition-all">
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 px-6 rounded-xl text-sm transition-colors shadow-lg shadow-gray-900/20">
                                Perbarui Password
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </main>

    @include('layouts.partials.footer')
</div>

{{-- SweetAlert untuk Notifikasi Sukses --}}
@if(session('success'))
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#2563eb',
            });
        });
    </script>
    @endpush
@endif
@endsection