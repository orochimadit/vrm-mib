@extends('layouts.jeramin')

@section('title', 'Claim Solution — ' . ucfirst($type) . ' (' . $year . ')')

@section('content')
<!-- DataTables CSS -->
<link rel="stylesheet" href="{{ asset('assets/themes/jeramin/vendors/data-table/DataTables-1.10.18/css/dataTables.bootstrap4.min.css') }}">

<!-- Breadcrumb -->
<div class="breadcrumb-outer mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h5 class="title-breadcrumb mb-1 font-weight-bold">Claim Solution</h5>
        <ul class="breadcrumb bg-transparent p-0 mb-0 small text-muted">
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Services</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin/claims') }}">Claim Solution</a></li>
            <li class="breadcrumb-item active text-dark font-weight-bold">Data ({{ ucfirst($type) }} - {{ $year }})</li>
        </ul>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ url('/admin/claims') }}" class="btn btn-outline-secondary btn-sm mr-2">
            <i class="fa fa-arrow-left mr-1"></i> Kembali ke Pilihan Portfolio
        </a>
        <a href="{{ url('/admin/claims/create') }}" class="btn btn-success btn-sm font-weight-bold">
            <i class="fa fa-plus mr-1"></i> Add New Claim Case
        </a>
    </div>
</div>
<!-- /Breadcrumb -->

<!-- Quick Filter Tabs (UX Improvement) -->
<div class="mb-3 d-flex flex-wrap align-items-center">
    <span class="mr-2 text-muted small font-weight-bold">Tahun Handled:</span>
    <a href="{{ url('/admin/claims/data/' . $type . '/All') }}" class="btn btn-sm mr-2 mb-1 {{ strtolower($year) === 'all' ? 'btn-primary font-weight-bold' : 'btn-light border' }}">
        All
    </a>
    @foreach ($availableYears as $y)
    <a href="{{ url('/admin/claims/data/' . $type . '/' . $y) }}" class="btn btn-sm mr-2 mb-1 {{ (string)$year === (string)$y ? 'btn-primary font-weight-bold' : 'btn-light border' }}">
        {{ $y }}
    </a>
    @endforeach

    <div class="ml-auto d-flex align-items-center">
        <span class="mr-2 text-muted small font-weight-bold">Kategori:</span>
        <div class="btn-group btn-group-sm">
            <a href="{{ url('/admin/claims/data/consultant/' . $year) }}" class="btn {{ $type === 'consultant' ? 'btn-dark font-weight-bold' : 'btn-light border' }}">Consultant</a>
            <a href="{{ url('/admin/claims/data/portofolio/' . $year) }}" class="btn {{ $type === 'portofolio' ? 'btn-dark font-weight-bold' : 'btn-light border' }}">Portofolio</a>
            <a href="{{ url('/admin/claims/data/all/' . $year) }}" class="btn {{ $type === 'all' ? 'btn-dark font-weight-bold' : 'btn-light border' }}">All</a>
        </div>
    </div>
</div>
<!-- /Quick Filter Tabs -->

