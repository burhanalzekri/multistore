/**
 * ms-alerts.js — نظام نوافذ التنبيه المنبثقة
 * يقرأ من window.__msAlerts ويعرض نافذة أنيقة
 */
(function () {
  'use strict';
  var data = window.__msAlerts || {};
  var queue = [];

  if (data.validation && data.validation.length) {
    data.validation.forEach(function (msg) { queue.push({ type: 'error', title: 'خطأ في التحقق', msg: msg }); });
  }
  if (data.error) queue.push({ type: 'error', title: 'حدث خطأ', msg: data.error });
  if (data.warning) queue.push({ type: 'warning', title: 'تنبيه', msg: data.warning });
  if (data.success) queue.push({ type: 'success', title: 'تم بنجاح', msg: data.success, auto: 3000 });

  if (!queue.length) return;

  var styles = {
    error:   { bg: 'linear-gradient(135deg,#dc2626,#b91c1c)', icon: '❌', btn: '#dc2626' },
    warning: { bg: 'linear-gradient(135deg,#f59e0b,#d97706)', icon: '⚠️', btn: '#f59e0b' },
    success: { bg: 'linear-gradient(135deg,#16a34a,#15803d)', icon: '✅', btn: '#16a34a' }
  };

  function show(alert) {
    var st = styles[alert.type] || styles.error;

    var overlay = document.createElement('div');
    overlay.style.cssText = 'position:fixed;inset:0;background:rgba(15,23,42,.65);z-index:99999;display:flex;align-items:center;justify-content:center;padding:20px;backdrop-filter:blur(4px);animation:msFadeIn .2s ease';

    var box = document.createElement('div');
    box.style.cssText = 'background:#fff;border-radius:22px;max-width:440px;width:100%;box-shadow:0 25px 60px rgba(0,0,0,.35);overflow:hidden;animation:msPopIn .25s cubic-bezier(.34,1.56,.64,1);direction:rtl;font-family:system-ui,-apple-system,sans-serif';

    var head = document.createElement('div');
    head.style.cssText = 'background:' + st.bg + ';padding:28px 24px 22px;text-align:center;color:#fff';
    head.innerHTML = '<div style="font-size:52px;line-height:1;margin-bottom:10px">' + st.icon + '</div>' +
                     '<div style="font-size:19px;font-weight:800">' + alert.title + '</div>';

    var body = document.createElement('div');
    body.style.cssText = 'padding:24px 26px 22px;color:#334155;font-size:15px;line-height:1.8;text-align:center;word-break:break-word';
    body.textContent = alert.msg;

    var foot = document.createElement('div');
    foot.style.cssText = 'padding:0 24px 26px;text-align:center';

    var btn = document.createElement('button');
    btn.textContent = 'حسناً';
    btn.style.cssText = 'background:' + st.btn + ';color:#fff;border:0;border-radius:12px;padding:13px 44px;font-size:15px;font-weight:800;cursor:pointer;min-width:160px;min-height:46px;box-shadow:0 6px 18px rgba(0,0,0,.18);transition:transform .15s';
    btn.onmouseover = function(){ btn.style.transform = 'scale(1.03)'; };
    btn.onmouseout  = function(){ btn.style.transform = 'scale(1)'; };
    btn.onclick = close;
    foot.appendChild(btn);

    box.appendChild(head);
    box.appendChild(body);
    box.appendChild(foot);
    overlay.appendChild(box);

    function close() {
      overlay.style.opacity = '0';
      overlay.style.transition = 'opacity .2s';
      setTimeout(function () {
        if (overlay.parentNode) overlay.parentNode.removeChild(overlay);
        next();
      }, 200);
    }

    overlay.addEventListener('click', function (e) { if (e.target === overlay) close(); });

    function onKey(e) { if (e.key === 'Escape') { document.removeEventListener('keydown', onKey); close(); } }
    document.addEventListener('keydown', onKey);

    document.body.appendChild(overlay);

    if (alert.auto) setTimeout(close, alert.auto);
  }

  function next() {
    if (!queue.length) return;
    show(queue.shift());
  }

  var styleTag = document.createElement('style');
  styleTag.textContent =
    '@keyframes msFadeIn{from{opacity:0}to{opacity:1}}' +
    '@keyframes msPopIn{from{opacity:0;transform:scale(.92) translateY(10px)}to{opacity:1;transform:scale(1) translateY(0)}}';
  document.head.appendChild(styleTag);

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', next);
  else next();
})();
