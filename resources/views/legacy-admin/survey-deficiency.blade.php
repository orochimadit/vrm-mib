@extends('layouts.jeramin')

@section('title', 'List of Deficiency — ' . ($survey->vessel?->name ?? 'Survey #' . $survey->id))

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-outer mb-4 d-flex justify-content-between align-items-center d-print-none">
    <div>
        <h5 class="title-breadcrumb mb-1 font-weight-bold">List of Deficiency</h5>
        <ul class="breadcrumb bg-transparent p-0 mb-0 small text-muted">
            <li class="breadcrumb-item"><a href="{{ url('/admin/services') }}">Services</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin/surveys') }}">Vessel Risk Management</a></li>
            <li class="breadcrumb-item"><a href="javascript:history.go(-1)">Data</a></li>
            <li class="breadcrumb-item active text-dark font-weight-bold">List of Deficiency</li>
        </ul>
    </div>
    <div class="d-flex align-items-center">
        <a href="javascript:history.go(-1)" class="btn btn-outline-secondary btn-sm mr-2">
            <i class="fa fa-arrow-left mr-1"></i> Kembali ke Tabel Data
        </a>
        <button type="button" class="btn btn-info btn-sm" onclick="window.print()">
            <i class="fa fa-print mr-1"></i> Cetak Dokumen
        </button>
    </div>
</div>
<!-- /Breadcrumb -->

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show d-print-none" role="alert">
    <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h3 class="mb-4 font-weight-bold text-dark">List of Deficiency &amp; Recommendation</h3>

                <!-- Info Table -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered mb-0" style="max-width: 800px;">
                        <tbody>
                            <tr>
                                <td class="font-weight-bold bg-light" style="width: 35%;">Date Of Survey</td>
                                <td>{{ $survey->date_of_survey ? \Carbon\Carbon::parse($survey->date_of_survey)->format('d-M-Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold bg-light">Surveyor Name</td>
                                <td>{{ $survey->surveyor_name ?? ($survey->surveyor?->name ?? 'PT. MARINE INSURANCE BROKER') }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold bg-light">Location Of Survey</td>
                                <td>{{ $survey->location ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold bg-light">Vessel Name</td>
                                <td class="font-weight-bold text-primary">{{ $survey->vessel?->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold bg-light">The Assured</td>
                                <td>{{ $survey->vessel?->ownerCompany?->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold bg-light">Date of Issue List Deficiency and Recommendation</td>
                                <td>
                                    {{ $survey->due_date ? \Carbon\Carbon::parse($survey->due_date)->format('d-M-Y') : date('d-M-Y') }}
                                    <button type="button" class="btn btn-info btn-xs pull-right d-print-none ml-2" onclick="alert('Ubah tanggal issue deficiency.')">
                                        Change Date Of Issue
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Action Button Group -->
                <div class="button-group mb-3 d-flex align-items-center justify-content-between d-print-none">
                    <div class="btn-group">
                        <a href="{{ url('/admin/surveys/' . $survey->id . '/deficiency?status=0') }}" class="btn {{ request('status', '0') == '0' ? 'btn-danger font-weight-bold' : 'btn-outline-danger' }} px-3">
                            <i class="fa fa-clock-o mr-1"></i> PENDING DEFECT
                        </a>
                        <a href="{{ url('/admin/surveys/' . $survey->id . '/deficiency?status=2') }}" class="btn {{ request('status') == '2' ? 'btn-success font-weight-bold' : 'btn-outline-success' }} px-3">
                            <i class="fa fa-check mr-1"></i> CLOSE DEFECT
                        </a>
                    </div>
                    <button type="button" class="btn btn-primary font-weight-bold" onclick="alert('Form modal tambah temuan defect baru.')">
                        <i class="fa fa-plus-circle mr-1"></i> ADD DEFECT
                    </button>
                </div>

                <!-- Deficiency Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover table-deficiency" style="width: 100%;">
                        <thead class="thead-dark small" style="white-space: nowrap;">
                            <tr>
                                <th style="width: 35px;">No</th>
                                <th>No Reff</th>
                                <th>Item Name</th>
                                <th>Deficiency Explanation</th>
                                <th class="d-print-none">Picture</th>
                                <th style="min-width: 200px;">Surveyor Recommendation</th>
                                <th style="min-width: 180px;">Rectification From Owner</th>
                                <th>Due Date (days)</th>
                                <th>Due Date</th>
                                <th>Days Left</th>
                                <th>Review</th>
                                <th class="d-print-none" style="text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($defects as $index => $defect)
                            <tr>
                                <td class="text-center align-middle">{{ $index + 1 }}</td>
                                <td class="align-middle font-weight-bold">{{ $defect->ref_no ?? ('REF-' . str_pad($defect->id, 3, '0', STR_PAD_LEFT)) }}</td>
                                <td class="align-middle font-weight-bold">{{ $defect->item_name ?? 'Item Temuan' }}</td>
                                <td class="align-middle">{{ $defect->explanation ?? $defect->description ?? '-' }}</td>
                                <td class="align-middle text-center d-print-none">
                                    <button type="button" class="btn btn-outline-info btn-xs" onclick="alert('Tampilkan lampiran foto surveyor.')">
                                        <i class="fa fa-picture-o mr-1"></i> View Picture
                                    </button>
                                </td>
                                <td class="align-middle">{{ $defect->recommendation_rectification ?? '-' }}</td>
                                <td class="align-middle">{{ $defect->rectification_owner ?? '-' }}</td>
                                <td class="align-middle text-center">{{ $defect->rectification_time_days ?? '30' }}</td>
                                <td class="align-middle text-center">{{ $defect->created_at ? \Carbon\Carbon::parse($defect->created_at)->addDays($defect->rectification_time_days ?? 30)->format('d-M-Y') : '-' }}</td>
                                <td class="align-middle text-center font-weight-bold">
                                    <span class="badge {{ in_array(strtolower($defect->status ?? ''), ['approve', 'closed', 'completed']) ? 'badge-success' : 'badge-danger' }}">
                                        {{ $defect->status ?? 'Outstanding' }}
                                    </span>
                                </td>
                                <td class="align-middle">{{ $defect->remark_defect ?? '-' }}</td>
                                <td class="align-middle text-center d-print-none">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-warning btn-xs text-white" title="Edit Defect">Edit</button>
                                        <button type="button" class="btn btn-success btn-xs" title="Close Defect">Close</button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" class="text-center py-5 text-muted">
                                    <i class="fa fa-check-circle fa-2x text-success mb-2 d-block"></i>
                                    Tidak ada daftar temuan deficiency (defect) pada status ini.
                                </td>
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
