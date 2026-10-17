<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Setting extends Model {
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'key';
    protected $keyType = 'string';
    protected $guarded = [];

    public static function get(string $key, $default = null) {
        try { return static::find($key)?->value ?? $default; }
        catch (\Throwable $e) { return $default; }   // tabel belum dimigrasi -> pakai default
    }
    public static function set(string $key, $value): void {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
    /** 0895... / +62 895... / 895... -> 62895... (hanya angka) */
    public static function normalizeWa(?string $n): string {
        $n = preg_replace('/\D/', '', (string) $n);
        if (str_starts_with($n, '0')) return '62'.substr($n, 1);
        if (str_starts_with($n, '8')) return '62'.$n;
        return $n;
    }
    /** Nomor WhatsApp admin aktif (siap dipakai di wa.me) */
    public static function adminWa(): string {
        return static::normalizeWa(static::get('admin_wa', config('warmad.admin_wa')));
    }
}
