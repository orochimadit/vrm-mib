@extends('layouts.jeramin')

@section('title', $isEdit ? 'Form Edit Company' : 'Form Add New Company')

@section('content')
<!-- Breadcrumb (1:1 format legacy kapal/company/form.html) -->
<div class="breadcrumb-outer mb-4">
    <h5 class="title-breadcrumb mb-2 mb-md-0 font-weight-bold">
        {{ $isEdit ? 'Form Edit Company' : 'Form Add New Company' }}
    </h5>
    <ul class="breadcrumb bg-transparent p-0 mb-0 small text-muted">
        <li class="breadcrumb-item">
            <a href="{{ url('/admin') }}"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('companies.legacy.index') }}">Vessel Company Data</a>
        </li>
        <li class="breadcrumb-item active text-dark font-weight-bold">
            <span>{{ $isEdit ? 'Form Edit Company' : 'Form Add New Company' }}</span>
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
                    <h2 class="font-weight-bold text-dark mb-4">
                        {{ $isEdit ? 'Form Edit Company' : 'Form Add New Company' }}
                    </h2>

                    <form action="{{ $isEdit ? route('companies.legacy.update', $company->id) : route('companies.legacy.store') }}" method="POST">
                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif

                        <div class="form-group mb-3">
                            <label for="name" class="font-weight-bold">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $company->name) }}" required placeholder="Nama Perusahaan">
                        </div>

                        <div class="form-group mb-3">
                            <label for="contact_info" class="font-weight-bold">Contact Information</label>
                            <input type="text" class="form-control" id="contact_info" name="contact_info" value="{{ old('contact_info', $company->contact_info) }}" placeholder="Email / Telepon">
                        </div>

                        <div class="form-group mb-4">
                            <label for="pic" class="font-weight-bold">Company PIC</label>
                            <input type="text" class="form-control" id="pic" name="pic" value="{{ old('pic', $company->pic) }}" placeholder="Nama PIC Perusahaan">
                        </div>

                        <button type="submit" class="btn btn-primary px-4 font-weight-bold">Save</button>
                        <a href="{{ route('companies.legacy.index') }}" class="btn btn-outline-secondary px-4 ml-2">Cancel</a>
                    </form>
                </div>
            </div>      
        </div>
    </div>
</div>
@endsection
