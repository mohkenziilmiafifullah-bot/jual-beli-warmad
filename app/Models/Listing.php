<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class Listing extends Model {
    const TYPES = ['jual'=>'Dijual','sewa'=>'Disewakan','oper'=>'Oper Kontrak'];
    const STATUSES = ['draft'=>'Draft','active'=>'Aktif (tampil)','sold'=>'Terjual / Tidak Tersedia'];
    protected $guarded = [];
    protected $casts = ['photos'=>'array','facilities'=>'array','is_featured'=>'boolean'];
    protected static function booted(): void {
        static::creating(function ($m) {
            $m->slug = Str::slug($m->title).'-'.Str::lower(Str::random(5));
            $m->user_id ??= auth()->id();
        });
    }
    public function scopeActive($q) { return $q->where('status','active'); }
    public function user() { return $this->belongsTo(User::class); }
    /** Hanya foto yang filenya benar-benar ada di disk, supaya browser tidak memicu error 404. */
    public function photoUrls(): array {
        $disk = Storage::disk('public');
        $out = [];
        foreach ((array) ($this->photos ?? []) as $p) {
            $p = ltrim((string) $p, '/');
            if ($p !== '' && $disk->exists($p)) $out[] = asset('storage/'.$p);
        }
        return $out;
    }
    public function getVideoUrlAttribute(): ?string {
        $v = ltrim((string) $this->video, '/');
        return ($v !== '' && Storage::disk('public')->exists($v)) ? asset('storage/'.$v) : null;
    }
    public function getCoverUrlAttribute(): ?string { return $this->photoUrls()[0] ?? null; }
    public function getPriceLabelAttribute(): string { return 'Rp '.number_format($this->price,0,',','.').($this->transaction_type==='sewa'?' / tahun':''); }
    public function getTypeLabelAttribute(): string { return self::TYPES[$this->transaction_type] ?? $this->transaction_type; }

    /** Link WhatsApp ke ADMIN dengan draft pesan berisi detail toko. */
    public function getWaUrlAttribute(): string {
        $n = Setting::adminWa();
        $lok = trim(($this->district ? $this->district.', ' : '').$this->city, ', ');
        $L = ["Halo Admin Warmad, saya tertarik dengan toko berikut:", "",
              "*{$this->title}*",
              "Jenis: {$this->type_label}",
              "Harga: {$this->price_label}"];
        if ($lok) $L[] = "Lokasi: {$lok}";
        if ($this->address) $L[] = "Alamat: {$this->address}";
        $L[] = "Link: ".route('listing.show', $this);
        $L[] = ""; $L[] = "Mohon info lebih lanjut. Terima kasih.";
        return 'https://wa.me/'.$n.'?text='.rawurlencode(implode("\n", $L));
    }
}
