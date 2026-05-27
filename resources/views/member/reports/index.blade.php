@extends('layouts.app')
@section('title', 'Laporan Saya — Pengaduan Desa')

@section('content')
<div class="min-h-screen bg-surface flex flex-col">
    @include('layouts.partials.navbar-member')

    <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header Halaman --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6" data-aos="fade-up">
            <div>
                <h1 class="text-xl font-extrabold text-gray-800">Laporan Saya</h1>
                <p class="text-sm text-secondary mt-0.5">Pantau semua laporan dan riwayat status pengaduan Anda</p>
            </div>
            <button onclick="openCreateModal()" class="w-full sm:w-auto btn-primary bg-primary text-white px-6 py-2.5 rounded-xl text-sm font-bold flex items-center justify-center gap-2 shadow-lg shadow-primary/30 hover:-translate-y-0.5 transition-all">
                <i class="fas fa-plus"></i> Buat Laporan Baru
            </button>
        </div>

        {{-- Reports List --}}
        <div class="space-y-5" id="reportsList">
            @forelse($reports as $report)
            @php
            $statusBg    = ['pending'=>'bg-orange-50 text-orange-600', 'process'=>'bg-blue-50 text-blue-600', 'completed'=>'bg-green-50 text-green-600', 'rejected'=>'bg-red-50 text-red-600'];
            $stepOrder   = ['pending'=>0, 'process'=>1, 'completed'=>2, 'rejected'=>2];
            $currentStep = $stepOrder[$report->status] ?? 0;
            @endphp
            
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300" data-aos="fade-up">
                <div class="p-5 sm:p-6">
                    <div class="flex items-start gap-4 sm:gap-5">
                        
                        {{-- Icon Kategori --}}
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gray-50 border border-gray-100 rounded-2xl flex items-center justify-center shrink-0">
                            <i class="{{ $report->category->icon }} text-2xl text-gray-500"></i>
                        </div>
                        
                        {{-- Isi Konten Laporan --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2 mb-2">
                                <div>
                                    <h3 class="font-extrabold text-gray-800 text-lg line-clamp-1">{{ $report->title }}</h3>
                                    <p class="text-xs text-primary font-mono font-bold mt-1 bg-primary/10 inline-block px-2 py-0.5 rounded-md">{{ $report->code }}</p>
                                </div>
                                <span class="status-badge {{ $statusBg[$report->status] ?? 'bg-gray-100 text-gray-700' }} shrink-0 px-3 py-1 rounded-full text-xs font-bold border border-current self-start">
                                    {{ $report->status_label }}
                                </span>
                            </div>
                            
                            <p class="text-sm text-gray-600 mt-3 line-clamp-2 leading-relaxed">{{ $report->description }}</p>
                            
                            <div class="flex flex-wrap items-center gap-4 mt-4 text-xs font-medium text-gray-500">
                                <span class="flex items-center gap-1.5"><i class="fas fa-map-marker-alt text-red-500"></i>{{ $report->location }}</span>
                                <span class="flex items-center gap-1.5"><i class="fas fa-tag text-primary"></i>{{ $report->category->name }}</span>
                                <span class="flex items-center gap-1.5"><i class="far fa-calendar-alt"></i>{{ $report->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Timeline Progress --}}
                    @if($report->status !== 'rejected')
                    <div class="mt-6 pt-5 border-t border-gray-50">
                        <div class="flex items-center justify-between relative max-w-lg mx-auto">
                            {{-- Line Background --}}
                            <div class="absolute top-1/2 left-0 right-0 h-0.5 bg-gray-100 -z-10 -translate-y-1/2"></div>
                            {{-- Line Progress --}}
                            <div class="absolute top-1/2 left-0 h-0.5 bg-primary -z-10 -translate-y-1/2 transition-all duration-500" style="width: {{ $currentStep === 0 ? '0%' : ($currentStep === 1 ? '50%' : '100%') }}"></div>
                            
                            @php
                            $timelineSteps = [
                                ['key'=>'pending',   'label'=>'Dikirim',  'icon'=>'fa-paper-plane'],
                                ['key'=>'process',   'label'=>'Diproses', 'icon'=>'fa-tools'],
                                ['key'=>'completed', 'label'=>'Selesai',  'icon'=>'fa-check'],
                            ];
                            @endphp
                            
                            @foreach($timelineSteps as $i => $step)
                            <div class="flex flex-col items-center gap-2 bg-white px-2">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm border-2 transition-colors duration-300
                                    {{ $currentStep >= $i ? 'bg-primary border-primary text-white shadow-md shadow-primary/30' : 'bg-white border-gray-200 text-gray-300' }}">
                                    <i class="fas {{ $step['icon'] }} text-xs"></i>
                                </div>
                                <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wider {{ $currentStep >= $i ? 'text-primary' : 'text-gray-400' }}">{{ $step['label'] }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="mt-5 bg-red-50 border border-red-100 rounded-xl px-4 py-3 flex items-start gap-3">
                        <i class="fas fa-times-circle text-red-500 mt-0.5 text-lg"></i>
                        <div>
                            <p class="text-sm text-red-700 font-bold">Laporan ini telah ditolak oleh admin.</p>
                            <p class="text-xs text-red-600 mt-1">Silakan klik "Lihat Detail" untuk mengetahui alasan penolakan.</p>
                        </div>
                    </div>
                    @endif

                    {{-- Actions Bottom --}}
                    <div class="flex items-center gap-3 mt-5 pt-4 border-t border-gray-50 bg-gray-50/30 -mx-5 sm:-mx-6 -mb-5 sm:-mb-6 px-5 sm:px-6 py-4">
                        <button onclick="viewReport({{ $report->id }})" class="flex items-center justify-center gap-2 text-primary bg-primary/10 px-4 py-2 rounded-lg text-sm font-bold hover:bg-primary/20 transition-colors flex-1 sm:flex-none">
                            <i class="fas fa-eye"></i> Detail & Tanggapan
                        </button>
                        @if($report->status === 'pending')
                        <button onclick="deleteMyReport({{ $report->id }})" class="flex items-center justify-center gap-2 text-red-500 bg-red-50 px-4 py-2 rounded-lg text-sm font-bold hover:bg-red-100 transition-colors sm:ml-auto">
                            <i class="fas fa-trash-alt"></i> Batalkan
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-12 sm:p-16 text-center" data-aos="fade-up">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-5 border border-gray-100">
                    <i class="fas fa-folder-open text-5xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-extrabold text-gray-800 mb-2">Belum Ada Laporan</h3>
                <p class="text-gray-500 text-sm mb-8 max-w-sm mx-auto">Anda belum pernah membuat laporan. Mari mulai berpartisipasi melaporkan kerusakan infrastruktur di desa kita.</p>
                <button onclick="openCreateModal()" class="btn-primary bg-primary text-white px-8 py-3.5 rounded-xl text-sm font-bold inline-flex items-center justify-center gap-2 shadow-lg shadow-primary/30 hover:-translate-y-0.5 transition-all">
                    <i class="fas fa-plus"></i> Buat Laporan Pertama
                </button>
            </div>
            @endforelse
        </div>

        <div class="mt-8">{{ $reports->links() }}</div>
    </main>

    @include('layouts.partials.footer')
</div>

{{-- ===== CREATE REPORT MODAL ===== --}}
<div id="createModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-gray-900/60 backdrop-blur-sm hidden transition-opacity">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg mx-4 max-h-[92vh] flex flex-col relative overflow-hidden">
        
        {{-- Header Modal --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 bg-white z-10">
            <h2 class="text-lg font-extrabold text-gray-800 flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                    <i class="fas fa-file-signature"></i>
                </div>
                Buat Laporan Baru
            </h2>
            <button onclick="closeCreateModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400 hover:text-red-500 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        {{-- Form Body --}}
        <div class="overflow-y-auto p-6 scrollbar-hide">
            <form id="createForm" enctype="multipart/form-data" class="space-y-5">
                @csrf
                
                {{-- Kategori (Icon Text Removed) --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <i class="fas fa-tag"></i> Kategori Kerusakan <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id" id="reportCategory" required
                            class="w-full px-4 py-3.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none appearance-none bg-gray-50 hover:bg-gray-100 focus:bg-white font-medium text-gray-800 transition-all cursor-pointer">
                        <option value="" disabled selected>-- Pilih Jenis Infrastruktur --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Judul --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <i class="fas fa-heading"></i> Judul Laporan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" required placeholder="Contoh: Jalan Rusak di RT 03"
                           class="w-full px-4 py-3.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-gray-50 hover:bg-gray-100 focus:bg-white font-medium text-gray-800 transition-all">
                </div>

                {{-- Lokasi --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <i class="fas fa-map-marker-alt"></i> Lokasi / Alamat <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="location" required placeholder="Contoh: Jl. Mawar No. 5, RT 02 RW 04"
                           class="w-full px-4 py-3.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-gray-50 hover:bg-gray-100 focus:bg-white font-medium text-gray-800 transition-all">
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <i class="fas fa-align-left"></i> Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" rows="3" required placeholder="Jelaskan kondisi kerusakan secara detail..."
                              class="w-full px-4 py-3.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none bg-gray-50 hover:bg-gray-100 focus:bg-white font-medium text-gray-800 transition-all"></textarea>
                </div>

                {{-- Foto --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <i class="fas fa-camera"></i> Foto Kerusakan <span class="text-red-500">*</span>
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-2xl p-6 hover:border-primary hover:bg-blue-50/50 transition-all cursor-pointer group bg-gray-50" id="dropZone" onclick="document.getElementById('photoInput').click()">
                        <input type="file" name="photo_damage" id="photoInput" required accept="image/*" class="hidden" onchange="previewPhoto(this)">
                        
                        <div id="uploadPrompt" class="text-center">
                            <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm border border-gray-100 group-hover:border-primary/30 transition-all">
                                <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 group-hover:text-primary transition-colors"></i>
                            </div>
                            <p class="text-sm text-gray-700 font-bold mb-1">Klik atau seret foto ke sini</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">Format: JPG, PNG · Maks 5MB</p>
                        </div>

                        <div id="photoPreview" class="hidden text-center">
                            <img id="previewImg" class="max-h-40 mx-auto rounded-xl object-cover mb-3 shadow-sm border border-gray-200">
                            <button type="button" onclick="clearPhoto(); event.stopPropagation();" class="text-xs text-red-600 font-bold hover:bg-red-100 bg-red-50 px-4 py-2 rounded-lg border border-red-100 transition-colors inline-flex items-center gap-1.5">
                                <i class="fas fa-trash-alt"></i> Hapus Foto
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Footer Modal --}}
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 z-10">
            <button type="button" onclick="submitReport()" class="w-full bg-primary hover:bg-blue-700 text-white font-bold py-4 rounded-xl text-sm flex items-center justify-center gap-2 transition-all shadow-lg shadow-primary/30 active:scale-[0.98]">
                <i class="fas fa-paper-plane"></i> Kirim Laporan Sekarang
            </button>
        </div>
    </div>
</div>

{{-- ===== DETAIL MODAL ===== --}}
<div id="detailModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-gray-900/60 backdrop-blur-sm hidden transition-opacity">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl mx-4 max-h-[90vh] flex flex-col relative overflow-hidden">
        
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 bg-white z-10">
            <h2 class="text-lg font-extrabold text-gray-800">Detail & Tanggapan</h2>
            <button onclick="document.getElementById('detailModal').classList.add('hidden')" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-400 hover:text-red-500 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        
        <div id="detailContent" class="overflow-y-auto p-6 sm:p-8 scrollbar-hide">
            <div class="animate-pulse flex flex-col gap-4">
                <div class="h-40 bg-gray-100 rounded-2xl"></div>
                <div class="h-4 bg-gray-100 rounded w-1/4 mt-4"></div>
                <div class="h-4 bg-gray-100 rounded w-full"></div>
                <div class="h-4 bg-gray-100 rounded w-3/4"></div>
            </div>
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
    // Validasi form manual dengan SweetAlert Custom Handler
    const form = document.getElementById('createForm');
    if(!form.checkValidity()) {
        const firstInvalid = form.querySelector(':invalid');
        let errorMsg = 'Harap lengkapi semua data yang diwajibkan.';
        
        if (firstInvalid) {
            if (firstInvalid.name === 'category_id') errorMsg = 'Silakan pilih kategori kerusakan terlebih dahulu.';
            else if (firstInvalid.name === 'title') errorMsg = 'Judul laporan tidak boleh kosong.';
            else if (firstInvalid.name === 'location') errorMsg = 'Lokasi/Alamat kerusakan tidak boleh kosong.';
            else if (firstInvalid.name === 'description') errorMsg = 'Deskripsi kerusakan tidak boleh kosong.';
            else if (firstInvalid.name === 'photo_damage') errorMsg = 'Anda wajib mengunggah foto bukti kerusakan.';
        }

        Swal.fire({
            icon: 'warning',
            title: 'Oops! Data Belum Lengkap',
            text: errorMsg,
            confirmButtonColor: '#2563eb',
            confirmButtonText: 'Saya Mengerti'
        });
        return;
    }

    const fd = new FormData(form);
    
    // UI Feedback (Loading)
    const btn = event.currentTarget;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Mengirim...';
    btn.disabled = true;

    try {
        const res = await fetch('{{ route("member.reports.store") }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }, body: fd });
        const data = await res.json();
        
        if (data.status === 'success') {
            closeCreateModal();
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message, confirmButtonColor: '#2563eb' }).then(() => location.reload());
        } else {
            const errors = data.errors ? Object.values(data.errors).flat().join('\n') : data.message;
            Swal.fire({ icon: 'error', title: 'Gagal!', text: errors, confirmButtonColor: '#2563eb' });
        }
    } catch(err) {
        Swal.fire({ icon: 'error', title: 'Kesalahan Sistem', text: 'Terjadi kesalahan jaringan.', confirmButtonColor: '#2563eb' });
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}

async function viewReport(id) {
    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('detailContent').innerHTML = '<div class="animate-pulse flex flex-col gap-4"><div class="h-40 bg-gray-100 rounded-2xl"></div><div class="h-4 bg-gray-100 rounded w-1/4 mt-4"></div><div class="h-4 bg-gray-100 rounded w-full"></div></div>';

    const res    = await fetch(`/member/laporan/${id}`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN } });
    const report = await res.json();

    const responses = report.responses?.map(r => `
        <div class="flex gap-4 bg-blue-50/50 rounded-2xl p-5 mt-4 border border-blue-100/50">
            <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-bold shrink-0 shadow-sm">${r.admin?.name?.charAt(0) || 'A'}</div>
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <p class="font-extrabold text-gray-800">${r.admin?.name || 'Admin'}</p>
                    <span class="bg-primary/10 text-primary text-[10px] uppercase font-bold px-2 py-0.5 rounded">Admin</span>
                </div>
                <p class="text-xs text-gray-500 font-medium mb-3"><i class="far fa-clock mr-1"></i> ${new Date(r.created_at).toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric', hour:'2-digit', minute:'2-digit'})}</p>
                <p class="text-sm text-gray-700 leading-relaxed font-medium">${r.message}</p>
                ${r.photo_repair ? `<img src="/storage/${r.photo_repair}" class="mt-4 rounded-xl max-h-48 w-full object-cover border border-gray-200 shadow-sm">` : ''}
            </div>
        </div>`).join('') || '<div class="bg-gray-50 border border-gray-100 rounded-2xl p-6 text-center mt-4"><p class="text-gray-500 text-sm font-medium">Belum ada tanggapan dari petugas terkait.</p></div>';

    document.getElementById('detailContent').innerHTML = `
        <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-100">
            <div>
                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold mb-1">Kode Laporan</p>
                <p class="font-mono text-primary font-bold bg-primary/10 px-2 py-1 rounded inline-block">${report.code}</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold mb-1">Status</p>
                <span class="status-badge ${{pending:'bg-orange-50 text-orange-600',process:'bg-blue-50 text-blue-600',completed:'bg-green-50 text-green-600',rejected:'bg-red-50 text-red-600'}[report.status] || 'bg-gray-100 text-gray-700'} px-3 py-1 rounded-full text-xs font-bold border border-current inline-block">
                    ${{pending:'Menunggu',process:'Diproses',completed:'Selesai',rejected:'Ditolak'}[report.status] || report.status}
                </span>
            </div>
        </div>

        ${report.photo_damage ? `<img src="/storage/${report.photo_damage}" class="w-full rounded-2xl mb-6 max-h-64 object-cover shadow-sm border border-gray-100">` : ''}
        
        <h3 class="font-extrabold text-gray-800 text-2xl mb-3 leading-tight">${report.title}</h3>
        
        <div class="flex items-center gap-2 mb-6">
            <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1.5 rounded-lg flex items-center gap-1.5"><i class="fas fa-tag text-primary"></i> ${report.category?.name || '-'}</span>
            <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1.5 rounded-lg flex items-center gap-1.5"><i class="far fa-calendar-alt text-primary"></i> ${new Date(report.created_at).toLocaleDateString('id-ID')}</span>
        </div>

        <p class="text-gray-600 text-sm mb-6 leading-relaxed bg-gray-50 p-4 rounded-2xl border border-gray-100">${report.description}</p>
        
        <div class="flex items-start gap-3 bg-red-50/50 p-4 rounded-2xl border border-red-100/50 mb-8">
            <i class="fas fa-map-marker-alt text-red-500 mt-0.5 text-lg"></i>
            <div>
                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold mb-1">Lokasi Kejadian / Kerusakan</p>
                <p class="text-gray-800 font-semibold text-sm leading-snug">${report.location}</p>
            </div>
        </div>
        
        <div class="border-t-2 border-gray-50 pt-6">
            <h4 class="font-extrabold text-gray-800 text-lg flex items-center gap-2"><div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary"><i class="fas fa-comments"></i></div> Tanggapan Petugas</h4>
            ${responses}
        </div>`;
}

function deleteMyReport(id) {
    Swal.fire({
        title: 'Batalkan Laporan?',
        text: "Laporan yang belum diproses akan dihapus permanen.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#9ca3af',
        confirmButtonText: 'Ya, Batalkan!',
        cancelButtonText: 'Tutup'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/member/laporan/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire('Dibatalkan!', data.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Gagal!', data.message, 'error');
                }
            });
        }
    });
}
</script>
@endpush