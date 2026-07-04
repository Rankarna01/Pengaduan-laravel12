@php
$statusClasses = ['pending'=>'bg-yellow-100 text-yellow-700','process'=>'bg-blue-100 text-blue-700','completed'=>'bg-green-100 text-green-700','rejected'=>'bg-red-100 text-red-700'];
$statusLabels  = ['pending'=>'Menunggu','process'=>'Diproses','completed'=>'Selesai','rejected'=>'Ditolak'];
@endphp
<tr class="hover:bg-gray-50 transition-colors">
    <td class="px-5 py-3.5 font-mono text-xs font-semibold text-primary">{{ $report->code }}</td>
    <td class="px-5 py-3.5">
        <p class="font-semibold text-gray-800 truncate max-w-xs">{{ $report->title }}</p>
        <p class="text-xs text-secondary"><i class="fas fa-map-marker-alt mr-1"></i>{{ Str::limit($report->location, 35) }}</p>
    </td>
    <td class="px-5 py-3.5">
        <div class="flex items-center gap-3">
            <img src="{{ $report->user->avatar_url }}" class="w-8 h-8 rounded-full shadow-sm border border-gray-100">
            <div>
                <span class="text-gray-800 text-sm font-bold block">{{ $report->user->name }}</span>
                @if($report->user->dusun)
                <span class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider flex items-center mt-0.5"><i class="fas fa-map-signs mr-1.5 text-primary/70"></i> {{ $report->user->dusun }}</span>
                @endif
            </div>
        </div>
    </td>
    <td class="px-5 py-3.5 text-sm text-gray-700">{{ $report->category->name }}</td>
    <td class="px-5 py-3.5">
        <span class="status-badge {{ $statusClasses[$report->status] ?? 'bg-gray-100 text-gray-700' }}">
            {{ $statusLabels[$report->status] ?? $report->status }}
        </span>
    </td>
    <td class="px-5 py-3.5 text-xs text-secondary whitespace-nowrap">{{ $report->created_at->format('d M Y') }}</td>
    <td class="px-5 py-3.5">
        <div class="flex items-center gap-2">
            <button onclick="openDetailModal({{ $report->id }})" data-id="{{ $report->id }}"
                    class="p-1.5 rounded-lg bg-primary-100 text-primary hover:bg-primary hover:text-white transition-colors" title="Detail & Tanggapan">
                <i class="fas fa-eye text-sm"></i>
            </button>
            @if($report->budget)
            <button onclick="viewBudget({{ $report->budget->amount }}, '{{ addslashes($report->budget->notes) }}', '{{ $report->budget->items ? addslashes(json_encode($report->budget->items)) : '' }}')"
                    class="p-1.5 rounded-lg bg-green-100 text-green-600 hover:bg-green-600 hover:text-white transition-colors" title="Detail Anggaran">
                <i class="fas fa-wallet text-sm"></i>
            </button>
            @endif
            <button onclick="deleteReport({{ $report->id }})"
                    class="p-1.5 rounded-lg bg-red-100 text-red-500 hover:bg-red-500 hover:text-white transition-colors" title="Hapus">
                <i class="fas fa-trash text-sm"></i>
            </button>
        </div>
    </td>
</tr>
