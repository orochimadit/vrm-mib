@extends('layouts.jeramin')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-outer mb-4">
    <h5 class="title-breadcrumb mb-2 mb-md-0">{{ $subtitle ?? 'Product Insurance' }}</h5>
    <ul class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ url('/admin/dashboard') }}"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item">
            <span>{{ $subtitle ?? 'Product Insurance' }}</span>
        </li>
    </ul>
</div>
<!-- End of Breadcrumb -->

<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="row">
                    @forelse ($companies as $key => $comp)
                        <div class="col-md-4 mb-3">
                            <a href="{{ url('/admin/insurance-products/company/' . $comp->id) }}" class="text-decoration-none">
                                <div class="card border-0 shadow-sm bg-primary h-100 transition-all hover-shadow" style="transition: transform 0.2s, box-shadow 0.2s; cursor: pointer; border-radius: 6px;">
                                    <div class="card-body py-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="icon-wrap mr-3">
                                                <i class="fa fa-building text-white fa-3x" style="opacity: 0.9;"></i>
                                            </div>
                                            <div class="d-flex flex-column text-right text-break">
                                                <h5 class="mb-0 text-white font-weight-bold" style="font-size: 1.05rem; letter-spacing: 0.3px; line-height: 1.3;">
                                                    {{ $comp->name }}
                                                </h5>
                                                <small class="text-white-50 mt-1 font-weight-bold">
                                                    {{ $comp->products()->count() }} Products &rarr;
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 text-muted">
                            <i class="fa fa-building fa-3x mb-3 text-muted"></i>
                            <p>No insurance companies found.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
