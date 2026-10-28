<?php
namespace App\Support;

use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Symfony\Component\Process\Process;

/**
 * Menyimpan video upload admin. Jika ukuran > batas (default 50 MB), video otomatis
 * DIPOTONG dari detik 0 sampai durasi yang muat di batas. Pemotongan memakai stream copy
 * (tanpa encode ulang) sehingga cepat dan kualitas tidak turun.
 */
class VideoTrimmer
{
    public static function store(TemporaryUploadedFile $file, string $dir = 'listings/video'): ?string
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');

        $limit = (int) config('warmad.video_max_mb', 50) * 1024 * 1024;
        $ext = strtolower($file->getClientOriginalExtension() ?: 'mp4');
        if (! in_array($ext, ['mp4', 'webm'], true)) $ext = 'mp4';
        $name = Str::uuid().'.'.$ext;
        $disk = Storage::disk('public');
        $src = $file->getRealPath();
        $size = (int) filesize($src);

        // Ukuran sudah aman -> simpan apa adanya.
        if ($size <= $limit) {
            return $disk->putFileAs($dir, new File($src), $name) ?: null;
        }

        $ffmpeg = (string) config('warmad.ffmpeg', 'ffmpeg');
        $ffprobe = (string) config('warmad.ffprobe', 'ffprobe');
        $duration = self::duration($ffprobe, $src);
        if ($duration === null) {
            self::fail('Video tidak bisa dipotong otomatis', 'ffmpeg/ffprobe belum terpasang atau file video rusak. Pasang ffmpeg, atau unggah video di bawah '.config('warmad.video_max_mb', 50).' MB.');
        }

        $base = tempnam(sys_get_temp_dir(), 'wm');
        $out = $base.'.'.$ext;
        $len = max(1.0, $duration * ($limit * 0.94) / $size); // 6% cadangan karena bitrate tidak rata
        $ok = false;

        try {
            for ($i = 0; $i < 6; $i++) {
                $args = [$ffmpeg, '-y', '-v', 'error', '-i', $src,
                    '-t', number_format($len, 3, '.', ''),
                    '-map', '0:v:0', '-map', '0:a?', '-c', 'copy'];
                if ($ext === 'mp4') array_push($args, '-movflags', '+faststart');
                $args[] = $out;

                $p = new Process($args);
                $p->setTimeout(1800);
                try { $p->run(); } catch (\Throwable $e) { break; }
                if (! $p->isSuccessful() || ! is_file($out)) break;

                $got = (int) filesize($out);
                if ($got > 0 && $got <= $limit) { $ok = true; break; }
                if ($len <= 1.0) break;
                $len = max(1.0, $len * ($limit * 0.94) / max($got, 1));
            }

            if (! $ok) {
                self::fail('Video gagal dipotong', 'Tidak berhasil memotong video ke bawah '.config('warmad.video_max_mb', 50).' MB. Coba video lain atau kompres dulu.');
            }

            $path = $disk->putFileAs($dir, new File($out), $name) ?: null;
        } finally {
            @unlink($out);
            @unlink($base);
        }

        Notification::make()->warning()->persistent()
            ->title('Video dipotong otomatis')
            ->body('Ukuran asli '.round($size / 1048576).' MB melebihi '.config('warmad.video_max_mb', 50).' MB. '
                .'Yang tersimpan: '.self::fmt($len).' pertama dari total '.self::fmt($duration).'.')
            ->send();

        return $path;
    }

    private static function duration(string $ffprobe, string $src): ?float
    {
        $p = new Process([$ffprobe, '-v', 'error', '-show_entries', 'format=duration',
            '-of', 'default=noprint_wrappers=1:nokey=1', $src]);
        $p->setTimeout(120);
        try { $p->run(); } catch (\Throwable $e) { return null; }
        $d = (float) trim($p->getOutput());
        return ($p->isSuccessful() && $d > 0) ? $d : null;
    }

    private static function fmt(float $s): string
    {
        $s = (int) round($s);
        return sprintf('%d:%02d menit', intdiv($s, 60), $s % 60);
    }

    /** Tampilkan pesan lalu batalkan simpan (form tetap terbuka). */
    private static function fail(string $title, string $body): never
    {
        Notification::make()->danger()->title($title)->body($body)->send();
        throw new Halt();
    }
}
