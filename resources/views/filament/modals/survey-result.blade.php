<div class="space-y-4 text-sm">
    {{-- HEADER KARTU HASIL EVALUASI --}}
    <div class="rounded-xl bg-gradient-to-r from-emerald-500/10 via-sky-500/10 to-transparent dark:from-emerald-950/40 p-4 border border-emerald-200 dark:border-emerald-800">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xs uppercase font-bold tracking-wider text-emerald-700 dark:text-emerald-400">
                    Survey Evaluation Result
                </span>
                <h3 class="text-lg font-extrabold text-gray-900 dark:text-gray-100">
                    {{ $record->vessel?->name ?: 'Vessel' }}
                </h3>
                <p class="text-xs text-gray-500">
                    Owner: {{ $record->vessel?->ownerCompany?->name ?: '—' }} &bull; IMO: {{ $record->vessel?->imo_number ?: '—' }}
                </p>
            </div>
            <div class="text-right">
                <span class="text-xs block text-gray-500 font-medium">Status Survei</span>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $record->status === 'Completed' ? 'bg-emerald-100 text-emerald-800' : ($record->status === 'Outstanding' ? 'bg-rose-100 text-rose-800' : 'bg-gray-100 text-gray-800') }}">
                    {{ $record->status ?: 'Outstanding' }}
                </span>
            </div>
        </div>
    </div>

    {{-- METRIK RINGKASAN --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-gray-50 dark:bg-gray-800/60 p-3 rounded-lg border border-gray-200 dark:border-gray-700 text-center">
            <span class="text-[11px] uppercase font-semibold text-gray-500 block">Risk Score</span>
            <span class="text-xl font-extrabold text-primary-600 dark:text-primary-400">
                {{ $record->risk_score !== null ? number_format($record->risk_score, 1) : '—' }}
            </span>
        </div>

        <div class="bg-gray-50 dark:bg-gray-800/60 p-3 rounded-lg border border-gray-200 dark:border-gray-700 text-center">
            <span class="text-[11px] uppercase font-semibold text-gray-500 block">Risk Rating</span>
            <span class="text-sm font-bold text-gray-800 dark:text-gray-200">
                {{ $record->risk_rating ?: 'Satisfactory' }}
            </span>
        </div>

        <div class="bg-gray-50 dark:bg-gray-800/60 p-3 rounded-lg border border-gray-200 dark:border-gray-700 text-center">
            <span class="text-[11px] uppercase font-semibold text-gray-500 block">Total Temuan</span>
            <span class="text-xl font-extrabold text-rose-600 dark:text-rose-400">
                {{ $record->defects()->count() }}
            </span>
        </div>

        <div class="bg-gray-50 dark:bg-gray-800/60 p-3 rounded-lg border border-gray-200 dark:border-gray-700 text-center">
            <span class="text-[11px] uppercase font-semibold text-gray-500 block">Defect Open</span>
            <span class="text-xl font-extrabold text-amber-600 dark:text-amber-400">
                {{ $record->defects()->where('status', 'Outstanding')->count() }}
            </span>
        </div>
    </div>

    {{-- INFORMASI POLIS & LOKASI --}}
    <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 p-3.5 space-y-2 text-xs">
        <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-800">
            <span class="text-gray-500">Asuransi Penjamin:</span>
            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $record->insuranceCompany?->name ?: '—' }}</span>
        </div>
        <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-800">
            <span class="text-gray-500">Perusahaan Surveyor:</span>
            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $record->surveyor?->name ?: ($record->surveyor_name ?: '—') }}</span>
        </div>
        <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-800">
            <span class="text-gray-500">Lokasi Inspeksi:</span>
            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $record->location ?: '—' }}</span>
        </div>
        <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-800">
            <span class="text-gray-500">Periode Polis (Policy Period):</span>
            <span class="font-semibold text-gray-800 dark:text-gray-200">
                {{ $record->policy_period_start ? $record->policy_period_start->format('d-M-Y') : '—' }} s/d {{ $record->policy_period_end ? $record->policy_period_end->format('d-M-Y') : '—' }}
            </span>
        </div>
        <div class="flex justify-between py-1">
            <span class="text-gray-500">Batas Waktu Survei (Due Date):</span>
            <span class="font-semibold text-rose-600 dark:text-rose-400">{{ $record->due_date ? $record->due_date->format('d-M-Y') : '—' }}</span>
        </div>
    </div>

    @if($record->summary || $record->resume_from_surveyor)
        <div class="bg-gray-50 dark:bg-gray-800/50 p-3 rounded-lg border border-gray-200 dark:border-gray-700 text-xs">
            <strong class="text-gray-800 dark:text-gray-200 block mb-1">Resume & Kesimpulan Evaluasi Surveyor:</strong>
            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                {{ $record->summary ?: $record->resume_from_surveyor }}
            </p>
        </div>
    @endif

    {{-- TOMBOL TINDAKAN LAPORAN LENGKAP & PRINT --}}
    <div class="pt-2 flex items-center justify-between border-t border-gray-200 dark:border-gray-800">
        <a href="{{ route('surveys.report', $record) }}" target="_blank" class="inline-flex items-center px-3.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-sm transition">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Buka & Cetak Laporan Lengkap (Full Report) ↗
        </a>

        <span class="text-[11px] text-gray-500">
            Format Resmi Standar PT MIB (1:1 Legacy)
        </span>
    </div>
</div>
