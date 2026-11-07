@extends('layouts.jeramin')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-outer mb-4 d-flex justify-content-between align-items-center d-print-none">
    <h5 class="title-breadcrumb mb-0 font-weight-bold">Survey Result</h5>
    <ul class="breadcrumb bg-transparent p-0 mb-0">
        <li class="breadcrumb-item"><a href="{{ url('/admin/services') }}">Services</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/admin/surveys') }}">Vessel Risk Management</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/admin/surveys') }}">Data</a></li>
        <li class="breadcrumb-item active text-muted">Result</li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4 p-md-5">

                <!-- Header Actions (Print & Back) -->
                <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
                    <a href="{{ url('/admin/surveys') }}" class="btn btn-outline-secondary btn-sm">
                        &larr; Kembali ke Daftar Survei
                    </a>
                    <button onclick="window.print()" class="btn btn-success btn-sm font-weight-bold">
                        <i class="fa fa-print mr-1"></i> Cetak / Simpan PDF
                    </button>
                </div>

                <!-- Centered Document Title -->
                <div class="text-center mb-5">
                    <h5 class="font-weight-bold text-uppercase mb-1" style="letter-spacing: 1px;">
                        {{ strtoupper($survey->condition_type ?: 'SELF-ASSESSMENT') }} FORM
                    </h5>
                    <h5 class="font-weight-bold text-uppercase mb-1" style="letter-spacing: 1px;">
                        PT MIB - VESSEL RISK MANAGEMENT
                    </h5>
                    <h5 class="font-weight-bold text-uppercase mb-1 text-primary" style="letter-spacing: 1px;">
                        {{ $survey->insuranceProduct?->product_name ?: 'WASP' }}
                    </h5>
                    <h6 class="font-weight-bold text-uppercase text-muted" style="letter-spacing: 1px;">
                        ASSESSMENT RESULT
                    </h6>
                </div>

                <!-- Section: GENERAL INFORMATION AND VESSEL'S DETAIL -->
                <h6 class="font-weight-bold text-uppercase mb-2 text-dark" style="font-size: 0.85rem;">
                    GENERAL INFORMATION AND VESSEL'S DETAIL
                </h6>

                <div class="table-responsive mb-4">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr class="table-secondary font-weight-bold">
                                <td colspan="3" class="pl-3">Vessel General Information</td>
                            </tr>
                            <tr>
                                <td width="5%" class="text-center">1</td>
                                <td width="35%" class="text-muted">Vessel Name</td>
                                <td width="60%" class="font-weight-bold">{{ $survey->vessel?->name ?: 'JALASHVA MAJESTIC' }}</td>
                            </tr>
                            <tr>
                                <td class="text-center">2</td>
                                <td class="text-muted">Type</td>
                                <td>{{ $survey->vessel?->vesselType?->name ?: ($survey->vessel?->type ?: 'BULK CARRIER') }}</td>
                            </tr>
                            <tr>
                                <td class="text-center">3</td>
                                <td class="text-muted">Year Built</td>
                                <td>{{ $survey->vessel?->year_built ?: '1991' }}</td>
                            </tr>
                            <tr>
                                <td class="text-center">4</td>
                                <td class="text-muted">Total Sum Insured</td>
                                <td>{{ $survey->vessel?->total_sum_insured ? 'USD ' . number_format($survey->vessel->total_sum_insured) : 'USD' }}</td>
                            </tr>
                            <tr>
                                <td class="text-center">5</td>
                                <td class="text-muted">GRT</td>
                                <td>{{ $survey->vessel?->grt ?: '250' }}</td>
                            </tr>

                            <tr class="table-secondary font-weight-bold">
                                <td colspan="3" class="pl-3">Vessel Identification</td>
                            </tr>
                            <tr>
                                <td class="text-center">1</td>
                                <td class="text-muted">IMO Number</td>
                                <td>{{ $survey->vessel?->imo_number ?: '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-center">2</td>
                                <td class="text-muted">Company Name</td>
                                <td>{{ $survey->vessel?->ownerCompany?->name ?: '—' }}</td>
                            </tr>

                            <tr class="table-light">
                                <td colspan="2" class="font-weight-bold pl-3">Main Engine</td>
                                <td>Number Of Engine : 1</td>
                            </tr>
                            <tr class="table-light">
                                <td colspan="2" class="font-weight-bold pl-3">Auxiliary Engine</td>
                                <td>Number Of Engine : 1</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Section: Person In Charge -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr class="table-secondary">
                                <th width="5%" class="text-center">No</th>
                                <th width="35%">Person In Charge</th>
                                <th width="35%">Name</th>
                                <th width="25%">Phone</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">1</td>
                                <td class="font-weight-semibold">Master</td>
                                <td>Capt. Bambang Suryo</td>
                                <td>+62811223344</td>
                            </tr>
                            <tr>
                                <td class="text-center">2</td>
                                <td class="font-weight-semibold">Chief Officer</td>
                                <td>Ahmad Fauzi</td>
                                <td>+62812345678</td>
                            </tr>
                            <tr>
                                <td class="text-center">3</td>
                                <td class="font-weight-semibold">Chief Engineer</td>
                                <td>Ir. Hendro Wijaya</td>
                                <td>+62813987654</td>
                            </tr>
                            <tr>
                                <td class="text-center">4</td>
                                <td class="font-weight-semibold">KKM</td>
                                <td>Dedi Supriyadi</td>
                                <td>+62819876543</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Section: Self Assessment Item Table -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr class="table-secondary font-weight-bold">
                                <th width="6%" class="text-center">No Reff</th>
                                <th width="38%">Assessment Item</th>
                                <th width="26%">Response</th>
                                <th width="15%">
                                    Attachment 
                                    <a href="{{ route('surveys.report', $survey) }}" class="small font-weight-normal text-primary d-print-none">(Download All)</a>
                                </th>
                                <th width="15%" class="text-center">Insurance Review</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $idx => $item)
                                @php
                                    $resp = $responses->get($item->item_id);
                                    $hasAttachment = $resp?->attachments?->isNotEmpty() ?? false;
                                @endphp
                                <tr>
                                    <td class="text-center font-weight-bold text-muted">{{ $item->ref_no ?: ($idx + 1) }}</td>
                                    <td>{{ $item->item_name }}</td>
                                    <td>
                                        {{ $resp?->response ?: '—' }}
                                    </td>
                                    <td>
                                        @if($hasAttachment)
                                            <a href="{{ route('document.attachment', ['filename' => $resp->attachments->first()->file ?? '']) }}" target="_blank" class="badge badge-success px-2 py-1">
                                                <i class="fa fa-file mr-1"></i> 1 Dokumen
                                            </a>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center flex-wrap" style="gap: 2px;">
                                            <span class="badge badge-warning px-1.5 py-1 text-dark" style="font-size: 10px; cursor: pointer;">Remark Log</span>
                                            <span class="badge badge-info px-1.5 py-1" style="font-size: 10px; cursor: pointer;">Reply Log</span>
                                            <button type="button" class="btn btn-xs btn-success py-0 px-1 font-weight-bold" style="font-size: 10px;" title="Approve">✓</button>
                                            <button type="button" class="btn btn-xs btn-danger py-0 px-1 font-weight-bold" style="font-size: 10px;" title="Decline">✕</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada item assessment.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
