@extends('layouts.app')
@section('title', 'ماسح الباركوود')
@section('page-title', '🔍 ماسح الباركوود')
@section('page-subtitle', 'امسح الباركوود أو أدخله يدوياً')

@section('content')

<!-- JsBarcode + Html5-QRCode -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<div style="max-width:900px;margin:0 auto;">

  {{-- 🔍 حقل الإدخال السريع --}}
  <div style="background:linear-gradient(135deg,#fef3c7,#fed7aa);border-radius:20px;padding:24px;margin-bottom:20px;border:1.5px solid #fcd34d;">
    <label style="font-size:14px;font-weight:900;color:#78350f;display:block;margin-bottom:10px;">🔍 امسح الباركوود أو اكتبه يدوياً</label>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
      <input type="text" id="barcodeInput" autofocus autocomplete="off"
             placeholder="امسح الباركوود هنا..."
             style="flex:1;min-width:220px;padding:16px;border:2px solid #f59e0b;border-radius:14px;background:#fff;font-size:16px;font-weight:900;font-family:monospace;outline:none;text-align:center;letter-spacing:1px;">
      <button type="button" onclick="startCamera()"
              style="padding:16px 24px;background:#17202b;color:#fff;border:0;border-radius:14px;font-size:14px;font-weight:900;cursor:pointer;font-family:inherit;display:flex;align-items:center;gap:8px;">
        📷 فتح الكاميرا
      </button>
      <button type="button" onclick="lookupBarcode()"
              style="padding:16px 24px;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;border:0;border-radius:14px;font-size:14px;font-weight:900;cursor:pointer;font-family:inherit;">
        🔍 بحث
      </button>
    </div>
    <div style="font-size:11px;color:#92400e;margin-top:8px;font-weight:700;">
      💡 يدعم القارئ الخارجي USB / Bluetooth — فقط صوّب وامسح
    </div>
  </div>

  {{-- 📷 منطقة الكاميرا --}}
  <div id="cameraWrap" style="display:none;margin-bottom:20px;">
    <div style="background:#000;border-radius:20px;padding:14px;position:relative;">
      <div id="reader" style="border-radius:14px;overflow:hidden;"></div>
      <button type="button" onclick="stopCamera()"
              style="position:absolute;top:22px;left:22px;background:#dc2626;color:#fff;border:0;padding:10px 18px;border-radius:10px;font-size:13px;font-weight:900;cursor:pointer;font-family:inherit;z-index:10;">
        ✕ إغلاق
      </button>
    </div>
  </div>

  {{-- 📊 نتيجة البحث --}}
  <div id="resultCard" style="display:none;background:#fff;border-radius:20px;padding:22px;box-shadow:0 10px 30px rgba(0,0,0,.08);border:1.5px solid #e9edf2;">
    <!-- filled dynamically -->
  </div>

  {{-- ❌ رسالة خطأ --}}
  <div id="errorBox" style="display:none;background:#fef2f2;border:1.5px solid #fecaca;border-radius:14px;padding:16px;color:#b91c1c;font-weight:800;text-align:center;font-size:14px;"></div>

</div>

