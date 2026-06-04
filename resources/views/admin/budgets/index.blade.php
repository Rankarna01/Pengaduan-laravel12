@extends('layouts.admin')
@section('page_title', 'Anggaran Laporan')
@section('breadcrumb', 'Anggaran')

@section('admin_content')
<div class="flex items-center justify-between mb-6">
    <div data-aos="fade-right">
        <h2 class="text-xl font-bold text-gray-800">Manajemen Anggaran</h2>
        <p class="text-sm text-secondary">Kelola estimasi dan realisasi anggaran untuk laporan yang diproses atau selesai.</p>
    </div>
</div>

{{-- Tabs --}}
<div class="flex gap-4 mb-6 border-b border-gray-100 pb-1" data-aos="fade-up">
    <a href="{{ route('admin.budgets.index', ['tab' => 'process']) }}"
       class="pb-2 font-semibold text-sm transition-all {{ $tab === 'process' ? 'text-primary border-b-2 border-primary' : 'text-gray-400 hover:text-gray-600' }}">
        <i class="fas fa-tools mr-1"></i> Sedang Diproses
    </a>
    <a href="{{ route('admin.budgets.index', ['tab' => 'completed']) }}"
       class="pb-2 font-semibold text-sm transition-all {{ $tab === 'completed' ? 'text-primary border-b-2 border-primary' : 'text-gray-400 hover:text-gray-600' }}">
        <i class="fas fa-check-circle mr-1"></i> Laporan Selesai
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100" data-aos="fade-up" data-aos-delay="100">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-50 bg-gray-50/50 text-left">
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Kode</th>
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Kategori</th>
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Judul Laporan</th>
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider">Anggaran</th>
                    <th class="px-5 py-3 text-xs font-semibold text-secondary uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($reports as $report)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3.5"><span class="font-mono text-primary font-bold bg-primary/10 px-2 py-0.5 rounded text-xs">{{ $report->code }}</span></td>
                    <td class="px-5 py-3.5 text-gray-600">{{ $report->category->name }}</td>
                    <td class="px-5 py-3.5">
                        <p class="font-bold text-gray-800 line-clamp-1">{{ $report->title }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $report->created_at->format('d M Y') }}</p>
                    </td>
                    <td class="px-5 py-3.5">
                        @if($report->budget)
                            <p class="text-green-600 font-bold">Rp {{ number_format($report->budget->amount, 0, ',', '.') }}</p>
                            @if($report->budget->notes)
                                <p class="text-xs text-gray-400 truncate max-w-[150px]">{{ $report->budget->notes }}</p>
                            @endif
                        @else
                            <span class="text-xs font-bold text-orange-500 bg-orange-50 px-2 py-0.5 rounded-md border border-orange-100">Belum diatur</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <button
    onclick="openBudgetModal({{ $report->id }}, '{{ $report->code }}', '{{ addslashes($report->title) }}', {{ $report->budget ? $report->budget->amount : "''" }}, '{{ $report->budget ? addslashes($report->budget->notes) : "" }}', '{{ $report->budget && $report->budget->items ? addslashes(json_encode($report->budget->items)) : "" }}')"
    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-primary rounded-xl shadow-md hover:shadow-lg hover:scale-105 hover:bg-primary/90 transition-all duration-300">
    <i class="fas fa-wallet"></i>
    <span>Set Anggaran</span>
</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-secondary">
                    <i class="fas fa-wallet text-5xl text-gray-200 mb-3 block"></i>
                    Belum ada laporan di tab ini.
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-5 border-t border-gray-100">{{ $reports->links() }}</div>
</div>

