@extends('layouts.app')
@section('title', 'المهام')
@section('page-title', '✅ المهام')

@section('content')

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px;margin-bottom:20px;">
  <div class="admin-card" style="padding:16px;border-right:4px solid #f59e0b;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;">⏳ قيد الانتظار</div>
    <div style="font-size:22px;font-weight:900;color:#f59e0b;margin-top:4px;">{{ $stats['pending'] }}</div>
  </div>
  <div class="admin-card" style="padding:16px;border-right:4px solid #3b82f6;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;">⚙️ قيد التنفيذ</div>
    <div style="font-size:22px;font-weight:900;color:#3b82f6;margin-top:4px;">{{ $stats['in_progress'] }}</div>
  </div>
  <div class="admin-card" style="padding:16px;border-right:4px solid #10b981;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;">✅ مكتملة</div>
    <div style="font-size:22px;font-weight:900;color:#10b981;margin-top:4px;">{{ $stats['completed'] }}</div>
  </div>
  <div class="admin-card" style="padding:16px;border-right:4px solid #ef4444;">
    <div style="font-size:11px;color:var(--text-muted);font-weight:800;">⚠️ متأخرة</div>
    <div style="font-size:22px;font-weight:900;color:#ef4444;margin-top:4px;">{{ $stats['overdue'] }}</div>
  </div>
</div>

<div class="admin-card" style="padding:20px;margin-bottom:20px;">
  <h2 style="font-size:15px;font-weight:900;margin-bottom:14px;">➕ مهمة جديدة</h2>
  <form method="POST" action="/dashboard/tasks" style="display:grid;grid-template-columns:1fr auto auto auto;gap:10px;">
    @csrf
    <input type="text" name="title" required placeholder="عنوان المهمة..." style="padding:12px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;background:var(--surface);color:var(--text);font-weight:600;outline:none;">
    <select name="priority" style="padding:12px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;background:var(--surface);color:var(--text);font-weight:700;">
      <option value="low">🟢 منخفضة</option>
      <option value="medium" selected>🟡 متوسطة</option>
      <option value="high">🟠 عالية</option>
      <option value="urgent">🔴 عاجلة</option>
    </select>
    <input type="date" name="due_date" style="padding:12px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;background:var(--surface);color:var(--text);font-weight:600;">
    <button type="submit" style="background:linear-gradient(135deg,#fbbf24,#f97316);color:white;border:none;padding:12px 24px;border-radius:10px;font-weight:900;cursor:pointer;font-family:inherit;">حفظ</button>
  </form>
</div>

<div class="admin-card">
  @forelse($tasks as $task)
  <div style="display:flex;align-items:center;gap:14px;padding:14px 20px;border-bottom:1px solid var(--border);{{ $task->status === 'completed' ? 'opacity:0.6;' : '' }}">
    <form method="POST" action="/dashboard/tasks/{{ $task->id }}/toggle" style="display:inline;">
      @csrf
      <button type="submit" style="width:28px;height:28px;border-radius:50%;border:2px solid {{ $task->status === 'completed' ? '#10b981' : '#cbd5e1' }};background:{{ $task->status === 'completed' ? '#10b981' : 'transparent' }};color:white;font-weight:900;cursor:pointer;display:flex;align-items:center;justify-content:center;font-family:inherit;">
        @if($task->status === 'completed') ✓ @endif
      </button>
    </form>
    <div style="flex:1;min-width:0;">
      <div style="font-weight:800;font-size:14px;color:var(--text);{{ $task->status === 'completed' ? 'text-decoration:line-through;' : '' }}">
        {{ $task->title }}
      </div>
      @if($task->due_date)
      <div style="font-size:11px;color:{{ $task->due_date->isPast() && $task->status !== 'completed' ? '#ef4444' : 'var(--text-muted)' }};margin-top:3px;">
        📅 {{ $task->due_date->format('Y-m-d') }}
      </div>
      @endif
    </div>
    <span style="font-size:10px;font-weight:900;padding:3px 10px;border-radius:999px;
      @if($task->priority === 'urgent') background:#fee2e2;color:#b91c1c;
      @elseif($task->priority === 'high') background:#fed7aa;color:#c2410c;
      @elseif($task->priority === 'medium') background:#fef3c7;color:#b45309;
      @else background:#dcfce7;color:#15803d; @endif">
      {{ ['urgent'=>'عاجلة','high'=>'عالية','medium'=>'متوسطة','low'=>'منخفضة'][$task->priority] }}
    </span>
    <form method="POST" action="/dashboard/tasks/{{ $task->id }}" onsubmit="return confirm('حذف المهمة؟')" style="display:inline;">
      @csrf @method('DELETE')
      <button type="submit" style="background:#fee2e2;color:#b91c1c;border:none;padding:6px 10px;border-radius:6px;font-weight:800;cursor:pointer;font-family:inherit;">✕</button>
    </form>
  </div>
  @empty
  <div style="padding:60px 20px;text-align:center;">
    <div style="font-size:56px;margin-bottom:12px;opacity:0.5;">✅</div>
    <div style="font-size:15px;font-weight:900;">لا توجد مهام</div>
  </div>
  @endforelse
</div>

@if($tasks->hasPages())
<div style="margin-top:16px;">{{ $tasks->links() }}</div>
@endif


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

@endsection
