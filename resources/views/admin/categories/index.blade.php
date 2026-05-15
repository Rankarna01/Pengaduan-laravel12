@extends('layouts.admin')
@section('page_title', 'Manajemen Kategori')
@section('breadcrumb', 'Kategori')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div data-aos="fade-right">
        <h2 class="text-xl font-bold text-gray-800">Kategori Infrastruktur</h2>
        <p class="text-sm text-secondary">Kelola kategori jenis kerusakan</p>
    </div>
    <button onclick="openCategoryModal()" data-aos="fade-left"
            class="btn-primary text-white px-5 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
        <i class="fas fa-plus"></i> Tambah Kategori
    </button>
</div>

{{-- Category Grid --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mb-6">
    @foreach($categories as $i => $cat)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 card-hover text-center" data-aos="fade-up" data-aos-delay="{{ ($i % 4) * 80 }}" id="cat-card-{{ $cat->id }}">
        <div class="text-3xl mb-2">{{ $cat->icon }}</div>
        <p class="font-bold text-gray-800 text-sm">{{ $cat->name }}</p>
        <p class="text-xs text-secondary mt-0.5">{{ $cat->reports_count }} laporan</p>
        <div class="flex justify-center gap-2 mt-3">
            <button onclick="editCategory({{ $cat->id }})" class="p-1.5 rounded-lg bg-yellow-100 text-yellow-600 hover:bg-yellow-500 hover:text-white transition-colors" title="Edit">
                <i class="fas fa-edit text-sm"></i>
            </button>
            <button onclick="deleteCategory({{ $cat->id }})" class="p-1.5 rounded-lg bg-red-100 text-red-500 hover:bg-red-500 hover:text-white transition-colors" title="Hapus">
                <i class="fas fa-trash text-sm"></i>
            </button>
        </div>
    </div>
    @endforeach
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100" data-aos="fade-up">
    <div class="p-5 border-b border-gray-100">
        <h3 class="font-bold text-gray-800">Daftar Kategori</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-50 bg-gray-50/50 text-left">
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Icon</th>
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Nama</th>
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Slug</th>
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Total Laporan</th>
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($categories as $cat)
                <tr class="hover:bg-gray-50 transition-colors" id="cat-row-{{ $cat->id }}">
                    <td class="px-5 py-3.5 text-2xl">{{ $cat->icon }}</td>
                    <td class="px-5 py-3.5 font-semibold text-gray-800">{{ $cat->name }}</td>
                    <td class="px-5 py-3.5 font-mono text-xs text-secondary">{{ $cat->slug }}</td>
                    <td class="px-5 py-3.5">
                        <span class="bg-primary-100 text-primary text-xs font-bold px-2 py-0.5 rounded-full">{{ $cat->reports_count }}</span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2">
                            <button onclick="editCategory({{ $cat->id }})" class="p-1.5 rounded-lg bg-yellow-100 text-yellow-600 hover:bg-yellow-500 hover:text-white transition-colors">
                                <i class="fas fa-edit text-sm"></i>
                            </button>
                            <button onclick="deleteCategory({{ $cat->id }})" class="p-1.5 rounded-lg bg-red-100 text-red-500 hover:bg-red-500 hover:text-white transition-colors">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-secondary">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-5 border-t border-gray-100">{{ $categories->links() }}</div>
</div>

{{-- Modal --}}
<div id="catModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm mx-4">
        <div class="flex items-center justify-between px-7 py-5 border-b border-gray-100">
            <h2 id="catModalTitle" class="text-lg font-bold text-gray-800">Tambah Kategori</h2>
            <button onclick="closeCategoryModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-7 space-y-4">
            <input type="hidden" id="catId">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fas fa-smile mr-1 text-secondary"></i> Icon (Emoji) <span class="text-danger">*</span></label>
                <input type="text" id="catIcon" maxlength="10" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-2xl text-center focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none" placeholder="🏗️">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fas fa-tag mr-1 text-secondary"></i> Nama Kategori <span class="text-danger">*</span></label>
                <input type="text" id="catName" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none" placeholder="Nama kategori">
            </div>
            <button onclick="submitCategory()" class="btn-primary w-full text-white font-bold py-3 rounded-xl text-sm flex items-center justify-center gap-2">
                <i class="fas fa-save"></i> Simpan
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openCategoryModal() {
    document.getElementById('catModal').classList.remove('hidden');
    document.getElementById('catModalTitle').textContent = 'Tambah Kategori';
    document.getElementById('catId').value   = '';
    document.getElementById('catName').value = '';
    document.getElementById('catIcon').value = '';
}
function closeCategoryModal() { document.getElementById('catModal').classList.add('hidden'); }

async function editCategory(id) {
    const res = await fetch(`/admin/kategori/${id}`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN } });
    const cat = await res.json();
    document.getElementById('catModal').classList.remove('hidden');
    document.getElementById('catModalTitle').textContent = 'Edit Kategori';
    document.getElementById('catId').value   = cat.id;
    document.getElementById('catName').value = cat.name;
    document.getElementById('catIcon').value = cat.icon;
}

async function submitCategory() {
    const id   = document.getElementById('catId').value;
    const data = { name: document.getElementById('catName').value, icon: document.getElementById('catIcon').value };
    const url    = id ? `/admin/kategori/${id}` : '/admin/kategori';
    const method = id ? 'PUT' : 'POST';
    const res    = await fetch(url, { method, headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }, body: JSON.stringify(data) });
    const result = await res.json();
    if (result.status === 'success') {
        closeCategoryModal();
        Swal.fire({ icon: 'success', title: result.message, timer: 1600, showConfirmButton: false, toast: true, position: 'top-end' });
        setTimeout(() => location.reload(), 1600);
    } else {
        Swal.fire({ icon: 'error', title: 'Gagal!', text: result.message });
    }
}

function deleteCategory(id) {
    confirmDelete(`/admin/kategori/${id}`, () => {
        document.getElementById(`cat-row-${id}`)?.remove();
        document.getElementById(`cat-card-${id}`)?.remove();
    });
}
</script>
@endpush
