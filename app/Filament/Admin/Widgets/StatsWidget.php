<?php
namespace App\Filament\Admin\Widgets;
use App\Models\Listing;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
class StatsWidget extends StatsOverviewWidget {
    protected function getStats(): array {
        return [
            Stat::make('Total Toko', Listing::count())->color('primary'),
            Stat::make('Tampil di Publik', Listing::active()->count())->color('success'),
            Stat::make('Terjual / Draft', Listing::where('status','!=','active')->count())->color('gray'),
            Stat::make('Total Dilihat', number_format((int) Listing::sum('views'),0,',','.'))->color('info'),
        ];
    }
}
