<?php
use App\Http\Controllers\MarketController;
use Illuminate\Support\Facades\Route;
Route::get('/', [MarketController::class, 'index'])->name('home');
Route::get('/toko/{listing:slug}', [MarketController::class, 'show'])->name('listing.show');

// Cadangan jika symlink `public/storage` gagal dibuat (umum di Windows tanpa hak admin).
// Jika symlink ada, server melayani file langsung dan route ini tidak terpakai.
Route::get('/storage/{path}', function (string $path) {
    $base = realpath(storage_path('app/public'));
    $file = $base ? realpath($base.DIRECTORY_SEPARATOR.$path) : false;
    abort_unless(
        $file && str_starts_with($file, $base.DIRECTORY_SEPARATOR) && is_file($file)
            && in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg','jpeg','png','webp','gif'], true),
        404
    );
    return response()->file($file, ['Cache-Control' => 'public, max-age=604800']);
})->where('path', '.*');
