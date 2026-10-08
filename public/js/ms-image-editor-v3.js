/**
 * ms-image-editor.js — محرر الصور مع القص
 */
(function () {
  'use strict';
  var MAX_DIM = 2200, JPEG_Q = 0.92;

  function injectStyles() {
    if (document.getElementById('msie-styles')) return;
    var s = document.createElement('style');
    s.id = 'msie-styles';
    s.textContent = [
      '.msie-overlay{position:fixed;inset:0;background:rgba(0,0,0,.9);z-index:99999;display:flex;align-items:center;justify-content:center;padding:6px;font-family:system-ui,-apple-system,sans-serif}',
      '.msie-box{background:#1e1e1e;color:#fff;border-radius:14px;width:100%;max-width:620px;max-height:98vh;display:flex;flex-direction:column;overflow:hidden}',
      '.msie-head{padding:10px 14px;border-bottom:1px solid #333;display:flex;justify-content:space-between;align-items:center}',
      '.msie-head h3{margin:0;font-size:15px}',
      '.msie-close{background:transparent;border:0;color:#fff;font-size:22px;cursor:pointer;padding:4px 8px}',
      '.msie-stage{flex:1;position:relative;background:#000;display:flex;align-items:center;justify-content:center;overflow:hidden;min-height:180px;max-height:48vh;touch-action:none}',
      '.msie-canvas-wrap{position:relative;line-height:0}',
      '.msie-canvas{display:block}',
      '.msie-crop-box{position:absolute;border:2px solid #f97316;box-shadow:0 0 0 9999px rgba(0,0,0,.5);touch-action:none}',
      '.msie-row{padding:8px 10px;display:flex;gap:6px;justify-content:center;flex-wrap:wrap;border-top:1px solid #333}',
      '.msie-btn{background:#2a2a2a;color:#fff;border:1px solid #444;border-radius:9px;padding:8px 12px;font-size:13px;cursor:pointer;min-height:38px}',
      '.msie-btn.active{background:#f97316;border-color:#f97316}',
      '.msie-hint{font-size:11px;color:#999;text-align:center;padding:4px}',
      '.msie-foot{padding:10px;display:flex;gap:8px;border-top:1px solid #333}',
      '.msie-foot button{flex:1;padding:12px;border:0;border-radius:9px;font-size:15px;font-weight:600;cursor:pointer;min-height:46px}',
      '.msie-save{background:#16a34a;color:#fff}',
      '.msie-cancel{background:#3a3a3a;color:#fff}'
    ].join('');
    document.head.appendChild(s);
  }

  function loadImage(file) {
    return new Promise(function (res, rej) {
      var r = new FileReader();
      r.onload = function (e) {
        var img = new Image();
        img.onload = function () { res(img); };
        img.onerror = rej;
        img.src = e.target.result;
      };
      r.onerror = rej;
      r.readAsDataURL(file);
    });
  }

  function open(file, onDone) {
    injectStyles();
    loadImage(file).then(function (img) { showUI(img, file, onDone); })
      .catch(function () { alert('تعذر قراءة الصورة'); onDone(null); });
  }

  function showUI(img, file, onDone) {
    var state = { img: img, rotation: 0, flipH: false, flipV: false, ratio: 0, crop: { x: 0, y: 0, w: 1, h: 1 } };
    var overlay = document.createElement('div');
    overlay.className = 'msie-overlay';
    overlay.innerHTML = ''
      + '<div class="msie-box">'
      + '<div class="msie-head"><h3>✏️ قص وتحرير الصورة</h3><button class="msie-close">✖</button></div>'
      + '<div class="msie-stage"><div class="msie-canvas-wrap"><canvas class="msie-canvas"></canvas><div class="msie-crop-box"></div></div></div>'
      + '<div class="msie-row">'
      + '<button class="msie-btn active" data-ratio="0">📐 أصلي</button>'
      + '<button class="msie-btn" data-ratio="1">⬛ مربع</button>'
      + '<button class="msie-btn" data-ratio="1.7777">🖥️ عريض</button>'
      + '<button class="msie-btn" data-ratio="0.5625">📱 طويل</button>'
      + '<button class="msie-btn" data-ratio="1.3333">🖼️ 4:3</button>'
      + '</div>'
      + '<div class="msie-row">'
      + '<button class="msie-btn" data-act="rot-l">↺ يسار</button>'
      + '<button class="msie-btn" data-act="rot-r">↻ يمين</button>'
      + '<button class="msie-btn" data-act="flip-h">↔ قلب</button>'
      + '<button class="msie-btn" data-act="flip-v">↕ قلب</button>'
      + '<button class="msie-btn" data-act="reset">⟲ إعادة</button>'
      + '</div>'
      + '<div class="msie-hint">اسحب المربع البرتقالي لتحريك منطقة القص</div>'
      + '<div class="msie-foot"><button class="msie-save">💾 حفظ</button><button class="msie-cancel">إلغاء</button></div>'
      + '</div>';
    document.body.appendChild(overlay);

    var canvas = overlay.querySelector('.msie-canvas');
    var cropBox = overlay.querySelector('.msie-crop-box');
    var stage = overlay.querySelector('.msie-stage');

    function render() {
      var rot = state.rotation;
      var dims = (rot === 90 || rot === 270) ? { w: img.naturalHeight, h: img.naturalWidth } : { w: img.naturalWidth, h: img.naturalHeight };
      var maxW = stage.clientWidth - 16, maxH = stage.clientHeight - 16;
      var scale = Math.min(maxW / dims.w, maxH / dims.h, 1);
      var dw = Math.round(dims.w * scale), dh = Math.round(dims.h * scale);
      canvas.width = dw; canvas.height = dh;
      canvas.style.width = dw + 'px'; canvas.style.height = dh + 'px';
      var ctx = canvas.getContext('2d');
      ctx.save();
      ctx.translate(dw / 2, dh / 2);
      ctx.rotate(rot * Math.PI / 180);
      ctx.scale(state.flipH ? -1 : 1, state.flipV ? -1 : 1);
      var rw = (rot === 90 || rot === 270) ? dh : dw;
      var rh = (rot === 90 || rot === 270) ? dw : dh;
      ctx.drawImage(img, -rw / 2, -rh / 2, rw, rh);
      ctx.restore();
      updateCropBox();
    }

    function updateCropBox() {
      var cw = canvas.clientWidth, ch = canvas.clientHeight;
      var c = state.crop;
      cropBox.style.left = (c.x * cw) + 'px';
      cropBox.style.top = (c.y * ch) + 'px';
      cropBox.style.width = (c.w * cw) + 'px';
      cropBox.style.height = (c.h * ch) + 'px';
    }

    function setRatio(r) {
      state.ratio = r;
      if (r === 0) { state.crop = { x: 0, y: 0, w: 1, h: 1 }; }
      else {
        var cw = canvas.clientWidth, ch = canvas.clientHeight;
        var cr = cw / ch, w, h, x, y;
        if (r > cr) { w = 1; h = cr / r; x = 0; y = (1 - h) / 2; }
        else { h = 1; w = r / cr; x = (1 - w) / 2; y = 0; }
        state.crop = { x: x, y: y, w: w, h: h };
      }
      updateCropBox();
      Array.prototype.forEach.call(overlay.querySelectorAll('.msie-btn[data-ratio]'), function (b) {
        b.classList.toggle('active', parseFloat(b.dataset.ratio) === r);
      });
    }

    var drag = null;
    function onStart(e) { e.preventDefault(); var t = e.touches ? e.touches[0] : e; drag = { sx: t.clientX, sy: t.clientY, cx: state.crop.x, cy: state.crop.y }; }
    function onMove(e) {
      if (!drag) return;
      var t = e.touches ? e.touches[0] : e;
      var cw = canvas.clientWidth, ch = canvas.clientHeight;
      var nx = drag.cx + (t.clientX - drag.sx) / cw;
      var ny = drag.cy + (t.clientY - drag.sy) / ch;
      nx = Math.max(0, Math.min(1 - state.crop.w, nx));
      ny = Math.max(0, Math.min(1 - state.crop.h, ny));
      state.crop.x = nx; state.crop.y = ny;
      updateCropBox();
    }
    function onEnd() { drag = null; }

    cropBox.addEventListener('touchstart', onStart, { passive: false });
    cropBox.addEventListener('mousedown', onStart);
    document.addEventListener('touchmove', onMove, { passive: false });
    document.addEventListener('mousemove', onMove);
    document.addEventListener('touchend', onEnd);
    document.addEventListener('mouseup', onEnd);

    function cleanup() {
      document.removeEventListener('touchmove', onMove);
      document.removeEventListener('mousemove', onMove);
      document.removeEventListener('touchend', onEnd);
      document.removeEventListener('mouseup', onEnd);
      document.removeEventListener('keydown', onKey);
      overlay.remove();
    }
    function cancel() { cleanup(); onDone(null); }
    function onKey(e) { if (e.key === 'Escape') cancel(); }
    document.addEventListener('keydown', onKey);

    function save() {
      var rot = state.rotation;
      var sw = img.naturalWidth, sh = img.naturalHeight;
      var tw = (rot === 90 || rot === 270) ? sh : sw;
      var th = (rot === 90 || rot === 270) ? sw : sh;
      var tmp = document.createElement('canvas');
      tmp.width = tw; tmp.height = th;
      var tc = tmp.getContext('2d');
      tc.save();
      tc.translate(tw / 2, th / 2);
      tc.rotate(rot * Math.PI / 180);
      tc.scale(state.flipH ? -1 : 1, state.flipV ? -1 : 1);
      tc.drawImage(img, -sw / 2, -sh / 2, sw, sh);
      tc.restore();
      var c = state.crop;
      var cx = Math.round(c.x * tw), cy = Math.round(c.y * th);
      var cw = Math.round(c.w * tw), ch = Math.round(c.h * th);
      var sc = Math.min(1, MAX_DIM / Math.max(cw, ch));
      var fw = Math.round(cw * sc), fh = Math.round(ch * sc);
      var out = document.createElement('canvas');
      out.width = fw; out.height = fh;
      out.getContext('2d').drawImage(tmp, cx, cy, cw, ch, 0, 0, fw, fh);
      out.toBlob(function (b) {
        if (!b) { alert('تعذر الحفظ'); return; }
        var nm = (file.name || 'image').replace(/\.[^.]+$/, '') + '.jpg';
        var f = new File([b], nm, { type: 'image/jpeg' });
        cleanup(); onDone(f);
      }, 'image/jpeg', JPEG_Q);
    }

    overlay.querySelector('.msie-close').onclick = cancel;
    overlay.querySelector('.msie-cancel').onclick = cancel;
    overlay.querySelector('.msie-save').onclick = save;
    Array.prototype.forEach.call(overlay.querySelectorAll('.msie-btn[data-ratio]'), function (b) {
      b.onclick = function () { setRatio(parseFloat(b.dataset.ratio)); };
    });
    Array.prototype.forEach.call(overlay.querySelectorAll('.msie-btn[data-act]'), function (b) {
      b.onclick = function () {
        var a = b.dataset.act;
        if (a === 'rot-l') state.rotation = (state.rotation + 270) % 360;
        else if (a === 'rot-r') state.rotation = (state.rotation + 90) % 360;
        else if (a === 'flip-h') state.flipH = !state.flipH;
        else if (a === 'flip-v') state.flipV = !state.flipV;
        else if (a === 'reset') { state.rotation = 0; state.flipH = false; state.flipV = false; setRatio(state.ratio); }
        render();
      };
    });
    requestAnimationFrame(function () { render(); setRatio(0); });
  }
  window.msOpenImageEditor = open;
})();
