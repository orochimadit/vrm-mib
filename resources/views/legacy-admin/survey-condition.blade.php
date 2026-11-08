@extends('layouts.jeramin')

@section('title', 'Condition Survey Form — ' . ($survey->vessel?->name ?? 'Survey #' . $survey->id))

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-outer mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h5 class="title-breadcrumb mb-1 font-weight-bold">Condition Survey Form</h5>
        <ul class="breadcrumb bg-transparent p-0 mb-0 small text-muted">
            <li class="breadcrumb-item"><a href="{{ url('/admin/services') }}">Services</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin/surveys') }}">Vessel Risk Management</a></li>
            <li class="breadcrumb-item"><a href="javascript:history.go(-1)">Data</a></li>
            <li class="breadcrumb-item active text-dark font-weight-bold">Condition Form</li>
        </ul>
    </div>
    <div>
        <a href="javascript:history.go(-1)" class="btn btn-outline-secondary btn-sm">
            <i class="fa fa-arrow-left mr-1"></i> Kembali ke Tabel Data
        </a>
    </div>
</div>
<!-- /Breadcrumb -->

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<div class="row">
    <!-- Col 1: Vessel Information & Broker/Partner -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 font-weight-bold text-dark">
                    <i class="fa fa-ship mr-2 text-primary"></i> Vessel Information
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <tbody>
                            <tr>
                                <td class="font-weight-bold text-muted" style="width: 45%;">Company Name</td>
                                <td class="font-weight-bold text-dark">{{ $survey->vessel?->ownerCompany?->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-muted">Contact Info</td>
                                <td>{{ $survey->vessel?->ownerCompany?->phone ?? ($survey->vessel?->ownerCompany?->email ?? '-') }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-muted">Vessel Name</td>
                                <td class="font-weight-bold text-primary">{{ $survey->vessel?->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-muted">Vessel Type</td>
                                <td>{{ $survey->vessel?->vesselType?->name ?? ($survey->vessel?->type ?? 'GENERAL CARGO') }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-muted">Year Built</td>
                                <td>{{ $survey->vessel?->year_built ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-muted">TSI</td>
                                <td>{{ $survey->vessel?->tsi ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-muted">GT</td>
                                <td>{{ $survey->vessel?->grt ?? ($survey->vessel?->gt ?? '-') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 font-weight-bold text-dark">
                    <i class="fa fa-handshake-o mr-2 text-primary"></i> Broker/Partner
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <tbody>
                            <tr>
                                <td class="font-weight-bold text-muted" style="width: 45%;">Company Name</td>
                                <td class="font-weight-bold text-dark">{{ $survey->broker?->name ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Col 2: Surveyor Company & Insurance Product & Co-Insurance -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 font-weight-bold text-dark">
                    <i class="fa fa-users mr-2 text-primary"></i> Surveyor Company
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <tbody>
                            <tr>
                                <td class="font-weight-bold text-muted" style="width: 45%;">Company Name</td>
                                <td class="font-weight-bold text-dark">{{ $survey->surveyor?->name ?? 'PT. MARINE INSURANCE BROKER' }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-muted">Company Info</td>
                                <td>{{ $survey->surveyor?->contact_info ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 font-weight-bold text-dark">
                    <i class="fa fa-shield mr-2 text-primary"></i> Insurance Product
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <tbody>
                            <tr>
                                <td class="font-weight-bold text-muted" style="width: 45%;">Insurance Company</td>
                                <td class="font-weight-bold text-dark">{{ $survey->insuranceCompany?->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-muted">Insurance Product</td>
                                <td>{{ $survey->insuranceProduct?->name ?? 'WROP' }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-muted">Risk Treatment</td>
                                <td><span class="badge badge-info">{{ $survey->condition_type ?? 'Self Assessment' }}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 font-weight-bold text-dark">
                    <i class="fa fa-building-o mr-2 text-primary"></i> CO - Insurance Member
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped mb-0" id="tableCoInsurance">
                        <tbody>
                            <tr>
                                <td class="text-muted small">No CO - Insurance Member registered.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Col 3: Risk Assessment Form -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 font-weight-bold text-dark">
                    <i class="fa fa-pencil-square-o mr-2 text-primary"></i> Risk Assessment
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('surveys.legacy.condition.update', $survey->id) }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="surveyorName" class="font-weight-bold small text-muted">Surveyor Name</label>
                        <input type="text" class="form-control" id="surveyorName" name="surveyor_name" placeholder="Enter Surveyor Name" value="{{ old('surveyor_name', $survey->surveyor_name) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="dateSurvey" class="font-weight-bold small text-muted">Survey Date</label>
                        <input type="date" class="form-control" id="dateSurvey" name="date_of_survey" value="{{ old('date_of_survey', $survey->date_of_survey?->format('Y-m-d')) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="locationSurvey" class="font-weight-bold small text-muted">Location of Survey</label>
                        <input type="text" class="form-control" id="locationSurvey" name="location" placeholder="Enter the location of the survey" value="{{ old('location', $survey->location) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="policy_period_start" class="font-weight-bold small text-muted">Policy Period Start</label>
                        <input type="date" class="form-control" id="policy_period_start" name="policy_period_start" value="{{ old('policy_period_start', $survey->policy_period_start?->format('Y-m-d')) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="policy_period_end" class="font-weight-bold small text-muted">Policy Period End</label>
                        <input type="date" class="form-control" id="policy_period_end" name="policy_period_end" value="{{ old('policy_period_end', $survey->policy_period_end?->format('Y-m-d')) }}">
                    </div>

                    <div class="form-group mb-4">
                        <label for="dueDate" class="font-weight-bold small text-muted">Due Date of Risk Assessment</label>
                        <input type="date" class="form-control bg-light" id="dueDate" name="due_date" value="{{ old('due_date', $survey->due_date?->format('Y-m-d')) }}">
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <button type="submit" name="submit" value="save" class="btn btn-primary font-weight-bold px-4 mr-2">
                            <i class="fa fa-save mr-1"></i> Save
                        </button>
                        <button type="submit" name="submit" value="next" class="btn btn-success font-weight-bold px-4">
                            Next <i class="fa fa-arrow-right ml-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
