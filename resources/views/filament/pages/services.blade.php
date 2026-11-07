<x-filament-panels::page>
    <div class="flex flex-col items-center justify-center min-h-[500px] p-8 text-center bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800">
        {{-- AUTHENTIC LOGO VRM --}}
        <div class="mb-10 flex flex-col items-center">
            <img src="{{ asset('images/legacy/logo-vrm.webp') }}" 
                 alt="Vessel Risk Management" 
                 class="h-20 w-auto object-contain drop-shadow-sm mb-2" 
                 onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';" />
        </div>

        {{-- DUA TOMBOL UTAMA (Sesuai Legacy) --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-6 w-full max-w-xl my-4">
            <a href="{{ \App\Filament\Resources\Surveys\SurveyResource::getUrl('index') }}" 
               class="w-full sm:w-1/2 flex items-center justify-center px-6 py-4 text-sm font-semibold tracking-wide text-white bg-[#007bff] hover:bg-[#0069d9] active:bg-[#0062cc] rounded-lg shadow-sm transition duration-150 transform hover:-translate-y-0.5 text-center">
                Vessel Risk Management
            </a>

            <a href="{{ \App\Filament\Resources\Claims\ClaimResource::getUrl('index') }}" 
               class="w-full sm:w-1/2 flex items-center justify-center px-6 py-4 text-sm font-semibold tracking-wide text-white bg-[#007bff] hover:bg-[#0069d9] active:bg-[#0062cc] rounded-lg shadow-sm transition duration-150 transform hover:-translate-y-0.5 text-center">
                Claim Solution
            </a>
        </div>

        {{-- FOOTER COPYRIGHT --}}
        <div class="mt-14 text-xs font-normal text-gray-400">
            copyright &copy; 2026 Vessel Risk Management
        </div>
    </div>
</x-filament-panels::page>
