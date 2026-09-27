<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>حساب جديد — {{ \App\Models\Shop::first()->name ?? 'المتجر' }}</title>
<script>(function(){const t=localStorage.getItem('theme')||'light';if(t==='dark')document.documentElement.classList.add('dark');})();</script>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { darkMode: 'class' };</script>
<script src="https://unpkg.com/lucide@latest"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<link rel="manifest" href="/manifest.json">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    font-family: 'Cairo', sans-serif;
    min-height: 100vh;
    background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 50%, #fbbf24 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 30px 20px;
    position: relative;
    overflow-x: hidden;
  }
  html.dark body {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
  }
  body::before,
  body::after {
    content: '';
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
  }
  body::before { top: -150px; right: -150px; width: 400px; height: 400px; background: rgba(251, 191, 36, 0.25); }
  body::after { bottom: -150px; left: -150px; width: 350px; height: 350px; background: rgba(249, 115, 22, 0.25); }

  .wrap {
    width: 100%;
    max-width: 500px;
    position: relative;
    z-index: 2;
    animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
  }
  @keyframes slideUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .brand {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin-bottom: 24px;
  }
  .brand-logo {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #fbbf24, #f97316);
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    box-shadow: 0 8px 24px rgba(245, 158, 11, 0.4);
  }
  .brand-text h1 { font-size: 24px; font-weight: 900; color: #b45309; line-height: 1; }
  .brand-text p { font-size: 12px; color: #92400e; margin-top: 4px; }
  html.dark .brand-text h1 { color: #fbbf24; }
  html.dark .brand-text p { color: #cbd5e1; }

  .card {
    background: white;
    border-radius: 24px;
    padding: 32px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
  }
  html.dark .card { background: #1e293b; }
  .card h2 { font-size: 22px; font-weight: 900; color: #1f2937; text-align: center; margin-bottom: 6px; }
  html.dark .card h2 { color: #f1f5f9; }
  .card .subtitle { font-size: 13px; color: #6b7280; text-align: center; margin-bottom: 8px; }
  html.dark .card .subtitle { color: #94a3b8; }

  .badge-wrap { text-align: center; margin-bottom: 22px; }
  .badge {
    display: inline-block;
    background: #d1fae5;
    color: #065f46;
    padding: 5px 14px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 900;
  }

  .error-box {
    background: #fee2e2;
    color: #991b1b;
    padding: 12px 16px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-size: 13px;
    font-weight: 700;
  }

  .field { margin-bottom: 16px; }
  .field label {
    display: block;
    font-size: 13px;
    font-weight: 800;
    color: #374151;
    margin-bottom: 8px;
  }
  html.dark .field label { color: #cbd5e1; }
  .field-input { position: relative; }
  .field-input input {
    width: 100%;
    padding: 13px 44px 13px 16px;
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    font-family: inherit;
    font-size: 14px;
    font-weight: 600;
    background: white;
    color: #1f2937;
    outline: none;
    transition: all 0.2s;
  }
  html.dark .field-input input {
    background: #0f172a;
    border-color: #334155;
    color: #f1f5f9;
  }
  .field-input input:focus {
    border-color: #f59e0b;
    box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.15);
  }
  .field-icon {
    position: absolute;
    top: 50%;
    right: 14px;
    transform: translateY(-50%);
    color: #9ca3af;
    width: 18px;
    height: 18px;
    pointer-events: none;
  }

  .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

  .btn-submit {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, #fbbf24, #f97316);
    color: white;
    border: none;
    border-radius: 14px;
    font-family: inherit;
    font-size: 16px;
    font-weight: 900;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s;
    box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4);
    margin-top: 8px;
  }
  .btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(245, 158, 11, 0.55);
  }

  .links {
    text-align: center;
    font-size: 13px;
    color: #6b7280;
    margin-top: 20px;
    font-weight: 700;
  }
  html.dark .links { color: #94a3b8; }
  .links a { color: #f59e0b; text-decoration: none; font-weight: 900; }
  .links a:hover { text-decoration: underline; }

  .theme-toggle {
    position: fixed;
    top: 20px;
    left: 20px;
    width: 48px;
    height: 28px;
    background: rgba(255,255,255,0.6);
    border-radius: 999px;
    cursor: pointer;
    border: none;
    padding: 0;
    z-index: 10;
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }
  .theme-thumb {
    position: absolute;
    top: 3px;
    right: 3px;
    width: 22px;
    height: 22px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    transition: transform 0.4s cubic-bezier(0.68,-0.55,0.265,1.55);
  }
  html.dark .theme-thumb { transform: translateX(-20px); background: #fbbf24; }
</style>
</head>
<body>

<button class="theme-toggle" onclick="toggleTheme()">
  <span class="theme-thumb" id="themeThumb">☀️</span>
</button>

<div class="wrap">
  <div class="brand">
    <div class="brand-logo">🛍️</div>
    <div class="brand-text">
      <h1>حساب جديد</h1>
      <p>تجربة تسوق أسرع</p>
    </div>
  </div>

  <form method="POST" action="/account/register" class="card">
    @csrf
    <h2>إنشاء حساب</h2>
    <p class="subtitle">مجاني تمامًا — بدون رسوم</p>
    <div class="badge-wrap"><span class="badge">✨ تتبع طلباتك بسهولة</span></div>

    @if($errors->any())
    <div class="error-box">
      @foreach($errors->all() as $e) <div>• {{ $e }}</div> @endforeach
    </div>
    @endif

    <div class="field">
      <label>الاسم الكامل</label>
      <div class="field-input">
        <input type="text" name="name" value="{{ old('name') }}" required placeholder="مثال: أحمد محمد">
        <i data-lucide="user" class="field-icon"></i>
      </div>
    </div>

    <div class="field">
      <label>رقم الجوال</label>
      <div class="field-input">
        <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="7XXXXXXXX">
        <i data-lucide="phone" class="field-icon"></i>
      </div>
    </div>

    <div class="field">
      <label>البريد الإلكتروني</label>
      <div class="field-input">
        <input type="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com">
        <i data-lucide="mail" class="field-icon"></i>
      </div>
    </div>

    <div class="grid-2">
      <div class="field">
        <label>كلمة المرور</label>
        <div class="field-input">
          <input type="password" name="password" required minlength="6" placeholder="••••••">
          <i data-lucide="lock" class="field-icon"></i>
        </div>
      </div>
      <div class="field">
        <label>التأكيد</label>
        <div class="field-input">
          <input type="password" name="password_confirmation" required placeholder="••••••">
          <i data-lucide="lock" class="field-icon"></i>
        </div>
      </div>
    </div>

    <button type="submit" class="btn-submit">
      <i data-lucide="user-plus" style="width:20px;height:20px;"></i>
      إنشاء الحساب
    </button>

    <div class="links">
      لديك حساب؟ <a href="/account/login">سجّل الدخول</a>
    </div>
  </form>
</div>

<script>
function toggleTheme() {
  const isDark = document.documentElement.classList.toggle('dark');
  localStorage.setItem('theme', isDark ? 'dark' : 'light');
  document.getElementById('themeThumb').textContent = isDark ? '🌙' : '☀️';
}
if (document.documentElement.classList.contains('dark')) {
  document.getElementById('themeThumb').textContent = '🌙';
}
lucide.createIcons();
</script>


  {{-- 🔔 التنبيهات --}}
  @include('components.toast')

</body>
</html>

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

      form.querySelectorAll('input[type="number"]').forEach(function(field) {
        if (field.value && isNaN(parseFloat(field.value))) {
          errors.push('🔢 ' + (field.name === 'price' ? 'السعر' : field.name) + ' يجب أن يكون رقماً');
          field.style.borderColor = '#ef4444';
          if (!firstInvalid) firstInvalid = field;
        }
      });

      let email = form.querySelector('input[type="email"]');
      if (email && email.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
        errors.push('📧 البريد الإلكتروني غير صحيح');
        email.style.borderColor = '#ef4444';
        if (!firstInvalid) firstInvalid = email;
      }

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
