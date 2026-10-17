const V='warmad-v2',ASSETS=['/offline.html','/icons/icon-192.png'];
self.addEventListener('install',e=>{e.waitUntil(caches.open(V).then(c=>c.addAll(ASSETS)));self.skipWaiting()});
self.addEventListener('activate',e=>{e.waitUntil(caches.keys().then(k=>Promise.all(k.filter(x=>x!==V).map(x=>caches.delete(x)))));self.clients.claim()});
self.addEventListener('fetch',e=>{
  const r=e.request,u=new URL(r.url);
  if(r.method!=='GET'||u.origin!==location.origin||/^\/(admin|app|livewire|filament)/.test(u.pathname))return;
  if(r.mode==='navigate'){e.respondWith(fetch(r).catch(()=>caches.match('/offline.html')));return}
  e.respondWith(caches.match(r).then(c=>c||fetch(r).then(res=>{const cp=res.clone();caches.open(V).then(x=>x.put(r,cp));return res})));
});
