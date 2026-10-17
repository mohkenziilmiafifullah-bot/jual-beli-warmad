<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
class AppServiceProvider extends ServiceProvider {
    public function register(): void {}
    public function boot(): void {
        // URL file upload dibuat relatif (/storage/...) agar selalu cocok dengan host:port yang sedang dibuka,
        // tidak bergantung pada APP_URL di .env.
        config(['filesystems.disks.public.url' => '/storage']);
    }
}
