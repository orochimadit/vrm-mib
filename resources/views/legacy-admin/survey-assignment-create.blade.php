@extends('layouts.jeramin')

@section('title', 'Surveyor Assignment List')

@section('content')
<!-- Breadcrumb (1:1 format legacy kapal/views/form_penugasan.php) -->
<div class="breadcrumb-outer mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h5 class="title-breadcrumb mb-1 font-weight-bold">Surveyor Assignment List</h5>
        <ul class="breadcrumb bg-transparent p-0 mb-0 small text-muted">
            <li class="breadcrumb-item"><a href="{{ url('/admin/services') }}">Services</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin/surveys') }}">Vessel Risk Management</a></li>
            <li class="breadcrumb-item active text-dark font-weight-bold">Form</li>
        </ul>
    </div>
    <div>
        <a href="{{ url('/admin/surveys') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa fa-arrow-left mr-1"></i> Kembali ke Asuransi
        </a>
    </div>
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

<form action="{{ route('surveys.legacy.store') }}" method="POST">
    @csrf
    <div class="row">
        <!-- Col 1: Vessel, Surveyor, Broker/Partner -->
        <div class="col-md-4">
            <!-- Card Vessel -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="font-weight-bold text-dark mb-3">Vessel</h3>
                    <div class="form-group mb-3">
                        <label for="vessel" class="font-weight-bold small text-muted">Vessel Name</label>
                        <select class="form-control" id="vessel" name="vessel_id" required>
                            <option value="">::Select Vessel Data::</option>
                            @foreach ($vessels as $v)
                                <option value="{{ $v->id }}">
                                    {{ $v->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="table-responsive" id="vessel-details-container" style="display: none;">
                        <table class="table table-bordered table-sm table-vessel-details mb-0 small">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Attribute</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>Company Name</td><td id="vd-company">-</td></tr>
                                <tr><td>Contact Info</td><td id="vd-contact">-</td></tr>
                                <tr><td>PIC</td><td id="vd-pic">-</td></tr>
                                <tr><td>Name</td><td id="vd-name">-</td></tr>
                                <tr><td>Type</td><td id="vd-type">-</td></tr>
                                <tr><td>Year Built</td><td id="vd-year">-</td></tr>
                                <tr><td>TSI</td><td id="vd-tsi">-</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Card Surveyor -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="font-weight-bold text-dark mb-3">Surveyor</h3>
                    <div class="form-group mb-3">
                        <label for="surveyor" class="font-weight-bold small text-muted">Surveyor Company</label>
                        <select class="form-control" id="surveyor" name="surveyor_id">
                            <option value="">::Select Surveyor Company::</option>
                            @foreach ($surveyors as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Card Broker/Partner -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="font-weight-bold text-dark mb-3">Broker/Partner</h3>
                    <div class="form-group mb-3">
                        <label for="broker" class="font-weight-bold small text-muted">Broker/Partner</label>
                        <select class="form-control" id="broker" name="broker_id">
                            <option value="">::Select Broker/Partner::</option>
                            @foreach ($brokers as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Col 2: Insurance & CO - Insurance Member -->
        <div class="col-md-4">
            <!-- Card Insurance -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="font-weight-bold text-dark mb-3">Insurance</h3>
                    <div class="form-group mb-3">
                        <label for="insurance_company" class="font-weight-bold small text-muted">Insurance Company</label>
                        <select class="form-control" id="insurance_company" name="insurance_company_id" required>
                            <option value="">::Select Insurance Company::</option>
                            @foreach ($insuranceCompanies as $ic)
                                <option value="{{ $ic->id }}">{{ $ic->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="insurance_product" class="font-weight-bold small text-muted">Insurance Product</label>
                        <select class="form-control" id="insurance_product" name="insurance_product">
                            <option value="WROP">WROP</option>
                            <option value="H&M">H&M (Hull & Machinery)</option>
                            <option value="P&I">P&I (Protection & Indemnity)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Card CO - Insurance Member -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="font-weight-bold text-dark mb-3">CO - Insurance Member</h3>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold small text-muted d-block">CO - Insurance Member</label>
                        <button type="button" class="btn btn-primary btn-sm px-3 mb-2" id="btn-add-coinsurance" data-toggle="modal" data-target="#modalCoInsurance">
                            Add
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm mb-0" id="tableCoInsurance">
                            <tbody>
                                <!-- Dinamis via Modal -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Col 3: Risk Assessment -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="font-weight-bold text-dark mb-3">Risk Assessment</h3>

                    <div class="form-group mb-3">
                        <label for="riskTreatment" class="font-weight-bold small text-muted">Risk Treatment</label>
                        <select class="form-control" id="riskTreatment" name="condition_type">
                            <option value="Self Assessment" selected>Self Assessment</option>
                            <option value="Photo Album">Photo Album</option>
                            <option value="Survey">Survey</option>
                            <option value="Underwriting">Underwriting</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="dueDate" class="font-weight-bold small text-muted">Due Date of Risk Assessment</label>
                        <input type="date" class="form-control" id="dueDate" name="due_date" value="{{ old('due_date') }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="policy_period_start" class="font-weight-bold small text-muted">Policy Period Start</label>
                        <input type="date" class="form-control" id="policy_period_start" name="policy_period_start" value="{{ old('policy_period_start', date('Y-m-d')) }}">
                    </div>

                    <div class="form-group mb-4">
                        <label for="policy_period_end" class="font-weight-bold small text-muted">Policy Period End</label>
                        <input type="date" class="form-control" id="policy_period_end" name="policy_period_end" value="{{ old('policy_period_end', date('Y-m-d', strtotime('+1 year'))) }}">
                    </div>

                    <button type="submit" class="btn btn-primary font-weight-bold px-4 py-2 shadow-sm">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal Co-Insurance -->
<div class="modal fade" id="modalCoInsurance" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold text-dark">Add CO - Insurance Member</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="form-group mb-3">
                    <label for="coInsuranceSelect" class="font-weight-bold small text-muted">Select Company</label>
                    <select class="form-control" id="coInsuranceSelect">
                        <option value="">::Select Co-Insurance Company::</option>
                        @foreach ($insuranceCompanies as $ic)
                            <option value="{{ $ic->id }}">{{ $ic->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm" id="btn-save-coinsurance">Save Member</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // AJAX load vessel details on change
    $('#vessel').change(function() {
        var vesselId = $(this).val();
        if (!vesselId) {
            $('#vessel-details-container').slideUp();
            return;
        }

        $.ajax({
            url: "{{ url('/admin/surveys/api/vessel') }}/" + vesselId,
            type: "GET",
            success: function(data) {
                $('#vd-company').text(data.company_name);
                $('#vd-contact').text(data.contact_info);
                $('#vd-pic').text(data.pic);
                $('#vd-name').text(data.name);
                $('#vd-type').text(data.type);
                $('#vd-year').text(data.year_built);
                $('#vd-tsi').text(data.tsi);
                $('#vessel-details-container').slideDown();
            },
            error: function() {
                $('#vessel-details-container').slideUp();
            }
        });
    });

    // Co-Insurance Member modal add
    $('#btn-save-coinsurance').click(function() {
        var sel = $('#coInsuranceSelect option:selected');
        if (sel.val()) {
            var row = $('<tr>');
            row.append($('<td class="align-middle">').text(sel.text()));
            row.append($('<input type="hidden" name="coinsurance_company[]">').val(sel.val()));
            row.append($('<td class="align-middle text-right" style="width: 50px;">').html('<button type="button" class="btn btn-danger btn-xs btn-remove-co"><i class="fa fa-trash"></i></button>'));
            $('#tableCoInsurance tbody').append(row);
            $('#modalCoInsurance').modal('hide');
        }
    });

    $('#tableCoInsurance').on('click', '.btn-remove-co', function() {
        $(this).closest('tr').remove();
    });
});
</script>
@endpush
