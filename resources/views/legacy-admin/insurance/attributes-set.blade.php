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

    <!-- Section 1: General Particulars -->
    <div class="col-md-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 font-weight-bold">General Particulars</h5>
                <div>
                    <button type="button" class="btn btn-sm btn-primary" onclick="openAddModal('General Particulars', 'vessel_particulars')">
                        <i class="fa fa-plus mr-1"></i> Add New
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 80px;">No</th>
                                <th>Label</th>
                                <th style="width: 140px; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $num = 1; @endphp
                            {{-- Base built-in particulars --}}
                            @foreach($baseParticulars as $bp)
                                <tr>
                                    <td>{{ $num++ }}</td>
                                    <td class="font-weight-semibold">{{ $bp['label'] }}</td>
                                    <td class="text-center text-muted small">-</td>
                                </tr>
                            @endforeach

                            {{-- Custom particulars from database --}}
                            @foreach($customParticulars as $cp)
                                <tr>
                                    <td>{{ $num++ }}</td>
                                    <td class="font-weight-semibold">{{ $cp->label }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-primary px-2" onclick="openEditModal({{ $cp->attribute_id }}, '{{ addslashes($cp->label) }}', '{{ $cp->category_attr }}', '{{ $cp->category_attr_type }}')">
                                            Edit
                                        </button>
                                        <a href="{{ url('/insurance/attributes/delete/' . $cp->attribute_id) }}" 
                                           class="btn btn-sm btn-danger px-2"
                                           onclick="return confirm('Are you sure you want to delete this attribute?');">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Machinery Particulars Header -->
    <div class="col-md-12">
        <div class="card border-0 shadow-sm mb-4 bg-light">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title mb-0 font-weight-bold text-dark">
                    <i class="fa fa-cogs mr-2 text-primary"></i> Machinery Particulars
                </h5>
            </div>
        </div>
    </div>

    <!-- Section 3: Main Engine -->
    <div class="col-md-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 font-weight-bold">Main Engine</h5>
                <div>
                    <button type="button" class="btn btn-sm btn-primary" onclick="openAddModal('Main Engine', 'main_engine')">
                        <i class="fa fa-plus mr-1"></i> Add New
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 80px;">No</th>
                                <th>Label</th>
                                <th style="width: 140px; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mainEngine as $idx => $me)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td class="font-weight-semibold">{{ $me->label }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-primary px-2" onclick="openEditModal({{ $me->attribute_id }}, '{{ addslashes($me->label) }}', '{{ $me->category_attr }}', '{{ $me->category_attr_type }}')">
                                            Edit
                                        </button>
                                        <a href="{{ url('/insurance/attributes/delete/' . $me->attribute_id) }}" 
                                           class="btn btn-sm btn-danger px-2"
                                           onclick="return confirm('Are you sure you want to delete this attribute?');">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-3 text-muted">No attributes found for Main Engine.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 4: Auxiliary Engine -->
    <div class="col-md-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 font-weight-bold">Auxiliary Engine</h5>
                <div>
                    <button type="button" class="btn btn-sm btn-primary" onclick="openAddModal('Auxiliary Engine', 'auxiliary_engine')">
                        <i class="fa fa-plus mr-1"></i> Add New
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 80px;">No</th>
                                <th>Label</th>
                                <th style="width: 140px; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($auxEngine as $idx => $ae)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td class="font-weight-semibold">{{ $ae->label }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-primary px-2" onclick="openEditModal({{ $ae->attribute_id }}, '{{ addslashes($ae->label) }}', '{{ $ae->category_attr }}', '{{ $ae->category_attr_type }}')">
                                            Edit
                                        </button>
                                        <a href="{{ url('/insurance/attributes/delete/' . $ae->attribute_id) }}" 
                                           class="btn btn-sm btn-danger px-2"
                                           onclick="return confirm('Are you sure you want to delete this attribute?');">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-3 text-muted">No attributes found for Auxiliary Engine.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 5: Additional Machinery Spaces -->
    <div class="col-md-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 font-weight-bold">Additional Machinery Spaces</h5>
                <div>
                    <button type="button" class="btn btn-sm btn-primary" onclick="openAddModal('Additional Machinery Spaces', 'additional_machinery_spaces')">
                        <i class="fa fa-plus mr-1"></i> Add New
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 80px;">No</th>
                                <th>Label</th>
                                <th style="width: 140px; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($addMachinery as $idx => $am)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td class="font-weight-semibold">{{ $am->label }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-primary px-2" onclick="openEditModal({{ $am->attribute_id }}, '{{ addslashes($am->label) }}', '{{ $am->category_attr }}', '{{ $am->category_attr_type }}')">
                                            Edit
                                        </button>
                                        <a href="{{ url('/insurance/attributes/delete/' . $am->attribute_id) }}" 
                                           class="btn btn-sm btn-danger px-2"
                                           onclick="return confirm('Are you sure you want to delete this attribute?');">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-3 text-muted">No attributes found for Additional Machinery Spaces.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 6: Person In Charge (PIC) -->
    <div class="col-md-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 font-weight-bold">List of Person In Charge</h5>
                <div>
                    <button type="button" class="btn btn-sm btn-primary" onclick="openAddPicModal()">
                        <i class="fa fa-plus mr-1"></i> Add New
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 80px;">No</th>
                                <th>Position</th>
                                <th style="width: 140px; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($picList as $idx => $pic)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td class="font-weight-semibold">{{ $pic->position }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-primary px-2" onclick="openEditPicModal({{ $pic->id_pic }}, '{{ addslashes($pic->position) }}')">
                                            Edit
                                        </button>
                                        <a href="{{ url('/insurance/attributes/pic/delete/' . $pic->id_pic) }}" 
                                           class="btn btn-sm btn-danger px-2"
                                           onclick="return confirm('Are you sure you want to delete this position?');">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-3 text-muted">No PIC positions recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add/Edit Attribute -->
<div class="modal fade" id="attributeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('/insurance/attributes/save') }}" method="POST">
                @csrf
                <input type="hidden" name="insurance_product_id" value="{{ $product->id }}">
                <input type="hidden" name="attribute_id" id="attr_attribute_id" value="">
                <input type="hidden" name="category_attr" id="attr_category_attr" value="">
                <input type="hidden" name="category_attr_type" id="attr_category_attr_type" value="">

                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold" id="attrModalTitle">Add New Attribute</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="attr_label" class="font-weight-bold">Label</label>
                        <input type="text" name="label" id="attr_label" class="form-control" required placeholder="e.g. IMO Number, Flag, etc.">
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

<!-- Modal Add/Edit PIC Position -->
<div class="modal fade" id="picModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('/insurance/attributes/pic/save') }}" method="POST">
                @csrf
                <input type="hidden" name="insurance_product_id" value="{{ $product->id }}">
                <input type="hidden" name="id_pic" id="pic_id_pic" value="">

                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold" id="picModalTitle">Add New PIC Position</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="pic_position" class="font-weight-bold">Position</label>
                        <input type="text" name="position" id="pic_position" class="form-control" required placeholder="e.g. Master, Chief Officer, etc.">
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
    function openAddModal(catName, catType) {
        $('#attrModalTitle').text('Add New ' + catName);
        $('#attr_attribute_id').val('');
        $('#attr_label').val('');
        $('#attr_category_attr').val(catName);
        $('#attr_category_attr_type').val(catType);
        $('#attributeModal').modal('show');
    }

    function openEditModal(attrId, label, catName, catType) {
        $('#attrModalTitle').text('Edit ' + catName);
        $('#attr_attribute_id').val(attrId);
        $('#attr_label').val(label);
        $('#attr_category_attr').val(catName);
        $('#attr_category_attr_type').val(catType);
        $('#attributeModal').modal('show');
    }

    function openAddPicModal() {
        $('#picModalTitle').text('Add New PIC Position');
        $('#pic_id_pic').val('');
        $('#pic_position').val('');
        $('#picModal').modal('show');
    }

    function openEditPicModal(idPic, pos) {
        $('#picModalTitle').text('Edit PIC Position');
        $('#pic_id_pic').val(idPic);
        $('#pic_position').val(pos);
        $('#picModal').modal('show');
    }
</script>
@endpush
@endsection
