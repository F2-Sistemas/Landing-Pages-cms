<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\EditProfile;
use App\Http\Middleware\InitializeTenantIfHasOne;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Pages\Auth\Login;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Hasnayeen\Themes\ThemesPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;
use Filament\Navigation\MenuItem;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $topNavigation = boolval(\Cookie::get('topNavigation', false));

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->passwordReset()
            ->sidebarCollapsibleOnDesktop()
            ->databaseNotifications()
            ->favicon(url('favicon.ico'))
            ->registration()
            ->breadcrumbs(true)
            // ->emailVerification()
            ->profile(EditProfile::class)
            ->profile()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            // ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\AccountWidget::class,
                \App\Filament\Widgets\CalendarWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                InitializeTenantIfHasOne::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class,
            ])
            ->tenantMiddleware([
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugin(
                FilamentFullCalendarPlugin::make()
                    // ->schedulerLicenseKey()
                    ->selectable()
                    ->editable()
                // ->timezone('America/Sao_Paulo')
                // ->locale('pt-BR')
                // ->plugins([])
                // ->config(['allDay' => true, 'allDayDefault' => true]),
                // ->config(['headerToolbar' => ['start' => 'title']]),
            )

            ->userMenuItems([
                'tenant_id' => MenuItem::make()
                    ->sort(-6)
                    ->hidden(fn () => !tenancy()?->initialized)
                    ->label(fn () => __('general.menu_items.tenant_id', [
                        'tenant_id' => tenant('id')
                    ]))
                    ->url('#!'),

                'profile' => MenuItem::make()
                    ->label(fn () => __('general.menu_items.profile', [
                        'user_name' => \Auth::user()?->{'name'}
                    ])),
            ])
            ->darkMode(true)
            ->collapsibleNavigationGroups(true)
            ->sidebarCollapsibleOnDesktop(true)
            ->maxContentWidth('full')
            ->topNavigation($topNavigation)
            ->brandName(config('app.name'))
            ->plugin(
                ThemesPlugin::make(),
            );
    }
}
