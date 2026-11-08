@extends('layouts.jeramin')

@section('title', 'Claim Solution — Data')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-outer mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h5 class="title-breadcrumb mb-1 font-weight-bold">Data</h5>
        <ul class="breadcrumb bg-transparent p-0 mb-0 small text-muted">
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Services</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
            <li class="breadcrumb-item active text-dark font-weight-bold">Claim Solution</li>
        </ul>
    </div>
</div>
<!-- /Breadcrumb -->

<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <a href="{{ url('/admin/claims/create') }}" class="btn btn-success font-weight-bold px-3 py-2">
                        <i class="fa fa-plus-circle mr-1"></i> Add New Claim Case
                    </a>
                </div>

                <div class="row row-portofolio">
                    @foreach ($types as $key => $label)
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 shadow-sm bg-primary text-white card-portfolio" style="border-radius: 8px; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;" data-id="{{ $key }}" data-label="{{ $label }}">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <i class="fa fa-building fa-3x text-white opacity-75"></i>
                                    <div class="text-right">
                                        <h3 class="mb-0 text-white font-weight-bold">{{ $label }}</h3>
                                        <small class="text-white-50">{{ $counts[$key] ?? 0 }} Case(s)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Claim Handled By Years -->
<div class="modal fade" id="modal-claim" tabindex="-1" role="dialog" aria-labelledby="modal-claim-title" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold text-dark" id="modal-claim-title">Claim Case Handled By Years</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size: 1.5rem;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-3">Pilih tahun periode penanganan kasus klaim untuk kategori <strong id="modal-portfolio-name" class="text-primary">Consultant</strong>:</p>
                <div id="claim-detail" class="d-flex flex-wrap align-items-center">
                    <!-- Dinamis via AJAX / Script -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
    .card-portfolio:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0, 123, 255, 0.25) !important;
    }
    .btn-year {
        min-width: 90px;
        margin-right: 12px;
        margin-bottom: 12px;
        font-weight: 600;
        border-radius: 6px;
    }
</style>
<script>
$(document).ready(function () {
    $(".row-portofolio").on("click", ".card-portfolio", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var label = $(this).data("label");
        $("#modal-portfolio-name").text(label);

        // Fetch years from server
        $.ajax({
            url: "{{ url('/admin/claims/years') }}",
            type: "GET",
            data: {
                portofolio: id
            },
            success: function (res) {
                renderYearButtons(id, res);
            },
            error: function () {
                // Fallback default years
                renderYearButtons(id, ['All', '2025', '2024', '2023', '2022']);
            }
        });
    });

    function renderYearButtons(portfolio, years) {
        var modal = $("#modal-claim");
        var container = modal.find("#claim-detail");
        var baseUrl = "{{ url('/admin/claims/data') }}";
        var html = '';

        $.each(years, function (index, year) {
            var url = baseUrl + '/' + portfolio + '/' + year;
            html += '<a href="' + url + '" class="btn btn-primary btn-lg btn-year shadow-sm">' + year + '</a>';
        });

        container.html(html);
        modal.modal("show");
    }
});
</script>
@endpush
