@extends('layouts.jeramin')

@section('title', 'Services — Vessel Risk Management')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 75vh;">
    <div class="col-md-8 col-lg-7">
        <div class="card border-0 shadow-sm text-center p-4 p-md-5" style="border-radius: 12px;">
            <div class="card-body">
                <!-- Centered VRM Logo -->
                <div class="mb-4">
                    <img src="{{ asset('assets/themes/public/images/logo-vrm.webp') }}" alt="Vessel Risk Management" style="max-height: 64px;" class="img-fluid mb-2">
                </div>

                <!-- Two Big Action Buttons -->
                <div class="d-flex flex-column flex-sm-row justify-content-center align-items-center gap-3 my-4">
                    <a href="{{ url('/admin/surveys') }}" class="btn btn-primary btn-lg font-weight-bold px-4 py-3 mr-sm-3 mb-3 mb-sm-0 shadow-sm" style="min-width: 240px; border-radius: 8px;">
                        <i class="fa fa-ship mr-2"></i> Vessel Risk Management
                    </a>
                    <a href="{{ url('/admin/claims') }}" class="btn btn-primary btn-lg font-weight-bold px-4 py-3 shadow-sm" style="min-width: 240px; border-radius: 8px;">
                        <i class="fa fa-balance-scale mr-2"></i> Claim Solution
                    </a>
                </div>

                <!-- Copyright Footer Text -->
                <span class="d-block text-center text-muted small mt-4 pt-3 border-top">
                    copyright &copy; {{ date('Y') }} Vessel Risk Management
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
