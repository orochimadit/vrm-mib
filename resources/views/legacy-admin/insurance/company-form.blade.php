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
            <a href="{{ url('/admin/insurance-companies') }}">Insurance Company</a>
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

                    <form action="{{ $isEdit ? url('/admin/insurance-companies/' . $company->id) : url('/admin/insurance-companies') }}" method="POST" class="form">
                        @csrf
                        @if($isEdit)
                            @method('PUT')
                            <input type="hidden" name="id" value="{{ $company->id }}">
                        @endif

                        <!-- Form Group: Name -->
                        <div class="form-group mb-3">
                            <label for="name" class="font-weight-bold">Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $company->name ?? '') }}" required>
                        </div>

                        <!-- Form Group: Address -->
                        <div class="form-group mb-3">
                            <label for="address" class="font-weight-bold">Address</label>
                            <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $company->address ?? '') }}" required>
                        </div>

                        <!-- Form Group: Phone -->
                        <div class="form-group mb-3">
                            <label for="phone" class="font-weight-bold">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $company->phone ?? '') }}" required>
                        </div>

                        <!-- Form Group: Email -->
                        <div class="form-group mb-3">
                            <label for="email" class="font-weight-bold">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $company->email ?? '') }}">
                        </div>

                        <!-- Form Group: Website -->
                        <div class="form-group mb-4">
                            <label for="website" class="font-weight-bold">Website</label>
                            <input type="text" name="website" id="website" class="form-control" value="{{ old('website', $company->website ?? '') }}">
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary px-4">Save</button>
                            <a href="{{ url('/admin/insurance-companies') }}" class="btn btn-light px-4 ml-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
