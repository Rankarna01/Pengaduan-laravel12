@extends('layouts.admin')
@section('page_title', 'Manajemen Pengguna')
@section('breadcrumb', 'Pengguna')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div data-aos="fade-right">
        <h2 class="text-xl font-bold text-gray-800">Daftar Masyarakat</h2>
        <p class="text-sm text-secondary">Kelola akun pengguna masyarakat</p>
    </div>
    <button onclick="openUserModal()" data-aos="fade-left"
            class="btn-primary text-white px-5 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
        <i class="fas fa-plus"></i> Tambah Pengguna
    </button>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6" data-aos="fade-up">
    <form method="GET" class="flex gap-3">
        <div class="relative flex-1">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
        </div>
        <button type="submit" class="btn-primary text-white px-5 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
            <i class="fas fa-search"></i> Cari
        </button>
        @if(request('search'))
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm text-secondary hover:bg-gray-50 flex items-center gap-1">
            <i class="fas fa-times"></i>
        </a>
        @endif
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100" data-aos="fade-up" data-aos-delay="100">
    <div class="flex items-center justify-between p-5 border-b border-gray-100">
        <p class="text-sm text-secondary font-medium">{{ $users->total() }} pengguna terdaftar</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-50 bg-gray-50/50 text-left">
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Pengguna</th>
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Email</th>
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">No. HP</th>
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Total Laporan</th>
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Bergabung</th>
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition-colors" id="user-row-{{ $user->id }}">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <img src="{{ $user->avatar_url }}" class="w-9 h-9 rounded-full ring-2 ring-gray-100">
                            <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 text-gray-600">{{ $user->email }}</td>
                    <td class="px-5 py-3.5 text-gray-600">{{ $user->phone ?? '-' }}</td>
                    <td class="px-5 py-3.5">
                        <span class="bg-primary-100 text-primary font-bold px-2 py-0.5 rounded-full text-xs">{{ $user->reports->count() }}</span>
                    </td>
                    <td class="px-5 py-3.5 text-xs text-secondary">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2">
                            <button onclick="editUser({{ $user->id }})"
                                    class="p-1.5 rounded-lg bg-yellow-100 text-yellow-600 hover:bg-yellow-500 hover:text-white transition-colors" title="Edit">
                                <i class="fas fa-edit text-sm"></i>
                            </button>
                            <button onclick="deleteUser({{ $user->id }})"
                                    class="p-1.5 rounded-lg bg-red-100 text-red-500 hover:bg-red-500 hover:text-white transition-colors" title="Hapus">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-secondary">
                    <i class="fas fa-users text-5xl text-gray-200 mb-3 block"></i>
                    Belum ada pengguna terdaftar.
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-5 border-t border-gray-100">{{ $users->links() }}</div>
</div>

{{-- Modal --}}
<div id="userModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4">
        <div class="flex items-center justify-between px-7 py-5 border-b border-gray-100">
            <h2 id="modalTitle" class="text-lg font-bold text-gray-800">Tambah Pengguna</h2>
            <button onclick="closeUserModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="userForm" class="p-7 space-y-4">
            <input type="hidden" id="userId">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fas fa-user mr-1 text-secondary"></i> Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" id="userName" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none" placeholder="Nama lengkap">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fas fa-envelope mr-1 text-secondary"></i> Email <span class="text-danger">*</span></label>
                <input type="email" id="userEmail" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none" placeholder="email@example.com">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fas fa-phone mr-1 text-secondary"></i> No. HP</label>
                <input type="text" id="userPhone" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none" placeholder="08xxxxxxxxxx">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    <i class="fas fa-lock mr-1 text-secondary"></i> Password
                    <span id="passRequired" class="text-danger">*</span>
                    <span id="passOptional" class="text-secondary text-xs hidden">(kosongkan jika tidak diubah)</span>
                </label>
                <input type="password" id="userPassword" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none" placeholder="Minimal 8 karakter">
            </div>
            <button type="button" id="submitBtn" onclick="submitUser()"
                    class="btn-primary w-full text-white font-bold py-3 rounded-xl text-sm flex items-center justify-center gap-2">
                <i class="fas fa-save"></i> Simpan
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openUserModal() {
    editingId = null;
    document.getElementById('userModal').classList.remove('hidden');
    document.getElementById('modalTitle').textContent = 'Tambah Pengguna';
    document.getElementById('userForm').reset();
    document.getElementById('userId').value = '';
    document.getElementById('passRequired').classList.remove('hidden');
    document.getElementById('passOptional').classList.add('hidden');
}

function closeUserModal() { document.getElementById('userModal').classList.add('hidden'); }

async function editUser(id) {
    const res  = await fetch(`/admin/pengguna/${id}`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN } });
    const user = await res.json();
    document.getElementById('userModal').classList.remove('hidden');
    document.getElementById('modalTitle').textContent = 'Edit Pengguna';
    document.getElementById('userId').value    = user.id;
    document.getElementById('userName').value  = user.name;
    document.getElementById('userEmail').value = user.email;
    document.getElementById('userPhone').value = user.phone || '';
    document.getElementById('userPassword').value = '';
    document.getElementById('passRequired').classList.add('hidden');
    document.getElementById('passOptional').classList.remove('hidden');
}

async function submitUser() {
    const id   = document.getElementById('userId').value;
    const data = { name: document.getElementById('userName').value, email: document.getElementById('userEmail').value, phone: document.getElementById('userPhone').value, password: document.getElementById('userPassword').value };
    const url    = id ? `/admin/pengguna/${id}` : '/admin/pengguna';
    const method = id ? 'PUT' : 'POST';

    const res    = await fetch(url, { method, headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }, body: JSON.stringify(data) });
    const result = await res.json();
    if (result.status === 'success') {
        closeUserModal();
        Swal.fire({ icon: 'success', title: result.message, timer: 1800, showConfirmButton: false, toast: true, position: 'top-end' });
        setTimeout(() => location.reload(), 1800);
    } else {
        Swal.fire({ icon: 'error', title: 'Gagal!', text: JSON.stringify(result.errors || result.message) });
    }
}

function deleteUser(id) {
    confirmDelete(`/admin/pengguna/${id}`, () => document.getElementById(`user-row-${id}`)?.remove());
}
</script>
@endpush
