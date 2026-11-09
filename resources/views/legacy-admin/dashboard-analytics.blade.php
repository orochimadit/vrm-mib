@extends('layouts.jeramin')

@section('title', 'Dashboard Analytics — Vessel Risk Management')

@section('content')
<!-- Highcharts JS CDN -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<!-- Breadcrumb -->
<div class="breadcrumb-outer mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h4 class="title-breadcrumb mb-1 font-weight-bold text-dark">Dashboard</h4>
        <ul class="breadcrumb bg-transparent p-0 mb-0 small text-muted">
            <li class="breadcrumb-item"><a href="{{ url('/admin/services') }}">Services</a></li>
            <li class="breadcrumb-item active text-dark font-weight-bold">Dashboard</li>
        </ul>
    </div>
    <div class="d-flex align-items-center">
        <span class="mr-2 text-muted small font-weight-bold"><i class="fa fa-calendar mr-1"></i> Filter Tahun:</span>
        <div class="btn-group btn-group-sm">
            <a href="{{ url('/admin/dashboard?year=ALL') }}" class="btn {{ $selectedYear === 'ALL' ? 'btn-primary font-weight-bold' : 'btn-light border' }}">
                ALL
            </a>
            @foreach ($availableYears as $y)
            <a href="{{ url('/admin/dashboard?year=' . $y) }}" class="btn {{ (string)$selectedYear === (string)$y ? 'btn-primary font-weight-bold' : 'btn-light border' }}">
                {{ $y }}
            </a>
            @endforeach
        </div>
    </div>
</div>
<!-- /Breadcrumb -->

<!-- Row 1: Executive KPI Summary Cards -->
<div class="row mb-4">
    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); border-radius: 10px;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small font-weight-bold text-uppercase opacity-75">Total Policies</span>
                    <i class="fa fa-file-text-o fa-lg opacity-75"></i>
                </div>
                <h3 class="font-weight-bold mb-0">{{ number_format($totalSurveys) }}</h3>
                <small class="text-white-50">{{ $selectedYear !== 'ALL' ? 'Tahun ' . $selectedYear : 'Semua Periode' }}</small>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); border-radius: 10px;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small font-weight-bold text-uppercase opacity-75">Total Vessels</span>
                    <i class="fa fa-ship fa-lg opacity-75"></i>
                </div>
                <h3 class="font-weight-bold mb-0">{{ number_format($totalVessels) }}</h3>
                <small class="text-white-50">Kapal Terdaftar</small>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%); border-radius: 10px;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small font-weight-bold text-uppercase opacity-75">Total GRT</span>
                    <i class="fa fa-anchor fa-lg opacity-75"></i>
                </div>
                <h3 class="font-weight-bold mb-0">{{ number_format($totalGrt, 0) }}</h3>
                <small class="text-white-50">Gross Tonnage</small>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); border-radius: 10px;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small font-weight-bold text-uppercase opacity-75">Total TSI</span>
                    <i class="fa fa-money fa-lg opacity-75"></i>
                </div>
                <h3 class="font-weight-bold mb-0" style="font-size: 1.35rem;" title="${{ number_format($totalTsi, 2) }}">
                    ${{ number_format($totalTsi / 1000000000, 2) }}B
                </h3>
                <small class="text-white-50">Sum Insured (USD)</small>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); border-radius: 10px;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small font-weight-bold text-uppercase opacity-75">Survey Completed</span>
                    <i class="fa fa-check-circle fa-lg opacity-75"></i>
                </div>
                <h3 class="font-weight-bold mb-0">{{ $completionRate }}%</h3>
                <small class="text-white-50">{{ $completedSurveys }} Selesai / {{ $outstandingSurveys }} Open</small>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%); border-radius: 10px;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small font-weight-bold text-uppercase opacity-75">Claims Handled</span>
                    <i class="fa fa-shield fa-lg opacity-75"></i>
                </div>
                <h3 class="font-weight-bold mb-0">{{ $totalClaims }}</h3>
                <small class="text-white-50">{{ $ongoingClaims }} Ongoing / {{ $acceptedClaims }} Settled</small>
            </div>
        </div>
    </div>
</div>

<!-- Row 2: Visualisasi Utama "Total Policy by Years" (1:1 Sesuai Screenshot Referensi Legacy) -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm" style="border-radius: 10px; overflow: hidden;">
            <div class="card-body p-0">
                <div id="chart-total-policy-years" style="min-height: 400px; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Row 3: Rekomendasi Visualisasi 1 & 2 (Insurance Distribution & Risk Treatment) -->