{{-- Modal Anggaran --}}
<div id="budgetModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
        <div class="flex items-center justify-between px-7 py-5 border-b border-gray-100">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Set Anggaran</h2>
                <p id="modalSubtitle" class="text-xs text-primary font-mono mt-1"></p>
            </div>
            <button onclick="closeBudgetModal()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="budgetForm" class="p-7 space-y-4">
            <input type="hidden" id="reportId">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Rincian Bahan / Item <span class="text-xs text-gray-400 font-normal ml-2">(opsional)</span></label>
                <div id="itemsContainer" class="space-y-2 mb-3 max-h-40 overflow-y-auto pr-1"></div>
                <button type="button" onclick="addBudgetItem()" class="text-xs text-primary font-bold hover:bg-primary/10 px-3 py-1.5 rounded-lg transition-colors border border-primary/20 bg-primary/5">
                    <i class="fas fa-plus mr-1"></i> Tambah Bahan
                </button>
            </div>
            
            <div class="pt-2 border-t border-gray-100">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Total Anggaran (Rp) <span class="text-danger">*</span></label>
                <input type="number" id="budgetAmount" required min="0" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none bg-gray-50 focus:bg-white" placeholder="Contoh: 1500000" oninput="if(document.querySelectorAll('.budget-item').length > 0) calculateTotal()">
                <p class="text-[10px] text-gray-400 mt-1">Total dapat dihitung otomatis dari rincian bahan di atas.</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Catatan / Rincian</label>
                <textarea id="budgetNotes" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none" placeholder="Opsional, rincian biaya atau catatan khusus..."></textarea>
            </div>
            <button type="button" onclick="submitBudget()" class="btn-primary w-full text-white bg-primary hover:bg-blue-700 font-bold py-3 rounded-xl text-sm transition-all shadow-lg shadow-primary/30 flex items-center justify-center gap-2 mt-2">
                <i class="fas fa-save"></i> Simpan Anggaran
            </button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function addBudgetItem(name = '', price = '') {
    const div = document.createElement('div');
    div.className = 'flex items-center gap-2 budget-item';
    div.innerHTML = `
        <input type="text" class="item-name flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none focus:border-primary" placeholder="Nama Bahan (ex: Pasir)" value="${name}" required>
        <input type="number" class="item-price w-32 px-3 py-2 border border-gray-200 rounded-lg text-sm outline-none focus:border-primary" placeholder="Harga (Rp)" value="${price}" oninput="calculateTotal()" required min="0">
        <button type="button" onclick="this.parentElement.remove(); calculateTotal();" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors"><i class="fas fa-trash-alt"></i></button>
    `;
    document.getElementById('itemsContainer').appendChild(div);
    calculateTotal();
}

function calculateTotal() {
    let total = 0;
    const items = document.querySelectorAll('.budget-item');
    if (items.length > 0) {
        items.forEach(item => {
            const price = parseFloat(item.querySelector('.item-price').value) || 0;
            total += price;
        });
        document.getElementById('budgetAmount').value = total;
        document.getElementById('budgetAmount').readOnly = true;
        document.getElementById('budgetAmount').classList.add('bg-gray-100');
    } else {
        document.getElementById('budgetAmount').readOnly = false;
        document.getElementById('budgetAmount').classList.remove('bg-gray-100');
    }
}

function openBudgetModal(id, code, title, amount, notes, itemsStr) {
    document.getElementById('reportId').value = id;
    document.getElementById('modalSubtitle').innerText = code + ' - ' + title;
    document.getElementById('budgetAmount').value = amount !== '' ? amount : '';
    document.getElementById('budgetNotes').value = notes;
    
    document.getElementById('itemsContainer').innerHTML = '';
    calculateTotal(); // reset readonly state

    if (itemsStr) {
        try {
            const items = JSON.parse(itemsStr);
            if (Array.isArray(items)) {
                items.forEach(i => addBudgetItem(i.name, i.price));
            }
        } catch(e) {}
    }

    document.getElementById('budgetModal').classList.remove('hidden');
}

function closeBudgetModal() {
    document.getElementById('budgetModal').classList.add('hidden');
    document.getElementById('budgetForm').reset();
    document.getElementById('itemsContainer').innerHTML = '';
}

async function submitBudget() {
    const id = document.getElementById('reportId').value;
    const amount = document.getElementById('budgetAmount').value;
    const notes = document.getElementById('budgetNotes').value;
    
    let items = [];
    document.querySelectorAll('.budget-item').forEach(item => {
        items.push({
            name: item.querySelector('.item-name').value,
            price: parseFloat(item.querySelector('.item-price').value) || 0
        });
    });

    if (!amount) return Swal.fire('Error', 'Nominal wajib diisi!', 'error');

    const res = await fetch(`/admin/anggaran/${id}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
        body: JSON.stringify({ amount, notes, items: items.length > 0 ? items : null })
    });
    
    const data = await res.json();
    if (data.status === 'success') {
        closeBudgetModal();
        Swal.fire({ icon: 'success', title: 'Berhasil', text: data.message, timer: 1500, showConfirmButton: false });
        setTimeout(() => location.reload(), 1500);
    } else {
        Swal.fire('Gagal!', data.message, 'error');
    }
}
</script>
@endpush