<script>
  var currentVariant = null;

  // ═══ Keyboard Wedge (USB/Bluetooth Scanner) ═══
  document.addEventListener('DOMContentLoaded', function() {
    var input = document.getElementById('barcodeInput');
    input.focus();
    
    input.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        lookupBarcode();
      }
    });
    
    // إعادة التركيز تلقائياً
    document.addEventListener('click', function(e) {
      if (e.target.tagName !== 'BUTTON' && e.target.tagName !== 'INPUT' && e.target.tagName !== 'A') {
        setTimeout(function() { input.focus(); }, 100);
      }
    });
  });

  // ═══ البحث ═══
  function lookupBarcode() {
    var input = document.getElementById('barcodeInput');
    var code = input.value.trim();
    if (!code) return;
    
    showError(null);
    document.getElementById('resultCard').style.display = 'none';
    
    fetch('/dashboard/api/barcode/lookup?code=' + encodeURIComponent(code), {
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json',
      },
    })
    .then(r => r.json())
    .then(data => {
      if (data.ok) {
        currentVariant = data.variant;
        renderResult(data.variant);
        beep(880, 120);
      } else {
        showError(data.message || 'لم يُعثر على المنتج');
        beep(200, 200);
      }
    })
    .catch(e => showError('خطأ في الاتصال'));
    
    input.value = '';
    input.focus();
  }

  // ═══ عرض النتيجة ═══
  function renderResult(v) {
    var html = '';
    html += '<div style="display:flex;gap:16px;align-items:flex-start;flex-wrap:wrap;">';
    
    // صورة
    html += '<div style="width:100px;height:100px;background:#f5f3ee;border-radius:16px;overflow:hidden;flex-shrink:0;display:grid;place-items:center;">';
    if (v.product.image) {
      var isExt = v.product.image.startsWith('http');
      var src = isExt ? v.product.image : '/storage/' + v.product.image;
      html += '<img src="' + src + '" style="width:100%;height:100%;object-fit:cover;">';
    } else {
      html += '📦';
    }
    html += '</div>';
    
    // المعلومات
    html += '<div style="flex:1;min-width:220px;">';
    html += '<h3 style="font-size:18px;font-weight:900;margin:0 0 8px;">' + v.product.name + '</h3>';
    html += '<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px;">';
    if (v.color) {
      html += '<span style="display:inline-flex;align-items:center;gap:5px;background:#f1f5f9;padding:5px 12px;border-radius:8px;font-size:12px;font-weight:800;">';
      if (v.color_hex) html += '<span style="width:14px;height:14px;border-radius:50%;background:' + v.color_hex + ';"></span>';
      html += v.color + '</span>';
    }
    if (v.size) html += '<span style="background:#f1f5f9;padding:5px 12px;border-radius:8px;font-size:12px;font-weight:800;">📏 ' + v.size + '</span>';
    html += '</div>';
    html += '<div style="font-family:monospace;font-size:12px;color:#94a3b8;font-weight:800;">SKU: ' + (v.sku || '—') + '</div>';
    html += '</div>';
    html += '</div>';
    
    // الكمية
    html += '<div style="margin-top:20px;padding-top:20px;border-top:1px solid #f0eeea;">';
    html += '<label style="font-size:13px;font-weight:900;color:#475569;display:block;margin-bottom:10px;">📦 المخزون الحالي</label>';
    html += '<div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">';
    html += '<button type="button" onclick="adjustStock(-1)" style="width:50px;height:50px;background:#f1f5f9;border:0;border-radius:12px;font-size:24px;font-weight:900;cursor:pointer;font-family:inherit;">−</button>';
    html += '<input type="number" id="stockInput" value="' + v.stock + '" min="0" style="width:100px;padding:14px;border:2px solid #f59e0b;border-radius:12px;font-size:20px;font-weight:900;text-align:center;font-family:inherit;">';
    html += '<button type="button" onclick="adjustStock(1)" style="width:50px;height:50px;background:#f1f5f9;border:0;border-radius:12px;font-size:24px;font-weight:900;cursor:pointer;font-family:inherit;">+</button>';
    html += '<button type="button" onclick="saveStock()" style="padding:14px 24px;background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;border:0;border-radius:12px;font-size:14px;font-weight:900;cursor:pointer;font-family:inherit;">💾 حفظ</button>';
    html += '<a href="' + v.product.url + '" style="padding:14px 24px;background:#17202b;color:#fff;border-radius:12px;font-size:14px;font-weight:900;text-decoration:none;">✏️ تعديل المنتج</a>';
    html += '</div>';
    html += '</div>';
    
    // الباركوود
    html += '<div style="margin-top:20px;padding-top:20px;border-top:1px solid #f0eeea;text-align:center;">';
    html += '<svg id="barcodeSvg"></svg>';
    html += '<div style="font-family:monospace;font-size:13px;font-weight:900;margin-top:6px;color:#475569;">' + v.barcode + '</div>';
    html += '</div>';
    
    document.getElementById('resultCard').innerHTML = html;
    document.getElementById('resultCard').style.display = 'block';
    
    // ارسم الباركوود
    if (v.barcode) {
      try {
        JsBarcode('#barcodeSvg', v.barcode, {
          format: 'CODE128',
          width: 2,
          height: 60,
          displayValue: false,
          margin: 0,
        });
      } catch(e) {}
    }
  }

  // ═══ تعديل الكمية ═══
  function adjustStock(delta) {
    var input = document.getElementById('stockInput');
    var val = parseInt(input.value) || 0;
    input.value = Math.max(0, val + delta);
  }

  function saveStock() {
    if (!currentVariant) return;
    var stock = parseInt(document.getElementById('stockInput').value) || 0;
    
    var btn = document.querySelector('[onclick="saveStock()"]');
    if (btn) {
      btn.disabled = true;
      btn.textContent = '⏳ جاري...';
    }
    
    fetch('/dashboard/api/barcode/quick-update', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json',
      },
      body: JSON.stringify({ variant_id: currentVariant.id, stock: stock }),
    })
    .then(r => r.json())
    .then(data => {
      if (btn) {
        btn.disabled = false;
        btn.innerHTML = '💾 حفظ';
      }
      if (data.ok) {
        beep(1200, 100);
        currentVariant.stock = data.new_stock;
        showNotification('✅ تم تحديث المخزون بنجاح', 'تم حفظ ' + data.new_stock + ' قطعة • إجمالي المنتج: ' + data.total, 'success');
      } else {
        showNotification('❌ فشل التحديث', data.message || 'خطأ غير معروف', 'error');
      }
    })
    .catch(e => {
      if (btn) { btn.disabled = false; btn.innerHTML = '💾 حفظ'; }
      showNotification('❌ خطأ', 'تعذر الاتصال بالسيرفر', 'error');
    });
  }

  // ═══ نافذة مخصصة ═══
  function showNotification(title, message, type) {
    type = type || 'success';
    var existing = document.getElementById('msNotification');
    if (existing) existing.remove();
    
    var colors = {
      success: { bg: 'linear-gradient(135deg,#16a34a,#15803d)', icon: '✅' },
      error: { bg: 'linear-gradient(135deg,#dc2626,#991b1b)', icon: '❌' },
      warning: { bg: 'linear-gradient(135deg,#f59e0b,#d97706)', icon: '⚠️' },
      info: { bg: 'linear-gradient(135deg,#3b82f6,#1d4ed8)', icon: 'ℹ️' },
    };
    var c = colors[type] || colors.success;
    
    var html = '<div id="msNotification" style="position:fixed;inset:0;background:rgba(15,23,42,.55);backdrop-filter:blur(4px);z-index:99999;display:flex;align-items:center;justify-content:center;padding:20px;opacity:0;transition:opacity .25s;">';
    html += '  <div style="background:#fff;border-radius:24px;padding:36px 32px;max-width:420px;width:100%;text-align:center;box-shadow:0 25px 80px rgba(0,0,0,.35);transform:scale(.85);transition:transform .3s cubic-bezier(.34,1.56,.64,1);" id="msNotificationBox">';
    html += '    <div style="width:80px;height:80px;background:' + c.bg + ';border-radius:50%;margin:0 auto 20px;display:grid;place-items:center;font-size:42px;box-shadow:0 12px 30px rgba(0,0,0,.15);">' + c.icon + '</div>';
    html += '    <h3 style="font-size:20px;font-weight:900;color:#17202b;margin:0 0 8px;">' + title + '</h3>';
    html += '    <p style="font-size:14px;color:#64748b;line-height:1.7;margin:0 0 24px;">' + message + '</p>';
    html += '    <button type="button" onclick="closeNotification()" style="padding:14px 40px;background:' + c.bg + ';color:#fff;border:0;border-radius:14px;font-size:15px;font-weight:900;cursor:pointer;font-family:inherit;box-shadow:0 8px 22px rgba(0,0,0,.15);">حسناً</button>';
    html += '  </div>';
    html += '</div>';
    
    document.body.insertAdjacentHTML('beforeend', html);
    
    setTimeout(function() {
      var el = document.getElementById('msNotification');
      var box = document.getElementById('msNotificationBox');
      if (el) el.style.opacity = '1';
      if (box) box.style.transform = 'scale(1)';
    }, 50);
  }

  function closeNotification() {
    var el = document.getElementById('msNotification');
    if (!el) return;
    var box = document.getElementById('msNotificationBox');
    if (box) box.style.transform = 'scale(.85)';
    el.style.opacity = '0';
    setTimeout(function() { el.remove(); }, 250);
  }

  // ESC للإغلاق
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeNotification();
  });

  // نقرة خارج البطاقة
  document.addEventListener('click', function(e) {
    var el = document.getElementById('msNotification');
    if (el && e.target === el) closeNotification();
  });

  // ═══ الكاميرا ═══
  var html5QrCode = null;
  function startCamera() {
    document.getElementById('cameraWrap').style.display = 'block';
    document.getElementById('barcodeInput').blur();
    
    if (!html5QrCode) {
      html5QrCode = new Html5Qrcode('reader');
    }
    
    html5QrCode.start(
      { facingMode: 'environment' },
      { fps: 10, qrbox: { width: 280, height: 180 } },
      function(decodedText) {
        document.getElementById('barcodeInput').value = decodedText;
        stopCamera();
        setTimeout(lookupBarcode, 200);
      },
      function(errorMessage) { /* ignore */ }
    ).catch(function(err) {
      alert('تعذر فتح الكاميرا: ' + err);
      document.getElementById('cameraWrap').style.display = 'none';
    });
  }

  function stopCamera() {
    if (html5QrCode) {
      html5QrCode.stop().then(function() {
        document.getElementById('cameraWrap').style.display = 'none';
        document.getElementById('barcodeInput').focus();
      }).catch(function() {
        document.getElementById('cameraWrap').style.display = 'none';
      });
    } else {
      document.getElementById('cameraWrap').style.display = 'none';
    }
  }

  // ═══ صوت ═══
  function beep(freq, duration) {
    try {
      var ctx = new (window.AudioContext || window.webkitAudioContext)();
      var osc = ctx.createOscillator();
      var gain = ctx.createGain();
      osc.connect(gain);
      gain.connect(ctx.destination);
      osc.frequency.value = freq;
      gain.gain.value = 0.15;
      osc.start();
      setTimeout(function() { osc.stop(); ctx.close(); }, duration);
    } catch(e) {}
  }

  function showError(msg) {
    var el = document.getElementById('errorBox');
    if (msg) {
      el.textContent = '❌ ' + msg;
      el.style.display = 'block';
    } else {
      el.style.display = 'none';
    }
  }
</script>

@endsection
