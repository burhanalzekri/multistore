// 🔔 Push Service Worker
self.addEventListener('install', function(e) {
  self.skipWaiting();
});

self.addEventListener('activate', function(e) {
  e.waitUntil(self.clients.claim());
});

self.addEventListener('push', function(event) {
  var data = {
    title: 'إشعار جديد',
    body: '',
    url: '/',
    icon: '/favicon.ico'
  };
  try {
    if (event.data) {
      data = Object.assign(data, event.data.json());
    }
  } catch (e) {}

  var options = {
    body: data.body,
    icon: data.icon || '/favicon.ico',
    badge: data.badge || '/favicon.ico',
    vibrate: [100, 50, 100],
    data: { url: data.url || '/' },
    dir: 'rtl',
    lang: 'ar',
    tag: data.tag || 'multistore',
    renotify: true,
  };

  event.waitUntil(self.registration.showNotification(data.title, options));
});

self.addEventListener('notificationclick', function(event) {
  event.notification.close();
  var url = event.notification.data.url || '/';
  event.waitUntil(
    clients.matchAll({ type: 'window' }).then(function(clientList) {
      for (var i = 0; i < clientList.length; i++) {
        var client = clientList[i];
        if (client.url.includes(url) && 'focus' in client) {
          return client.focus();
        }
      }
      if (clients.openWindow) return clients.openWindow(url);
    })
  );
});