<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h3 class="mb-3 font-weight-bold text-dark">Claim Solution</h3>

                <div class="table-responsive">
                    <table class="table table-hover table-striped datatable table-claim" id="table-claims" style="width: 100%;">
                        <thead class="thead-light" style="white-space: nowrap;">
                            <tr>
                                <th style="width: 40px;">No</th>
                                <th>Vessel Name</th>
                                <th>Insurance</th>
                                <th>Surveyor</th>
                                <th>Adjuster</th>
                                <th>Case Handled</th>
                                <th>Date Of Loss</th>
                                <th>Location</th>
                                <th>Casualty</th>
                                <th>Accident</th>
                                <th>Status</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($claims as $index => $row)
                            <tr>
                                <td class="align-middle text-center">{{ $index + 1 }}</td>
                                <td class="align-middle font-weight-bold text-dark" style="min-width: 180px;">
                                    {{ $row->vessel?->name ?? 'N/A' }}
                                    @if ($row->vessel?->ownerCompany)
                                        <div class="small text-muted font-weight-normal">- {{ $row->vessel->ownerCompany->name }}</div>
                                    @endif
                                </td>
                                <td class="align-middle" style="min-width: 180px;">
                                    {{ $row->insuranceCompany?->name ?? '-' }}
                                    @if ($row->insuranceProduct)
                                        <div class="small text-muted font-weight-normal">({{ $row->insuranceProduct->name }})</div>
                                    @endif
                                </td>
                                <td class="align-middle small" style="min-width: 140px;">
                                    {{-- Surveyor Placeholder / Eager loaded --}}
                                    -
                                </td>
                                <td class="align-middle small" style="min-width: 140px;">
                                    {{-- Adjuster Placeholder / Eager loaded --}}
                                    -
                                </td>
                                <td class="align-middle text-nowrap" style="min-width: 110px;">
                                    {{ $row->case_handled ? $row->case_handled->format('Y-m-d') : '-' }}
                                </td>
                                <td class="align-middle text-nowrap" style="min-width: 110px;">
                                    {{ $row->date_of_loss ? $row->date_of_loss->format('Y-m-d') : '-' }}
                                </td>
                                <td class="align-middle" style="min-width: 120px;">
                                    {{ $row->location ?: 'TBA' }}
                                </td>
                                <td class="align-middle font-weight-bold" style="min-width: 100px;">
                                    {{ $row->casualtyRef?->casualty ?: '-' }}
                                </td>
                                <td class="align-middle small" style="min-width: 160px;">
                                    {{ $row->accident ?: '-' }}
                                </td>
                                <td class="align-middle text-center" style="min-width: 90px;">
                                    @php
                                        $st = strtolower($row->status ?? 'ongoing');
                                    @endphp
                                    @if ($st === 'accepted')
                                        <span class="badge badge-success px-2 py-1 font-weight-bold">Accepted</span>
                                    @elseif ($st === 'rejected')
                                        <span class="badge badge-danger px-2 py-1 font-weight-bold">Rejected</span>
                                    @else
                                        <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold" style="background-color: #ffc107 !important;">Ongoing</span>
                                    @endif
                                </td>
                                <td class="align-middle text-nowrap text-center" style="min-width: 220px;">
                                    <a href="{{ url('/admin/claims/' . $row->id) }}" class="btn btn-info btn-sm mr-1 shadow-sm font-weight-bold">
                                        Detail
                                    </a>
                                    <a href="{{ url('/admin/claims/' . $row->id . '/summary') }}" class="btn btn-warning btn-sm mr-1 shadow-sm font-weight-bold text-dark" style="background-color: #f59e0b !important; border-color: #d97706 !important; color: #111 !important;">
                                        Summary
                                    </a>
                                    <a href="{{ url('/admin/claims/' . $row->id . '/edit') }}" class="btn btn-primary btn-sm mr-1 shadow-sm font-weight-bold">
                                        Edit
                                    </a>
                                    <form action="{{ url('/admin/claims/' . $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus klaim ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm shadow-sm font-weight-bold">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" class="text-center py-4 text-muted">
                                    Tidak ada data kasus klaim untuk kategori <strong>{{ ucfirst($type) }}</strong> pada tahun <strong>{{ $year }}</strong>.
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

@push('scripts')
<script src="{{ asset('assets/themes/jeramin/vendors/data-table/DataTables-1.10.18/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/themes/jeramin/vendors/data-table/DataTables-1.10.18/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('#table-claims').DataTable({
        pageLength: 10,
        responsive: true,
        scrollX: true,
        order: [[5, 'desc']],
        language: {
            search: "Search:",
            searchPlaceholder: "Cari data klaim..."
        }
    });
});
</script>
@endpush
