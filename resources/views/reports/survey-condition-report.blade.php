<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Condition Survey Report — {{ $survey->vessel?->name ?: 'Vessel' }} ({{ $survey->id }})</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: #ffffff !important;
                color: #000000 !important;
                font-size: 11pt;
            }
            .page-break {
                page-break-before: always;
            }
            .report-card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
                padding: 0 !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 antialiased font-sans">

    {{-- Top Action Bar (Screen Only) --}}
    <div class="no-print sticky top-0 z-50 bg-slate-900 text-white shadow-md px-6 py-3.5 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <a href="{{ url('/admin/surveys') }}" class="inline-flex items-center px-3 py-1.5 rounded bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-gray-200 border border-slate-700">
                &larr; Kembali ke Daftar Survei
            </a>
            <span class="text-xs text-slate-400">|</span>
            <span class="text-xs font-bold text-slate-300">
                Survei #{{ $survey->id }} &bull; {{ $survey->vessel?->name }}
            </span>
        </div>

        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="inline-flex items-center px-4 py-1.5 rounded bg-emerald-600 hover:bg-emerald-500 text-xs font-bold text-white shadow-sm transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Laporan / Simpan PDF
            </button>
        </div>
    </div>

    {{-- Main Document Container --}}
    <div class="max-w-5xl mx-auto my-8 p-8 sm:p-12 bg-white shadow-xl rounded-xl border border-gray-200 report-card">

        {{-- Header Section with Authentic Logo --}}
        <div class="flex items-start justify-between border-b-2 border-slate-800 pb-5 mb-6">
            <div class="flex items-center space-x-4">
                <img src="{{ asset('images/legacy/logo-vrm.webp') }}" alt="VRM Logo" class="h-16 w-auto object-contain">
                <div>
                    <h1 class="text-xl font-extrabold uppercase tracking-wide text-slate-900">
                        PT MIB — Vessel Risk Management
                    </h1>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-600">
                        Condition Survey &amp; Marine Risk Assessment Form
                    </p>
                    <p class="text-[11px] text-gray-500">
                        Ref No: VRM/{{ date('Y') }}/{{ str_pad($survey->id, 4, '0', STR_PAD_LEFT) }} &bull; Tanggal Laporan: {{ date('d F Y') }}
                    </p>
                </div>
            </div>

            <div class="text-right">
                <span class="inline-block px-3 py-1 rounded text-xs font-extrabold uppercase {{ $survey->status === 'Completed' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-rose-100 text-rose-800 border border-rose-300' }}">
                    Status: {{ $survey->status ?: 'Outstanding' }}
                </span>
                <p class="text-[11px] text-gray-500 mt-1">
                    Risk Rating: <strong class="text-slate-800">{{ $survey->risk_rating ?: 'Satisfactory' }}</strong>
                </p>
            </div>
        </div>

        {{-- Vessel Title --}}
        <div class="text-center my-4 py-2 bg-slate-50 border-y border-slate-200">
            <h2 class="text-lg font-black uppercase text-slate-900 tracking-wider">
                {{ $survey->vessel?->name ?: 'VESSEL NAME' }}
            </h2>
            <p class="text-xs text-slate-600">
                Owner: <strong>{{ $survey->vessel?->ownerCompany?->name ?: '—' }}</strong> &bull; IMO: {{ $survey->vessel?->imo_number ?: '—' }} &bull; Flag / Type: {{ $survey->vessel?->vesselType?->name ?: 'General Cargo' }}
            </p>
        </div>

        {{-- SURVEYOR & POLICY INFORMATION TABLE --}}
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 mt-6 mb-2">
            1. General Survey Information
        </h3>
        <table class="w-full text-xs border border-gray-300 mb-6 divide-y divide-gray-200">
            <tbody class="divide-y divide-gray-200">
                <tr class="bg-gray-50/50">
                    <td class="w-1/4 px-3 py-2 font-semibold text-gray-600">Surveyor Name</td>
                    <td class="w-1/4 px-3 py-2 font-bold text-gray-900">{{ $survey->surveyor_name ?: ($survey->surveyor?->name ?: '—') }}</td>
                    <td class="w-1/4 px-3 py-2 font-semibold text-gray-600">Surveyor Company</td>
                    <td class="w-1/4 px-3 py-2 font-bold text-gray-900">{{ $survey->surveyor?->company_name ?: ($survey->surveyor?->name ?: '—') }}</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 font-semibold text-gray-600">Date of Survey</td>
                    <td class="px-3 py-2 font-bold text-gray-900">{{ $survey->date_of_survey ? $survey->date_of_survey->format('d-M-Y') : '—' }}</td>
                    <td class="px-3 py-2 font-semibold text-gray-600">Survey Location</td>
                    <td class="px-3 py-2 font-bold text-gray-900">{{ $survey->location ?: '—' }}</td>
                </tr>
                <tr class="bg-gray-50/50">
                    <td class="px-3 py-2 font-semibold text-gray-600">Insurance Company</td>
                    <td class="px-3 py-2 font-bold text-gray-900">{{ $survey->insuranceCompany?->name ?: '—' }}</td>
                    <td class="px-3 py-2 font-semibold text-gray-600">Insurance Broker / Partner</td>
                    <td class="px-3 py-2 font-bold text-gray-900">{{ $survey->broker?->name ?: '—' }}</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 font-semibold text-gray-600">Policy Period</td>
                    <td class="px-3 py-2 text-gray-900">
                        {{ $survey->policy_period_start ? $survey->policy_period_start->format('d-M-Y') : '—' }} s/d {{ $survey->policy_period_end ? $survey->policy_period_end->format('d-M-Y') : '—' }}
                    </td>
                    <td class="px-3 py-2 font-semibold text-gray-600">Due Date</td>
                    <td class="px-3 py-2 font-bold text-rose-600">{{ $survey->due_date ? $survey->due_date->format('d-M-Y') : '—' }}</td>
                </tr>
            </tbody>
        </table>

        {{-- RESULT SUMMARY TABLE (SUB-ITEMS) --}}
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 mt-6 mb-2">
            2. Result &amp; Grading Evaluation
        </h3>
        <table class="w-full text-xs border border-gray-300 mb-6 text-left">
            <thead class="bg-slate-800 text-white">
                <tr>
                    <th class="px-3 py-2 text-center w-12">No</th>
                    <th class="px-3 py-2">Name of Sub-Item / Inspection Category</th>
                    <th class="px-3 py-2 text-center w-24">Score</th>
                    <th class="px-3 py-2 text-center w-24">Minimum</th>
                    <th class="px-3 py-2 text-center w-20">Grade</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($categoryResults as $index => $cat)
                    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                        <td class="px-3 py-2 text-center font-medium">{{ $index + 1 }}</td>
                        <td class="px-3 py-2 font-semibold text-slate-800">{{ $cat->category }}</td>
                        <td class="px-3 py-2 text-center font-bold text-slate-900">{{ $cat->total_score }}</td>
                        <td class="px-3 py-2 text-center text-gray-600">{{ $cat->minimum }}</td>
                        <td class="px-3 py-2 text-center font-black {{ $cat->grade === 'A' ? 'text-emerald-600' : ($cat->grade === 'B' ? 'text-amber-600' : 'text-rose-600') }}">
                            Grade {{ $cat->grade }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-3 py-4 text-center text-gray-500 italic">
                            Belum ada rekapitulasi checklist penilaian untuk survei ini.
                        </td>
                    </tr>
                @endforelse
                <tr class="bg-slate-100 font-extrabold border-t-2 border-slate-400">
                    <td colspan="2" class="px-3 py-2.5 text-right uppercase tracking-wider">Total Evaluasi:</td>
                    <td class="px-3 py-2.5 text-center text-slate-900 font-black text-sm">{{ $grandTotalScore }}</td>
                    <td class="px-3 py-2.5 text-center text-gray-700 text-sm">{{ $grandTotalMinimum }}</td>
                    <td class="px-3 py-2.5 text-center font-black text-sm {{ $overallGrade === 'A' ? 'text-emerald-700 bg-emerald-100' : ($overallGrade === 'B' ? 'text-amber-700 bg-amber-100' : 'text-rose-700 bg-rose-100') }}">
                        Grade {{ $overallGrade }}
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- RESUME & CONCERN FROM SURVEYOR --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-lg">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800 mb-1.5">
                    Resume Vessel Condition From Surveyor
                </h4>
                <p class="text-xs text-gray-700 leading-relaxed italic">
                    {{ $survey->resume_from_surveyor ?: ($survey->summary ?: 'Kondisi fisik kapal secara umum terawat sesuai standar keselamatan maritim.') }}
                </p>
            </div>

            <div class="p-3.5 bg-amber-50/60 border border-amber-200 rounded-lg">
                <h4 class="text-xs font-bold uppercase tracking-wider text-amber-900 mb-1.5">
                    Concern From Surveyor / Rekomendasi Kritis
                </h4>
                <p class="text-xs text-amber-950 leading-relaxed italic">
                    {{ $survey->concern_from_surveyor ?: 'Perhatikan sertifikasi berkala alat keselamatan dan jadwal berkala docking mesin utama.' }}
                </p>
            </div>
        </div>

        {{-- VESSEL GENERAL INFORMATION & PARTICULARS --}}
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 mt-6 mb-2">
            3. General Information and Vessel's Detail
        </h3>
        <table class="w-full text-xs border border-gray-300 mb-6 divide-y divide-gray-200">
            <tbody class="divide-y divide-gray-200">
                <tr>
                    <td class="w-1/3 px-3 py-2 font-semibold text-gray-600">Vessel Name</td>
                    <td class="px-3 py-2 font-bold text-gray-900">{{ $survey->vessel?->name ?: '—' }}</td>
                </tr>
                <tr class="bg-gray-50/50">
                    <td class="px-3 py-2 font-semibold text-gray-600">Type of Vessel</td>
                    <td class="px-3 py-2 font-bold text-gray-900">{{ $survey->vessel?->vesselType?->name ?: 'General Cargo' }}</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 font-semibold text-gray-600">Year Built</td>
                    <td class="px-3 py-2 font-bold text-gray-900">{{ $survey->vessel?->year_built ?: '—' }}</td>
                </tr>
                <tr class="bg-gray-50/50">
                    <td class="px-3 py-2 font-semibold text-gray-600">Gross Tonnage (GRT)</td>
                    <td class="px-3 py-2 font-bold text-gray-900">{{ $survey->vessel?->grt ? number_format($survey->vessel->grt) . ' GRT' : '—' }}</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 font-semibold text-gray-600">Total Sum Insured (TSI)</td>
                    <td class="px-3 py-2 font-bold text-emerald-700">
                        USD {{ $survey->vessel?->total_sum_insured ? number_format($survey->vessel->total_sum_insured, 2) : '—' }}
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- SURVEY ITEMS DETAILS TABLE (1:1 LEGACY RESULT.PHP) --}}
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 mt-6 mb-2">
            4. Survey Items Details &amp; Checklists
        </h3>
        <table class="w-full text-xs border border-gray-300 mb-6 text-left">
            <thead class="bg-slate-800 text-white font-bold">
                <tr>
                    <th class="px-3 py-2 w-16 text-center">Ref</th>
                    <th class="px-3 py-2">Item Name / Inspection Question</th>
                    <th class="px-3 py-2 w-28 text-center">Item Score</th>
                    <th class="px-3 py-2 w-24 text-center">Status</th>
                    <th class="px-3 py-2 w-1/3">Remark From Surveyor</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($groupedResponses as $category => $responses)
                    <tr class="bg-slate-100 font-extrabold border-y border-slate-300">
                        <td colspan="5" class="px-3 py-1.5 text-slate-800 uppercase tracking-wider text-[11px]">
                            &bull; {{ $category }} ({{ $responses->count() }} Items)
                        </td>
                    </tr>
                    @foreach($responses as $resp)
                        @php
                            $isDefect = in_array((string)$resp->response, ['1', 'Poor', 'DEFECT']);
                        @endphp
                        <tr class="{{ $isDefect ? 'bg-rose-50/70' : 'bg-white hover:bg-gray-50' }}">
                            <td class="px-3 py-2 text-center font-mono text-[11px] font-semibold text-gray-600">
                                {{ $resp->item?->ref_no ?: '—' }}
                            </td>
                            <td class="px-3 py-2 text-gray-900 font-medium">
                                {{ $resp->item?->item_name ?: 'Inspection Item' }}
                                @if($resp->attachments->isNotEmpty())
                                    <span class="inline-flex items-center ml-1.5 px-1.5 py-0.5 rounded text-[10px] font-bold bg-sky-100 text-sky-800">
                                        📎 {{ $resp->attachments->count() }} Berkas
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 py-2 text-center font-bold">
                                {{ match((string)$resp->response) {
                                    '1' => '1 - Poor',
                                    '2' => '2 - Fair',
                                    '3' => '3 - Satisfactory',
                                    '4' => '4 - Excellent',
                                    default => $resp->response ?: '—'
                                } }}
                            </td>
                            <td class="px-3 py-2 text-center">
                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-black {{ $isDefect ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800' }}">
                                    {{ $isDefect ? 'DEFECT' : 'GOOD' }}
                                </span>
                            </td>
                            <td class="px-3 py-2 text-gray-700 italic text-[11px]">
                                {{ $resp->remarks_from_surveyor ?: '—' }}
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="5" class="px-3 py-4 text-center text-gray-500 italic">
                            Belum ada rincian item checklist penilaian untuk survei ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- DEFICIENCIES TABLE --}}
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 mt-6 mb-2">
            5. List of Deficiencies &amp; Rectification Status
        </h3>
        <table class="w-full text-xs border border-gray-300 mb-6 text-left">
            <thead class="bg-gray-100 text-gray-700 font-bold">
                <tr>
                    <th class="px-3 py-2 w-10 text-center">No</th>
                    <th class="px-3 py-2 w-28">Kategori</th>
                    <th class="px-3 py-2">Temuan Kerusakan</th>
                    <th class="px-3 py-2">Rekomendasi Surveyor</th>
                    <th class="px-3 py-2">Tindakan Perbaikan Pemilik</th>
                    <th class="px-3 py-2 text-center w-24">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($survey->defects as $dIdx => $defect)
                    <tr class="{{ $dIdx % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                        <td class="px-3 py-2 text-center font-medium">{{ $dIdx + 1 }}</td>
                        <td class="px-3 py-2 font-semibold text-gray-700">{{ $defect->item_category ?: 'Umum' }}</td>
                        <td class="px-3 py-2 text-gray-900">{{ $defect->explanation }}</td>
                        <td class="px-3 py-2 text-gray-700">{{ $defect->recommendation_rectification ?: '—' }}</td>
                        <td class="px-3 py-2 text-gray-700 italic">{{ $defect->rectification_owner ?: '— Belum Ada —' }}</td>
                        <td class="px-3 py-2 text-center">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold {{ $defect->status === 'Approve' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ $defect->status ?: 'Outstanding' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-3 py-4 text-center text-gray-500 italic">
                            Tidak ada daftar defisiensi (Zero Deficiency).
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- SUMMARY & SIGNATURE BLOCK --}}
        <div class="mt-12 pt-6 border-t border-gray-300">
            <div class="grid grid-cols-3 gap-6 text-center text-xs">
                <div>
                    <p class="font-bold text-gray-700">Surveyor Pelaksana</p>
                    <div class="h-20 flex items-end justify-center">
                        <span class="border-b border-gray-400 w-44 inline-block font-semibold pb-1">
                            {{ $survey->surveyor_name ?: ($survey->surveyor?->name ?: 'Surveyor MIB') }}
                        </span>
                    </div>
                    <p class="text-[10px] text-gray-500 mt-1">Marine Surveyor</p>
                </div>

                <div>
                    <p class="font-bold text-gray-700">Perwakilan Pemilik Kapal</p>
                    <div class="h-20 flex items-end justify-center">
                        <span class="border-b border-gray-400 w-44 inline-block font-semibold pb-1">
                            Master / Superintendent
                        </span>
                    </div>
                    <p class="text-[10px] text-gray-500 mt-1">{{ $survey->vessel?->ownerCompany?->name ?: 'Ship Owner' }}</p>
                </div>

                <div>
                    <p class="font-bold text-gray-700">PT MIB Underwriting</p>
                    <div class="h-20 flex items-end justify-center">
                        <span class="border-b border-gray-400 w-44 inline-block font-semibold pb-1">
                            Authorized Risk Officer
                        </span>
                    </div>
                    <p class="text-[10px] text-gray-500 mt-1">Vessel Risk Management Division</p>
                </div>
            </div>
        </div>

        {{-- Footer Note --}}
        <div class="mt-8 pt-4 border-t border-gray-200 text-center text-[10px] text-gray-400">
            Dokumen ini dicetak secara digital melalui sistem Vessel Risk Management (VRM) Modern — PT MIB.
        </div>

    </div>

</body>
</html>
