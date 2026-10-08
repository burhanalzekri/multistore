/**
 * ms-image-preview.js v2 — معاينة + قص لأي input[type=file]
 * يعمل مع ms-image-editor-v3.js
 */
(function () {
  'use strict';

  function fmtSize(b) {
    if (b < 1024) return b + ' B';
    if (b < 1048576) return (b / 1024).toFixed(1) + ' KB';
    return (b / 1048576).toFixed(2) + ' MB';
  }

  function attach(input) {
    if (input.dataset.msPreviewBound === '1') return;
    var accept = (input.getAttribute('accept') || '').toLowerCase();
    var isImage =
      accept === '' ||
      accept.indexOf('image') !== -1 ||
      /\.(jpg|jpeg|png|webp|gif|heic|heif|bmp|svg|ico|avif)\b/.test(accept);
    if (!isImage) return;
    input.dataset.msPreviewBound = '1';

    var wrap = document.createElement('div');
    wrap.style.cssText = 'margin-top:10px;display:none;';

    var card = document.createElement('div');
    card.style.cssText = 'display:flex;align-items:center;gap:10px;padding:10px 12px;background:#f8fafc;border:1.5px dashed #cbd5e1;border-radius:12px;transition:border-color .2s;';

    var thumb = document.createElement('img');
    thumb.alt = '';
    thumb.style.cssText = 'width:60px;height:60px;object-fit:cover;border-radius:10px;background:#e2e8f0;flex-shrink:0;border:1px solid #e2e8f0;cursor:pointer;';

    var meta = document.createElement('div');
    meta.style.cssText = 'flex:1;min-width:0;';

    var nameEl = document.createElement('div');
    nameEl.style.cssText = 'font-weight:700;color:#0f172a;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;';

    var sizeEl = document.createElement('div');
    sizeEl.style.cssText = 'color:#64748b;font-size:11px;margin-top:3px;font-weight:600;';

    var actions = document.createElement('div');
    actions.style.cssText = 'display:flex;gap:6px;flex-shrink:0;';

    var cropBtn = document.createElement('button');
    cropBtn.type = 'button';
    cropBtn.textContent = '✂️';
    cropBtn.title = 'قص الصورة';
    cropBtn.style.cssText = 'background:#fef3c7;color:#b45309;border:0;border-radius:9px;width:36px;height:36px;font-size:15px;cursor:pointer;';

    var rmBtn = document.createElement('button');
    rmBtn.type = 'button';
    rmBtn.textContent = '✕';
    rmBtn.title = 'إزالة';
    rmBtn.style.cssText = 'background:#fee2e2;color:#b91c1c;border:0;border-radius:9px;width:36px;height:36px;font-size:15px;font-weight:800;cursor:pointer;';

    function renderPreview(f) {
      if (!f) {
        wrap.style.display = 'none';
        card.style.borderColor = '#cbd5e1';
        return;
      }
      nameEl.textContent = f.name;
      sizeEl.textContent = fmtSize(f.size) + ' • ' + (f.type || 'صورة');
      var r = new FileReader();
      r.onload = function (e) {
        thumb.src = e.target.result;
        wrap.style.display = 'block';
        card.style.borderColor = '#86efac';
      };
      r.onerror = function () { wrap.style.display = 'none'; };
      r.readAsDataURL(f);
    }

    function setInputFile(f) {
      try {
        var dt = new DataTransfer();
        dt.items.add(f);
        input.files = dt.files;
        renderPreview(f);
        return true;
      } catch (err) {
        return false;
      }
    }

    function openCrop() {
      var f = input.files && input.files[0];
      if (!f) { alert('اختر صورة أولاً'); return; }
      if (!window.msOpenImageEditor) {
        alert('المحرر غير محمّل. الرجاء تحديث الصفحة.');
        return;
      }
      window.msOpenImageEditor(f, function (edited) {
        if (!edited) return;
        if (!setInputFile(edited)) {
          // Fallback: استخدم input مخفي
          alert('متصفحك لا يدعم استبدال الملفات. جرّب Chrome الحديث.');
        }
      });
    }

    cropBtn.onclick = function (e) { e.preventDefault(); openCrop(); };
    thumb.onclick = function (e) { e.preventDefault(); openCrop(); };
    rmBtn.onclick = function (e) {
      e.preventDefault();
      input.value = '';
      renderPreview(null);
    };

    actions.appendChild(cropBtn);
    actions.appendChild(rmBtn);
    meta.appendChild(nameEl);
    meta.appendChild(sizeEl);
    card.appendChild(thumb);
    card.appendChild(meta);
    card.appendChild(actions);
    wrap.appendChild(card);
    input.parentNode.insertBefore(wrap, input.nextSibling);

    input.addEventListener('change', function () {
      var f = input.files && input.files[0];
      if (!f) { renderPreview(null); return; }
      if (f.type && f.type.indexOf('image/') !== 0 &&
          !/\.(jpg|jpeg|png|webp|gif|heic|heif|bmp|svg|ico|avif)$/i.test(f.name)) {
        renderPreview(null);
        return;
      }
      renderPreview(f);
    });
  }

  function init() {
    document.querySelectorAll('input[type="file"]').forEach(attach);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  var mo = new MutationObserver(function (muts) {
    muts.forEach(function (m) {
      if (!m.addedNodes) return;
      m.addedNodes.forEach(function (n) {
        if (n.nodeType !== 1) return;
        if (n.matches && n.matches('input[type="file"]')) attach(n);
        if (n.querySelectorAll) n.querySelectorAll('input[type="file"]').forEach(attach);
      });
    });
  });
  mo.observe(document.documentElement || document.body, { childList: true, subtree: true });
})();