<div class="row mb-4">
    <!-- Chart: Insurance Distribution -->
    <div class="col-lg-7 mb-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 10px;">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="card-title font-weight-bold text-dark mb-0">
                    <i class="fa fa-building-o text-primary mr-2"></i> Number of Vessels Based on Insurance Company
                </h5>
                <span class="badge badge-light border text-muted">Top Underwriters</span>
            </div>
            <div class="card-body pt-0">
                <div id="chart-insurance-distribution" style="min-height: 350px;"></div>
            </div>
        </div>
    </div>

    <!-- Chart: Risk Treatment & Status -->
    <div class="col-lg-5 mb-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 10px;">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="card-title font-weight-bold text-dark mb-0">
                    <i class="fa fa-pie-chart text-success mr-2"></i> Risk Treatment Distribution
                </h5>
                <span class="badge badge-light border text-muted">Metode Penilaian</span>
            </div>
            <div class="card-body pt-0">
                <div id="chart-risk-treatment" style="min-height: 350px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Row 4: Rekomendasi Visualisasi 3 & 4 (Survey Status vs Claims Solution Pipeline) -->
<div class="row mb-4">
    <!-- Chart: Survey Status -->
    <div class="col-lg-5 mb-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 10px;">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="card-title font-weight-bold text-dark mb-0">
                    <i class="fa fa-tasks text-warning mr-2"></i> Survey Execution Status
                </h5>
            </div>
            <div class="card-body pt-0">
                <div id="chart-survey-status" style="min-height: 320px;"></div>
            </div>
        </div>
    </div>

    <!-- Chart: Claims Solution Portfolio -->
    <div class="col-lg-7 mb-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 10px;">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="card-title font-weight-bold text-dark mb-0">
                    <i class="fa fa-life-ring text-danger mr-2"></i> Claim Solution Case Status
                </h5>
                <a href="{{ url('/admin/claims') }}" class="btn btn-outline-primary btn-xs font-weight-bold">
                    Buka Klaim <i class="fa fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="card-body pt-0">
                <div id="chart-claim-solution" style="min-height: 320px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Row 5: Aktivitas Survei Terkini (Quick Access Table) -->
