@extends('market.layout')
@section('title',$listing->title.' — Warmad')
@section('content')
<section class="wm-wrap wm-sec"><a href="{{ route('home') }}" class="wm-meta">← Kembali</a>
<div class="wm-detail">
  <div>
    <div class="wm-gallery">@forelse($listing->photoUrls() as $u)<img src="{{ $u }}" alt="{{ $listing->title }}" loading="lazy" decoding="async">@empty<div class="wm-img wm-big"></div>@endforelse</div>
    <h1 class="wm-h1">{{ $listing->title }}</h1>
    <p class="wm-meta">{{ $listing->address }}</p>
    <h3>Deskripsi</h3><p>{!! nl2br(e($listing->description)) !!}</p>
    @if($listing->facilities)<div class="wm-chips">@foreach($listing->facilities as $f)<span class="wm-chip">{{ $f }}</span>@endforeach</div>@endif
  </div>
  <aside class="wm-box">
    <span class="wm-badge">{{ $listing->type_label }}</span>
    <div class="wm-price wm-xl">{{ $listing->price_label }}</div>
    <dl class="wm-dl">
      @if($listing->monthly_omzet)<dt>Omzet / bulan</dt><dd>Rp {{ number_format($listing->monthly_omzet,0,',','.') }}</dd>@endif
      @if($listing->land_area)<dt>Luas tanah</dt><dd>{{ $listing->land_area }} m²</dd>@endif
      @if($listing->building_area)<dt>Luas bangunan</dt><dd>{{ $listing->building_area }} m²</dd>@endif
      <dt>Lokasi</dt><dd>{{ $listing->district }} {{ $listing->city }}</dd><dt>Dilihat</dt><dd>{{ $listing->views }}x</dd>
    </dl>
    <a class="wm-btn wm-full wm-wa" href="{{ $listing->wa_url }}" target="_blank" rel="noopener">Tertarik? Chat Admin via WhatsApp</a>
    @if($listing->map_url)<a class="wm-ghost wm-full" href="{{ $listing->map_url }}" target="_blank" rel="noopener">Lihat di Peta</a>@endif
  </aside>
</div></section>
@endsection
