@extends('layouts.admin')
@section('page_title', 'Manajemen Berita')
@section('breadcrumb', 'Berita')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div data-aos="fade-right">
        <h2 class="text-xl font-bold text-gray-800">Berita Desa</h2>
        <p class="text-sm text-secondary">Kelola konten berita dan pengumuman</p>
    </div>
    <button onclick="openNewsModal()" data-aos="fade-left"
            class="btn-primary text-white px-5 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
        <i class="fas fa-plus"></i> Tambah Berita
    </button>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6" data-aos="fade-up">
    <form method="GET" class="flex gap-3">
        <div class="relative flex-1">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul berita..."
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
        </div>
        <button type="submit" class="btn-primary text-white px-5 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
            <i class="fas fa-search"></i> Cari
        </button>
        @if(request('search'))
        <a href="{{ route('admin.news.index') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm text-secondary hover:bg-gray-50 flex items-center">
            <i class="fas fa-times"></i>
        </a>
        @endif
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100" data-aos="fade-up" data-aos-delay="100">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-50 bg-gray-50/50 text-left">
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Judul</th>
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Kategori</th>
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Penulis</th>
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Tanggal</th>
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($news as $item)
                <tr class="hover:bg-gray-50 transition-colors" id="news-row-{{ $item->id }}">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            @if($item->image_url)
                            <img src="{{ $item->image_url }}" class="w-10 h-10 rounded-lg object-cover">
                            @else
                            <div class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center">
                                <i class="fas fa-newspaper text-primary"></i>
                            </div>
                            @endif
                            <div>
                                <p class="font-semibold text-gray-800 max-w-xs truncate">{{ $item->title }}</p>
                                <p class="text-xs text-secondary">{{ $item->excerpt }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="bg-primary-100 text-primary text-xs font-semibold px-2 py-0.5 rounded-full">{{ $item->category }}</span>
                    </td>
                    <td class="px-5 py-3.5 text-gray-600 text-sm">{{ $item->admin->name }}</td>
                    <td class="px-5 py-3.5">
                        @if($item->is_published)
                        <span class="status-badge bg-green-100 text-green-700"><i class="fas fa-check-circle mr-1"></i> Dipublikasi</span>
                        @else
                        <span class="status-badge bg-gray-100 text-gray-600"><i class="fas fa-file-alt mr-1"></i> Draft</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-xs text-secondary">{{ $item->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2">
                            <button onclick="editNews({{ $item->id }})" class="p-1.5 rounded-lg bg-yellow-100 text-yellow-600 hover:bg-yellow-500 hover:text-white transition-colors">
                                <i class="fas fa-edit text-sm"></i>
                            </button>
                            <button onclick="deleteNews({{ $item->id }})" class="p-1.5 rounded-lg bg-red-100 text-red-500 hover:bg-red-500 hover:text-white transition-colors">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-secondary">
                    <i class="fas fa-newspaper text-5xl text-gray-200 mb-3 block"></i>
                    Belum ada berita.
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-5 border-t border-gray-100">{{ $news->links() }}</div>
</div>

{{-- Modal --}}
<div id="newsModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-7 py-5 border-b border-gray-100 sticky top-0 bg-white rounded-t-3xl">
            <h2 id="newsModalTitle" class="text-lg font-bold text-gray-800">Tambah Berita</h2>
            <button onclick="closeNewsModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="newsForm" enctype="multipart/form-data" class="p-7 space-y-4">
            <input type="hidden" id="newsId">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fas fa-heading mr-1 text-secondary"></i> Judul Berita <span class="text-danger">*</span></label>
                <input type="text" id="newsTitle" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fas fa-tag mr-1 text-secondary"></i> Kategori</label>
                    <select id="newsCategory" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
                        <option>Umum</option><option>Pengumuman</option><option>Berita</option><option>Infrastruktur</option><option>Kegiatan</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-2 cursor-pointer pb-2">
                        <input type="checkbox" id="newsPublished" checked class="rounded border-gray-300 text-primary w-4 h-4">
                        <span class="text-sm font-semibold text-gray-700">Publikasikan</span>
                    </label>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fas fa-image mr-1 text-secondary"></i> Gambar</label>
                <input type="file" id="newsImage" name="image" accept="image/*"
                       class="w-full text-sm text-secondary file:mr-3 file:px-4 file:py-2 file:rounded-lg file:border-0 file:bg-primary-100 file:text-primary file:font-semibold hover:file:bg-primary hover:file:text-white file:transition-colors">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fas fa-align-left mr-1 text-secondary"></i> Konten <span class="text-danger">*</span></label>
                <textarea id="newsContent" rows="6" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none resize-none" placeholder="Tulis konten berita..."></textarea>
            </div>
            <button type="button" onclick="submitNews()" class="btn-primary w-full text-white font-bold py-3 rounded-xl text-sm flex items-center justify-center gap-2">
                <i class="fas fa-save"></i> Simpan Berita
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openNewsModal() {
    document.getElementById('newsModal').classList.remove('hidden');
    document.getElementById('newsModalTitle').textContent = 'Tambah Berita';
    document.getElementById('newsId').value = '';
    document.getElementById('newsForm').reset();
}
function closeNewsModal() { document.getElementById('newsModal').classList.add('hidden'); }

async function editNews(id) {
    const res  = await fetch(`/admin/berita/${id}`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN } });
    const item = await res.json();
    document.getElementById('newsModal').classList.remove('hidden');
    document.getElementById('newsModalTitle').textContent = 'Edit Berita';
    document.getElementById('newsId').value       = item.id;
    document.getElementById('newsTitle').value    = item.title;
    document.getElementById('newsCategory').value = item.category;
    document.getElementById('newsContent').value  = item.content;
    document.getElementById('newsPublished').checked = item.is_published;
}

async function submitNews() {
    const id = document.getElementById('newsId').value;
    const fd = new FormData();
    fd.append('title',        document.getElementById('newsTitle').value);
    fd.append('content',      document.getElementById('newsContent').value);
    fd.append('category',     document.getElementById('newsCategory').value);
    fd.append('is_published', document.getElementById('newsPublished').checked ? '1' : '0');
    const img = document.getElementById('newsImage').files[0];
    if (img) fd.append('image', img);
    const url = id ? `/admin/berita/${id}` : '/admin/berita';
    const res = await fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }, body: fd });
    const result = await res.json();
    if (result.status === 'success') {
        closeNewsModal();
        Swal.fire({ icon: 'success', title: result.message, timer: 1800, showConfirmButton: false, toast: true, position: 'top-end' });
        setTimeout(() => location.reload(), 1800);
    } else {
        Swal.fire({ icon: 'error', title: 'Gagal!', text: result.message });
    }
}

function deleteNews(id) {
    confirmDelete(`/admin/berita/${id}`, () => document.getElementById(`news-row-${id}`)?.remove());
}
</script>
@endpush
