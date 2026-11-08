@extends('layouts.jeramin')

@section('title', 'Vessel Risk Management — Insurance Companies')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-outer mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h5 class="title-breadcrumb mb-1 font-weight-bold">Vessel Risk Management</h5>
        <ul class="breadcrumb bg-transparent p-0 mb-0 small text-muted">
            <li class="breadcrumb-item"><a href="{{ url('/admin/services') }}">Services</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
            <li class="breadcrumb-item active text-dark font-weight-bold">Vessel Risk Management</li>
        </ul>
    </div>
</div>
<!-- /Breadcrumb -->

<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <a href="{{ url('/admin/surveys/create') }}" class="btn btn-success font-weight-bold px-3 py-2">
                        <i class="fa fa-plus-circle mr-1"></i> Add New Assignment
                    </a>
                </div>

                <div class="row row-companies">
                    @foreach ($companies as $comp)
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 shadow-sm bg-primary text-white card-company" style="border-radius: 8px; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; min-height: 110px;" data-id="{{ $comp->id }}" data-name="{{ $comp->name }}">
                            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                <i class="fa fa-building fa-3x text-white opacity-75 mr-3"></i>
                                <div class="text-right flex-grow-1">
                                    <h5 class="mb-1 text-white font-weight-bold text-uppercase" style="font-size: 0.95rem; line-height: 1.3;">
                                        {{ $comp->name }}
                                    </h5>
                                    <small class="text-white-50">{{ $comp->total_surveys }} Survey(s)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Card All Portofolio -->
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 shadow-sm bg-primary text-white card-company" style="border-radius: 8px; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; min-height: 110px;" data-id="0" data-name="ALL PORTOFOLIO">
                            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                <i class="fa fa-building fa-3x text-white opacity-75 mr-3"></i>
                                <div class="text-right flex-grow-1">
                                    <h5 class="mb-1 text-white font-weight-bold text-uppercase" style="font-size: 0.95rem; line-height: 1.3;">
                                        ALL PORTOFOLIO
                                    </h5>
                                    <small class="text-white-50">{{ $totalAllSurveys }} Total Survey(s)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Surveys Policy Period By Years -->
<div class="modal fade" id="modal-survey-years" tabindex="-1" role="dialog" aria-labelledby="modalSurveyYearsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold text-dark" id="modalSurveyYearsLabel">Surveys Policy Period By Years</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size: 1.5rem;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-3">Pilih tahun periode polis untuk perusahaan <strong id="modal-company-title" class="text-primary">GREAT AMERICAN INSURANCE COMPANY</strong>:</p>
                <div id="survey-years-list" class="d-flex flex-wrap align-items-center">
                    <!-- Dinamis via AJAX -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
    .card-company:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0, 123, 255, 0.25) !important;
    }
    .btn-survey-year {
        min-width: 85px;
        margin-right: 12px;
        margin-bottom: 12px;
        font-weight: 600;
        border-radius: 6px;
    }
</style>
<script>
$(document).ready(function () {
    $(".row-companies").on("click", ".card-company", function (e) {
        e.preventDefault();
        var companyId = $(this).data("id");
        var companyName = $(this).data("name");
        $("#modal-company-title").text(companyName);

        // Fetch policy years from server
        $.ajax({
            url: "{{ url('/admin/surveys/years') }}",
            type: "GET",
            data: {
                company_id: companyId
            },
            success: function (years) {
                renderYearButtons(companyId, years);
            },
            error: function () {
                // Fallback default
                renderYearButtons(companyId, ['2027', '2026', '2025', '2024', 'ALL']);
            }
        });
    });

    function renderYearButtons(companyId, years) {
        var modal = $("#modal-survey-years");
        var container = modal.find("#survey-years-list");
        var baseUrl = "{{ url('/admin/surveys/data') }}";
        var html = '';

        $.each(years, function (index, year) {
            var url = baseUrl + '/' + companyId + '/' + year;
            html += '<a href="' + url + '" class="btn btn-primary btn-lg btn-survey-year shadow-sm">' + year + '</a>';
        });

        container.html(html);
        modal.modal("show");
    }
});
</script>
@endpush
