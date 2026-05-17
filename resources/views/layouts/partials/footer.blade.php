<footer class="bg-gray-900 text-gray-300 py-10 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 md:gap-12 mb-10">
            {{-- Brand --}}
            <div class="md:col-span-2">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 mb-4">
                    <div class="w-10 h-10 rounded-xl gradient-primary flex items-center justify-center shadow-lg">
                        @if(isset($globalSettings['logo_url']) && $globalSettings['logo_url'])
                            <img src="{{ asset('storage/' . $globalSettings['logo_url']) }}" class="w-full h-full object-cover rounded-xl" alt="Logo">
                        @else
                            <i class="fas fa-landmark text-white text-lg"></i>
                        @endif
                    </div>
                    <div>
                        <p class="text-white font-bold text-sm leading-tight">{{ $globalSettings['system_name'] ?? 'Pengaduan Desa' }}</p>
                        <p class="text-gray-400 text-xs">{{ $globalSettings['system_sub_name'] ?? 'Infrastruktur' }}</p>
                    </div>
                </a>
                <p class="text-gray-400 text-sm leading-relaxed mb-6 max-w-sm">
                    {{ $globalSettings['footer_text'] ?? 'Sistem Pelaporan Kerusakan Infrastruktur Desa terpadu. Membangun desa yang lebih baik melalui partisipasi aktif masyarakat.' }}
                </p>
                <div class="flex gap-3">
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-all"><i class="fab fa-facebook-f text-xs"></i></a>
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-all"><i class="fab fa-twitter text-xs"></i></a>
                    <a href="#" class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-all"><i class="fab fa-instagram text-xs"></i></a>
                </div>
            </div>

            {{-- Kontak --}}
            <div>
                <h4 class="text-white font-bold mb-4 uppercase text-sm tracking-wider">Hubungi Kami</h4>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3 text-gray-400 text-sm group">
                        <i class="fas fa-map-marker-alt mt-1 text-gray-500 group-hover:text-primary transition-colors"></i>
                        <span>{{ $globalSettings['contact_address'] ?? 'Kantor Kepala Desa, Jl. Merdeka No. 123' }}</span>
                    </li>
                    <li class="flex items-center gap-3 text-gray-400 text-sm group">
                        <i class="fas fa-phone-alt text-gray-500 group-hover:text-primary transition-colors"></i>
                        <span>{{ $globalSettings['contact_phone'] ?? '+62 812 3456 7890' }}</span>
                    </li>
                    <li class="flex items-center gap-3 text-gray-400 text-sm group">
                        <i class="fas fa-envelope text-gray-500 group-hover:text-primary transition-colors"></i>
                        <span>{{ $globalSettings['contact_email'] ?? 'info@pengaduan.desa.id' }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="pt-8 border-t border-gray-800 text-center text-sm text-gray-500 flex flex-col md:flex-row justify-between items-center gap-4">
            <p>&copy; {{ date('Y') }} {{ $globalSettings['system_name'] ?? 'Sistem Pengaduan Infrastruktur Desa' }}. All rights reserved.</p>
            <p class="mt-2 sm:mt-0">Powered by Laravel 12 & Tailwind CSS</p>
        </div>
    </div>
</footer>
