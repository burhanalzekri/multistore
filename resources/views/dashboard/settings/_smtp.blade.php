<div class="admin-card" style="padding:22px;margin-top:20px;">
  <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
    <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#3b82f6,#1d4ed8);display:flex;align-items:center;justify-content:center;font-size:20px;">📧</div>
    <div>
      <h2 style="font-size:16px;font-weight:900;color:var(--text);margin:0;">إعدادات البريد الإلكتروني</h2>
      <p style="font-size:12px;color:var(--text-muted);margin:2px 0 0 0;">البريد الذي تُرسل منه رسائل متجرك</p>
    </div>
  </div>

  @if(session('smtp_success'))
  <div style="background:#dcfce7;color:#15803d;padding:12px 16px;border-radius:12px;margin-bottom:16px;font-weight:800;font-size:13px;">
    ✅ {{ session('smtp_success') }}
  </div>
  @endif

  @if(session('smtp_error'))
  <div style="background:#fee2e2;color:#b91c1c;padding:12px 16px;border-radius:12px;margin-bottom:16px;font-weight:800;font-size:13px;">
    ❌ {{ session('smtp_error') }}
  </div>
  @endif

  <form method="POST" action="/dashboard/settings/smtp" style="display:flex;flex-direction:column;gap:14px;">
    @csrf

    <div>
      <label style="display:block;font-size:13px;font-weight:800;margin-bottom:6px;color:var(--text);">مزوّد البريد</label>
      <select name="provider" onchange="fillProvider(this.value)"
        style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;background:var(--surface);color:var(--text);font-family:inherit;font-weight:700;outline:none;">
        <option value="gmail" {{ ($shop->smtp_settings['provider'] ?? 'gmail') === 'gmail' ? 'selected' : '' }}>📧 Gmail</option>
        <option value="outlook" {{ ($shop->smtp_settings['provider'] ?? '') === 'outlook' ? 'selected' : '' }}>📨 Outlook / Hotmail</option>
        <option value="custom" {{ ($shop->smtp_settings['provider'] ?? '') === 'custom' ? 'selected' : '' }}>🔧 SMTP مخصص</option>
      </select>
    </div>

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:12px;">
      <div>
        <label style="display:block;font-size:13px;font-weight:800;margin-bottom:6px;color:var(--text);">SMTP Host</label>
        <input type="text" name="host" id="smtp_host" value="{{ $shop->smtp_settings['host'] ?? 'smtp.gmail.com' }}"
          style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;background:var(--surface);color:var(--text);font-family:monospace;font-weight:700;outline:none;">
      </div>
      <div>
        <label style="display:block;font-size:13px;font-weight:800;margin-bottom:6px;color:var(--text);">المنفذ</label>
        <input type="number" name="port" id="smtp_port" value="{{ $shop->smtp_settings['port'] ?? 587 }}"
          style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;background:var(--surface);color:var(--text);font-family:monospace;font-weight:700;outline:none;">
      </div>
    </div>

    <div>
      <label style="display:block;font-size:13px;font-weight:800;margin-bottom:6px;color:var(--text);">التشفير</label>
      <select name="encryption" id="smtp_encryption"
        style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;background:var(--surface);color:var(--text);font-family:inherit;font-weight:700;outline:none;">
        <option value="tls" {{ ($shop->smtp_settings['encryption'] ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS (587)</option>
        <option value="ssl" {{ ($shop->smtp_settings['encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL (465)</option>
      </select>
    </div>

    <div>
      <label style="display:block;font-size:13px;font-weight:800;margin-bottom:6px;color:var(--text);">البريد (Username) *</label>
      <input type="email" name="username" value="{{ $shop->smtp_settings['username'] ?? '' }}"
        placeholder="you@gmail.com" required
        style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;background:var(--surface);color:var(--text);font-family:inherit;font-weight:700;outline:none;">
    </div>

    <div>
      <label style="display:block;font-size:13px;font-weight:800;margin-bottom:6px;color:var(--text);">كلمة المرور (App Password) *</label>
      <input type="password" name="password" value="{{ $shop->smtp_settings['password'] ?? '' }}"
        placeholder="xxxx xxxx xxxx xxxx" required
        style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;background:var(--surface);color:var(--text);font-family:monospace;font-weight:700;outline:none;">
      <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">
        💡 لـ Gmail: أنشئ App Password من myaccount.google.com/apppasswords
      </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
      <div>
        <label style="display:block;font-size:13px;font-weight:800;margin-bottom:6px;color:var(--text);">اسم المرسل</label>
        <input type="text" name="from_name" value="{{ $shop->smtp_settings['from_name'] ?? $shop->name }}"
          style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;background:var(--surface);color:var(--text);font-family:inherit;font-weight:700;outline:none;">
      </div>
      <div>
        <label style="display:block;font-size:13px;font-weight:800;margin-bottom:6px;color:var(--text);">بريد المرسل</label>
        <input type="email" name="from_address" value="{{ $shop->smtp_settings['from_address'] ?? '' }}"
          placeholder="noreply@yourstore.com"
          style="width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:10px;background:var(--surface);color:var(--text);font-family:inherit;font-weight:700;outline:none;">
      </div>
    </div>

    <div style="display:flex;gap:10px;margin-top:8px;">
      <button type="submit" style="flex:1;padding:14px;background:linear-gradient(135deg,#3b82f6,#1d4ed8);color:white;border:none;border-radius:12px;font-weight:900;cursor:pointer;font-family:inherit;">
        💾 حفظ الإعدادات
      </button>
      <button type="submit" formaction="/dashboard/settings/smtp/test" style="padding:14px 20px;background:#f1f5f9;color:#475569;border:none;border-radius:12px;font-weight:900;cursor:pointer;font-family:inherit;">
        📧 اختبار الإرسال
      </button>
    </div>
  </form>
</div>

<script>
function fillProvider(provider) {
    const hosts = {
        'gmail': 'smtp.gmail.com',
        'outlook': 'smtp-mail.outlook.com',
        'custom': ''
    };
    const ports = {
        'gmail': 587,
        'outlook': 587,
        'custom': 587
    };
    const encs = {
        'gmail': 'tls',
        'outlook': 'tls',
        'custom': 'tls'
    };
    document.getElementById('smtp_host').value = hosts[provider] || '';
    document.getElementById('smtp_port').value = ports[provider] || 587;
    document.getElementById('smtp_encryption').value = encs[provider] || 'tls';
}
</script>
