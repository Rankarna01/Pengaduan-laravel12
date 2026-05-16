{{-- Admin Sidebar (Tema Terang / Light Mode E-Kecamatan Style) --}}
<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-100 shadow-sm transform transition-transform duration-300 lg:translate-x-0 -translate-x-full flex flex-col">
    
    {{-- Logo --}}
    <div class="flex items-center gap-3 px-6 py-6 border-b border-gray-50">
        <h1 class="text-primary font-extrabold text-xl italic tracking-wide uppercase">
            E-Pengaduan
        </h1>
    </div>

    {{-- Navigation --}}
    <nav class="px-4 py-6 space-y-1 overflow-y-auto flex-1">
        <p class="text-gray-400 text-[10px] uppercase tracking-widest px-2 mb-3 font-bold">Menu Utama</p>

        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">
            <i class="fas fa-chart-pie w-5 text-center"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.reports.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.reports*') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">
            <i class="fas fa-file-alt w-5 text-center"></i>
            <span>Kelola Laporan</span>
            @php $pending = \App\Models\Report::where('status','pending')->count() @endphp
            @if($pending > 0)
                <span class="ml-auto bg-orange-100 text-orange-600 text-[10px] rounded-full px-2 py-0.5 font-bold">{{ $pending }}</span>
            @endif
        </a>

        <p class="text-gray-400 text-[10px] uppercase tracking-widest px-2 mb-3 mt-6 font-bold">Manajemen</p>

        <a href="{{ route('admin.users.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.users*') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">
            <i class="fas fa-users w-5 text-center"></i>
            <span>Data Pengguna</span>
        </a>

        <a href="{{ route('admin.categories.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.categories*') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">
            <i class="fas fa-layer-group w-5 text-center"></i>
            <span>Kategori</span>
        </a>

        <a href="{{ route('admin.news.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.news*') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-gray-600 hover:bg-gray-50 hover:text-primary' }}">
            <i class="fas fa-newspaper w-5 text-center"></i>
            <span>Berita Desa</span>
        </a>

        <p class="text-gray-400 text-[10px] uppercase tracking-widest px-2 mb-3 mt-6 font-bold">Pengaturan</p>

        <a href="{{ route('admin.export.reports-pdf') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-primary transition-all">
            <i class="fas fa-file-pdf w-5 text-center"></i>
            <span>Export PDF</span>
        </a>
    </nav>

    {{-- Admin Info & Logout di Bawah --}}
    <div class="p-4 border-t border-gray-50">
        <div class="flex items-center gap-3 px-2 mb-4">
            <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=Admin&background=0D8ABC&color=fff' }}" class="w-9 h-9 rounded-full object-cover" alt="Avatar">
            <div class="overflow-hidden">
                <p class="text-gray-800 text-sm font-bold truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
                <p class="text-gray-400 text-xs">Admin Desa</p>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-red-500 text-sm font-medium hover:bg-red-50 transition-all">
                <i class="fas fa-sign-out-alt w-5 text-center"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>

{{-- Mobile overlay --}}
<div id="sidebarOverlay" class="fixed inset-0 bg-black/20 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity" onclick="toggleSidebar()"></div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}
</script>