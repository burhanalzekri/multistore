@extends('layouts.app')
@section('title', 'حملة جديدة')
@section('page-title', '📧 حملة بريدية جديدة')
@section('page-subtitle', 'صمم حملتك واستهدف عملاءك')

@section('content')

<style>
  .cc-wrap { max-width: 900px; margin: 0 auto; display: flex; flex-direction: column; gap: 16px; }
  .cc-card { background: #fff; border-radius: 20px; padding: 24px; border: 1.5px solid #e2e8f0; box-shadow: 0 4px 12px rgba(15,23,42,.04); }
  .cc-card-title { display: flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 900; color: #0f172a; padding-bottom: 14px; margin-bottom: 18px; border-bottom: 1px dashed #e2e8f0; }
  .cc-field { margin-bottom: 14px; }
  .cc-label { display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 900; color: #334155; margin-bottom: 8px; }
  .cc-label .req { color: #dc2626; }
  .cc-input { width: 100%; padding: 12px 14px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 14px; font-weight: 700; color: #0f172a; outline: none; font-family: inherit; transition: .2s; background: #fff; }
  .cc-input:focus { border-color: #e96b2c; box-shadow: 0 0 0 4px rgba(233,107,44,.12); }
  .cc-textarea { resize: vertical; min-height: 200px; font-family: monospace; font-size: 13px; line-height: 1.6; }

  .cc-target-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
  .cc-target-card { cursor: pointer; }
  .cc-target-card input { display: none; }
  .cc-target-inner { padding: 14px 12px; border: 1.5px solid #e2e8f0; border-radius: 12px; text-align: center; transition: .2s; display: flex; flex-direction: column; align-items: center; gap: 4px; }
  .cc-target-inner .icon { font-size: 22px; }
  .cc-target-inner .title { font-size: 12px; font-weight: 900; color: #0f172a; }
  .cc-target-inner .desc { font-size: 10px; color: #94a3b8; font-weight: 700; }
  .cc-target-card input:checked + .cc-target-inner { border-color: #e96b2c; background: #fff7ed; box-shadow: 0 6px 16px rgba(233,107,44,.15); }

  .cc-value-wrap { display: none; margin-top: 12px; }
  .cc-value-wrap.show { display: block; }

  .cc-actions { display: flex; gap: 10px; margin-top: 12px; flex-wrap: wrap; }
  .cc-btn { padding: 14px 20px; border-radius: 14px; font-size: 13px; font-weight: 900; cursor: pointer; font-family: inherit; border: 0; transition: .2s; display: inline-flex; align-items: center; justify-content: center; gap: 6px; }
  .cc-btn.draft { flex: 1; background: #f1f5f9; color: #475569; }
  .cc-btn.draft:hover { background: #e2e8f0; }
  .cc-btn.schedule { flex: 1; background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: #fff; box-shadow: 0 8px 20px rgba(59,130,246,.3); }
  .cc-btn.schedule:hover { transform: translateY(-2px); }
  .cc-btn.send { flex: 2; background: linear-gradient(135deg, #10b981, #059669); color: #fff; box-shadow: 0 8px 20px rgba(16,185,129,.35); }
  .cc-btn.send:hover { transform: translateY(-2px); }
</style>

@if($errors->any())
  <div style="max-width:900px;margin:0 auto 16px;background:#fef2f2;color:#b91c1c;padding:14px 18px;border-radius:14px;font-weight:800;font-size:13px;">
    @foreach($errors->all() as $e) <div>• {{ $e }}</div> @endforeach
  </div>
@endif

<form method="POST" action="/dashboard/campaigns" class="cc-wrap">
  @csrf

  <div class="cc-card">
    <div class="cc-card-title">📝 معلومات الحملة</div>

    <div class="cc-field">
      <label class="cc-label">📛 اسم الحملة <span class="req">*</span></label>
      <input type="text" name="name" value="{{ old('name') }}" required placeholder="حملة عروض الصيف" class="cc-input">
    </div>

    <div class="cc-field">
      <label class="cc-label">✉️ عنوان البريد (Subject) <span class="req">*</span></label>
      <input type="text" name="subject" value="{{ old('subject') }}" required placeholder="🔥 عروض حصرية لك" class="cc-input">
    </div>

    <div class="cc-field">
      <label class="cc-label">📄 نص البريد <span class="req">*</span></label>
      <textarea name="body" required placeholder="اكتب نص البريد هنا..." class="cc-input cc-textarea">{{ old('body', "مرحباً،\n\nلدينا لك عرض حصري هذا الأسبوع!\n\nتابع متجرنا للمزيد.\n\nشكراً لك.") }}</textarea>
      <div style="font-size:11px;color:#94a3b8;margin-top:6px;">💡 يمكن استخدام نص عادي. HTML مدعوم لاحقاً.</div>
    </div>
  </div>

  <div class="cc-card">
    <div class="cc-card-title">🎯 استهداف العملاء</div>

    <div class="cc-target-grid">
      <label class="cc-target-card">
        <input type="radio" name="target_type" value="all" checked onchange="showTargetValue()">
        <div class="cc-target-inner">
          <span class="icon">👥</span>
          <span class="title">كل العملاء</span>
          <span class="desc">{{ $customersCount }} عميل</span>
        </div>
      </label>
      <label class="cc-target-card">
        <input type="radio" name="target_type" value="tier" onchange="showTargetValue()">
        <div class="cc-target-inner">
          <span class="icon">💎</span>
          <span class="title">حسب المستوى</span>
          <span class="desc">برونزي/فضي/ذهبي/بلاتيني</span>
        </div>
      </label>
      <label class="cc-target-card">
        <input type="radio" name="target_type" value="orders" onchange="showTargetValue()">
        <div class="cc-target-inner">
          <span class="icon">🛍️</span>
          <span class="title">حسب الطلبات</span>
          <span class="desc">لديهم ≥ X طلب</span>
        </div>
      </label>
      <label class="cc-target-card">
        <input type="radio" name="target_type" value="points" onchange="showTargetValue()">
        <div class="cc-target-inner">
          <span class="icon">💰</span>
          <span class="title">حسب النقاط</span>
          <span class="desc">نقاط ≥ X</span>
        </div>
      </label>
    </div>

    <div id="targetValueWrap" class="cc-value-wrap">
      <label class="cc-label" id="targetValueLabel">القيمة <span class="req">*</span></label>

      <select id="targetTierSelect" name="target_value_tier" style="display:none;width:100%;padding:12px 14px;border:1.5px solid #e2e8f0;border-radius:12px;font-size:14px;font-weight:700;font-family:inherit;outline:none;">
        <option value="bronze">🥉 برونزي (من 0 نقطة)</option>
        <option value="silver">🥈 فضي (من 1,000 نقطة)</option>
        <option value="gold">🥇 ذهبي (من 5,000 نقطة)</option>
        <option value="platinum">💎 بلاتيني (من 20,000 نقطة)</option>
      </select>

      <input type="number" id="targetNumberInput" name="target_value_number" min="1" placeholder="مثلاً: 3" class="cc-input" style="display:none;">
    </div>

    <input type="hidden" name="target_value" id="targetValueFinal" value="">
  </div>

  <div class="cc-card">
    <div class="cc-card-title">⏰ وقت الإرسال</div>

    <div class="cc-field">
      <label class="cc-label">📅 جدولة الإرسال (اختياري)</label>
      <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}" class="cc-input">
      <div style="font-size:11px;color:#94a3b8;margin-top:6px;">اتركه فارغاً للإرسال الفوري</div>
    </div>
  </div>

  <div class="cc-actions">
    <button type="submit" name="action" value="draft" class="cc-btn draft">💾 حفظ كمسودة</button>
    <button type="submit" name="action" value="schedule" class="cc-btn schedule">📅 جدولة</button>
    <button type="button" onclick="openSendConfirmCreate()" class="cc-btn send">🚀 إرسال الآن</button>
  </div>

</form>

<script>
function showTargetValue() {
  var type = document.querySelector('input[name="target_type"]:checked').value;
  var wrap = document.getElementById('targetValueWrap');
  var tierSel = document.getElementById('targetTierSelect');
  var numInput = document.getElementById('targetNumberInput');
  var label = document.getElementById('targetValueLabel');

  if (type === 'all') {
    wrap.classList.remove('show');
    tierSel.style.display = 'none';
    numInput.style.display = 'none';
    tierSel.disabled = true;
    numInput.disabled = true;
  } else if (type === 'tier') {
    wrap.classList.add('show');
    tierSel.style.display = 'block';
    numInput.style.display = 'none';
    tierSel.disabled = false;
    numInput.disabled = true;
    label.innerHTML = '💎 المستوى <span class="req">*</span>';
  } else {
    wrap.classList.add('show');
    tierSel.style.display = 'none';
    numInput.style.display = 'block';
    tierSel.disabled = true;
    numInput.disabled = false;
    if (type === 'orders') label.innerHTML = '🛍️ عدد الطلبات الأدنى <span class="req">*</span>';
    else label.innerHTML = '💰 الحد الأدنى للنقاط <span class="req">*</span>';
  }
}

document.querySelector('form').addEventListener('submit', function() {
  var type = document.querySelector('input[name="target_type"]:checked').value;
  var final = document.getElementById('targetValueFinal');
  if (type === 'tier') {
    final.value = document.getElementById('targetTierSelect').value;
  } else if (type === 'orders' || type === 'points') {
    final.value = document.getElementById('targetNumberInput').value;
  } else {
    final.value = '';
  }
});

document.addEventListener('DOMContentLoaded', showTargetValue);
</script>


{{-- Modal: تأكيد الإرسال --}}
<div id="sendConfirmCreateModal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.7);backdrop-filter:blur(8px);z-index:99999;align-items:center;justify-content:center;padding:20px;">
  <div style="background:#fff;border-radius:24px;padding:32px;max-width:440px;width:100%;box-shadow:0 30px 80px rgba(0,0,0,0.35);text-align:center;animation:modalIn 0.3s cubic-bezier(.2,.9,.3,1.05);">

    <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#d1fae5,#a7f3d0);display:grid;place-items:center;margin:0 auto 18px;font-size:40px;box-shadow:0 10px 25px rgba(16,185,129,0.3);">
      🚀
    </div>

    <h3 style="font-size:20px;font-weight:900;color:#0f172a;margin:0 0 10px;">إرسال الحملة الآن؟</h3>
    <p style="font-size:14px;color:#64748b;font-weight:700;line-height:1.7;margin:0 0 20px;">
      سيتم حفظ الحملة وإرسالها فوراً لجميع المستهدفين.
    </p>

    <div style="padding:14px;background:#fef2f2;border:1px solid #fecaca;border-radius:14px;font-size:12px;color:#991b1b;font-weight:700;line-height:1.7;margin-bottom:22px;">
      ⚠️ <strong>تنبيه:</strong> هذا الإجراء لا يمكن التراجع عنه
    </div>

    <div style="display:flex;gap:10px;">
      <button type="button" onclick="closeSendConfirmCreate()" style="flex:1;padding:14px;background:#f1f5f9;color:#475569;border:0;border-radius:14px;font-size:14px;font-weight:900;cursor:pointer;font-family:inherit;">
        إلغاء
      </button>
      <button type="button" onclick="confirmSendCreate()" id="confirmSendCreateBtn" style="flex:2;padding:14px;background:linear-gradient(135deg,#10b981,#059669);color:#fff;border:0;border-radius:14px;font-size:14px;font-weight:900;cursor:pointer;font-family:inherit;box-shadow:0 8px 20px rgba(16,185,129,0.35);display:inline-flex;align-items:center;justify-content:center;gap:8px;">
        ✅ نعم، إرسال الآن
      </button>
    </div>

  </div>
</div>

<style>
  @keyframes modalIn {
    from { opacity: 0; transform: scale(0.9) translateY(-20px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
  }
</style>

<script>
window.openSendConfirmCreate = function() {
  document.getElementById('sendConfirmCreateModal').style.display = 'flex';
};
window.closeSendConfirmCreate = function() {
  document.getElementById('sendConfirmCreateModal').style.display = 'none';
};
window.confirmSendCreate = function() {
  var btn = document.getElementById('confirmSendCreateBtn');
  btn.disabled = true;
  btn.innerHTML = '⏳ جاري الإرسال...';
  btn.style.background = 'linear-gradient(135deg,#64748b,#475569)';

  // إنشاء hidden input action=send_now وإرسال الفورم
  var form = document.querySelector('form');
  var input = document.createElement('input');
  input.type = 'hidden';
  input.name = 'action';
  input.value = 'send_now';
  form.appendChild(input);
  form.submit();
};
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeSendConfirmCreate();
});
document.addEventListener('click', function(e) {
  if (e.target.id === 'sendConfirmCreateModal') closeSendConfirmCreate();
});
</script>

@endsection
