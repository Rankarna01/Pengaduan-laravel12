@extends('layouts.app')
@section('title', 'Laporan Saya — Pengaduan Desa')

@section('content')
<div class="min-h-screen bg-surface flex flex-col">
    @include('layouts.partials.navbar-member')

    <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-6" data-aos="fade-up">
            <div>
                <h1 class="text-xl font-bold text-gray-800">Laporan Saya</h1>
                <p class="text-sm text-secondary">Pantau semua laporan dan riwayat status</p>
            </div>
            <button onclick="openCreateModal()"
                    class="btn-primary text-white px-5 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2 shadow">
                <i class="fas fa-plus"></i> Buat Laporan
            </button>
        </div>

        {{-- Reports List --}}
        <div class="space-y-4" id="reportsList">
            @forelse($reports as $report)
            @php
            $statusBg   = ['pending'=>'bg-yellow-100 text-yellow-700','process'=>'bg-blue-100 text-blue-700','completed'=>'bg-green-100 text-green-700','rejected'=>'bg-red-100 text-red-700'];
            $stepOrder  = ['pending'=>0,'process'=>1,'completed'=>2,'rejected'=>2];
            $currentStep = $stepOrder[$report->status] ?? 0;
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden card-hover" data-aos="fade-up">
                <div class="p-5">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center text-2xl shrink-0">{{ $report->category->icon }}</div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="font-bold text-gray-800">{{ $report->title }}</p>
                                    <p class="text-xs text-primary font-mono mt-0.5">{{ $report->code }}</p>
                                </div>
                                <span class="status-badge {{ $statusBg[$report->status] ?? 'bg-gray-100 text-gray-700' }} shrink-0">
                                    {{ $report->status_label }}
                                </span>
                            </div>
                            <p class="text-sm text-secondary mt-2 line-clamp-2">{{ $report->description }}</p>
                            <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-secondary">
                                <span><i class="fas fa-map-marker-alt mr-1 text-red-400"></i>{{ $report->location }}</span>
                                <span><i class="fas fa-tag mr-1 text-primary"></i>{{ $report->category->name }}</span>
                                <span><i class="fas fa-calendar mr-1"></i>{{ $report->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Timeline --}}
                    @if($report->status !== 'rejected')
                    <div class="mt-5 flex items-center">
                        @php
                        $timelineSteps = [
                            ['key'=>'pending',   'label'=>'Dikirim',  'icon'=>'fa-paper-plane'],
                            ['key'=>'process',   'label'=>'Diproses', 'icon'=>'fa-wrench'],
                            ['key'=>'completed', 'label'=>'Selesai',  'icon'=>'fa-check-circle'],
                        ];
                        @endphp
                        @foreach($timelineSteps as $i => $step)
                        <div class="flex items-center {{ $i < count($timelineSteps) - 1 ? 'flex-1' : '' }}">
                            <div class="flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm
                                    {{ $currentStep >= $i ? 'gradient-primary text-white shadow-md' : 'bg-gray-100 text-gray-400' }}">
                                    <i class="fas {{ $step['icon'] }} text-xs"></i>
                                </div>
                                <p class="text-xs mt-1 font-medium {{ $currentStep >= $i ? 'text-primary' : 'text-gray-400' }} whitespace-nowrap">{{ $step['label'] }}</p>
                            </div>
                            @if($i < count($timelineSteps) - 1)
                            <div class="flex-1 h-0.5 mx-2 {{ $currentStep > $i ? 'bg-primary' : 'bg-gray-200' }}"></div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="mt-4 bg-red-50 border border-red-100 rounded-xl px-4 py-2.5 flex items-center gap-2">
                        <i class="fas fa-times-circle text-red-500"></i>
                        <p class="text-sm text-red-600 font-medium">Laporan ini telah ditolak oleh admin.</p>
                    </div>
                    @endif

                    {{-- Actions --}}
                    <div class="flex items-center gap-3 mt-4 pt-4 border-t border-gray-50">
                        <button onclick="viewReport({{ $report->id }})" class="flex items-center gap-1.5 text-primary text-sm font-semibold hover:underline">
                            <i class="fas fa-eye"></i> Lihat Detail & Tanggapan
                        </button>
                        @if($report->status === 'pending')
                        <button onclick="deleteMyReport({{ $report->id }})" class="flex items-center gap-1.5 text-red-500 text-sm font-medium hover:underline ml-auto">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center" data-aos="fade-up">
                <i class="fas fa-clipboard-list text-6xl text-gray-200 mb-4 block"></i>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Belum Ada Laporan</h3>
                <p class="text-secondary text-sm mb-6">Mulai laporkan kerusakan infrastruktur di sekitar Anda.</p>
                <button onclick="openCreateModal()" class="btn-primary text-white px-6 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-2 mx-auto">
                    <i class="fas fa-plus"></i> Buat Laporan Pertama
                </button>
            </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $reports->links() }}</div>
    </main>

    @include('layouts.partials.footer')
