<div class="space-y-4 text-sm">
    <div class="rounded-lg bg-gray-50 dark:bg-gray-800 p-3 border border-gray-200 dark:border-gray-700">
        <div class="font-semibold text-gray-900 dark:text-gray-100">
            Temuan: <span class="font-normal">{{ $record->explanation }}</span>
        </div>
        @if($record->recommendation_rectification)
            <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                <strong>Rekomendasi Surveyor:</strong> {{ $record->recommendation_rectification }}
            </div>
        @endif
    </div>

    @php
        $activeFiles = $record->files()->get();
        $logFiles = $record->logRectificationFiles()->get();
    @endphp

    {{-- BAGIAN 1: BERKAS AKTIF --}}
    <div>
        <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-2 flex items-center justify-between">
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Berkas Bukti Aktif (Saat Ini)
            </span>
            <span class="text-xs font-normal text-gray-500">{{ $activeFiles->count() }} file aktif</span>
        </h4>

        @if($activeFiles->isEmpty())
            <div class="p-3 text-center text-xs text-gray-500 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-dashed border-gray-300 dark:border-gray-700">
                Belum ada berkas bukti aktif yang terlampir.
            </div>
        @else
            <div class="overflow-x-auto rounded-lg border border-emerald-200 dark:border-emerald-900/50 mb-4">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left">
                    <thead class="bg-emerald-50/80 dark:bg-emerald-950/40 text-xs font-semibold uppercase text-emerald-900 dark:text-emerald-200">
                        <tr>
                            <th class="px-3 py-2 w-10">No</th>
                            <th class="px-3 py-2">Judul Dokumen</th>
                            <th class="px-3 py-2">Tipe</th>
                            <th class="px-3 py-2">Status</th>
                            <th class="px-3 py-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900 text-xs">
                        @foreach($activeFiles as $idx => $file)
                            <tr class="hover:bg-emerald-50/30 dark:hover:bg-emerald-950/20">
                                <td class="px-3 py-2.5 font-medium">{{ $idx + 1 }}</td>
                                <td class="px-3 py-2.5 font-medium text-gray-900 dark:text-gray-100">
                                    {{ $file->title ?: ($file->file ?: 'Dokumen Perbaikan') }}
                                </td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                        {{ strtoupper($file->type ?: 'FILE') }}
                                    </span>
                                </td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                                        ✓ Aktif
                                    </span>
                                </td>
                                <td class="px-3 py-2.5 text-right">
                                    @if($file->file_url)
                                        <a href="{{ $file->file_url }}" target="_blank" class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-white bg-primary-600 hover:bg-primary-700 rounded shadow-sm">
                                            Buka Berkas ↗
                                        </a>
                                    @else
                                        <span class="text-gray-400 italic">Tidak ada tautan</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- BAGIAN 2: LOG DOKUMEN YANG DIHAPUS / DIARSIPKAN OLEH SHIP OWNER --}}
    <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between mb-2">
            <h4 class="font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Log Dokumen yang Dihapus / Diarsipkan oleh Ship Owner
            </h4>
            @if($logFiles->isNotEmpty())
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                    {{ $logFiles->count() }} Dokumen Diarsipkan
                </span>
            @endif
        </div>

        @if($logFiles->isEmpty())
            <div class="p-3 text-center text-xs text-gray-500 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-dashed border-gray-300 dark:border-gray-700">
                Tidak ada dokumen yang pernah dihapus atau diarsipkan pada temuan ini.
            </div>
        @else
            <div class="mb-2 text-xs text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/40 p-2.5 rounded-lg border border-amber-200 dark:border-amber-900">
                ℹ Berkas di bawah ini adalah rekaman dokumen yang pernah diunggah oleh Ship Owner namun kemudian <strong>diganti/dihapus</strong> saat pengajuan revisi perbaikan. Semua file tetap tersimpan di server untuk kebutuhan audit trail.
            </div>

            <div class="overflow-x-auto rounded-lg border border-amber-200 dark:border-amber-900">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left">
                    <thead class="bg-amber-50/70 dark:bg-amber-950/30 text-xs font-semibold uppercase text-amber-900 dark:text-amber-200">
                        <tr>
                            <th class="px-3 py-2 w-10">No</th>
                            <th class="px-3 py-2">Waktu Dihapus / Arsip</th>
                            <th class="px-3 py-2">Judul Dokumen Terhapus</th>
                            <th class="px-3 py-2">Tipe</th>
                            <th class="px-3 py-2">Status Audit</th>
                            <th class="px-3 py-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900 text-xs">
                        @foreach($logFiles as $idx => $lfile)
                            <tr class="hover:bg-amber-50/20 dark:hover:bg-amber-950/10">
                                <td class="px-3 py-2.5 text-gray-500">{{ $idx + 1 }}</td>
                                <td class="px-3 py-2.5 text-gray-600 dark:text-gray-400 font-mono text-[11px]">
                                    {{ $lfile->datetime ? $lfile->datetime->format('d-M-Y H:i:s') : 'Arsip' }}
                                </td>
                                <td class="px-3 py-2.5 font-medium text-gray-800 dark:text-gray-200">
                                    {{ $lfile->file_title ?: ($lfile->file_name ?: 'Dokumen Arsip') }}
                                </td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300">
                                        {{ strtoupper($lfile->file_type ?: 'ARSIP') }}
                                    </span>
                                </td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-rose-100 text-rose-800 dark:bg-rose-900 dark:text-rose-200">
                                        ✕ Dihapus / Diarsipkan
                                    </span>
                                </td>
                                <td class="px-3 py-2.5 text-right">
                                    @if($lfile->file_url)
                                        <a href="{{ $lfile->file_url }}" target="_blank" class="inline-flex items-center px-2 py-0.5 text-xs text-primary-600 hover:text-primary-800 dark:text-primary-400 hover:underline">
                                            Lihat Arsip ↗
                                        </a>
                                    @else
                                        <span class="text-gray-400 italic">File lama diganti</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
