@extends('layouts.app')
@section('title', 'إنشاء كوبون جديد')
@section('page-title', '🎁 إنشاء كوبون جديد')
@section('page-subtitle', 'أنشئ كوبون خصم متقدم بخصائص ذكية')

@section('content')

<style>
  .cp-wrap { max-width: 900px; margin: 0 auto; display: flex; flex-direction: column; gap: 16px; }
  .cp-card { background: #fff; border-radius: 20px; padding: 24px; border: 1.5px solid #e5e7eb; box-shadow: 0 4px 12px rgba(15,23,42,.04); }
  .cp-card-title { display: flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 900; color: #0f172a; padding-bottom: 14px; margin-bottom: 18px; border-bottom: 1px dashed #e5e7eb; }
  .cp-card-title span:first-child { font-size: 20px; }
  .cp-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
  .cp-field { margin-bottom: 14px; }
  .cp-label { display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 900; color: #334155; margin-bottom: 8px; }
  .cp-label .req { color: #dc2626; }
  .cp-label .hint { font-size: 10px; color: #94a3b8; font-weight: 700; }
  .cp-input { width: 100%; padding: 12px 14px; border: 1.5px solid #e5e7eb; border-radius: 12px; font-size: 14px; font-weight: 700; color: #0f172a; outline: none; font-family: inherit; transition: .2s; background: #fff; }
  .cp-input:focus { border-color: #e96b2c; box-shadow: 0 0 0 4px rgba(233,107,44,.12); }
  .cp-textarea { resize: vertical; min-height: 70px; }
  .cp-help { font-size: 11px; color: #94a3b8; font-weight: 700; margin-top: 6px; }

  .cp-type-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 14px; }
  .cp-type-card { cursor: pointer; }
  .cp-type-card input { display: none; }
  .cp-type-inner { padding: 16px 12px; border: 2px solid #e5e7eb; border-radius: 14px; text-align: center; transition: .2s; display: flex; flex-direction: column; align-items: center; gap: 6px; }
  .cp-type-inner .icon { font-size: 26px; }
  .cp-type-inner .title { font-size: 13px; font-weight: 900; color: #0f172a; }
  .cp-type-inner .desc { font-size: 10px; color: #94a3b8; font-weight: 700; }
  .cp-type-card input:checked + .cp-type-inner { border-color: #e96b2c; background: #fff7ed; box-shadow: 0 6px 16px rgba(233,107,44,.15); }

  .cp-scope-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
  .cp-scope-card { cursor: pointer; }
  .cp-scope-card input { display: none; }
  .cp-scope-inner { padding: 14px 8px; border: 1.5px solid #e5e7eb; border-radius: 12px; text-align: center; transition: .2s; font-size: 12px; font-weight: 800; }
  .cp-scope-card input:checked + .cp-scope-inner { border-color: #e96b2c; background: #fff7ed; color: #c2410c; }

  .cp-toggle { display: flex; align-items: center; justify-content: space-between; padding: 14px 16px; background: #f8fafc; border-radius: 14px; margin-bottom: 12px; cursor: pointer; }
  .cp-toggle-label { display: flex; flex-direction: column; gap: 3px; }
  .cp-toggle-label strong { font-size: 13px; font-weight: 900; color: #0f172a; }
  .cp-toggle-label span { font-size: 11px; color: #64748b; font-weight: 700; }
  .cp-switch { position: relative; width: 46px; height: 26px; background: #cbd5e1; border-radius: 99px; transition: .25s; }
  .cp-switch::after { content: ''; position: absolute; top: 3px; right: 3px; width: 20px; height: 20px; border-radius: 50%; background: #fff; transition: .25s; box-shadow: 0 2px 4px rgba(0,0,0,.15); }
  input:checked + .cp-switch { background: linear-gradient(135deg, #10b981, #059669); }
  input:checked + .cp-switch::after { transform: translateX(-20px); }
  .cp-switch-input { display: none; }

  .cp-actions { display: flex; gap: 10px; margin-top: 12px; }
  .cp-btn { padding: 14px 20px; border-radius: 14px; font-size: 14px; font-weight: 900; cursor: pointer; font-family: inherit; border: 0; transition: .2s; display: inline-flex; align-items: center; justify-content: center; gap: 8px; }
  .cp-btn.primary { flex: 2; background: linear-gradient(135deg, #e96b2c, #d9541a); color: #fff; box-shadow: 0 10px 25px rgba(233,107,44,.3); }
  .cp-btn.primary:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(233,107,44,.4); }
  .cp-btn.cancel { flex: 1; background: #f1f5f9; color: #475569; text-decoration: none; }
  .cp-btn.cancel:hover { background: #e2e8f0; }

  @media (max-width: 640px) {
    .cp-card { padding: 18px; border-radius: 16px; }
    .cp-grid { grid-template-columns: 1fr; }
    .cp-scope-grid { grid-template-columns: 1fr; }
  }
</style>

<form method="POST" action="/dashboard/coupons" class="cp-wrap">
  @csrf

  @if($errors->any())
    <div style="background:#fef2f2;color:#b91c1c;padding:14px 18px;border-radius:14px;font-weight:800;font-size:13px;">
      @foreach($errors->all() as $e) <div>• {{ $e }}</div> @endforeach
    </div>
  @endif

  {{-- 1) البيانات الأساسية --}}
  <div class="cp-card">
    <div class="cp-card-title">
      <span>📝</span>
      <span>البيانات الأساسية</span>
    </div>

    <div class="cp-grid">
      <div class="cp-field">
        <label class="cp-label">
          <span>🎫 كود الكوبون</span>
          <span class="req">*</span>
          <span class="hint">(أحرف إنجليزية وأرقام)</span>
        </label>
        <input type="text" name="code" value="{{ old('code') }}" required
          placeholder="SUMMER20" style="text-transform: uppercase; font-family: monospace; letter-spacing: 1px;"
          class="cp-input">
      </div>

      <div class="cp-field">
        <label class="cp-label">
          <span>📄 وصف مختصر</span>
          <span class="hint">(يظهر للعميل)</span>
        </label>
        <input type="text" name="description" value="{{ old('description') }}"
          placeholder="خصم الصيف 20%" class="cp-input">
      </div>
    </div>
  </div>

  {{-- 2) نوع الخصم --}}
  <div class="cp-card">
    <div class="cp-card-title">
      <span>💰</span>
      <span>نوع الخصم</span>
    </div>

    <div class="cp-type-grid">
      <label class="cp-type-card">
        <input type="radio" name="type" value="percentage" checked onchange="updateValueLabel()">
        <div class="cp-type-inner">
          <span class="icon">📊</span>
          <span class="title">نسبة مئوية</span>
          <span class="desc">مثلاً: 10% من الإجمالي</span>
        </div>
      </label>
      <label class="cp-type-card">
        <input type="radio" name="type" value="fixed" onchange="updateValueLabel()">
        <div class="cp-type-inner">
          <span class="icon">💵</span>
          <span class="title">مبلغ ثابت</span>
          <span class="desc">مثلاً: 500 ريال</span>
        </div>
      </label>
    </div>

    <div class="cp-grid">
      <div class="cp-field">
        <label class="cp-label">
          <span id="valueLabel">📊 قيمة الخصم (%)</span>
          <span class="req">*</span>
        </label>
        <input type="number" name="value" value="{{ old('value') }}" required min="0" step="0.01"
          placeholder="10" class="cp-input" id="valueInput">
      </div>

      <div class="cp-field">
        <label class="cp-label">
          <span>🛑 سقف الخصم</span>
          <span class="hint">(اختياري - للنسبة فقط)</span>
        </label>
        <input type="number" name="max_discount" value="{{ old('max_discount') }}" min="0" step="0.01"
          placeholder="مثلاً: 5000" class="cp-input">
        <div class="cp-help">أقصى مبلغ يمكن خصمه عند استخدام نسبة</div>
      </div>
    </div>

    <div class="cp-grid">
      <div class="cp-field">
        <label class="cp-label">
          <span>💳 الحد الأدنى للطلب</span>
          <span class="hint">(اختياري)</span>
        </label>
        <input type="number" name="min_order" value="{{ old('min_order', 0) }}" min="0" step="0.01"
          placeholder="0" class="cp-input">
        <div class="cp-help">لا يعمل الكوبون إلا إذا وصل الطلب لهذا المبلغ</div>
      </div>

      <div class="cp-field">
        <label class="cp-label">
          <span>📦 الحد الأدنى للكمية</span>
          <span class="hint">(اختياري)</span>
        </label>
        <input type="number" name="min_qty" value="{{ old('min_qty', 0) }}" min="0"
          placeholder="0" class="cp-input">
      </div>
    </div>
  </div>

  {{-- 3) نطاق التطبيق --}}
  <div class="cp-card">
    <div class="cp-card-title">
      <span>🎯</span>
      <span>نطاق التطبيق</span>
    </div>

    <div class="cp-scope-grid">
      <label class="cp-scope-card">
        <input type="radio" name="applies_to" value="all" checked onchange="toggleScope()">
        <div class="cp-scope-inner">🛍️ كل المنتجات</div>
      </label>
      <label class="cp-scope-card">
        <input type="radio" name="applies_to" value="product" onchange="toggleScope()">
        <div class="cp-scope-inner">📦 منتج محدد</div>
      </label>
      <label class="cp-scope-card">
        <input type="radio" name="applies_to" value="category" onchange="toggleScope()">
        <div class="cp-scope-inner">🏷️ تصنيف محدد</div>
      </label>
    </div>

    <div id="scopeProduct" style="display: none; margin-top: 14px;">
      <label class="cp-label">📦 اختر المنتج</label>
      <select name="applies_to_id" class="cp-input" id="scopeProductSelect" disabled>
        <option value="">— اختر منتج —</option>
        @foreach($products as $p)
          <option value="{{ $p->id }}">{{ $p->name }}</option>
        @endforeach
      </select>
    </div>

    <div id="scopeCategory" style="display: none; margin-top: 14px;">
      <label class="cp-label">🏷️ اختر التصنيف</label>
      <select name="applies_to_id" class="cp-input" id="scopeCategorySelect" disabled>
        <option value="">— اختر تصنيف —</option>
        @foreach($categories as $c)
          <option value="{{ $c->id }}">{{ $c->name }}</option>
        @endforeach
      </select>
    </div>
  </div>

  {{-- 4) القيود --}}
  <div class="cp-card">
    <div class="cp-card-title">
      <span>⚙️</span>
      <span>القيود والحدود</span>
    </div>

    <div class="cp-grid">
      <div class="cp-field">
        <label class="cp-label">🎯 إجمالي مرات الاستخدام</label>
        <input type="number" name="max_uses" value="{{ old('max_uses') }}" min="1"
          placeholder="بلا حد" class="cp-input">
        <div class="cp-help">اتركه فارغاً للاستخدام غير المحدود</div>
      </div>

      <div class="cp-field">
        <label class="cp-label">👤 حد الاستخدام لكل مستخدم</label>
        <input type="number" name="per_user_limit" value="{{ old('per_user_limit') }}" min="1"
          placeholder="بلا حد" class="cp-input">
      </div>
    </div>

    <div class="cp-grid">
      <div class="cp-field">
        <label class="cp-label">📅 تاريخ البدء</label>
        <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}" class="cp-input">
        <div class="cp-help">اتركه فارغاً للتطبيق الفوري</div>
      </div>

      <div class="cp-field">
        <label class="cp-label">📅 تاريخ الانتهاء</label>
        <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}" class="cp-input">
        <div class="cp-help">اتركه فارغاً لعدم الانتهاء</div>
      </div>
    </div>

    <label class="cp-toggle">
      <div class="cp-toggle-label">
        <strong>🥇 للطلب الأول فقط</strong>
        <span>الكوبون يعمل فقط للعملاء الجدد (بدون طلبات سابقة)</span>
      </div>
      <input type="checkbox" name="first_order_only" value="1" {{ old('first_order_only') ? 'checked' : '' }} class="cp-switch-input">
      <span class="cp-switch"></span>
    </label>

    <label class="cp-toggle">
      <div class="cp-toggle-label">
        <strong>✅ تفعيل الكوبون</strong>
        <span>الكوبون يعمل فوراً بعد الحفظ</span>
      </div>
      <input type="checkbox" name="is_active" value="1" checked class="cp-switch-input">
      <span class="cp-switch"></span>
    </label>
  </div>

  {{-- 5) الأزرار --}}
  <div class="cp-actions">
    <button type="submit" class="cp-btn primary">💾 حفظ الكوبون</button>
    <a href="/dashboard/coupons" class="cp-btn cancel">إلغاء</a>
  </div>

</form>

<script>
  function updateValueLabel() {
    var type = document.querySelector('input[name="type"]:checked').value;
    var label = document.getElementById('valueLabel');
    var input = document.getElementById('valueInput');
    if (type === 'percentage') {
      label.textContent = '📊 قيمة الخصم (%)';
      input.placeholder = '10';
      input.max = '100';
    } else {
      label.textContent = '💵 قيمة الخصم (ر.ي)';
      input.placeholder = '500';
      input.removeAttribute('max');
    }
  }

  function toggleScope() {
    var scope = document.querySelector('input[name="applies_to"]:checked').value;
    var prodWrap = document.getElementById('scopeProduct');
    var catWrap = document.getElementById('scopeCategory');
    var prodSel = document.getElementById('scopeProductSelect');
    var catSel = document.getElementById('scopeCategorySelect');

    prodWrap.style.display = scope === 'product' ? 'block' : 'none';
    catWrap.style.display = scope === 'category' ? 'block' : 'none';

    prodSel.disabled = scope !== 'product';
    catSel.disabled = scope !== 'category';

    if (scope !== 'product') prodSel.value = '';
    if (scope !== 'category') catSel.value = '';
  }
</script>

@endsection
