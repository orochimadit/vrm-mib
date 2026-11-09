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
            <a href="{{ url('/admin/insurance-products') }}">{{ $subtitle ?? 'Product Insurance' }}</a>
        </li>
        @if($company)
            <li class="breadcrumb-item">
                <span>{{ $company->name }}</span>
            </li>
        @endif
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
                <div class="col-md-12 table-responsive">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                        <div>
                            <h1 class="h3 font-weight-bold mb-1">{{ $title }}</h1>
                            @if($company)
                                <p class="text-muted mb-0">Company: <strong class="text-primary">{{ $company->name }}</strong></p>
                            @endif
                        </div>
                        <div class="mt-2 mt-md-0">
                            <a href="{{ url('/admin/insurance-products') }}" class="btn btn-outline-secondary mr-2">
                                <i class="fa fa-arrow-left mr-1"></i> Back to Companies
                            </a>
                            <a href="{{ url('/admin/insurance-products/create' . ($company ? '?company_id=' . $company->id : '')) }}" class="btn btn-primary">
                                <i class="fa fa-plus-circle mr-1"></i> Add New Product
                            </a>
                        </div>
                    </div>

                    @if(session('msg'))
                        {!! session('msg') !!}
                    @endif

                    <table class="table table-hover table-striped align-middle mt-3">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 22%;">Company</th>
                                <th style="width: 25%;">Name</th>
                                <th style="width: 20%;">Description</th>
                                <th style="width: 13%;">Risk Treatment</th>
                                <th style="width: 20%; text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td class="font-weight-bold text-dark">
                                        {{ $product->insuranceCompany->name ?? '-' }}
                                    </td>
                                    <td>
                                        <span class="font-weight-semibold">{{ $product->name }}</span>
                                        @if(!empty($product->risk_treatment_label))
                                            <br>
                                            <span class="badge badge-info mt-1">{{ $product->risk_treatment_label }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted small">{{ $product->description ?: '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary py-1 px-2 font-weight-normal">
                                            {{ $riskTreatments[$product->risk_treatment] ?? ($product->risk_treatment ?: '-') }}
                                        </span>
                                    </td>
                                    <td style="text-align: center; white-space: nowrap;">
                                        <div class="btn-group btn-action-group">
                                            <a href="{{ url('/insurance/attributes/set/' . $product->id) }}" class="btn btn-success btn-sm" title="Vessel Attributes">
                                                Vessel Attributes
                                            </a>
                                            <a href="{{ url('/surveys/items/index/' . $product->id) }}" class="btn btn-info btn-sm text-white" title="Survey Items">
                                                Survey Items
                                            </a>
                                            <a href="{{ url('/admin/insurance-products/' . $product->id . '/edit') }}" class="btn btn-primary btn-sm" title="Edit">
                                                Edit
                                            </a>
                                            <a href="{{ url('/admin/insurance-products/' . $product->id . '/delete') }}" 
                                               class="btn btn-danger btn-sm" 
                                               title="Delete"
                                               onclick="return confirm('Are you sure you want to delete this product?');">
                                                Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        No products found for this insurance company.
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
