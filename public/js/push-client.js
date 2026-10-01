(function() {
  'use strict';
  var SW_PATH = '/push-sw.js';

  function urlBase64ToUint8Array(base64String) {
    var padding = '='.repeat((4 - base64String.length % 4) % 4);
    var base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
    var raw = window.atob(base64);
    var out = new Uint8Array(raw.length);
    for (var i = 0; i < raw.length; ++i) out[i] = raw.charCodeAt(i);
    return out;
  }

  function isSupported() {
    return 'serviceWorker' in navigator && 'PushManager' in window && 'Notification' in window;
  }

  async function init() {
    if (!isSupported()) return;
    try {
      var reg = await navigator.serviceWorker.register(SW_PATH);
      var existing = await reg.pushManager.getSubscription();
      updateUI(existing ? 'subscribed' : 'idle');
    } catch (e) {
      console.warn('Push init failed:', e);
    }
  }

  async function subscribe() {
    if (!isSupported()) {
      alert('المتصفح لا يدعم الإشعارات');
      return false;
    }

    var perm = await Notification.requestPermission();
    if (perm !== 'granted') {
      alert('الرجاء السماح بالإشعارات');
      return false;
    }

    var reg = await navigator.serviceWorker.register(SW_PATH);
    await navigator.serviceWorker.ready;

    var resp = await fetch('/api/push/public-key');
    var data = await resp.json();
    if (!data.publicKey) {
      alert('خطأ في الإعداد');
      return false;
    }

    var sub = await reg.pushManager.subscribe({
      userVisibleOnly: true,
      applicationServerKey: urlBase64ToUint8Array(data.publicKey),
    });

    var r = await fetch('/api/push/subscribe', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': getCsrf(),
      },
      body: JSON.stringify(sub.toJSON()),
    });

    if (r.ok) {
      updateUI('subscribed');
      return true;
    }
    return false;
  }

  async function unsubscribe() {
    var reg = await navigator.serviceWorker.ready;
    var sub = await reg.pushManager.getSubscription();
    if (sub) {
      await fetch('/api/push/unsubscribe', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCsrf() },
        body: JSON.stringify({ endpoint: sub.endpoint }),
      });
      await sub.unsubscribe();
    }
    updateUI('idle');
  }

  function getCsrf() {
    var m = document.querySelector('meta[name="csrf-token"]');
    return m ? m.content : '';
  }

  function updateUI(state) {
    document.querySelectorAll('[data-push-toggle]').forEach(function(btn) {
      var isOn = state === 'subscribed';
      btn.classList.toggle('active', isOn);
      btn.setAttribute('data-state', state);
      var label = btn.querySelector('[data-push-label]');
      if (label) label.textContent = isOn ? 'الإشعارات مفعّلة' : 'تفعيل الإشعارات';
    });
  }

  window.MultiStorePush = {
    subscribe: subscribe,
    unsubscribe: unsubscribe,
    toggle: async function() {
      var reg = await navigator.serviceWorker.ready;
      var sub = await reg.pushManager.getSubscription();
      if (sub) { await unsubscribe(); } else { await subscribe(); }
    },
    isSupported: isSupported,
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
