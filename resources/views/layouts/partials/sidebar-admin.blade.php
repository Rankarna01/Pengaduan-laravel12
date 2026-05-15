{{-- Admin Sidebar --}}
<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900 shadow-2xl transform transition-transform duration-300 lg:translate-x-0 -translate-x-full">
    {{-- Logo --}}
    <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-700">
        <div class="w-10 h-10 rounded-xl gradient-primary flex items-center justify-center shadow-lg">
            <i class="fas fa-landmark text-white text-lg"></i>
        </div>
        <div>
            <p class="text-white font-bold text-sm leading-tight">Pengaduan</p>
            <p class="text-gray-400 text-xs">Infrastruktur Desa</p>
        </div>
    </div>

    {{-- Admin Info --}}
    <div class="px-6 py-4 border-b border-gray-700">
        <div class="flex items-center gap-3">
            <img src="{{ auth()->user()->avatar_url }}" class="w-9 h-9 rounded-full ring-2 ring-primary" alt="Avatar">
            <div class="overflow-hidden">
                <p class="text-white text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                <span class="text-xs bg-primary/20 text-primary-light px-2 py-0.5 rounded-full">Administrator</span>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="px-4 py-4 space-y-1 overflow-y-auto h-[calc(100vh-180px)]">
        <p class="text-gray-500 text-xs uppercase tracking-widest px-2 mb-3 font-semibold">Menu Utama</p>

        <a href="{{ route('admin.dashboard') }}"
           class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 text-sm {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-bar w-5 text-center"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        <a href="{{ route('admin.reports.index') }}"
           class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 text-sm {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
            <i class="fas fa-clipboard-list w-5 text-center"></i>
            <span class="font-medium">Kelola Laporan</span>
            @php $pending = \App\Models\Report::where('status','pending')->count() @endphp
            @if($pending > 0)
                <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-0.5 font-bold">{{ $pending }}</span>
            @endif
        </a>

        <p class="text-gray-500 text-xs uppercase tracking-widest px-2 mb-3 mt-5 font-semibold">Manajemen</p>

        <a href="{{ route('admin.users.index') }}"
           class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 text-sm {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <i class="fas fa-users w-5 text-center"></i>
            <span class="font-medium">Pengguna</span>
        </a>

        <a href="{{ route('admin.categories.index') }}"
           class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 text-sm {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
            <i class="fas fa-tags w-5 text-center"></i>
            <span class="font-medium">Kategori</span>
        </a>

        <a href="{{ route('admin.news.index') }}"
           class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 text-sm {{ request()->routeIs('admin.news*') ? 'active' : '' }}">
            <i class="fas fa-newspaper w-5 text-center"></i>
            <span class="font-medium">Berita</span>
        </a>

        <p class="text-gray-500 text-xs uppercase tracking-widest px-2 mb-3 mt-5 font-semibold">Export</p>

        <a href="{{ route('admin.export.reports-pdf') }}"
           class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-300 text-sm">
            <i class="fas fa-file-pdf w-5 text-center"></i>
            <span class="font-medium">Export PDF</span>
        </a>

        <div class="pt-4 border-t border-gray-700 mt-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-red-400 text-sm hover:text-red-300">
                    <i class="fas fa-sign-out-alt w-5 text-center"></i>
                    <span class="font-medium">Logout</span>
                </button>
            </form>
        </div>
    </nav>
</aside>

{{-- Mobile overlay --}}
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden" onclick="toggleSidebar()"></div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}
</script>
