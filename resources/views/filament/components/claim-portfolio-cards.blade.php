@php
    $activeFilter = request()->input('tableFilters.portfolio_consultant.value');
@endphp

<div class="mb-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- 1. CONSULTANT --}}
        <a href="{{ \App\Filament\Resources\Claims\ClaimResource::getUrl('index', ['tableFilters' => ['portfolio_consultant' => ['value' => 'consultant']]]) }}"
           class="flex items-center gap-5 p-6 rounded-xl transition duration-200 shadow-sm border {{ $activeFilter === 'consultant' ? 'bg-[#0056b3] border-[#004085] ring-2 ring-[#007bff]' : 'bg-[#007bff] hover:bg-[#0069d9] border-[#0062cc]' }} text-white group">
            <div class="p-3 bg-white/15 rounded-lg flex-shrink-0 group-hover:scale-110 transition duration-150">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="flex-1">
                <div class="text-xl font-bold tracking-tight text-white">
                    Consultant
                </div>
                <div class="text-xs text-blue-100 mt-1">
                    Klaim Konsultasi
                </div>
            </div>
        </a>

        {{-- 2. PORTOFOLIO --}}
        <a href="{{ \App\Filament\Resources\Claims\ClaimResource::getUrl('index', ['tableFilters' => ['portfolio_consultant' => ['value' => 'portfolio']]]) }}"
           class="flex items-center gap-5 p-6 rounded-xl transition duration-200 shadow-sm border {{ $activeFilter === 'portfolio' ? 'bg-[#0056b3] border-[#004085] ring-2 ring-[#007bff]' : 'bg-[#007bff] hover:bg-[#0069d9] border-[#0062cc]' }} text-white group">
            <div class="p-3 bg-white/15 rounded-lg flex-shrink-0 group-hover:scale-110 transition duration-150">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="flex-1">
                <div class="text-xl font-bold tracking-tight text-white">
                    Portofolio
                </div>
                <div class="text-xs text-blue-100 mt-1">
                    Portofolio Terdaftar
                </div>
            </div>
        </a>

        {{-- 3. ALL PORTOFOLIO --}}
        <a href="{{ \App\Filament\Resources\Claims\ClaimResource::getUrl('index') }}"
           class="flex items-center gap-5 p-6 rounded-xl transition duration-200 shadow-sm border {{ empty($activeFilter) ? 'bg-[#0056b3] border-[#004085] ring-2 ring-[#007bff]' : 'bg-[#007bff] hover:bg-[#0069d9] border-[#0062cc]' }} text-white group">
            <div class="p-3 bg-white/15 rounded-lg flex-shrink-0 group-hover:scale-110 transition duration-150">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="flex-1">
                <div class="text-xl font-bold tracking-tight text-white">
                    All Portofolio
                </div>
                <div class="text-xs text-blue-100 mt-1">
                    Seluruh Klaim Kasus
                </div>
            </div>
        </a>
    </div>
</div>