</div>

{{-- ===== CREATE REPORT MODAL ===== --}}
<div id="createModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg mx-4 max-h-[92vh] overflow-y-auto">
        <div class="flex items-center justify-between px-7 py-5 border-b border-gray-100 sticky top-0 bg-white rounded-t-3xl">
            <h2 class="text-lg font-bold text-gray-800"><i class="fas fa-clipboard-list mr-2 text-primary"></i> Buat Laporan Baru</h2>
            <button onclick="closeCreateModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="createForm" enctype="multipart/form-data" class="p-7 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fas fa-tag mr-1 text-secondary"></i> Kategori Kerusakan <span class="text-danger">*</span></label>
                <select name="category_id" id="reportCategory" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fas fa-heading mr-1 text-secondary"></i> Judul Laporan <span class="text-danger">*</span></label>
                <input type="text" name="title" required placeholder="Contoh: Jalan Rusak di RT 03"
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fas fa-map-marker-alt mr-1 text-secondary"></i> Lokasi / Alamat <span class="text-danger">*</span></label>
                <input type="text" name="location" required placeholder="Contoh: Jl. Mawar No. 5, RT 02 RW 04"
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fas fa-align-left mr-1 text-secondary"></i> Deskripsi Kerusakan <span class="text-danger">*</span></label>
                <textarea name="description" rows="4" required placeholder="Jelaskan kondisi kerusakan secara detail..."
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none resize-none"></textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5"><i class="fas fa-camera mr-1 text-secondary"></i> Foto Kerusakan</label>
                <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 hover:border-primary transition-colors" id="dropZone">
                    <input type="file" name="photo_damage" id="photoInput" accept="image/*" class="hidden" onchange="previewPhoto(this)">
                    <div id="uploadPrompt" class="text-center cursor-pointer" onclick="document.getElementById('photoInput').click()">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-300 mb-2 block"></i>
                        <p class="text-sm text-secondary font-medium">Klik atau seret foto ke sini</p>
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP · Maks 5MB</p>
                    </div>
                    <div id="photoPreview" class="hidden text-center">
                        <img id="previewImg" class="max-h-32 mx-auto rounded-xl object-cover mb-2">
                        <button type="button" onclick="clearPhoto()" class="text-xs text-red-500 hover:underline"><i class="fas fa-times mr-1"></i>Hapus foto</button>
                    </div>
                </div>
            </div>
            <button type="button" onclick="submitReport()" class="btn-primary w-full text-white font-bold py-3.5 rounded-xl text-sm flex items-center justify-center gap-2">
                <i class="fas fa-paper-plane"></i> Kirim Laporan
            </button>
        </form>
    </div>
</div>

{{-- ===== DETAIL MODAL ===== --}}
<div id="detailModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-7 py-5 border-b border-gray-100 sticky top-0 bg-white rounded-t-3xl">
            <h2 class="text-lg font-bold text-gray-800">Detail Laporan</h2>
            <button onclick="document.getElementById('detailModal').classList.add('hidden')" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="detailContent" class="p-7">
            <div class="skeleton h-32 rounded-xl mb-4"></div>
            <div class="skeleton h-4 rounded w-3/4 mb-2"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openCreateModal() { document.getElementById('createModal').classList.remove('hidden'); }
