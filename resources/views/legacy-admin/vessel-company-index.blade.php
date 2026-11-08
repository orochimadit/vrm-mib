@extends('layouts.jeramin')

@section('title', 'Vessel Company Data')

@section('content')
<!-- DataTables CSS -->
<link rel="stylesheet" href="{{ asset('assets/themes/jeramin/vendors/data-table/DataTables-1.10.18/css/dataTables.bootstrap4.min.css') }}">

<!-- Breadcrumb (1:1 format legacy kapal/company.html) -->
<div class="breadcrumb-outer mb-4">
    <h5 class="title-breadcrumb mb-2 mb-md-0 font-weight-bold">Vessel Company Data</h5>
    <ul class="breadcrumb bg-transparent p-0 mb-0 small text-muted">
        <li class="breadcrumb-item">
            <a href="{{ url('/admin') }}"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active text-dark font-weight-bold">
            <span>Vessel Company Data</span>
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
                    <h2 class="font-weight-bold text-dark mb-3">Vessel Company Data</h2>
                    
                    <a href="{{ route('companies.legacy.create') }}" class="btn btn-primary font-weight-bold mb-3 px-3 shadow-sm">
                        Add Company
                    </a>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped datatable table-company" id="table-company" style="width: 100%;">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Company Name</th>
                                    <th>Contact Info</th>
                                    <th>PIC</th>
                                    <th style="width: 250px; text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($companies as $index => $row)
                                <tr>
                                    <td class="align-middle text-center">{{ $index + 1 }}</td>
                                    <td class="align-middle font-weight-bold text-dark">{{ $row->name }}</td>
                                    <td class="align-middle text-muted">{{ $row->contact_info ?? '-' }}</td>
                                    <td class="align-middle font-weight-bold text-uppercase">{{ $row->pic ?? '-' }}</td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('companies.legacy.vessels', $row->id) }}" class="btn btn-info btn-sm font-weight-bold mr-1">
                                                Vessel
                                            </a>
                                            <a href="{{ route('companies.legacy.edit', $row->id) }}" class="btn btn-warning btn-sm font-weight-bold text-white mr-1">
                                                Edit
                                            </a>
                                            <form action="{{ route('companies.legacy.delete', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this company?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm font-weight-bold">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
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
    $('#table-company').DataTable({
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
});
</script>
@endpush
