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
            <a href="{{ url('/admin/insurance-products/company/' . $product->insurance_company_id) }}">{{ $product->insuranceCompany->name ?? 'Company' }}</a>
        </li>
        <li class="breadcrumb-item">
            <span>{{ $title }}</span>
        </li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h2 class="h4 font-weight-bold mb-1">{{ $title }}</h2>
                        <p class="text-muted mb-0">Product: <strong class="text-primary">{{ $product->name }}</strong> ({{ $product->insuranceCompany->name ?? '-' }})</p>
                    </div>
                    <a href="{{ url('/admin/insurance-products/company/' . $product->insurance_company_id) }}" class="btn btn-outline-secondary">
                        <i class="fa fa-arrow-left mr-1"></i> Back to Products
                    </a>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-hover table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 10%;">No</th>
                                <th style="width: 50%;">Survey Item / Inspection Topic</th>
                                <th style="width: 25%;">Category</th>
                                <th style="width: 15%; text-align: center;">Mandatory</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $idx => $item)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td class="font-weight-semibold">{{ $item->item ?? $item->name ?? '-' }}</td>
                                    <td><span class="badge badge-light border">{{ $item->category ?? 'General' }}</span></td>
                                    <td class="text-center"><span class="badge badge-success">Yes</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        No custom survey items defined for this product. Default survey items will apply.
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
