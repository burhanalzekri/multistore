@extends('layouts.app')
@section('title', 'الإعدادات')
@section('page-title', 'الإعدادات')
@section('page-subtitle', 'إعدادات متجرك')

@section('content')

@if($errors->any())
<div style="background:#fee2e2;color:#b91c1c;padding:14px 18px;border-radius:12px;margin-bottom:20px;font-weight:700;font-size:14px;">
  @foreach($errors->all() as $e) <div>• {{ $e }}</div> @endforeach
</div>
@endif

<div class="st-wrap">

  {{-- 🗂️ شريط التبويبات --}}
  <div class="st-tabs-bar">
    <button type="button" class="st-tab active" data-tab="shop" onclick="switchTab('shop')">
      <span class="st-tab-icon">🏪</span>
      <span class="st-tab-label">المتجر</span>
    </button>
    <button type="button" class="st-tab" data-tab="webhook" onclick="switchTab('webhook')">
      <span class="st-tab-icon">🔗</span>
      <span class="st-tab-label">Webhook</span>
    </button>
    <button type="button" class="st-tab" data-tab="appearance" onclick="switchTab('appearance')">
      <span class="st-tab-icon">🎨</span>
      <span class="st-tab-label">المظهر</span>
    </button>
    <button type="button" class="st-tab" data-tab="smtp" onclick="switchTab('smtp')">
      <span class="st-tab-icon">📧</span>
      <span class="st-tab-label">SMTP</span>
    </button>
  </div>

  {{-- ═══════════════ التبويب 1: المتجر ═══════════════ --}}
  <div class="st-pane active" data-pane="shop">
    <form method="POST" action="/dashboard/settings" class="shine-card">
      @csrf
      <div class="shine-card-header">
        <div class="shine-card-title">
          <i data-lucide="store"></i> بيانات المتجر
        </div>
      </div>
      <div style="padding:22px;display:flex;flex-direction:column;gap:16px;">
        <div>
          <label style="display:block;font-size:13px;font-weight:800;margin-bottom:8px;color:var(--text);">اسم المتجر *</label>
          <input type="text" name="name" value="{{ old('name', $shop->name) }}" required
            style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-size:14px;background:var(--surface);color:var(--text);outline:none;font-weight:600;">
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div>
            <label style="display:block;font-size:13px;font-weight:800;margin-bottom:8px;color:var(--text);">رقم الهاتف</label>
            <input type="text" name="phone" value="{{ old('phone', $shop->phone) }}"
              style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-size:14px;background:var(--surface);color:var(--text);outline:none;">
          </div>
          <div>
            <label style="display:block;font-size:13px;font-weight:800;margin-bottom:8px;color:var(--text);">واتساب</label>
            <input type="text" name="whatsapp" value="{{ old('whatsapp', $shop->whatsapp) }}"
              style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-size:14px;background:var(--surface);color:var(--text);outline:none;">
          </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
          <div>
            <label style="display:block;font-size:13px;font-weight:800;margin-bottom:8px;color:var(--text);">اللون الرئيسي</label>
            <input type="color" name="primary_color" value="{{ old('primary_color', $shop->primary_color ?? '#f59e0b') }}"
              style="width:100%;height:48px;border:1.5px solid var(--border);border-radius:10px;padding:4px;background:var(--surface);cursor:pointer;">
          </div>
          <div>
            <label style="display:block;font-size:13px;font-weight:800;margin-bottom:8px;color:var(--text);">العملة</label>
            <select name="currency" style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-size:14px;background:var(--surface);color:var(--text);outline:none;font-weight:700;">
              <option value="YER" {{ $shop->currency==='YER'?'selected':'' }}>ريال يمني (YER)</option>
              <option value="SAR" {{ $shop->currency==='SAR'?'selected':'' }}>ريال سعودي (SAR)</option>
              <option value="USD" {{ $shop->currency==='USD'?'selected':'' }}>دولار (USD)</option>
            </select>
          </div>
        </div>

        {{-- 🏷️ تسميات Variants --}}
        <div style="margin-top:8px;padding-top:20px;border-top:1px dashed #e2e8f0;">
          <h3 style="font-size:14px;font-weight:900;color:#334155;margin-bottom:6px;display:flex;align-items:center;gap:8px;">
            <i data-lucide="tag" style="width:18px;height:18px;"></i>
            🏷️ تسميات الخيارات المتعددة (Variants)
          </h3>
          <p style="font-size:12px;color:#64748b;margin-bottom:14px;line-height:1.6;">
            خصّص تسميات قسمي الخيارات حسب نوع منتجاتك — مثلاً: <strong>عسل → النوع + الوزن</strong> أو <strong>ملابس → اللون + المقاس</strong>.
          </p>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <div>
              <label style="font-size:12px;font-weight:800;color:#475569;display:block;margin-bottom:6px;">
                القسم الأول (اللون / النوع)
              </label>
              <div style="display:flex;gap:8px;">
                <input type="text" name="variant_icon_1"
                  value="{{ $shop->settings['variant_icon_1'] ?? '🎨' }}"
                  maxlength="4" placeholder="🎨"
                  style="width:64px;text-align:center;font-size:22px;padding:8px;border:1.5px solid #e2e8f0;border-radius:10px;background:#fff;font-family:inherit;">
                <input type="text" name="variant_label_1"
                  value="{{ $shop->settings['variant_label_1'] ?? 'اللون' }}"
                  maxlength="50" placeholder="اللون"
                  style="flex:1;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;background:#fff;font-weight:700;font-family:inherit;">
              </div>
            </div>

            <div>
              <label style="font-size:12px;font-weight:800;color:#475569;display:block;margin-bottom:6px;">
                القسم الثاني (المقاس / الوزن)
              </label>
              <div style="display:flex;gap:8px;">
                <input type="text" name="variant_icon_2"
                  value="{{ $shop->settings['variant_icon_2'] ?? '📏' }}"
                  maxlength="4" placeholder="📏"
                  style="width:64px;text-align:center;font-size:22px;padding:8px;border:1.5px solid #e2e8f0;border-radius:10px;background:#fff;font-family:inherit;">
                <input type="text" name="variant_label_2"
                  value="{{ $shop->settings['variant_label_2'] ?? 'المقاس' }}"
                  maxlength="50" placeholder="المقاس"
                  style="flex:1;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;background:#fff;font-weight:700;font-family:inherit;">
              </div>
            </div>
          </div>

          <div style="margin-top:12px;padding:12px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;font-size:12px;color:#475569;line-height:1.9;">
            <strong>💡 أمثلة:</strong><br>
            🍯 عسل → <em>النوع</em> + <em>الوزن</em> &nbsp;·&nbsp;
            👕 ملابس → <em>اللون</em> + <em>المقاس</em> &nbsp;·&nbsp;
            📱 إلكترونيات → <em>اللون</em> + <em>الذاكرة</em>
          </div>
        </div>

        <button type="submit" class="shine-btn shine-btn-primary" style="justify-content:center;padding:14px;">
          <i data-lucide="save"></i> حفظ إعدادات المتجر
        </button>
      </div>
    </form>
  </div>

  {{-- ═══════════════ التبويب 2: Webhook ═══════════════ --}}
  <div class="st-pane" data-pane="webhook">
    <div class="shine-card">
      <div class="shine-card-header">
        <div class="shine-card-title">
          <i data-lucide="link"></i> Webhook للتطبيق
        </div>
      </div>
      <div style="padding:22px;display:flex;flex-direction:column;gap:16px;">
        <p style="margin:0;font-size:13px;color:var(--text-muted);">
          استخدم هذه البيانات في تطبيق SMS Forwarder
        </p>

        <div>
          <label style="display:block;font-size:13px;font-weight:800;margin-bottom:8px;color:var(--text);">Webhook URL</label>
          <input type="text" readonly value="{{ request()->getSchemeAndHttpHost() . '/webhooks/sms/' . $shop->webhook_token }}"
            onclick="this.select()"
            style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;font-family:monospace;font-size:11px;background:#f8fafc;color:var(--text);outline:none;">
        </div>

        <div>
          <label style="display:block;font-size:13px;font-weight:800;margin-bottom:8px;color:var(--text);">Token</label>
          <input type="text" readonly value="{{ $shop->webhook_token }}" onclick="this.select()"
            style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;font-family:monospace;font-size:11px;background:#f8fafc;color:var(--text);outline:none;">
        </div>

        <div style="background:#dbeafe;color:#1d4ed8;padding:12px 16px;border-radius:10px;font-size:12px;display:flex;gap:8px;font-weight:700;">
          <span>💡</span>
          <span>انسخ الرابط والصقه في تطبيق SMS Forwarder. سيرسل كل رسالة واردة تلقائياً للنظام.</span>
        </div>
      </div>
    </div>
  </div>

  {{-- ═══════════════ التبويب 3: المظهر ═══════════════ --}}
  <div class="st-pane" data-pane="appearance">
    <div class="shine-card">
      <div class="shine-card-header">
        <div class="shine-card-title">
          <i data-lucide="palette"></i> إعدادات المظهر
        </div>
      </div>
      <div style="padding:22px;">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;">
          <div>
            <div style="font-weight:800;font-size:14px;color:var(--text);margin-bottom:4px;">الوضع الليلي</div>
            <div style="font-size:12px;color:var(--text-muted);">تبديل بين النهاري والليلي</div>
          </div>
          <button type="button" class="shine-theme-toggle" onclick="toggleTheme()">
            <span class="shine-theme-thumb" id="settingsThemeThumb">{{ (session('theme') ?? 'light') === 'dark' ? '🌙' : '☀️' }}</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- ═══════════════ التبويب 4: SMTP ═══════════════ --}}
  <div class="st-pane" data-pane="smtp">
    @include("dashboard.settings._smtp")
  </div>

