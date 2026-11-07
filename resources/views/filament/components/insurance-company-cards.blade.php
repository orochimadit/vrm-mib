@php
    $companies = \App\Models\InsuranceCompany::has('products')->orderBy('name')->get();
    $activeCompanyId = request()->input('tableFilters.insurance_company_id.value');
@endphp

<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">
            Pilih Perusahaan Asuransi (Product Insurance Grid)
        </h3>
        @if($activeCompanyId)
            <a href="{{ \App\Filament\Resources\InsuranceProducts\InsuranceProductResource::getUrl('index') }}" 
               class="text-xs text-primary-600 dark:text-primary-400 hover:underline flex items-center gap-1 font-medium">
                ✕ Tampilkan Semua Perusahaan
            </a>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($companies as $company)
            @php
                $isActive = (string)$activeCompanyId === (string)$company->id;
            @endphp
            <a href="{{ \App\Filament\Resources\InsuranceProducts\InsuranceProductResource::getUrl('index', ['tableFilters' => ['insurance_company_id' => ['value' => $company->id]]]) }}"
               class="flex items-center gap-4 p-5 rounded-xl transition duration-200 shadow-sm border {{ $isActive ? 'bg-[#0056b3] border-[#004085] ring-2 ring-[#007bff] ring-offset-2' : 'bg-[#007bff] hover:bg-[#0069d9] border-[#0062cc]' }} text-white group">
                <div class="p-2.5 bg-white/15 rounded-lg flex-shrink-0 group-hover:scale-110 transition duration-150">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-bold uppercase tracking-tight text-white leading-tight line-clamp-2">
                        {{ $company->name }}
                    </div>
                    <div class="text-[11px] text-blue-100 mt-1 font-normal">
                        {{ $company->products()->count() }} Produk Klaim Tersedia
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
