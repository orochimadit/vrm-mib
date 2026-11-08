@extends('layouts.jeramin')

@section('title', 'Surveyor Assignment — ' . ($survey->vessel?->name ?? 'Survey #' . $survey->id))

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-outer mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h5 class="title-breadcrumb mb-1 font-weight-bold">Surveyor Assignment List</h5>
        <ul class="breadcrumb bg-transparent p-0 mb-0 small text-muted">
            <li class="breadcrumb-item"><a href="{{ url('/admin/services') }}">Services</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin/surveys') }}">Vessel Risk Management</a></li>
            <li class="breadcrumb-item"><a href="javascript:history.go(-1)">Data</a></li>
            <li class="breadcrumb-item active text-dark font-weight-bold">Edit Assignment</li>
        </ul>
    </div>
    <div>
        <a href="javascript:history.go(-1)" class="btn btn-outline-secondary btn-sm">
            <i class="fa fa-arrow-left mr-1"></i> Kembali ke Tabel Data
        </a>
    </div>
</div>
<!-- /Breadcrumb -->

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<form action="{{ route('surveys.legacy.edit.update', $survey->id) }}" method="POST">
    @csrf
    <div class="row">
        <!-- Col 1: Vessel, Surveyor, Broker -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="font-weight-bold text-dark mb-3">Vessel</h5>
                    <div class="form-group mb-3">
                        <label for="vessel_id" class="font-weight-bold small text-muted">Vessel Name</label>
                        <select class="form-control" id="vessel_id" name="vessel_id">
                            @foreach ($vessels as $v)
                                <option value="{{ $v->id }}" {{ $survey->vessel_id == $v->id ? 'selected' : '' }}>
                                    {{ $v->name }} ({{ $v->ownerCompany?->name ?? 'No Company' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="table-responsive bg-light p-2 rounded">
                        <table class="table table-sm table-borderless mb-0 small">
                            <tr>
                                <td class="text-muted">Type:</td>
                                <td class="font-weight-bold">{{ $survey->vessel?->vesselType?->name ?? 'GENERAL CARGO' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Year Built:</td>
                                <td class="font-weight-bold">{{ $survey->vessel?->year_built ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">GRT:</td>
                                <td class="font-weight-bold">{{ $survey->vessel?->grt ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="font-weight-bold text-dark mb-3">Surveyor</h5>
                    <div class="form-group mb-3">
                        <label for="surveyor_id" class="font-weight-bold small text-muted">Surveyor Company</label>
                        <select class="form-control" id="surveyor_id" name="surveyor_id">
                            @foreach ($surveyors as $s)
                                <option value="{{ $s->id }}" {{ $survey->surveyor_id == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="font-weight-bold text-dark mb-3">Broker/Partner</h5>
                    <div class="form-group mb-3">
                        <label for="broker_id" class="font-weight-bold small text-muted">Broker/Partner Company</label>
                        <select class="form-control" id="broker_id" name="broker_id">
                            <option value="">:: Select Broker/Partner ::</option>
                            @foreach ($brokers as $b)
                                <option value="{{ $b->id }}" {{ $survey->broker_id == $b->id ? 'selected' : '' }}>
                                    {{ $b->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Col 2: Insurance & Co-Insurance -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="font-weight-bold text-dark mb-3">Insurance</h5>
                    <div class="form-group mb-3">
                        <label for="insurance_company_id" class="font-weight-bold small text-muted">Insurance Company</label>
                        <select class="form-control" id="insurance_company_id" name="insurance_company_id">
                            @foreach ($insuranceCompanies as $ic)
                                <option value="{{ $ic->id }}" {{ $survey->insurance_company_id == $ic->id ? 'selected' : '' }}>
                                    {{ $ic->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="insurance_product" class="font-weight-bold small text-muted">Insurance Product</label>
                        <input type="text" class="form-control" id="insurance_product" name="insurance_product" value="{{ old('insurance_product', 'WROP') }}">
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="font-weight-bold text-dark mb-3">CO - Insurance Member</h5>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small text-muted">Daftar Member</span>
                        <button type="button" class="btn btn-primary btn-xs" onclick="alert('Tambah Co-Insurance Member.')">
                            <i class="fa fa-plus mr-1"></i> Add Member
                        </button>
                    </div>
                    <div class="table-responsive bg-light p-2 rounded">
                        <p class="small text-muted mb-0">Tidak ada anggota Co-Insurance terdaftar.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Col 3: Risk Assessment Parameters -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="font-weight-bold text-dark mb-3">Risk Assessment</h5>

                    <div class="form-group mb-3">
                        <label for="condition_type" class="font-weight-bold small text-muted">Risk Treatment</label>
                        <select class="form-control" id="condition_type" name="condition_type">
                            <option value="Self Assessment" {{ $survey->condition_type === 'Self Assessment' ? 'selected' : '' }}>Self Assessment</option>
                            <option value="Photo Album" {{ $survey->condition_type === 'Photo Album' ? 'selected' : '' }}>Photo Album</option>
                            <option value="Survey" {{ $survey->condition_type === 'Survey' ? 'selected' : '' }}>Survey</option>
                            <option value="Underwriting" {{ $survey->condition_type === 'Underwriting' ? 'selected' : '' }}>Underwriting</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="due_date" class="font-weight-bold small text-muted">Due Date of Risk Assessment</label>
                        <input type="date" class="form-control" id="due_date" name="due_date" value="{{ old('due_date', $survey->due_date?->format('Y-m-d')) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="policy_period_start" class="font-weight-bold small text-muted">Policy Period Start</label>
                        <input type="date" class="form-control" id="policy_period_start" name="policy_period_start" value="{{ old('policy_period_start', $survey->policy_period_start?->format('Y-m-d')) }}">
                    </div>

                    <div class="form-group mb-4">
                        <label for="policy_period_end" class="font-weight-bold small text-muted">Policy Period End</label>
                        <input type="date" class="form-control" id="policy_period_end" name="policy_period_end" value="{{ old('policy_period_end', $survey->policy_period_end?->format('Y-m-d')) }}">
                    </div>

                    <button type="submit" class="btn btn-primary btn-block font-weight-bold py-2 shadow-sm">
                        <i class="fa fa-save mr-1"></i> Simpan Pembaruan Penugasan
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