function closeCreateModal() { document.getElementById('createModal').classList.add('hidden'); document.getElementById('createForm').reset(); clearPhoto(); }

function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('uploadPrompt').classList.add('hidden');
            document.getElementById('photoPreview').classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function clearPhoto() {
    document.getElementById('photoInput').value = '';
    document.getElementById('uploadPrompt').classList.remove('hidden');
    document.getElementById('photoPreview').classList.add('hidden');
}

async function submitReport() {
    const fd = new FormData(document.getElementById('createForm'));
    const res = await fetch('{{ route("member.reports.store") }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }, body: fd });
    const data = await res.json();
    if (data.status === 'success') {
        closeCreateModal();
        Swal.fire({ icon: 'success', title: 'Laporan Terkirim!', text: data.message, confirmButtonColor: '#2563eb' }).then(() => location.reload());
    } else {
        const errors = data.errors ? Object.values(data.errors).flat().join('\n') : data.message;
        Swal.fire({ icon: 'error', title: 'Gagal!', text: errors });
    }
}

async function viewReport(id) {
    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('detailContent').innerHTML = '<div class="skeleton h-32 rounded-xl mb-4"></div><div class="skeleton h-4 rounded w-3/4 mb-2"></div>';

    const res    = await fetch(`/member/laporan/${id}`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN } });
    const report = await res.json();

    const responses = report.responses?.map(r => `
        <div class="flex gap-3 bg-primary-50 rounded-xl p-4 mt-3">
            <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white text-xs font-bold shrink-0">${r.admin?.name?.charAt(0) || 'A'}</div>
            <div>
                <p class="text-sm font-semibold text-gray-800">${r.admin?.name || 'Admin'} <span class="text-xs text-secondary ml-1">${new Date(r.created_at).toLocaleDateString('id-ID')}</span></p>
                <p class="text-sm text-gray-700 mt-1">${r.message}</p>
                ${r.photo_repair ? `<img src="/storage/${r.photo_repair}" class="mt-2 rounded-xl max-h-32 object-cover">` : ''}
            </div>
        </div>`).join('') || '<p class="text-secondary text-sm italic py-3">Belum ada tanggapan dari admin.</p>';

    document.getElementById('detailContent').innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <p class="font-mono text-primary font-bold">${report.code}</p>
            <span class="status-badge ${{pending:'bg-yellow-100 text-yellow-700',process:'bg-blue-100 text-blue-700',completed:'bg-green-100 text-green-700',rejected:'bg-red-100 text-red-700'}[report.status] || 'bg-gray-100 text-gray-700'}">
                ${{pending:'Menunggu',process:'Diproses',completed:'Selesai',rejected:'Ditolak'}[report.status] || report.status}
            </span>
        </div>
        <h3 class="font-bold text-gray-800 text-lg mb-2">${report.title}</h3>
        <p class="text-secondary text-sm mb-4">${report.description}</p>
        ${report.photo_damage ? `<img src="/storage/${report.photo_damage}" class="w-full rounded-xl mb-5 max-h-52 object-cover">` : ''}
        <div class="bg-gray-50 rounded-xl p-4 mb-5">
            <p class="text-xs text-secondary"><i class="fas fa-map-marker-alt mr-1 text-primary"></i> Lokasi: <span class="text-gray-800 font-medium">${report.location}</span></p>
        </div>
        <div class="border-t border-gray-100 pt-4">
            <h4 class="font-bold text-gray-800 mb-2"><i class="fas fa-comments mr-2 text-primary"></i> Tanggapan Admin</h4>
            ${responses}
        </div>`;
}

function deleteMyReport(id) {
    confirmDelete(`/member/laporan/${id}`, () => location.reload());
}
</script>
@endpush
