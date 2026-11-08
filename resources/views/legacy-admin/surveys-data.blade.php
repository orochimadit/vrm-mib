@extends('layouts.jeramin')

@section('title', 'Vessel Risk Management — ' . ($company ? $company->name : 'All Portofolio') . ' (' . $year . ')')

@section('content')
<!-- DataTables CSS -->
<link rel="stylesheet" href="{{ asset('assets/themes/jeramin/vendors/data-table/DataTables-1.10.18/css/dataTables.bootstrap4.min.css') }}">

<!-- Breadcrumb -->
<div class="breadcrumb-outer mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h5 class="title-breadcrumb mb-1 font-weight-bold">Vessel Risk Management</h5>
        <ul class="breadcrumb bg-transparent p-0 mb-0 small text-muted">
            <li class="breadcrumb-item"><a href="{{ url('/admin/services') }}">Services</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin/surveys') }}">Vessel Risk Management</a></li>
            <li class="breadcrumb-item active text-dark font-weight-bold">Data</li>
        </ul>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ url('/admin/surveys') }}" class="btn btn-outline-secondary btn-sm mr-2">
            <i class="fa fa-arrow-left mr-1"></i> Kembali ke Pilihan Asuransi
        </a>
        <a href="{{ url('/admin/surveys/create') }}" class="btn btn-success btn-sm font-weight-bold">
            <i class="fa fa-plus-circle mr-1"></i> Add New Assignment
        </a>
    </div>
</div>
<!-- /Breadcrumb -->

<!-- Quick Filter Years -->
<div class="mb-3 d-flex flex-wrap align-items-center">
    <span class="mr-2 text-muted small font-weight-bold">Periode Polis:</span>
    <a href="{{ url('/admin/surveys/data/' . $companyId . '/ALL') }}" class="btn btn-sm mr-2 mb-1 {{ strtoupper($year) === 'ALL' ? 'btn-primary font-weight-bold' : 'btn-light border' }}">
        ALL
    </a>
    @foreach ($availableYears as $y)
    <a href="{{ url('/admin/surveys/data/' . $companyId . '/' . $y) }}" class="btn btn-sm mr-2 mb-1 {{ (string)$year === (string)$y ? 'btn-primary font-weight-bold' : 'btn-light border' }}">
        {{ $y }}
    </a>
    @endforeach

    @if ($company)
    <div class="ml-auto d-flex align-items-center">
        <span class="badge badge-info p-2 font-weight-bold" style="font-size: 0.85rem;">
            <i class="fa fa-building mr-1"></i> {{ $company->name }}
        </span>
    </div>
    @endif
</div>
<!-- /Quick Filter Years -->

