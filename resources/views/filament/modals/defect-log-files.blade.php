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

    <div>
        <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-2 flex items-center gap-1.5">
            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Riwayat Dokumen Bukti Perbaikan (Aktif & Dihapus/Diarsipkan)
        </h4>

        @php
            $activeFiles = $record->files()->get();
            $logFiles = $record->logRectificationFiles()->get();
        @endphp

        @if($activeFiles->isEmpty() && $logFiles->isEmpty())
            <div class="p-4 text-center text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-dashed border-gray-300 dark:border-gray-700">
                Belum ada berkas atau dokumen yang diunggah untuk temuan ini.
            </div>
        @else
            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left">
                    <thead class="bg-gray-100 dark:bg-gray-800 text-xs font-semibold uppercase text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-3 py-2">No</th>
                            <th class="px-3 py-2">Waktu Unggah / Log</th>
                            <th class="px-3 py-2">Judul Dokumen</th>
                            <th class="px-3 py-2">Tipe</th>
                            <th class="px-3 py-2">Status Dokumen</th>
                            <th class="px-3 py-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900 text-xs">
                        {{-- 1. BERKAS AKTIF --}}
                        @php $no = 1; @endphp
                        @foreach($activeFiles as $file)
                            <tr class="bg-emerald-50/40 dark:bg-emerald-950/20 hover:bg-emerald-50 dark:hover:bg-emerald-950/30">
                                <td class="px-3 py-2.5 font-medium">{{ $no++ }}</td>
                                <td class="px-3 py-2.5 text-gray-600 dark:text-gray-400">
                                    {{ $file->created_at ? $file->created_at->format('d-M-Y H:i') : 'Aktif Saat Ini' }}
                                </td>
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
                                        ✓ Berkas Aktif (Tersedia)
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

                        {{-- 2. RIWAYAT ARSIP / DIHAPUS (LOG_RECTIFICATION_FILE) --}}
                        @foreach($logFiles as $lfile)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-3 py-2.5 text-gray-500">{{ $no++ }}</td>
                                <td class="px-3 py-2.5 text-gray-600 dark:text-gray-400">
                                    {{ $lfile->datetime ? $lfile->datetime->format('d-M-Y H:i:s') : 'Arsip' }}
                                </td>
                                <td class="px-3 py-2.5 text-gray-800 dark:text-gray-200">
                                    {{ $lfile->file_title ?: ($lfile->file_name ?: 'Dokumen Arsip') }}
                                </td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300">
                                        {{ strtoupper($lfile->file_type ?: 'ARSIP') }}
                                    </span>
                                </td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                                        ℹ Sudah Diganti / Diarsipkan
                                    </span>
                                </td>
                                <td class="px-3 py-2.5 text-right">
                                    @if($lfile->file_url)
                                        <a href="{{ $lfile->file_url }}" target="_blank" class="inline-flex items-center px-2 py-0.5 text-xs text-gray-700 dark:text-gray-300 hover:underline">
                                            Lihat Arsip ↗
                                        </a>
                                    @else
                                        <span class="text-gray-400 italic">File diganti</span>
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
