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
    public function photoUrls(): array { return array_map(fn ($p) => asset('storage/'.ltrim($p,'/')), $this->photos ?? []); }
    public function getCoverUrlAttribute(): ?string { return $this->photoUrls()[0] ?? null; }
    public function getPriceLabelAttribute(): string { return 'Rp '.number_format($this->price,0,',','.').($this->transaction_type==='sewa'?' / tahun':''); }
    public function getTypeLabelAttribute(): string { return self::TYPES[$this->transaction_type] ?? $this->transaction_type; }

    /** Link WhatsApp ke ADMIN dengan draft pesan berisi detail toko. */
    public function getWaUrlAttribute(): string {
        $n = preg_replace('/\D/','', config('warmad.admin_wa'));
        if (str_starts_with($n,'0')) $n = '62'.substr($n,1);
        $lok = trim(($this->district ? $this->district.', ' : '').$this->city, ', ');
        $L = ["Halo Admin Warmad, saya tertarik dengan toko berikut:", "",
              "*{$this->title}*",
              "Jenis: {$this->type_label}",
              "Harga: {$this->price_label}"];
        if ($this->monthly_omzet) $L[] = 'Omzet/bulan: Rp '.number_format($this->monthly_omzet,0,',','.');
        if ($lok) $L[] = "Lokasi: {$lok}";
        if ($this->address) $L[] = "Alamat: {$this->address}";
        if ($this->land_area) $L[] = "Luas tanah: {$this->land_area} m²";
        if ($this->building_area) $L[] = "Luas bangunan: {$this->building_area} m²";
        if ($this->facilities) $L[] = 'Fasilitas: '.implode(', ', $this->facilities);
        $L[] = "Link: ".route('listing.show', $this);
        $L[] = ""; $L[] = "Mohon info lebih lanjut. Terima kasih.";
        return 'https://wa.me/'.$n.'?text='.rawurlencode(implode("\n", $L));
    }
    public function getMapUrlAttribute(): ?string {
        if ($this->latitude && $this->longitude) return 'https://www.google.com/maps?q='.$this->latitude.','.$this->longitude;
        return $this->address ? 'https://www.google.com/maps/search/'.rawurlencode($this->address) : null;
    }
}
