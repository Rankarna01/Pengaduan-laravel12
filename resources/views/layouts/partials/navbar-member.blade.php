    {{-- Member Navbar --}}
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl gradient-primary flex items-center justify-center shadow">
                        <i class="fas fa-landmark text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 text-sm leading-none">Pengaduan Desa</p>
                        <p class="text-xs text-secondary leading-none">Infrastruktur</p>
                    </div>
                </a>

                {{-- Desktop Nav --}}
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-sm font-medium {{ request()->routeIs('home') ? 'text-primary' : 'text-gray-600 hover:text-primary' }} transition-colors">
                        <i class="fas fa-home mr-1"></i> Beranda
                    </a>
                    <a href="{{ route('member.reports.index') }}" class="text-sm font-medium {{ request()->routeIs('member.reports*') ? 'text-primary' : 'text-gray-600 hover:text-primary' }} transition-colors">
                        <i class="fas fa-clipboard-list mr-1"></i> Laporan Saya
                    </a>
                    <a href="{{ route('news.index') }}" class="text-sm font-medium {{ request()->routeIs('news*') ? 'text-primary' : 'text-gray-600 hover:text-primary' }} transition-colors">
                        <i class="fas fa-newspaper mr-1"></i> Berita
                    </a>
                </div>

                {{-- User Menu --}}
                <div class="flex items-center gap-3" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 px-3 py-1.5 rounded-xl hover:bg-gray-100 transition-colors">
                        <img src="{{ auth()->user()->avatar_url }}" class="w-8 h-8 rounded-full" alt="Avatar">
                        <div class="text-left hidden sm:block">
                            <p class="text-sm font-semibold text-gray-800 leading-none">{{ Str::words(auth()->user()->name, 2, '') }}</p>
                            <p class="text-xs text-secondary">Masyarakat</p>
                        </div>
                        <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="open" x-transition @click.outside="open = false"
                        class="absolute right-4 top-16 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-xs text-secondary">Masuk sebagai</p>
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('member.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-chart-bar w-4 text-center text-secondary"></i> Dashboard
                        </a>
                        <a href="{{ route('member.reports.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-clipboard-list w-4 text-center text-secondary"></i> Laporan Saya
                        </a>
                        <div class="border-t border-gray-100 mt-1 pt-1">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-500 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt w-4 text-center"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
