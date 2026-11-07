<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(\App\Filament\Pages\Auth\Login::class)
            ->brandName('VESSEL RISK MANAGEMENT')
            ->font('Manrope')
            ->colors([
                'primary' => Color::hex('#007bff'),
                'info' => Color::hex('#17a2b8'),
                'success' => Color::hex('#28a745'),
                'warning' => Color::hex('#ffc107'),
                'danger' => Color::hex('#dc3545'),
            ])
            ->renderHook('panels::head.end', fn () => new \Illuminate\Support\HtmlString('
                <style>
                    .fi-logo {
                        font-weight: 800 !important;
                        letter-spacing: 0.02em !important;
                        font-size: 1.15rem !important;
                        color: #111827 !important;
                        text-transform: uppercase !important;
                    }
                    .fi-sidebar-item-active > a,
                    .fi-sidebar-item-active > button {
                        border-left: 4px solid #16a34a !important;
                        background-color: #f0fdf4 !important;
                        color: #15803d !important;
                        font-weight: 600 !important;
                    }
                    .fi-sidebar-item-active .fi-sidebar-item-icon {
                        color: #16a34a !important;
                    }
                    .fi-ta-header-cell-label {
                        font-weight: 600 !important;
                        color: #1f2937 !important;
                    }
                </style>
            '))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
