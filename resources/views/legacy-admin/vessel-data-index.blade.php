@extends('layouts.jeramin')

@section('title', 'Vessel Data' . ($company ? ' — ' . $company->name : ''))

@section('content')
<!-- DataTables CSS -->
<link rel="stylesheet" href="{{ asset('assets/themes/jeramin/vendors/data-table/DataTables-1.10.18/css/dataTables.bootstrap4.min.css') }}">

<!-- Breadcrumb (1:1 format legacy kapal/index/8.html) -->
<div class="breadcrumb-outer mb-4">
    <h5 class="title-breadcrumb mb-2 mb-md-0 font-weight-bold">Vessel Data</h5>
    <ul class="breadcrumb bg-transparent p-0 mb-0 small text-muted">
        <li class="breadcrumb-item">
            <a href="{{ url('/admin') }}"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('companies.legacy.index') }}">Vessel Company</a>
        </li>
        <li class="breadcrumb-item active text-dark font-weight-bold">
            <span>Vessel Data</span>
        </li>
    </ul>
</div>
<!-- /Breadcrumb -->

@if (session('msg'))
    {!! session('msg') !!}
@endif

<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="font-weight-bold text-dark mb-0">Vessel Data</h2>
                        @if ($company)
                            <span class="badge badge-info p-2 font-weight-bold" style="font-size: 0.9rem;">
                                <i class="fa fa-building mr-1"></i> {{ $company->name }}
                            </span>
                        @endif
                    </div>

                    <a href="{{ route('vessels.legacy.create', ['company_id' => $company?->id]) }}" class="btn btn-primary font-weight-bold mb-3 px-3 shadow-sm">
                        Add Vessel
                    </a>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped datatable table-vessel" id="table-vessel" style="width: 100%;">
                            <thead class="thead-light">
                                <tr>
                                    <th>Vessel Name</th>
                                    <th>Type</th>
                                    <th>GRT</th>
                                    <th>TSI</th>
                                    <th>Company Name</th>
                                    <th style="width: 160px; text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($vessels as $vessel)
                                <tr>
                                    <td class="align-middle font-weight-bold text-dark">{{ $vessel->name }}</td>
                                    <td class="align-middle text-muted">{{ $vessel->vesselType?->name ?? ($vessel->type_name_legacy ?? 'GENERAL CARGO') }}</td>
                                    <td class="align-middle">{{ number_format((float) $vessel->grt, 0) }}</td>
                                    <td class="align-middle">{{ number_format((float) $vessel->tsi, 2) }}</td>
                                    <td class="align-middle font-weight-bold">{{ $vessel->ownerCompany?->name ?? '-' }}</td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('vessels.legacy.edit', $vessel->id) }}" class="btn btn-warning btn-sm font-weight-bold text-white mr-1">
                                                Edit
                                            </a>
                                            <form action="{{ route('vessels.legacy.delete', $vessel->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this vessel?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm font-weight-bold">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        Tidak ada data kapal untuk perusahaan ini.
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
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/themes/jeramin/vendors/data-table/DataTables-1.10.18/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/themes/jeramin/vendors/data-table/DataTables-1.10.18/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
$(document).ready(function() {
    if ($('#table-vessel tbody tr').length > 1 || !$('#table-vessel tbody tr td[colspan]').length) {
        $('#table-vessel').DataTable({
            "pageLength": 25,
            "ordering": true,
            "language": {
                "search": "search:",
                "paginate": {
                    "next": "Next",
                    "previous": "Previous"
                }
            }
        });
    }
});
</script>
@endpush
