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
            <span>{{ $title }}</span>
        </li>
    </ul>
</div>
<!-- End of Breadcrumb -->

<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="col-md-12">
                    <h1 class="h3 font-weight-bold mb-3">{{ $title }}</h1>

                    @if(session('msg'))
                        {!! session('msg') !!}
                    @endif

                    <a href="{{ url('/admin/insurance-companies/create') }}" class="btn btn-primary mb-3">
                        <i class="fa fa-plus-circle mr-1"></i> Add New Company
                    </a>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 25%;">Name</th>
                                    <th style="width: 25%;">Address</th>
                                    <th style="width: 15%;">Phone</th>
                                    <th style="width: 15%;">Email</th>
                                    <th style="width: 10%;">Website</th>
                                    <th style="width: 10%; text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($companies as $company)
                                    <tr>
                                        <td class="font-weight-bold text-dark">{{ $company->name }}</td>
                                        <td>{{ $company->address ?? '-' }}</td>
                                        <td>{{ $company->phone ?? '-' }}</td>
                                        <td>
                                            @if($company->email)
                                                <a href="mailto:{{ $company->email }}">{{ $company->email }}</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($company->website)
                                                <a href="{{ str_starts_with($company->website, 'http') ? $company->website : 'https://' . $company->website }}" target="_blank" rel="noopener">
                                                    {{ $company->website }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td style="text-align: center; white-space: nowrap;">
                                            <a href="{{ url('/admin/insurance-companies/' . $company->id . '/edit') }}" class="btn btn-primary btn-sm px-3 mr-1">
                                                Edit
                                            </a>
                                            <a href="{{ url('/admin/insurance-companies/' . $company->id . '/delete') }}" 
                                               class="btn btn-danger btn-sm px-3" 
                                               onclick="return confirm('Are you sure you want to delete this company?');">
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            No insurance companies found.
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
</div>
@endsection