<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="card-title font-weight-bold text-dark mb-0">
                    <i class="fa fa-clock-o text-info mr-2"></i> Penugasan Survei Terkini
                </h5>
                <a href="{{ url('/admin/surveys') }}" class="btn btn-outline-secondary btn-sm font-weight-bold">
                    Lihat Semua Penugasan <i class="fa fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Vessel Name</th>
                                <th>Insurance Company</th>
                                <th>Surveyor</th>
                                <th>Risk Treatment</th>
                                <th>Policy Period</th>
                                <th>Status</th>
                                <th style="text-align: center;">Quick Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentSurveys as $s)
                            <tr>
                                <td class="align-middle font-weight-bold text-dark">
                                    {{ $s->vessel?->name ?? 'N/A' }}
                                    <div class="small text-muted font-weight-normal">{{ $s->vessel?->ownerCompany?->name }}</div>
                                </td>
                                <td class="align-middle">
                                    {{ $s->insuranceCompany?->name ?? '-' }}
                                    <div class="small text-muted font-weight-normal">(WROP)</div>
                                </td>
                                <td class="align-middle">{{ $s->surveyor_name ?? 'PT. MARINE INSURANCE BROKER' }}</td>
                                <td class="align-middle font-weight-bold text-secondary">{{ $s->condition_type ?? 'Self Assessment' }}</td>
                                <td class="align-middle small">
                                    {{ $s->policy_period_start ? \Carbon\Carbon::parse($s->policy_period_start)->format('Y-m-d') : '-' }} s/d 
                                    {{ $s->policy_period_end ? \Carbon\Carbon::parse($s->policy_period_end)->format('Y-m-d') : '-' }}
                                </td>
                                <td class="align-middle">
                                    @php $st = strtolower($s->status ?? 'outstanding'); @endphp
                                    @if (in_array($st, ['completed', 'selesai', 'approved']))
                                        <span class="badge badge-success px-2 py-1">Completed</span>
                                    @else
                                        <span class="badge badge-danger px-2 py-1">Outstanding</span>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('surveys.legacy.condition', $s->id) }}" class="btn btn-info btn-xs" title="Condition Form">Condition</a>
                                        <a href="{{ route('surveys.legacy.form', $s->id) }}" class="btn btn-primary btn-xs" title="Survey Form">Survey</a>
                                        <a href="{{ route('surveys.legacy.result', $s->id) }}" class="btn btn-success btn-xs" title="Result Summary">Result</a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    // -------------------------------------------------------------
    // CHART 1: Total Policy by Years (1:1 Match with Screenshot)
    // -------------------------------------------------------------
    Highcharts.chart('chart-total-policy-years', {
        chart: {
            type: 'bar',
            backgroundColor: '#181818',
            style: {
                fontFamily: 'Roboto, sans-serif'
            }
        },
        title: {
            text: 'Total Policy by Years',
            align: 'left',
            style: {
                color: '#ffffff',
                fontWeight: 'bold',
                fontSize: '16px'
            }
        },
        xAxis: {
            categories: {!! json_encode($chartYears) !!},
            title: {
                text: 'Years',
                style: { color: '#888888' }
            },
            labels: {
                style: { color: '#cccccc' }
            },
            gridLineColor: '#2b2b2b',
            lineColor: '#333333'
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total Policy',
                style: { color: '#888888' }
            },
            labels: {
                style: { color: '#cccccc' }
            },
            gridLineColor: '#2b2b2b'
        },
        legend: {
            itemStyle: {
                color: '#cccccc',
                fontWeight: 'normal'
            },
            itemHoverStyle: {
                color: '#ffffff'
            }
        },
        credits: {
            enabled: true,
            style: { color: '#555555' }
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: false
                },
                borderRadius: 2,
                cursor: 'pointer',
                point: {
                    events: {
                        click: function () {
                            var year = this.category;
                            window.location.href = "{{ url('/admin/surveys/data/0') }}/" + year;
                        }
                    }
                }
            }
        },
        series: [{
            name: 'Total Policy',
            data: {!! json_encode($chartYearCounts) !!},
            color: '#00aaff'
        }]
    });

    // -------------------------------------------------------------
    // CHART 2: Number of Vessels Based on Insurance Company
    // -------------------------------------------------------------
    Highcharts.chart('chart-insurance-distribution', {
        chart: {
            type: 'bar',
            backgroundColor: 'transparent'
        },
        title: { text: '' },
        xAxis: {
            categories: {!! json_encode($insuranceLabels) !!},
            title: { text: null },
            gridLineWidth: 1
        },
        yAxis: {
            min: 0,
            title: { text: 'Number of Vessels' }
        },
        legend: { enabled: false },
        plotOptions: {
            bar: {
                borderRadius: 4,
                colorByPoint: true,
                colors: ['#0284c7', '#0ea5e9', '#06b6d4', '#14b8a6', '#10b981', '#6366f1', '#8b5cf6', '#ec4899']
            }
        },
        series: [{
            name: 'Vessels',
            data: {!! json_encode($insuranceCounts) !!}
        }]
    });

    // -------------------------------------------------------------
    // CHART 3: Risk Treatment Distribution (Donut Chart)
    // -------------------------------------------------------------
    Highcharts.chart('chart-risk-treatment', {
        chart: {
            type: 'pie',
            backgroundColor: 'transparent'
        },
        title: { text: '' },
        plotOptions: {
            pie: {
                innerSize: '60%',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f}%'
                },
                colors: ['#0284c7', '#10b981', '#f59e0b', '#8b5cf6']
            }
        },
        series: [{
            name: 'Policies',
            data: {!! json_encode($treatmentSeries) !!}
        }]
    });

    // -------------------------------------------------------------
    // CHART 4: Survey Execution Status
    // -------------------------------------------------------------
    Highcharts.chart('chart-survey-status', {
        chart: {
            type: 'pie',
            backgroundColor: 'transparent'
        },
        title: { text: '' },
        plotOptions: {
            pie: {
                innerSize: '50%',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.y}'
                }
            }
        },
        series: [{
            name: 'Surveys',
            data: {!! json_encode($statusSeries) !!}
        }]
    });

    // -------------------------------------------------------------
    // CHART 5: Claim Solution Case Status
    // -------------------------------------------------------------
    Highcharts.chart('chart-claim-solution', {
        chart: {
            type: 'column',
            backgroundColor: 'transparent'
        },
        title: { text: '' },
        xAxis: {
            categories: ['Ongoing', 'Accepted', 'Rejected'],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: { text: 'Number of Cases' }
        },
        legend: { enabled: false },
        plotOptions: {
            column: {
                borderRadius: 4,
                colorByPoint: true,
                colors: ['#f59e0b', '#10b981', '#ef4444']
            }
        },
        series: [{
            name: 'Claims',
            data: [
                {{ $claimStatusData['ongoing'] ?? 15 }},
                {{ $claimStatusData['accepted'] ?? 5 }},
                {{ $claimStatusData['rejected'] ?? 2 }}
            ]
        }]
    });
});
</script>
@endpush
