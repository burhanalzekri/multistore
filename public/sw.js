// 🔧 MultiStore Service Worker v3
const CACHE_NAME = 'multistore-v3';
const RUNTIME_CACHE = 'multistore-runtime-v3';
const OFFLINE_URL = '/offline';

const PRECACHE_URLS = [
  '/',
  '/demo-shop',
  '/offline',
  '/manifest.webmanifest',
  '/css/app.css',
  '/icon-192.png',
  '/icon-512.png',
];

// ═══ Install ═══
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
      return Promise.all(
        PRECACHE_URLS.map(url => cache.add(url).catch(() => null))
      );
    })
  );
  self.skipWaiting();
});

// ═══ Activate ═══
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys => {
      return Promise.all(
        keys.filter(k => k !== CACHE_NAME && k !== RUNTIME_CACHE)
            .map(k => caches.delete(k))
      );
    }).then(() => self.clients.claim())
  );
});

// ═══ Fetch ═══
self.addEventListener('fetch', event => {
  const req = event.request;
  const url = new URL(req.url);

  // Skip non-GET
  if (req.method !== 'GET') return;

  // Skip external / API
  if (url.origin !== location.origin) return;
  if (url.pathname.startsWith('/api/')) return;

  // HTML pages: Network first, fallback to cache, then offline
  if (req.mode === 'navigate' || req.headers.get('accept')?.includes('text/html')) {
    event.respondWith(
      fetch(req)
        .then(res => {
          const copy = res.clone();
          caches.open(RUNTIME_CACHE).then(c => c.put(req, copy)).catch(() => null);
          return res;
        })
        .catch(() =>
          caches.match(req).then(cached => cached || caches.match(OFFLINE_URL))
        )
    );
    return;
  }

  // Static assets: Cache first
  event.respondWith(
    caches.match(req).then(cached => {
      if (cached) return cached;
      return fetch(req).then(res => {
        if (res.ok && (res.type === 'basic' || res.type === 'cors')) {
          const copy = res.clone();
          caches.open(RUNTIME_CACHE).then(c => c.put(req, copy)).catch(() => null);
        }
        return res;
      }).catch(() => cached);
    })
  );
});

// ═══ Push (منفصل) ═══
self.addEventListener('push', event => {
  let data = { title: 'إشعار جديد', body: '', url: '/' };
  try { if (event.data) data = Object.assign(data, event.data.json()); } catch (e) {}

  event.waitUntil(
    self.registration.showNotification(data.title, {
      body: data.body,
      icon: data.icon || '/icon-192.png',
      badge: '/icon-192.png',
      vibrate: [100, 50, 100],
      dir: 'rtl',
      lang: 'ar',
      data: { url: data.url || '/' },
    })
  );
});

self.addEventListener('notificationclick', event => {
  event.notification.close();
  const url = event.notification.data.url || '/';
  event.waitUntil(
    clients.matchAll({ type: 'window' }).then(list => {
      for (const c of list) {
        if (c.url.includes(url) && 'focus' in c) return c.focus();
      }
      if (clients.openWindow) return clients.openWindow(url);
    })
  );
});
