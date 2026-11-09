@extends('layouts.jeramin')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-outer mb-4">
    <h5 class="title-breadcrumb mb-2 mb-md-0">{{ $title }}</h5>
    <ul class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ url('/admin/dashboard') }}"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ url('/admin/insurance-products') }}">Product Insurance</a>
        </li>
        <li class="breadcrumb-item">
            <span>{{ $title }}</span>
        </li>
    </ul>
</div>
<!-- End of Breadcrumb -->

<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="col-md-12">
                    <h2 class="h4 font-weight-bold mb-4">{{ $title }}</h2>

                    @if(isset($errors) && $errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 pl-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('msg'))
                        {!! session('msg') !!}
                    @endif

                    <form action="{{ $isEdit ? url('/admin/insurance-products/' . $product->id) : url('/admin/insurance-products') }}" method="POST" class="form">
                        @csrf
                        @if($isEdit)
                            @method('PUT')
                            <input type="hidden" name="id" value="{{ $product->id }}">
                        @endif

                        <!-- Form Group: Company ID -->
                        <div class="form-group mb-3">
                            <label for="company_id" class="font-weight-bold">Company ID</label>
                            <select name="company_id" id="company_id" class="form-control" required>
                                <option value="">:: Select Company ::</option>
                                @foreach ($companies as $comp)
                                    <option value="{{ $comp->id }}" {{ (old('company_id', $product->insurance_company_id ?? $selectedCompanyId) == $comp->id) ? 'selected' : '' }}>
                                        {{ $comp->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Form Group: Name -->
                        <div class="form-group mb-3">
                            <label for="name" class="font-weight-bold">Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
                        </div>

                        <!-- Form Group: Description -->
                        <div class="form-group mb-3">
                            <label for="description" class="font-weight-bold">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $product->description ?? '') }}</textarea>
                        </div>

                        <!-- Form Group: Risk Treatment -->
                        <div class="form-group mb-3">
                            <label for="risk_treatment" class="font-weight-bold">Risk Treatment</label>
                            <select name="risk_treatment" id="risk_treatment" class="form-control">
                                <option value="">:: Select Risk Treatment ::</option>
                                @foreach ($riskTreatments as $key => $label)
                                    <option value="{{ $key }}" {{ (old('risk_treatment', $product->risk_treatment ?? '') == $key) ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Form Group: Risk Treatment Label -->
                        <div class="form-group mb-4">
                            <label for="risk_treatment_label" class="font-weight-bold">Risk Treatment Label</label>
                            <input type="text" name="risk_treatment_label" id="risk_treatment_label" class="form-control" value="{{ old('risk_treatment_label', $product->risk_treatment_label ?? '') }}">
                        </div>

                        <!-- Action Buttons -->
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary px-4">Save</button>
                            <a href="{{ $selectedCompanyId ? url('/admin/insurance-products/company/' . $selectedCompanyId) : url('/admin/insurance-products') }}" class="btn btn-light px-4 ml-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