</div>

<style>
  .st-wrap { max-width: 680px; margin: 0 auto; }

  /* Tabs bar */
  .st-tabs-bar {
    position: sticky; top: 8px; z-index: 30;
    display: flex; gap: 6px; padding: 6px;
    background: rgba(255,255,255,0.92);
    backdrop-filter: blur(12px);
    border: 1px solid var(--border, #e2e8f0);
    border-radius: 14px;
    margin-bottom: 18px;
    overflow-x: auto;
    scrollbar-width: none;
    box-shadow: 0 4px 20px rgba(15,23,42,0.04);
  }
  .st-tabs-bar::-webkit-scrollbar { display: none; }

  .st-tab {
    flex: 1; min-width: 78px;
    display: flex; flex-direction: column; align-items: center; gap: 2px;
    padding: 8px 12px;
    background: transparent;
    border: 0;
    border-radius: 10px;
    font-family: inherit; font-weight: 800; font-size: 12px;
    color: #64748b;
    cursor: pointer;
    transition: .2s;
    white-space: nowrap;
  }
  .st-tab:hover { background: #f1f5f9; color: #334155; }
  .st-tab.active {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
    box-shadow: 0 6px 16px rgba(217,119,6,.25);
  }
  .st-tab-icon { font-size: 18px; line-height: 1; }
  .st-tab-label { font-size: 11px; font-weight: 900; }

  /* Panes */
  .st-pane { display: none; animation: stFade .25s ease; }
  .st-pane.active { display: block; }
  @keyframes stFade {
    from { opacity: 0; transform: translateY(6px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  @media(max-width: 480px) {
    .st-tab { min-width: 66px; padding: 6px 8px; }
    .st-tab-icon { font-size: 16px; }
    .st-tab-label { font-size: 10px; }
    .st-tabs-bar { top: 4px; padding: 4px; gap: 4px; }
  }
</style>

<script>
// ═══ 🗂️ نظام التبويبات ═══
window.switchTab = function(name) {
  // تحديث الأزرار
  document.querySelectorAll('.st-tab').forEach(function(b) {
    b.classList.toggle('active', b.dataset.tab === name);
  });
  // تحديث المحتوى
  document.querySelectorAll('.st-pane').forEach(function(p) {
    p.classList.toggle('active', p.dataset.pane === name);
  });
  // تحديث URL hash
  try {
    if (history.replaceState) history.replaceState(null, '', '#' + name);
  } catch(e) {}
};

// استرجاع التبويب من URL عند التحميل
document.addEventListener('DOMContentLoaded', function() {
  var hash = (location.hash || '').replace('#', '');
  var valid = ['shop', 'webhook', 'appearance', 'smtp'];
  if (hash && valid.indexOf(hash) >= 0) {
    window.switchTab(hash);
  }
});

// عند تغيير الـ hash يدوياً
window.addEventListener('hashchange', function() {
  var hash = (location.hash || '').replace('#', '');
  if (hash) window.switchTab(hash);
});
</script>

<script>
if (document.documentElement.classList.contains('dark')) {
  const t = document.getElementById('settingsThemeThumb');
  if (t) t.textContent = '🌙';
}
</script>

{{-- 🔍 التحقق الفوري + تنبيهات --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('form').forEach(function(form) {
    if (form.dataset.validationReady) return;
    form.dataset.validationReady = '1';
    form.setAttribute('novalidate', 'novalidate');

    form.querySelectorAll('input, textarea, select').forEach(function(field) {
      field.addEventListener('input', function() {
        this.style.borderColor = '';
        this.style.background = '';
      });
    });

    form.addEventListener('submit', function(e) {
      let errors = [];
      let firstInvalid = null;

      form.querySelectorAll('[required]').forEach(function(field) {
        field.style.borderColor = '';
        field.style.background = '';

        if (!field.value.trim()) {
          let label = field.name;
          let labelEl = field.closest('div')?.querySelector('label');
          if (labelEl) {
            label = labelEl.textContent.replace('*', '').replace('مطلوب', '').trim();
          }
          errors.push('📌 ' + label + ' مطلوب');
          field.style.borderColor = '#ef4444';
          field.style.background = '#fef2f2';
          if (!firstInvalid) firstInvalid = field;
        }
      });

      if (errors.length > 0) {
        e.preventDefault();
        e.stopPropagation();
        errors.forEach(function(err, i) {
          setTimeout(function() {
            if (window.showToast) window.showToast(err, 'error', 5000);
            else alert(err);
          }, i * 200);
        });
        if (firstInvalid) {
          firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
          setTimeout(function() { firstInvalid.focus(); }, 300);
        }
        return false;
      }
    });
  });

  @if($errors->any())
    @foreach($errors->all() as $error)
      setTimeout(function() {
        if (window.showToast) window.showToast("{{ addslashes($error) }}", 'error', 6000);
      }, {{ $loop->index * 200 }});
    @endforeach
  @endif

  @if(session('success'))
    setTimeout(function() {
      if (window.showToast) window.showToast("{{ addslashes(session('success')) }}", 'success', 5000);
    }, 300);
  @endif

  @if(session('error'))
    setTimeout(function() {
      if (window.showToast) window.showToast("{{ addslashes(session('error')) }}", 'error', 6000);
    }, 300);
  @endif
});
</script>

@endsection
