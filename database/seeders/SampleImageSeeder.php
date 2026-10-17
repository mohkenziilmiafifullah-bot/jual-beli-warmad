<?php
namespace Database\Seeders;
use App\Models\Listing;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Menyalin gambar contoh (database/seeders/images) ke storage/app/public/listings/seed
 * lalu memasangnya ke 6 listing pertama yang BELUM punya foto.
 * Jalankan manual: php artisan db:seed --class=SampleImageSeeder
 */
class SampleImageSeeder extends Seeder {
    public function run(): void {
        $dir = database_path('seeders/images');
        $disk = Storage::disk('public');
        $targets = Listing::orderBy('id')->get()->filter(fn ($l) => empty($l->photos))->take(6)->values();
        foreach ($targets as $i => $listing) {
            $n = $i + 1; $paths = [];
            foreach (['depan', 'dalam'] as $part) {
                $src = "$dir/toko-$n-$part.jpg";
                if (!is_file($src)) continue;
                $dest = "listings/seed/toko-$n-$part.jpg";
                $disk->put($dest, file_get_contents($src));
                $paths[] = $dest;
            }
            if ($paths) $listing->update(['photos' => $paths]);
        }
        $this->command?->info($targets->count().' listing diberi foto contoh.');
    }
}
