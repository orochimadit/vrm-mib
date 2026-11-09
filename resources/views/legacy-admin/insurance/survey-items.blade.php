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

@if(session('msg'))
    {!! session('msg') !!}
@endif

<!-- Top Card: Insurance Product Information -->
<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title mb-0 font-weight-bold">Insurance Product</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0" style="max-width: 600px;">
                        <tr>
                            <td class="text-muted font-weight-bold" style="width: 200px;">Insurance Company</td>
                            <td class="font-weight-bold text-dark">: {{ $product->insuranceCompany->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted font-weight-bold">Insurance Product</td>
                            <td class="font-weight-bold text-dark">: {{ $product->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted font-weight-bold">Risk Treatment</td>
                            <td class="font-weight-bold text-primary">: {{ $riskTreatments[$product->risk_treatment] ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Survey Items Table Card -->
    <div class="col-md-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                    <h4 class="h5 font-weight-bold mb-0">Survey Items Checklist ({{ count($items) }} items)</h4>
                    <button type="button" class="btn btn-primary" onclick="openAddItemModal()">
                        <i class="fa fa-plus-circle mr-1"></i> Add New Survey Item
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 90px;">Ref No</th>
                                <th style="width: 220px;">Category</th>
                                <th>Item Name</th>
                                <th style="width: 140px; text-align: center;">Risk Value Expected</th>
                                <th style="width: 100px; text-align: center;">Risk Value</th>
                                <th style="width: 120px; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td class="font-weight-bold">{{ $item->ref_no }}</td>
                                    <td><span class="badge badge-light border text-dark py-1 px-2">{{ $item->category }}</span></td>
                                    <td>{{ $item->item_name }}</td>
                                    <td class="text-center">
                                        @php
                                            $val = $item->risk_value_expected ?? $riskValueExpected[$item->risk_value] ?? 'Low';
                                            $badgeClass = match(strtolower($val)) {
                                                'high' => 'badge-warning',
                                                'critical' => 'badge-danger',
                                                'medium' => 'badge-info',
                                                default => 'badge-success',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} py-1 px-2">{{ $val }}</span>
                                    </td>
                                    <td class="text-center font-weight-bold">{{ $item->risk_value }}</td>
                                    <td class="text-center" style="white-space: nowrap;">
                                        <button type="button" 
                                                class="btn btn-sm btn-info text-white mr-1"
                                                onclick="openEditItemModal({{ $item->item_id }}, '{{ addslashes($item->ref_no) }}', '{{ addslashes($item->category) }}', '{{ addslashes($item->item_name) }}', '{{ $item->risk_value }}')">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <a href="{{ url('/surveys/items/delete/' . $item->item_id) }}" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Are you sure you want to delete this item?');">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        No survey items found for this product. Click "Add New Survey Item" to create one.
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

<!-- Modal Add / Edit Survey Item -->
<div class="modal fade" id="surveyItemModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('/surveys/items/proses') }}" method="POST">
                @csrf
                <input type="hidden" name="insurance_product_id" value="{{ $product->id }}">
                <input type="hidden" name="item_id" id="item_item_id" value="">

                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold" id="itemModalTitle">Add New Survey Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="modal_ref_no" class="font-weight-bold">Ref No</label>
                        <input type="text" name="ref_no" id="modal_ref_no" class="form-control" required placeholder="e.g. 101, 202">
                    </div>

                    <div class="form-group mb-3">
                        <label for="modal_category" class="font-weight-bold">Category</label>
                        <input type="text" name="category" id="modal_category" class="form-control" required placeholder="e.g. MANNING, HULL, MACHINERY">
                    </div>

                    <div class="form-group mb-3">
                        <label for="modal_item_name" class="font-weight-bold">Item Name / Description</label>
                        <textarea name="item_name" id="modal_item_name" class="form-control" rows="3" required placeholder="Question or inspection item statement..."></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="modal_risk_value_expected" class="font-weight-bold">Risk Value Expected</label>
                        <select name="risk_value_expected" id="modal_risk_value_expected" class="form-control" required>
                            <option value="1">Low</option>
                            <option value="2">Medium</option>
                            <option value="3">High</option>
                            <option value="4">Critical</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary px-4">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openAddItemModal() {
        $('#itemModalTitle').text('Add New Survey Item');
        $('#item_item_id').val('');
        $('#modal_ref_no').val('');
        $('#modal_category').val('');
        $('#modal_item_name').val('');
        $('#modal_risk_value_expected').val('1');
        $('#surveyItemModal').modal('show');
    }

    function openEditItemModal(itemId, refNo, cat, name, riskVal) {
        $('#itemModalTitle').text('Edit Survey Item');
        $('#item_item_id').val(itemId);
        $('#modal_ref_no').val(refNo);
        $('#modal_category').val(cat);
        $('#modal_item_name').val(name);
        $('#modal_risk_value_expected').val(riskVal || '1');
        $('#surveyItemModal').modal('show');
    }
</script>
@endpush
@endsection
