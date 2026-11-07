<div class="space-y-4 text-sm">
    <div class="rounded-lg bg-gray-50 dark:bg-gray-800 p-3 border border-gray-200 dark:border-gray-700">
        <div class="font-semibold text-gray-900 dark:text-gray-100">
            Temuan: <span class="font-normal">{{ $record->explanation }}</span>
        </div>
        <div class="mt-1 text-xs text-gray-600 dark:text-gray-400">
            <strong>Catatan Saat Ini:</strong> {{ $record->rectification_owner ?: '— Belum ada respon perbaikan dari pemilik kapal —' }}
        </div>
    </div>

    <div>
        <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-2 flex items-center gap-1.5">
            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Histori Kronologis Catatan Perbaikan & Status
        </h4>

        @php
            $logs = $record->logRectifications()->with('admin')->get();
        @endphp

        @if($logs->isEmpty())
            <div class="p-4 text-center text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-dashed border-gray-300 dark:border-gray-700">
                Belum ada riwayat aktivitas atau perubahan catatan pada temuan ini.
            </div>
        @else
            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left text-xs">
                    <thead class="bg-gray-100 dark:bg-gray-800 font-semibold uppercase text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-3 py-2">No</th>
                            <th class="px-3 py-2">Waktu Perubahan</th>
                            <th class="px-3 py-2">Kategori</th>
                            <th class="px-3 py-2">Isi Catatan / Status</th>
                            <th class="px-3 py-2">Eksekutor / User</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                        @foreach($logs as $index => $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-3 py-2.5 font-medium">{{ $index + 1 }}</td>
                                <td class="px-3 py-2.5 text-gray-600 dark:text-gray-400">
                                    {{ $log->datetime ? $log->datetime->format('d-M-Y H:i:s') : '—' }}
                                </td>
                                <td class="px-3 py-2.5">
                                    @if($log->category === 'rectification_owner')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                            Revisi Owner
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300">
                                            Status Remark
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-2.5 font-medium text-gray-900 dark:text-gray-100">
                                    {{ $log->value }}
                                </td>
                                <td class="px-3 py-2.5 text-gray-600 dark:text-gray-400">
                                    {{ $log->admin?->name ?: ($log->admin?->username ?: 'Sistem/User #'.$log->id_admin) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
