<?php
namespace App\Providers\Filament;
use Filament\Http\Middleware\{Authenticate, DisableBladeIconComponents, DispatchServingFilamentEvent};
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\{AddQueuedCookiesToResponse, EncryptCookies};
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\{AuthenticateSession, StartSession};
use Illuminate\View\Middleware\ShareErrorsFromSession;
class Base {
    public static function make(Panel $p, string $id): Panel {
        return $p->id($id)->path($id)->brandName('WARMAD')->font('Plus Jakarta Sans')->darkMode(false)
            ->sidebarCollapsibleOnDesktop()->profile()->pages([Dashboard::class])
            ->renderHook(PanelsRenderHook::HEAD_END, fn () => view('pwa-head'))
            ->middleware([EncryptCookies::class, AddQueuedCookiesToResponse::class, StartSession::class, AuthenticateSession::class,
                ShareErrorsFromSession::class, VerifyCsrfToken::class, SubstituteBindings::class,
                DisableBladeIconComponents::class, DispatchServingFilamentEvent::class])
            ->authMiddleware([Authenticate::class]);
    }
}