<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="mb-0 font-weight-bold text-dark">
                        Vessel Risk Management - {{ $year }}
                    </h3>
                </div>

                <!-- 4 Tabs Treatment Filter (1:1 format legacy Jeramin data.php) -->
                @php
                    $tabList = [
                        'underwriting' => ['label' => 'Underwriting', 'key' => 'Underwriting'],
                        'self_assessment' => ['label' => 'Self Assessment', 'key' => 'Self Assessment'],
                        'photo_album' => ['label' => 'Photo Album', 'key' => 'Photo Album'],
                        'survey' => ['label' => 'Survey', 'key' => 'Survey'],
                    ];
                    $activeKey = strtolower(str_replace(' ', '_', $activeTab));
                @endphp
                <ul class="nav nav-tabs mb-4" id="treatmentTab" role="tablist">
                    @foreach ($tabList as $tabId => $tabConfig)
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold {{ $activeKey === $tabId ? 'active text-primary' : 'text-muted' }}" 
                           id="tab-btn-{{ $tabId }}" 
                           data-toggle="tab" 
                           href="#{{ $tabId }}" 
                           role="tab">
                            {{ $tabConfig['label'] }}
                            <span class="badge {{ count($groupedSurveys[$tabConfig['key']] ?? []) > 0 ? 'badge-primary' : 'badge-light text-muted' }} ml-1">
                                {{ count($groupedSurveys[$tabConfig['key']] ?? []) }}
                            </span>
                        </a>
                    </li>
                    @endforeach
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="treatmentTabContent">
                    @foreach ($tabList as $tabId => $tabConfig)
                    @php
                        $tabSurveys = $groupedSurveys[$tabConfig['key']] ?? [];
                    @endphp
                    <div class="tab-pane fade {{ $activeKey === $tabId ? 'show active' : '' }}" id="{{ $tabId }}" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped datatable table-vrm" id="table-{{ $tabId }}" style="width: 100%;">
                                <thead class="thead-light" style="white-space: nowrap;">
                                    <tr>
                                        <th style="width: 35px;" class="text-center">No</th>
                                        <th>Vessel Name</th>
                                        <th>Insurance</th>
                                        <th>Surveyor Name</th>
                                        <th>Location of Survey</th>
                                        <th>Policy Period Start</th>
                                        <th>Policy Period End</th>
                                        <th>Due Date</th>
                                        <th>Risk Treatment</th>
                                        <th>Status</th>
                                        <th style="text-align: center; min-width: 280px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($tabSurveys as $index => $row)
                                    <tr>
                                        <td class="align-middle text-center">{{ $index + 1 }}</td>
                                        <td class="align-middle font-weight-bold text-dark" style="min-width: 180px;">
                                            {{ $row->vessel?->name ?? 'N/A' }}
                                            @if ($row->vessel?->ownerCompany)
                                                <div class="small text-muted font-weight-normal">{{ $row->vessel->ownerCompany->name }}</div>
                                            @endif
                                        </td>
                                        <td class="align-middle" style="min-width: 180px;">
                                            <div class="font-weight-bold text-uppercase">{{ $row->insuranceCompany?->name ?? '-' }}</div>
                                            <div class="small text-muted font-weight-normal">(WROP)</div>
                                        </td>
                                        <td class="align-middle font-weight-normal" style="min-width: 160px;">
                                            {{ $row->surveyor_name ?? ($row->surveyor?->name ?? 'PT. MARINE INSURANCE BROKER') }}
                                        </td>
                                        <td class="align-middle font-weight-normal" style="min-width: 140px;">
                                            {{ $row->location ?? '-' }}
                                        </td>
                                        <td class="align-middle text-center font-weight-normal" style="white-space: nowrap;">
                                            {{ $row->policy_period_start ? \Carbon\Carbon::parse($row->policy_period_start)->format('Y-m-d') : '-' }}
                                        </td>
                                        <td class="align-middle text-center font-weight-normal" style="white-space: nowrap;">
                                            {{ $row->policy_period_end ? \Carbon\Carbon::parse($row->policy_period_end)->format('Y-m-d') : '-' }}
                                        </td>
                                        <td class="align-middle text-center font-weight-normal" style="white-space: nowrap;">
                                            {{ $row->due_date ? \Carbon\Carbon::parse($row->due_date)->format('Y-m-d') : '-' }}
                                        </td>
                                        <td class="align-middle font-weight-bold text-secondary" style="white-space: nowrap;">
                                            {{ $row->condition_type ?? $tabConfig['label'] }}
                                        </td>
                                        <td class="align-middle text-center">
                                            @php
                                                $st = strtolower($row->status ?? 'outstanding');
                                            @endphp
                                            @if (in_array($st, ['completed', 'selesai', 'approved']))
                                                <span class="badge badge-success px-2 py-1 font-weight-bold">Completed</span>
                                            @elseif (in_array($st, ['terminated']))
                                                <span class="badge badge-dark px-2 py-1 font-weight-bold">Terminated</span>
                                            @else
                                                <span class="badge badge-danger px-2 py-1 font-weight-bold">Outstanding</span>
                                            @endif

                                            @if ($row->policy_period_end && \Carbon\Carbon::parse($row->policy_period_end)->isPast())
                                                <div class="mt-1">
                                                    <span class="badge badge-warning text-dark font-weight-bold px-2 py-0" style="font-size: 0.7rem;">Expired</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center" style="white-space: nowrap;">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('surveys.legacy.condition', $row->id) }}" class="btn btn-info btn-sm font-weight-bold mr-1" title="Condition Survey Form">
                                                    Condition
                                                </a>
                                                <a href="{{ route('surveys.legacy.form', $row->id) }}" class="btn btn-primary btn-sm font-weight-bold mr-1" title="Survey Checklist Form">
                                                    Survey
                                                </a>
                                                <a href="{{ route('surveys.legacy.result', $row->id) }}" class="btn btn-success btn-sm font-weight-bold mr-1" title="Result Summary">
                                                    Result
                                                </a>
                                                <a href="{{ route('surveys.legacy.deficiency', $row->id) }}" class="btn btn-danger btn-sm font-weight-bold mr-1" title="List Of Deficiency">
                                                    List Of Deficiency
                                                </a>
                                                <a href="{{ route('surveys.legacy.edit', $row->id) }}" class="btn btn-warning text-white btn-sm font-weight-bold mr-1" title="Edit Assignment">
                                                    Edit
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm font-weight-bold mr-1" onclick="alert('Fitur hapus dinonaktifkan untuk menjaga integritas data demo.')" title="Delete">
                                                    Delete
                                                </button>
                                                <button type="button" class="btn btn-dark btn-sm font-weight-bold" onclick="alert('Status terminated.')" title="Terminated">
                                                    Terminated
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-5 text-muted">
                                            <i class="fa fa-info-circle fa-2x mb-3 text-secondary d-block"></i>
                                            Tidak ada data survei untuk kategori <strong>{{ $tabConfig['label'] }}</strong> pada periode tahun <strong>{{ $year }}</strong>.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/themes/jeramin/vendors/data-table/DataTables-1.10.18/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/themes/jeramin/vendors/data-table/DataTables-1.10.18/js/dataTables.bootstrap4.min.js') }}"></script>
<style>
    .table-vrm td, .table-vrm th {
        font-size: 0.85rem;
        vertical-align: middle !important;
    }
    .nav-tabs .nav-link {
        font-size: 0.95rem;
        border-top: none;
        border-left: none;
        border-right: none;
        border-bottom: 3px solid transparent;
        padding: 0.6rem 1.2rem;
    }
    .nav-tabs .nav-link.active {
        border-bottom: 3px solid #007bff;
        background-color: transparent;
    }
</style>
<script>
$(document).ready(function() {
    $('.table-vrm').each(function() {
        if ($(this).find('tbody tr').length > 1 || !$(this).find('tbody tr td[colspan]').length) {
            $(this).DataTable({
                "pageLength": 25,
                "ordering": true,
                "responsive": true,
                "language": {
                    "search": "Search:",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    }
                }
            });
        }
    });

    // Re-adjust columns when switching tabs
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
    });
});
</script>
@endpush
