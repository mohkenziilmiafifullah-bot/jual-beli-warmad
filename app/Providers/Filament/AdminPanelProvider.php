<?php
namespace App\Providers\Filament;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
class AdminPanelProvider extends PanelProvider {
    public function panel(Panel $panel): Panel {
        return Base::make($panel, 'admin')->login()->colors(['primary' => Color::Violet])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->pages([\App\Filament\Admin\Pages\Settings::class])
            ->widgets([\App\Filament\Admin\Widgets\StatsWidget::class]);
    }
}
