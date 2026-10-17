<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('title','Warmad — Marketplace Jual Beli Warung Madura')</title>
@include('pwa-head')
<style>@view-transition{navigation:auto}::view-transition-old(root),::view-transition-new(root){animation-duration:.22s}</style>
<script>
document.documentElement.classList.add('js');
addEventListener('DOMContentLoaded',function(){
  var els=[].slice.call(document.querySelectorAll('.wm-rv'));
  if(!('IntersectionObserver' in window)){els.forEach(function(e){e.classList.add('wm-in')});return}
  var io=new IntersectionObserver(function(es){es.forEach(function(e){if(e.isIntersecting){e.target.classList.add('wm-in');io.unobserve(e.target)}})},{rootMargin:'0px 0px -6% 0px',threshold:.06});
  els.forEach(function(e){io.observe(e)});
});
</script>
</head><body class="wm-body">
<header class="wm-nav"><div class="wm-wrap wm-nav-in">
  <a href="{{ route('home') }}" class="wm-logo">WARMAD</a>
  <nav><a href="https://wa.me/{{ config('warmad.admin_wa') }}?text={{ rawurlencode('Halo Admin Warmad, saya ingin bertanya.') }}" class="wm-btn" target="_blank" rel="noopener">Hubungi Admin</a></nav>
</div></header>
<main>@yield('content')</main>
<footer class="wm-foot"><div class="wm-wrap">© {{ date('Y') }} Warmad — Marketplace Lokasi Warung Madura</div></footer>
</body></html>
