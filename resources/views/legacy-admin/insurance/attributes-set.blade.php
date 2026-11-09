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

                <div class="alert alert-info py-2">
                    <i class="fa fa-info-circle mr-1"></i> Vessel Attributes configuration for product <strong>{{ $product->name }}</strong>.
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 35%;">Attribute Name</th>
                                <th style="width: 25%;">Field Type</th>
                                <th style="width: 20%; text-align: center;">Required</th>
                                <th style="width: 20%; text-align: center;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-weight-bold">Vessel Name</td>
                                <td>Text Input</td>
                                <td class="text-center"><span class="badge badge-success">Yes</span></td>
                                <td class="text-center"><span class="badge badge-primary">Enabled</span></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Vessel Type</td>
                                <td>Dropdown Select</td>
                                <td class="text-center"><span class="badge badge-success">Yes</span></td>
                                <td class="text-center"><span class="badge badge-primary">Enabled</span></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Gross Tonnage (GRT)</td>
                                <td>Numeric</td>
                                <td class="text-center"><span class="badge badge-success">Yes</span></td>
                                <td class="text-center"><span class="badge badge-primary">Enabled</span></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Year Built</td>
                                <td>Year (4 digits)</td>
                                <td class="text-center"><span class="badge badge-secondary">Optional</span></td>
                                <td class="text-center"><span class="badge badge-primary">Enabled</span></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Total Sum Insured (TSI)</td>
                                <td>Currency (USD)</td>
                                <td class="text-center"><span class="badge badge-success">Yes</span></td>
                                <td class="text-center"><span class="badge badge-primary">Enabled</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
