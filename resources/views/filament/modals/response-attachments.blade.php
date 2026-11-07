<div class="space-y-4 text-sm">
    <div class="rounded-lg bg-gray-50 dark:bg-gray-800 p-3.5 border border-gray-200 dark:border-gray-700">
        <div class="text-xs font-semibold text-primary-600 dark:text-primary-400 uppercase tracking-wider mb-1">
            Ref {{ $record->item?->ref_no ?: 'Item' }} &bull; {{ $record->item?->category ?: 'Checklist' }}
        </div>
        <div class="font-medium text-gray-900 dark:text-gray-100 text-base">
            {{ $record->item?->item_name ?: 'Checklist Pertanyaan' }}
        </div>
        @if($record->remarks_from_surveyor)
            <div class="mt-2 text-xs text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-900 p-2 rounded border border-gray-200 dark:border-gray-700">
                <strong>Catatan / Remark:</strong> {{ $record->remarks_from_surveyor }}
            </div>
        @endif
    </div>

    <div>
        <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-2 flex items-center gap-1.5 text-sm">
            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
            Dokumen & Foto Bukti Terlampir pada Field Ini
        </h4>

        @php
            $attachments = $record->attachments()->get();
        @endphp

        @if($attachments->isEmpty())
            <div class="p-4 text-center text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 text-xs">
                Belum ada dokumen sertifikat atau foto yang diunggah untuk pertanyaan ini.
            </div>
        @else
            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left text-xs">
                    <thead class="bg-gray-100 dark:bg-gray-800 font-semibold uppercase text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-3 py-2 w-8">No</th>
                            <th class="px-3 py-2">Judul Dokumen</th>
                            <th class="px-3 py-2">Tipe Berkas</th>
                            <th class="px-3 py-2">Ketersediaan</th>
                            <th class="px-3 py-2">Nama Berkas</th>
                            <th class="px-3 py-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                        @foreach($attachments as $index => $att)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-3 py-2.5 font-medium">{{ $index + 1 }}</td>
                                <td class="px-3 py-2.5 font-medium text-gray-900 dark:text-gray-100">
                                    {{ $att->title ?: ($att->file ?: 'Lampiran Dokumen') }}
                                </td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                        {{ strtoupper($att->type ?: 'FILE') }}
                                    </span>
                                </td>
                                <td class="px-3 py-2.5">
                                    @if($att->has_physical_file)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                                            ✓ Ada di Server
                                        </span>
                                    @elseif(!empty($att->gdrive_id))
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                            ☁ Google Drive
                                        </span>
                                    @elseif(!empty($att->link))
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-semibold bg-sky-100 text-sky-800 dark:bg-sky-900 dark:text-sky-200">
                                            🔗 Tautan Web
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                                            ⚠ Arsip Historis
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-2.5 text-gray-600 dark:text-gray-400 truncate max-w-xs font-mono text-[11px]">
                                    {{ $att->file ?: ($att->link ?: '—') }}
                                </td>
                                <td class="px-3 py-2.5 text-right whitespace-nowrap">
                                    @if($att->has_physical_file)
                                        <a href="{{ $att->file_url }}" target="_blank" class="inline-flex items-center px-2.5 py-1 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded shadow-sm">
                                            Buka Dokumen ↗
                                        </a>
                                    @elseif(!empty($att->gdrive_id))
                                        <a href="{{ $att->file_url }}" target="_blank" class="inline-flex items-center px-2.5 py-1 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded shadow-sm">
                                            Buka GDrive ↗
                                        </a>
                                    @elseif(!empty($att->link))
                                        <a href="{{ $att->link }}" target="_blank" class="inline-flex items-center px-2.5 py-1 text-xs font-semibold text-white bg-sky-600 hover:bg-sky-700 rounded shadow-sm">
                                            Buka Tautan ↗
                                        </a>
                                    @elseif($att->file_url)
                                        <a href="{{ $att->file_url }}" target="_blank" class="inline-flex items-center px-2 py-1 text-xs font-medium text-amber-700 bg-amber-50 hover:bg-amber-100 rounded border border-amber-300">
                                            Cek Berkas ↗
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
</div>
