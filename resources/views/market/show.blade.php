@extends('market.layout')
@section('title',$listing->title.' — Warmad')
@section('content')
<section class="wm-wrap wm-sec"><a href="{{ route('home') }}" class="wm-meta">← Kembali</a>
<div class="wm-detail">
  <div>
    <div class="wm-gallery">@forelse($listing->photoUrls() as $u)<img src="{{ $u }}" alt="{{ $listing->title }}" loading="lazy" decoding="async">@empty<div class="wm-img wm-big"></div>@endforelse</div>
    @if($listing->video_url)<video class="wm-video" controls playsinline preload="metadata" @if($listing->cover_url) poster="{{ $listing->cover_url }}" @endif><source src="{{ $listing->video_url }}"></video>@endif
    <h1 class="wm-h1">{{ $listing->title }}</h1>
    <p class="wm-meta">{{ $listing->address }}</p>
    <h3>Deskripsi</h3><p>{!! nl2br(e($listing->description)) !!}</p>
  </div>
  <aside class="wm-box">
    <span class="wm-badge">{{ $listing->type_label }}</span>
    <div class="wm-price wm-xl">{{ $listing->price_label }}</div>
    <dl class="wm-dl">
      <dt>Lokasi</dt><dd>{{ collect([$listing->district,$listing->city,$listing->province])->filter()->implode(', ') }}</dd><dt>Dilihat</dt><dd>{{ $listing->views }}x</dd>
    </dl>
    <a class="wm-btn wm-full wm-wa" href="{{ $listing->wa_url }}" target="_blank" rel="noopener">Tertarik? Chat Admin via WhatsApp</a>
  </aside>
</div></section>
@endsection
