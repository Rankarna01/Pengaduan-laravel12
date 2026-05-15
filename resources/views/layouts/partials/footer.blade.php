<footer class="bg-gray-900 text-gray-300 py-10 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center gap-2.5 mb-4">
                    <div class="w-9 h-9 rounded-xl gradient-primary flex items-center justify-center">
                        <i class="fas fa-landmark text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm">Pengaduan Desa</p>
                        <p class="text-gray-400 text-xs">Sistem Infrastruktur</p>
                    </div>
                </div>
                <p class="text-sm text-gray-400 leading-relaxed">Platform pelaporan kerusakan infrastruktur desa. Bersama membangun desa yang lebih baik.</p>
            </div>
            <div>
                <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Tautan Cepat</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition-colors flex items-center gap-2"><i class="fas fa-home w-4"></i> Beranda</a></li>
                    <li><a href="{{ route('news.index') }}" class="text-gray-400 hover:text-white transition-colors flex items-center gap-2"><i class="fas fa-newspaper w-4"></i> Berita Desa</a></li>
                    @guest
                    <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition-colors flex items-center gap-2"><i class="fas fa-sign-in-alt w-4"></i> Login</a></li>
                    <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white transition-colors flex items-center gap-2"><i class="fas fa-user-plus w-4"></i> Daftar</a></li>
                    @endguest
                </ul>
            </div>
            <div>
                <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Kontak</h3>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li class="flex items-center gap-2"><i class="fas fa-map-marker-alt w-4 text-primary-light"></i> Kantor Desa, Jl. Utama No. 1</li>
                    <li class="flex items-center gap-2"><i class="fas fa-phone w-4 text-primary-light"></i> (0123) 456-789</li>
                    <li class="flex items-center gap-2"><i class="fas fa-envelope w-4 text-primary-light"></i> info@desa.id</li>
                </ul>
            </div>
        </div>
        <div class="mt-8 pt-6 border-t border-gray-800 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-500">
            <p>© {{ date('Y') }} Sistem Pengaduan Infrastruktur Desa. All rights reserved.</p>
            <p class="mt-2 sm:mt-0">Powered by Laravel 12 & Tailwind CSS</p>
        </div>
    </div>
</footer>
