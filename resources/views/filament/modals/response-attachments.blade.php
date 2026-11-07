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
                            <th class="px-3 py-2">No</th>
                            <th class="px-3 py-2">Judul Dokumen</th>
                            <th class="px-3 py-2">Tipe Berkas</th>
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
                                <td class="px-3 py-2.5 text-gray-600 dark:text-gray-400 truncate max-w-xs">
                                    {{ $att->file ?: ($att->link ?: '—') }}
                                </td>
                                <td class="px-3 py-2.5 text-right">
                                    @if($att->file_url)
                                        <a href="{{ $att->file_url }}" target="_blank" class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-white bg-primary-600 hover:bg-primary-700 rounded shadow-sm">
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
</div>
