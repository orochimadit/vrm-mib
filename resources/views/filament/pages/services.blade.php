<x-filament-panels::page>
    <div class="flex flex-col items-center justify-center min-h-[550px] p-8 text-center bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800">
        {{-- LOGO VRM --}}
        <div class="mb-8 flex flex-col items-center">
            <div class="flex items-center gap-3">
                <svg width="56" height="56" style="width: 56px; height: 56px; min-width: 56px; max-width: 56px; display: block;" class="text-[#0066cc]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="9"/>
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M12 3v6m0 6v6M3 12h6m6 0h6"/>
                    <path d="M5.6 5.6l4.2 4.2m4.4 4.4l4.2 4.2M5.6 18.4l4.2-4.2m4.4-4.4l4.2-4.2"/>
                </svg>
                <span class="text-4xl font-extrabold tracking-tight text-[#1e3a8a]">VRM<sup class="text-xs">®</sup></span>
            </div>
            <span class="text-xs font-semibold tracking-wider text-rose-600 uppercase mt-1">Vessel Risk Management</span>
        </div>

        {{-- DUA TOMBOL UTAMA --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full max-w-2xl my-6">
            <a href="{{ \App\Filament\Resources\Surveys\SurveyResource::getUrl('index') }}" 
               class="flex items-center justify-center px-8 py-5 text-base font-bold text-white bg-[#007bff] hover:bg-[#0069d9] active:bg-[#0062cc] rounded-lg shadow transition duration-200 transform hover:-translate-y-0.5">
                Vessel Risk Management
            </a>

            <a href="{{ \App\Filament\Resources\Claims\ClaimResource::getUrl('index') }}" 
               class="flex items-center justify-center px-8 py-5 text-base font-bold text-white bg-[#007bff] hover:bg-[#0069d9] active:bg-[#0062cc] rounded-lg shadow transition duration-200 transform hover:-translate-y-0.5">
                Claim Solution
            </a>
        </div>

        {{-- FOOTER COPYRIGHT --}}
        <div class="mt-16 text-xs text-gray-400">
            copyright &copy; 2026 Vessel Risk Management
        </div>
    </div>
</x-filament-panels::page>
