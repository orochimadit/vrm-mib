@extends('layouts.jeramin')

@section('title', $isEdit ? 'Edit Vessel Management' : 'Vessel Management Form')

@section('content')
<!-- Breadcrumb (1:1 format legacy kapal/form.html) -->
<div class="breadcrumb-outer mb-4">
    <h5 class="title-breadcrumb mb-2 mb-md-0 font-weight-bold">
        {{ $isEdit ? 'Edit Vessel' : 'Vessel Management Form' }}
    </h5>
    <ul class="breadcrumb bg-transparent p-0 mb-0 small text-muted">
        <li class="breadcrumb-item">
            <a href="{{ url('/admin') }}"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ $selectedCompany ? route('companies.legacy.vessels', $selectedCompany->id) : route('companies.legacy.index') }}">
                Vessel Data
            </a>
        </li>
        <li class="breadcrumb-item active text-dark font-weight-bold">
            <span>{{ $isEdit ? 'Edit Vessel' : 'Add Vessel' }}</span>
        </li>
    </ul>
</div>
<!-- /Breadcrumb -->

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="col-md-12">
                    <h2 class="font-weight-bold text-dark mb-4">Vessel Management Form</h2>

                    <form action="{{ $isEdit ? route('vessels.legacy.update', $vessel->id) : route('vessels.legacy.store') }}" method="POST">
                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif

                        <div class="form-group mb-3">
                            <label for="name" class="font-weight-bold">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $vessel->name) }}" required placeholder="Nama Kapal">
                        </div>

                        <div class="form-group mb-3">
                            <label for="vessel_type_id" class="font-weight-bold">Type</label>
                            <select class="form-control" id="vessel_type_id" name="vessel_type_id">
                                <option value="">Select Vessel Type</option>
                                @foreach ($vesselTypes as $vt)
                                    <option value="{{ $vt->id }}" {{ (string) old('vessel_type_id', $vessel->vessel_type_id) === (string) $vt->id ? 'selected' : '' }}>
                                        {{ $vt->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="owner_company_id" class="font-weight-bold">Company</label>
                            <select class="form-control" id="owner_company_id" name="owner_company_id">
                                <option value="">::Select Company::</option>
                                @foreach ($companies as $comp)
                                    <option value="{{ $comp->id }}" {{ (string) old('owner_company_id', $selectedCompany?->id ?? $vessel->owner_company_id) === (string) $comp->id ? 'selected' : '' }}>
                                        {{ $comp->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="year_built" class="font-weight-bold">Year Built</label>
                            <input type="text" class="form-control" id="year_built" name="year_built" value="{{ old('year_built', $vessel->year_built) }}" placeholder="Tahun Pembuatan">
                        </div>

                        <div class="form-group mb-3">
                            <label for="tsi" class="font-weight-bold">Total Sum Insured (USD)</label>
                            <input type="text" class="form-control" id="tsi" name="tsi" value="{{ old('tsi', $vessel->tsi) }}" placeholder="Contoh: 320,512.82">
                        </div>

                        <div class="form-group mb-4">
                            <label for="grt" class="font-weight-bold">GRT</label>
                            <input type="text" class="form-control" id="grt" name="grt" value="{{ old('grt', $vessel->grt) }}" placeholder="Contoh: 655">
                        </div>

                        <button type="submit" class="btn btn-primary px-4 font-weight-bold">Save</button>
                        <a href="{{ $selectedCompany ? route('companies.legacy.vessels', $selectedCompany->id) : route('companies.legacy.index') }}" class="btn btn-outline-secondary px-4 ml-2">Cancel</a>
                    </form>
                </div>
            </div>      
        </div>
    </div>
</div>
@endsection
