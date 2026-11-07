@extends('layouts.jeramin')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-outer mb-4 d-flex justify-content-between align-items-center">
    <h5 class="title-breadcrumb mb-0 font-weight-bold">Vessel Survey Form</h5>
    <ul class="breadcrumb bg-transparent p-0 mb-0">
        <li class="breadcrumb-item"><a href="{{ url('/admin/services') }}">Services</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/admin/surveys') }}">Vessel Risk Management</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/admin/surveys') }}">Data</a></li>
        <li class="breadcrumb-item active text-muted">Form</li>
    </ul>
</div>

<!-- Alert Status -->
<div class="row">
    <div class="col-md-12">
        <div class="alert {{ $survey->status === 'Completed' ? 'alert-success' : 'alert-danger' }} border-0 shadow-sm" role="alert">
            <h5 class="alert-heading font-weight-bold mb-1">{{ strtoupper($survey->status ?: 'OUTSTANDING') }}</h5>
            <p class="mb-0 small">{{ $survey->status === 'Completed' ? 'Survey is completed' : 'Survey is not yet completed' }}</p>
        </div>
    </div>
</div>

<form action="{{ route('surveys.legacy.form.update', $survey) }}" method="POST">
    @csrf

    <!-- Card 1: Insurance Product -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="card-title mb-0 font-weight-bold" style="font-size: 1rem;">Insurance Product</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0 text-sm">
                <tbody>
                    <tr>
                        <td width="30%" class="font-weight-semibold text-muted pl-4">Insurance Company</td>
                        <td width="70%">: {{ $survey->insuranceCompany?->name ?: 'GREAT AMERICAN INSURANCE COMPANY' }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-semibold text-muted pl-4">Insurance Product</td>
                        <td>: {{ $survey->insuranceProduct?->product_name ?: ($survey->condition_type ?: 'WASP') }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-semibold text-muted pl-4">Risk Treatment</td>
                        <td>: {{ $survey->condition_type ?: 'Self Assessment' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Card 2: Vessel Attributes -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="card-title mb-0 font-weight-bold" style="font-size: 1rem;">Vessel Attributes</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr class="bg-light">
                        <th width="5%" class="text-center">No</th>
                        <th width="35%">Item</th>
                        <th width="60%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="table-secondary font-weight-bold">
                        <td colspan="3" class="pl-4">Vessel General Information</td>
                    </tr>
                    <tr>
                        <td class="text-center">1</td>
                        <td class="text-muted">Vessel Name</td>
                        <td class="font-weight-bold">{{ $survey->vessel?->name ?: 'JALASHVA MAJESTIC' }}</td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td class="text-muted">Type</td>
                        <td>{{ $survey->vessel?->vesselType?->name ?: ($survey->vessel?->type ?: 'GENERAL CARGO') }}</td>
                    </tr>
                    <tr>
                        <td class="text-center">3</td>
                        <td class="text-muted">Year Built</td>
                        <td>{{ $survey->vessel?->year_built ?: '1985' }}</td>
                    </tr>
                    <tr>
                        <td class="text-center">4</td>
                        <td class="text-muted">Total Sum Insured</td>
                        <td>{{ $survey->vessel?->total_sum_insured ? 'USD ' . number_format($survey->vessel->total_sum_insured) : 'USD' }}</td>
                    </tr>
                    <tr>
                        <td class="text-center">5</td>
                        <td class="text-muted">GRT</td>
                        <td>{{ $survey->vessel?->grt ?: '312' }}</td>
                    </tr>

                    <tr class="table-secondary font-weight-bold">
                        <td colspan="3" class="pl-4">Vessel Identification</td>
                    </tr>
                    <tr>
                        <td class="text-center">1</td>
                        <td class="text-muted">IMO Number</td>
                        <td>
                            <input type="text" class="form-control form-control-sm" name="imo_number" value="{{ $survey->vessel?->imo_number }}">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td class="text-muted">Company Name</td>
                        <td>
                            <input type="text" class="form-control form-control-sm" name="owner_company_name" value="{{ $survey->vessel?->ownerCompany?->name }}">
                        </td>
                    </tr>

                    <tr class="table-light">
                        <td colspan="2" class="font-weight-bold pl-4">Main Engine</td>
                        <td>
                            <div class="form-inline">
                                <label class="mr-2 text-muted small">Number Of Engine :</label>
                                <input type="number" class="form-control form-control-sm" name="main_engine_count" value="1" min="0" max="10" style="width: 80px;">
                            </div>
                        </td>
                    </tr>
                    <tr class="table-light">
                        <td colspan="2" class="font-weight-bold pl-4">Auxiliary Engine</td>
                        <td>
                            <div class="form-inline">
                                <label class="mr-2 text-muted small">Number Of Engine :</label>
                                <input type="number" class="form-control form-control-sm" name="aux_engine_count" value="1" min="0" max="10" style="width: 80px;">
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Card 3: Person In Charge -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="card-title mb-0 font-weight-bold" style="font-size: 1rem;">Person In Charge</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr class="bg-light">
                        <th width="5%" class="text-center">No</th>
                        <th width="30%">Position</th>
                        <th width="35%">Name</th>
                        <th width="25%">Phone</th>
                        <th width="5%" class="text-center"><i class="fa fa-check"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $pics = [
                            ['no' => 1, 'pos' => 'Master', 'name' => 'Capt. Bambang Suryo', 'phone' => '+62811223344'],
                            ['no' => 2, 'pos' => 'Chief Officer', 'name' => 'Ahmad Fauzi', 'phone' => '+62812345678'],
                            ['no' => 3, 'pos' => 'Chief Engineer', 'name' => 'Ir. Hendro Wijaya', 'phone' => '+62813987654'],
                            ['no' => 4, 'pos' => 'KKM', 'name' => 'Dedi Supriyadi', 'phone' => '+62819876543'],
                        ];
                    @endphp
                    @foreach($pics as $p)
                        <tr>
                            <td class="text-center">{{ $p['no'] }}</td>
                            <td class="font-weight-bold text-muted">{{ $p['pos'] }}</td>
                            <td><input type="text" class="form-control form-control-sm" name="pic_name_{{ $p['no'] }}" value="{{ $p['name'] }}"></td>
                            <td><input type="text" class="form-control form-control-sm" name="pic_phone_{{ $p['no'] }}" value="{{ $p['phone'] }}"></td>
                            <td class="text-center">
                                <input type="checkbox" name="pic_check_{{ $p['no'] }}" checked>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Card 4: Checklist Items (Yellow Highlight When Empty) -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr class="bg-light">
                            <th width="8%" class="text-center">No Reff</th>
                            <th width="45%">Item Name</th>
                            <th width="32%">Response</th>
                            <th width="15%" class="text-center">Attachment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $idx => $item)
                            @php
                                $resp = $responses->get($item->item_id);
                                $hasResponse = !empty($resp?->response);
                                $hasAttachment = $resp?->attachments?->isNotEmpty() ?? false;
                            @endphp
                            <tr class="{{ $hasResponse ? '' : 'table-yellow-row' }}">
                                <td class="text-center font-weight-bold">{{ $item->ref_no ?: ($idx + 1) }}</td>
                                <td>{{ $item->item_name }}</td>
                                <td>
                                    <input type="text" 
                                           class="form-control form-control-sm bg-white" 
                                           name="responses[{{ $item->item_id }}]" 
                                           value="{{ $resp?->response }}" 
                                           placeholder="Ketik respon pemeriksaan...">
                                </td>
                                <td class="text-center btn-action-group">
                                    <button type="button" class="btn btn-sm btn-warning" title="Response Quick Edit" onclick="editResponseModal({{ $item->item_id }}, '{{ addslashes($item->item_name) }}', '{{ addslashes($resp?->response ?? '') }}')">
                                        <i class="fa fa-reply"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-info" title="Upload Attachment" onclick="uploadModal({{ $item->item_id }}, '{{ addslashes($item->item_name) }}')">
                                        <i class="fa fa-upload"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm {{ $hasAttachment ? 'btn-success' : 'btn-danger' }}" title="List Attachment" onclick="viewModal({{ $item->item_id }})">
                                        <i class="fa fa-list"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada item checklist.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Submit Button (Blue) -->
    <div class="mb-5">
        <button type="submit" class="btn btn-primary px-4 py-2 font-weight-bold shadow-sm">
            Save
        </button>
        <a href="{{ url('/admin/surveys') }}" class="btn btn-secondary px-3 py-2 ml-2">
            Kembali
        </a>
    </div>

</form>

<!-- Modal Quick Edit Response -->
<div class="modal fade" id="modalQuickEdit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="modalQuickEditTitle">Ubah Respon</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p id="modalQuickEditQuestion" class="small text-muted mb-2"></p>
                <textarea id="modalQuickEditInput" class="form-control" rows="3"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="saveQuickEdit()">Simpan Respon</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let currentEditItemId = null;

    function editResponseModal(itemId, itemName, currentVal) {
        currentEditItemId = itemId;
        $('#modalQuickEditTitle').text('Item Reff #' + itemId);
        $('#modalQuickEditQuestion').text(itemName);
        $('#modalQuickEditInput').val(currentVal);
        $('#modalQuickEdit').modal('show');
    }

    function saveQuickEdit() {
        if (!currentEditItemId) return;
        const val = $('#modalQuickEditInput').val();
        $('input[name="responses[' + currentEditItemId + ']"]').val(val);
        if (val.trim() !== '') {
            $('input[name="responses[' + currentEditItemId + ']"]').closest('tr').removeClass('table-yellow-row');
        } else {
            $('input[name="responses[' + currentEditItemId + ']"]').closest('tr').addClass('table-yellow-row');
        }
        $('#modalQuickEdit').modal('hide');
    }

    function uploadModal(itemId, itemName) {
        window.location.href = "{{ url('/admin/surveys/' . $survey->id . '/checklist') }}";
    }

    function viewModal(itemId) {
        window.location.href = "{{ url('/admin/surveys/' . $survey->id . '/checklist') }}";
    }
</script>
@endpush
