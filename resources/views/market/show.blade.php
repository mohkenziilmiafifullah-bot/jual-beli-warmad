@extends('market.layout')
@section('title',$listing->title.' — Warmad')
@section('content')
<section class="wm-wrap wm-sec"><a href="{{ route('home') }}" class="wm-meta">← Kembali</a>
<div class="wm-detail">
  <div>
    @php($photos = $listing->photoUrls())
    <div class="wm-gallery">@forelse($photos as $i=>$u)
      <figure class="wm-frame {{ $i===0 ? 'wm-frame-main' : '' }}">
        <button type="button" class="wm-zoom" data-i="{{ $i }}" aria-label="Perbesar foto {{ $i+1 }} dari {{ count($photos) }}">
          <img src="{{ $u }}" alt="{{ $listing->title }} - foto {{ $i+1 }}" @if($i>0) loading="lazy" @endif decoding="async">
          <span class="wm-cnt">{{ $i+1 }} / {{ count($photos) }}</span>
        </button>
      </figure>
    @empty<div class="wm-img wm-big"></div>@endforelse</div>
    @if($listing->video_url)<div class="wm-frame wm-vframe"><video class="wm-video" controls playsinline preload="metadata" @if($listing->cover_url) poster="{{ $listing->cover_url }}" @endif><source src="{{ $listing->video_url }}"></video></div>@endif
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
@if(count($photos))
<div class="wm-lb" id="wmLb" role="dialog" aria-modal="true" aria-label="Galeri foto {{ $listing->title }}" aria-hidden="true" data-close>
  <button type="button" class="wm-lb-x" data-close aria-label="Tutup">&times;</button>
  <button type="button" class="wm-lb-p" aria-label="Foto sebelumnya">&#8249;</button>
  <button type="button" class="wm-lb-n" aria-label="Foto berikutnya">&#8250;</button>
  <div class="wm-lb-frame"><img alt="{{ $listing->title }}"><span class="wm-lb-cnt"></span></div>
  <div class="wm-lb-th"></div>
</div>
<script>
(function(){
  var P=@json($photos),lb=document.getElementById('wmLb');if(!lb||!P.length)return;
  var img=lb.querySelector('.wm-lb-frame img'),cnt=lb.querySelector('.wm-lb-cnt'),th=lb.querySelector('.wm-lb-th'),i=0,last=null,tok=0,sx=null;
  if(P.length<2)lb.classList.add('one');
  P.forEach(function(u,k){var b=document.createElement('button');b.type='button';b.className='wm-th';b.setAttribute('aria-label','Foto '+(k+1));
    var t=new Image();t.src=u;t.alt='';t.loading='lazy';b.appendChild(t);b.addEventListener('click',function(e){e.stopPropagation();show(k)});th.appendChild(b)});
  function show(k){
    i=(k+P.length)%P.length;var my=++tok;img.classList.add('wm-fade');
    var n=new Image();n.onload=n.onerror=function(){if(my!==tok)return;img.src=P[i];img.classList.remove('wm-fade')};n.src=P[i];
    cnt.textContent=(i+1)+' / '+P.length;
    [].forEach.call(th.children,function(b,x){var on=x===i;b.classList.toggle('on',on);if(on)b.setAttribute('aria-current','true');else b.removeAttribute('aria-current');
      if(on&&b.scrollIntoView)b.scrollIntoView({inline:'center',block:'nearest'})});
    if(P.length>1){(new Image()).src=P[(i+1)%P.length]}
  }
  function open(k){last=document.activeElement;lb.classList.add('on');lb.setAttribute('aria-hidden','false');document.documentElement.style.overflow='hidden';show(k);lb.querySelector('.wm-lb-x').focus()}
  function close(){lb.classList.remove('on');lb.setAttribute('aria-hidden','true');document.documentElement.style.overflow='';if(last&&last.focus)last.focus()}
  [].forEach.call(document.querySelectorAll('.wm-zoom'),function(b){b.addEventListener('click',function(){open(+b.dataset.i||0)})});
  lb.addEventListener('click',function(e){if(e.target.hasAttribute('data-close'))close()});
  lb.querySelector('.wm-lb-p').addEventListener('click',function(e){e.stopPropagation();show(i-1)});
  lb.querySelector('.wm-lb-n').addEventListener('click',function(e){e.stopPropagation();show(i+1)});
  document.addEventListener('keydown',function(e){
    if(!lb.classList.contains('on'))return;
    if(e.key==='Escape')close();else if(e.key==='ArrowLeft')show(i-1);else if(e.key==='ArrowRight')show(i+1);
    else if(e.key==='Tab'){var f=[].slice.call(lb.querySelectorAll('button')).filter(function(b){return b.offsetParent!==null});
      if(!f.length)return;var a=f.indexOf(document.activeElement);
      if(e.shiftKey&&a<=0){e.preventDefault();f[f.length-1].focus()}else if(!e.shiftKey&&a===f.length-1){e.preventDefault();f[0].focus()}}
  });
  lb.addEventListener('touchstart',function(e){sx=e.touches[0].clientX},{passive:true});
  lb.addEventListener('touchend',function(e){if(sx===null)return;var d=e.changedTouches[0].clientX-sx;sx=null;if(Math.abs(d)>50)show(d>0?i-1:i+1)});
})();
</script>
@endif

@endsection
