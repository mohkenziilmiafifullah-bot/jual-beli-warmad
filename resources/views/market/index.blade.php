@extends('market.layout')
@section('content')
<section class="wm-hero"><div class="wm-wrap">
  <span class="wm-chip">Jual Beli Warung Madura</span>
  <h1>Temukan <em class="wm-grad">Lokasi Warung</em><br>Madura Impian Anda</h1>
  <p>Lihat daftar toko yang dijual, disewakan, atau oper kontrak. Tertarik? Tinggal klik, langsung chat Admin via WhatsApp.</p>
  <form class="wm-search" method="get" action="{{ route('home') }}">
    <input name="q" value="{{ request('q') }}" placeholder="Cari judul, alamat, kecamatan...">
    <input name="city" value="{{ request('city') }}" placeholder="Kota">
    <select name="type"><option value="">Semua Jenis</option>@foreach(\App\Models\Listing::TYPES as $k=>$v)<option value="{{ $k }}" @selected(request('type')===$k)>{{ $v }}</option>@endforeach</select>
    <button class="wm-btn">Cari</button>
  </form>
</div></section>
<section class="wm-wrap wm-sec">
  <div class="wm-grid">
  @forelse($listings as $l)
    <a class="wm-card wm-rv" style="--d:{{ ($loop->index % 4) * 70 }}ms" href="{{ route('listing.show',$l) }}">
      @php($cover = $l->cover_url)
      <div class="wm-img" @if($cover) style="--bg:url('{{ $cover }}')" @endif>
        <span class="wm-badge">{{ $l->type_label }}</span>@if($l->is_featured)<span class="wm-badge wm-star">Unggulan</span>@endif
      </div>
      <div class="wm-cb"><h3>{{ $l->title }}</h3>
        <div class="wm-price">{{ $l->price_label }}</div>
        <div class="wm-loc"><svg viewBox="0 0 24 24" width="12" height="12" fill="currentColor"><path d="M12 2a7 7 0 0 0-7 7c0 5 7 13 7 13s7-8 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5Z"/></svg><span>{{ collect([$l->district,$l->city])->filter()->implode(', ') }}</span></div></div>
    </a>
  @empty <p class="wm-empty">Belum ada listing yang cocok.</p>
  @endforelse
  </div>
  <div class="wm-pager wm-rv">@if($listings->previousPageUrl())<a class="wm-ghost" href="{{ $listings->previousPageUrl() }}">← Sebelumnya</a>@endif
  @if($listings->nextPageUrl())<a class="wm-ghost" href="{{ $listings->nextPageUrl() }}">Berikutnya →</a>@endif</div>
</section>
@endsection
