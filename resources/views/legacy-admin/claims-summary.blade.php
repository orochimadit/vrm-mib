@extends('layouts.jeramin')

@section('title', 'Summary Claim Case Of ' . ($claim->vessel?->name ?? 'Vessel'))

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-outer mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h5 class="title-breadcrumb mb-1 font-weight-bold">Claims</h5>
        <ul class="breadcrumb bg-transparent p-0 mb-0 small text-muted">
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Services</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin/claims') }}">Claim Solution</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin/claims/data/' . ($claim->portfolio_consultant ?: 'all') . '/All') }}">Data</a></li>
            <li class="breadcrumb-item active text-dark font-weight-bold">Summary</li>
        </ul>
    </div>
    <div class="d-print-none">
        <button type="button" class="btn btn-outline-primary btn-sm mr-2" onclick="window.print();">
            <i class="fa fa-print mr-1"></i> Print Summary
        </button>
        <a href="{{ url('/admin/claims/data/' . ($claim->portfolio_consultant ?: 'all') . '/All') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa fa-arrow-left mr-1"></i> Kembali ke Daftar Klaim
        </a>
    </div>
</div>
<!-- /Breadcrumb -->

<div class="row">
    <div class="col-md-12">
        <!-- Main Card: Summary Claim Case -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2">
                <h2 class="title text-center text-uppercase font-weight-bold text-dark mb-0" style="letter-spacing: 0.5px;">
                    SUMMARY CLAIM CASE OF {{ $claim->vessel?->name ?? 'VESSEL' }}
                </h2>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mb-0">
                        <tbody>
                            <tr>
                                <td style="width: 30%;" class="font-weight-normal text-muted">Vessel Name</td>
                                <td class="font-weight-bold text-dark">{{ $claim->vessel?->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Ship Owner Company</td>
                                <td>{{ $claim->vessel?->ownerCompany?->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Insurance Company</td>
                                <td>{{ $claim->insuranceCompany?->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Insurance Product</td>
                                <td>{{ $claim->insuranceProduct?->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Surveyors</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Adjuster</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Co Insurances</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Brokers</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Source of Case</td>
                                <td class="text-uppercase font-weight-bold">{{ $claim->portfolio_consultant ?? 'CONSULTANT' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Accident</td>
                                <td class="text-uppercase">{{ $claim->accident ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Date Of Loss</td>
                                <td>{{ $claim->date_of_loss ? $claim->date_of_loss->format('d M Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Time Of Loss</td>
                                <td>{{ $claim->time_of_loss ? substr($claim->time_of_loss, 0, 5) : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Location</td>
                                <td>{{ $claim->location ?: 'TBA' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Estimated Claim</td>
                                <td class="font-weight-bold">{{ $claim->estimated_claim_currency ?: 'IDR' }} {{ number_format($claim->estimated_claim ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Deductible</td>
                                <td>{{ $claim->deductible ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Policy Number</td>
                                <td>{{ $claim->policy_number ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Period Of Policy Start</td>
                                <td>{{ $claim->period_of_policy_start ? $claim->period_of_policy_start->format('d M Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Period Of Policy End</td>
                                <td>{{ $claim->period_of_policy_end ? $claim->period_of_policy_end->format('d M Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">MIC BM</td>
                                <td>{{ $claim->mic_bm ?: 'AUL' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">MIC CLP</td>
                                <td>{{ $claim->mic_clp ?: 'AUL' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">TSI LOL</td>
                                <td>{{ number_format($claim->tsi_lol ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Settled Claim Amount</td>
                                <td>{{ number_format($claim->settled_claim_amount ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status</td>
                                <td>
                                    @php $st = strtolower($claim->status ?? 'ongoing'); @endphp
                                    @if ($st === 'accepted')
                                        <span class="badge badge-success px-2 py-1 font-weight-bold">Accepted</span>
                                    @elseif ($st === 'rejected')
                                        <span class="badge badge-danger px-2 py-1 font-weight-bold">Rejected</span>
                                    @else
                                        <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold" style="background-color: #ffc107 !important;">Ongoing</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status Description</td>
                                <td>{{ $claim->status_description ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Settled Date</td>
                                <td>{{ $claim->settled_date ? $claim->settled_date->format('d M y') : '01 Jan 70' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Handling Time</td>
                                <td>
                                    @if ($claim->settled_date && $claim->case_handled)
                                        {{ $claim->case_handled->diffInDays($claim->settled_date) }} Days
                                    @else
                                        0 Days
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Section: Chronological of Claim -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom-0 text-center pt-4 pb-2">
                <h3 class="font-weight-bold text-dark text-uppercase mb-0">CHRONOLOGICAL OF CLAIM</h3>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 50px;" class="text-center">No</th>
                                <th style="width: 50%;">Detail Of Event</th>
                                <th style="width: 35%;">Attachment</th>
                                <th style="width: 15%; text-align: center;">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <em>Belum ada catatan progres kronologi klaim untuk kasus ini.</em>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
