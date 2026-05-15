@extends('layouts.admin')
@section('page_title', 'Kelola Laporan')
@section('breadcrumb', 'Laporan')

@section('admin_content')
{{-- Filters --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6" data-aos="fade-up">
    <form method="GET" class="flex flex-wrap gap-3 items-center">
        <div class="relative flex-1 min-w-48">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau judul..."
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
        </div>
        <select name="status" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
            <option value="">Semua Status</option>
            <option value="pending"   {{ request('status')=='pending'   ? 'selected':'' }}>Menunggu</option>
            <option value="process"   {{ request('status')=='process'   ? 'selected':'' }}>Diproses</option>
            <option value="completed" {{ request('status')=='completed' ? 'selected':'' }}>Selesai</option>
            <option value="rejected"  {{ request('status')=='rejected'  ? 'selected':'' }}>Ditolak</option>
        </select>
        <button type="submit" class="btn-primary text-white px-5 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2">
            <i class="fas fa-search"></i> Cari
        </button>
        @if(request()->hasAny(['search','status']))
        <a href="{{ route('admin.reports.index') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm text-secondary hover:bg-gray-50 transition-colors flex items-center gap-1">
            <i class="fas fa-times"></i> Reset
        </a>
        @endif
        <a href="{{ route('admin.export.reports-pdf') }}" class="ml-auto flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100" data-aos="fade-up" data-aos-delay="100">
    <div class="flex items-center justify-between p-6 border-b border-gray-100">
        <div>
            <h3 class="font-bold text-gray-800">Daftar Laporan</h3>
            <p class="text-xs text-secondary">{{ $reports->total() }} laporan ditemukan</p>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-50 text-left bg-gray-50/50">
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Kode</th>
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Laporan</th>
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Pelapor</th>
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Kategori</th>
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Tanggal</th>
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50" id="reportsTable">
                @forelse($reports as $report)
                @include('admin.reports._row', ['report' => $report])
                @empty
                <tr><td colspan="7" class="px-6 py-16 text-center text-secondary">
                    <i class="fas fa-clipboard-list text-5xl text-gray-200 mb-3 block"></i>
                    Tidak ada laporan ditemukan.
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-5 border-t border-gray-100">{{ $reports->links() }}</div>
</div>

{{-- ===== DETAIL/RESPONSE MODAL ===== --}}
<div id="detailModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto mx-4">
        <div class="flex items-center justify-between px-7 py-5 border-b border-gray-100 sticky top-0 bg-white rounded-t-3xl z-10">
            <h2 class="text-lg font-bold text-gray-800">Detail Laporan</h2>
            <button onclick="closeDetailModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 transition-colors text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="modalContent" class="p-7">
            <div class="skeleton h-40 rounded-xl mb-4"></div>
            <div class="skeleton h-4 rounded w-3/4 mb-2"></div>
            <div class="skeleton h-4 rounded w-1/2"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openDetailModal(reportId) {
    document.getElementById('detailModal').classList.remove('hidden');
    const content = document.getElementById('modalContent');
    content.innerHTML = `<div class="skeleton h-40 rounded-xl mb-4"></div><div class="skeleton h-4 rounded w-3/4 mb-2"></div><div class="skeleton h-4 rounded w-1/2"></div>`;

    fetch(`/admin/laporan/${reportId}`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN } })
        .then(r => r.json())
        .then(report => {
            const statusMap = {
                pending:   { label: 'Menunggu',  cls: 'bg-yellow-100 text-yellow-700' },
                process:   { label: 'Diproses',  cls: 'bg-blue-100 text-blue-700' },
                completed: { label: 'Selesai',   cls: 'bg-green-100 text-green-700' },
                rejected:  { label: 'Ditolak',   cls: 'bg-red-100 text-red-700' },
            };
            const responses = report.responses?.map(r => `
                <div class="flex gap-3 bg-gray-50 rounded-xl p-4 mt-3">
                    <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white text-xs font-bold shrink-0">${r.admin?.name?.charAt(0) || 'A'}</div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold text-gray-800">${r.admin?.name || 'Admin'}</p>
                            <p class="text-xs text-gray-400">${new Date(r.created_at).toLocaleDateString('id-ID')}</p>
                        </div>
                        <p class="text-sm text-gray-700 mt-1">${r.message}</p>
                        ${r.photo_repair ? `<img src="/storage/${r.photo_repair}" class="mt-2 rounded-xl max-h-32 object-cover">` : ''}
                    </div>
                </div>`).join('') || '<p class="text-secondary text-sm italic py-3">Belum ada tanggapan.</p>';

            content.innerHTML = `
            <div class="grid sm:grid-cols-2 gap-4 mb-5">
                <div>
                    <p class="text-xs text-secondary uppercase tracking-wider">Kode Laporan</p>
                    <p class="font-mono font-bold text-primary text-lg">${report.code}</p>
                </div>
                <div>
                    <p class="text-xs text-secondary uppercase tracking-wider mb-1">Ubah Status</p>
                    <select onchange="updateStatus(${report.id}, this.value)" class="px-3 py-1.5 rounded-lg border border-gray-200 text-sm font-semibold focus:ring-2 focus:ring-primary/30 focus:border-primary w-full">
                        ${['pending','process','completed','rejected'].map(st => `<option value="${st}" ${report.status===st?'selected':''}>${{pending:'Menunggu',process:'Diproses',completed:'Selesai',rejected:'Ditolak'}[st]}</option>`).join('')}
                    </select>
                </div>
            </div>
            <h3 class="font-bold text-gray-800 text-base mb-2">${report.title}</h3>
            <p class="text-secondary text-sm mb-4">${report.description}</p>
            <div class="grid sm:grid-cols-2 gap-3 mb-5">
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs text-secondary"><i class="fas fa-map-marker-alt mr-1 text-primary"></i> Lokasi</p>
                    <p class="text-sm font-medium text-gray-800 mt-1">${report.location}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs text-secondary"><i class="fas fa-calendar mr-1 text-primary"></i> Tanggal</p>
                    <p class="text-sm font-medium text-gray-800 mt-1">${new Date(report.created_at).toLocaleDateString('id-ID',{day:'numeric',month:'long',year:'numeric'})}</p>
                </div>
            </div>
            ${report.photo_damage ? `<img src="/storage/${report.photo_damage}" class="w-full rounded-xl mb-5 max-h-60 object-cover">` : ''}
            <div class="border-t border-gray-100 pt-5">
                <h4 class="font-bold text-gray-800 mb-3"><i class="fas fa-comments mr-2 text-primary"></i> Riwayat Tanggapan</h4>
                ${responses}
            </div>
            <div class="border-t border-gray-100 pt-5 mt-5">
                <h4 class="font-bold text-gray-800 mb-3"><i class="fas fa-reply mr-2 text-primary"></i> Tambah Tanggapan</h4>
                <form id="responseForm" enctype="multipart/form-data" class="space-y-3">
                    <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        <option value="process" ${report.status==='process'?'selected':''}>Ubah ke Diproses</option>
                        <option value="completed" ${report.status==='completed'?'selected':''}>Ubah ke Selesai</option>
                        <option value="rejected" ${report.status==='rejected'?'selected':''}>Ubah ke Ditolak</option>
                        <option value="pending" ${report.status==='pending'?'selected':''}>Kembalikan ke Menunggu</option>
                    </select>
                    <textarea name="message" rows="3" required placeholder="Tulis tanggapan..." class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary resize-none"></textarea>
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block"><i class="fas fa-image mr-1"></i> Foto Bukti Perbaikan (opsional)</label>
                        <input type="file" name="photo_repair" accept="image/*" class="w-full text-sm text-secondary file:mr-3 file:px-4 file:py-2 file:rounded-lg file:border-0 file:bg-primary-100 file:text-primary file:font-semibold hover:file:bg-primary hover:file:text-white file:transition-colors">
                    </div>
                    <button type="button" onclick="submitResponse(${report.id})" class="btn-primary w-full text-white font-bold py-2.5 rounded-xl text-sm flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i> Kirim Tanggapan
                    </button>
                </form>
            </div>`;
        });
}

function closeDetailModal() { document.getElementById('detailModal').classList.add('hidden'); }

async function updateStatus(id, status) {
    const res  = await fetch(`/admin/laporan/${id}/status`, { method: 'PATCH', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }, body: JSON.stringify({ status }) });
    const data = await res.json();
    Swal.fire({ icon: 'success', title: data.message, timer: 1500, showConfirmButton: false, toast: true, position: 'top-end' });
    setTimeout(() => location.reload(), 1500);
}

async function submitResponse(reportId) {
    const fd = new FormData(document.getElementById('responseForm'));
    const res = await fetch(`/admin/laporan/${reportId}/response`, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }, body: fd });
    const data = await res.json();
    if (data.status === 'success') {
        Swal.fire({ icon: 'success', title: data.message, timer: 1800, showConfirmButton: false, toast: true, position: 'top-end' });
        closeDetailModal();
        setTimeout(() => location.reload(), 1800);
    } else {
        Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message });
    }
}

function deleteReport(id) {
    confirmDelete(`/admin/laporan/${id}`, () => document.querySelector(`[data-id="${id}"]`)?.closest('tr').remove());
}
</script>
@endpush
