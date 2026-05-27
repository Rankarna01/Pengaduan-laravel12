{{-- Member Navbar --}}
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl gradient-primary flex items-center justify-center shadow">
                    @if(isset($globalSettings['logo_url']) && $globalSettings['logo_url'])
                        <img src="{{ asset('storage/' . $globalSettings['logo_url']) }}" class="w-full h-full object-cover rounded-xl" alt="Logo">
                    @else
                        <i class="fas fa-landmark text-white text-sm"></i>
                    @endif
                </div>
                <div>
                    <p class="font-bold text-gray-800 text-sm leading-none">{{ $globalSettings['system_name'] ?? 'Pengaduan Desa' }}</p>
                    <p class="text-xs text-secondary leading-none">{{ $globalSettings['system_sub_name'] ?? 'Infrastruktur' }}</p>
                </div>
                </a>

                {{-- Desktop Nav --}}
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('member.dashboard') }}" class="text-sm font-medium {{ request()->routeIs('member.dashboard') ? 'text-primary' : 'text-gray-600 hover:text-primary' }} transition-colors">
                        <i class="fas fa-chart-pie mr-1"></i> Dashboard
                    </a>
                    <a href="{{ route('member.reports.index') }}" class="text-sm font-medium {{ request()->routeIs('member.reports*') ? 'text-primary' : 'text-gray-600 hover:text-primary' }} transition-colors">
                        <i class="fas fa-clipboard-list mr-1"></i> Laporan Saya
                    </a>
                    <a href="{{ route('news.index') }}" class="text-sm font-medium {{ request()->routeIs('news*') ? 'text-primary' : 'text-gray-600 hover:text-primary' }} transition-colors">
                        <i class="fas fa-newspaper mr-1"></i> Berita
                    </a>
                    <a href="{{ route('member.panduan') }}" class="text-sm font-medium {{ request()->routeIs('member.panduan') ? 'text-primary' : 'text-gray-600 hover:text-primary' }} transition-colors">
                        <i class="fas fa-book-open mr-1"></i> Panduan
                    </a>
                </div>

                {{-- Right Actions (Notif & User Menu) --}}
                <div class="flex items-center gap-4">
                    
                    {{-- Notifikasi Lonceng --}}
                    <a href="{{ route('member.notifications.index') }}" class="relative p-2 text-gray-500 hover:text-primary hover:bg-gray-100 rounded-full transition-colors">
                        <i class="fas fa-bell text-lg"></i>
                        @php $unreadMemberCount = auth()->user()->unreadNotifications->count(); @endphp
                        @if($unreadMemberCount > 0)
                            <span class="absolute top-1 right-1 flex h-3 w-3 items-center justify-center">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 border-2 border-white text-[8px] font-bold text-white"></span>
                            </span>
                        @endif
                    </a>

                    {{-- User Menu --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 px-3 py-1.5 rounded-xl hover:bg-gray-100 transition-colors">
                            <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}" class="w-8 h-8 rounded-full object-cover border border-gray-200" alt="Avatar">
                            <div class="text-left hidden sm:block">
                                <p class="text-sm font-semibold text-gray-800 leading-none">{{ Str::words(auth()->user()->name, 2, '') }}</p>
                                <p class="text-xs text-secondary mt-0.5">Masyarakat</p>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                        </button>

                        <div x-show="open" x-transition @click.outside="open = false" style="display: none;"
                            class="absolute right-0 top-14 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-xs text-secondary">Masuk sebagai</p>
                                <p class="text-sm font-semibold text-gray-800 truncate" title="{{ auth()->user()->email }}">{{ auth()->user()->email }}</p>
                            </div>
                            
                            {{-- Diganti dari Dashboard ke Profil Saya --}}
                            <a href="{{ route('member.profile.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors">
                                <i class="fas fa-user-circle w-4 text-center text-gray-400"></i> Profil Saya
                            </a>
                            
                            <a href="{{ route('member.reports.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors">
                                <i class="fas fa-clipboard-list w-4 text-center text-gray-400"></i> Laporan Saya
                            </a>
                            
                            {{-- Opsi untuk tampilan mobile --}}
                            <a href="{{ route('member.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors md:hidden">
                                <i class="fas fa-chart-pie w-4 text-center text-gray-400"></i> Dashboard
                            </a>
                            <a href="{{ route('member.panduan') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors md:hidden">
                                <i class="fas fa-book-open w-4 text-center text-gray-400"></i> Panduan
                            </a>

                            <div class="border-t border-gray-100 mt-1 pt-1">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors">
                                        <i class="fas fa-sign-out-alt w-4 text-center"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </nav>