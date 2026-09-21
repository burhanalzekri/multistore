@extends('layouts.app')

@section('title', 'الإعدادات')
@section('page-title', '⚙️ الإعدادات')

@section('content')

@if($errors->any())
<div class="bg-red-100 text-red-700 p-3 rounded-xl mb-4 text-sm">
  @foreach($errors->all() as $e) <div>• {{ $e }}</div> @endforeach
</div>
@endif

<div class="max-w-2xl space-y-4">

  <form method="POST" action="/dashboard/settings" class="bg-white rounded-2xl p-6 shadow-sm space-y-4">
    @csrf

    <h2 class="font-bold text-lg">🏪 بيانات المتجر</h2>

    <label class="block">
      <span class="text-sm font-bold">اسم المتجر *</span>
      <input type="text" name="name" value="{{ old('name', $shop->name) }}" required
        class="w-full mt-1 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-amber-500 outline-none">
    </label>

    <div class="grid grid-cols-2 gap-4">
      <label class="block">
        <span class="text-sm font-bold">رقم الهاتف</span>
        <input type="text" name="phone" value="{{ old('phone', $shop->phone) }}"
          class="w-full mt-1 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-amber-500 outline-none">
      </label>
      <label class="block">
        <span class="text-sm font-bold">واتساب</span>
        <input type="text" name="whatsapp" value="{{ old('whatsapp', $shop->whatsapp) }}"
          class="w-full mt-1 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-amber-500 outline-none">
      </label>
    </div>

    <div class="grid grid-cols-2 gap-4">
      <label class="block">
        <span class="text-sm font-bold">اللون الرئيسي</span>
        <input type="color" name="primary_color" value="{{ old('primary_color', $shop->primary_color ?? '#f59e0b') }}"
          class="w-full mt-1 h-12 border rounded-lg">
      </label>
      <label class="block">
        <span class="text-sm font-bold">العملة</span>
        <select name="currency" class="w-full mt-1 px-4 py-3 border rounded-lg">
          <option value="YER" {{ $shop->currency==='YER'?'selected':'' }}>ريال يمني (YER)</option>
          <option value="SAR" {{ $shop->currency==='SAR'?'selected':'' }}>ريال سعودي (SAR)</option>
          <option value="USD" {{ $shop->currency==='USD'?'selected':'' }}>دولار (USD)</option>
        </select>
      </label>
    </div>

    <button type="submit" class="w-full py-3 bg-amber-600 text-white rounded-lg font-bold">💾 حفظ الإعدادات</button>
  </form>

  <div class="bg-white rounded-2xl p-6 shadow-sm">
    <h2 class="font-bold text-lg mb-3">🔗 بيانات Webhook للتطبيق</h2>
    <p class="text-xs text-slate-500 mb-3">استخدم هذه المعلومات في تطبيق الأندرويد لتحويل رسائل SMS</p>

    <label class="block mb-3">
      <span class="text-sm font-bold">Webhook URL</span>
      <input type="text" readonly value="{{ request()->getSchemeAndHttpHost() . '/webhooks/sms/' . $shop->webhook_token }}"
        onclick="this.select()"
        class="w-full mt-1 px-4 py-3 border rounded-lg bg-slate-50 font-mono text-xs">
    </label>

    <label class="block">
      <span class="text-sm font-bold">Token</span>
      <input type="text" readonly value="{{ request()->getSchemeAndHttpHost() . '/webhooks/sms/' . $shop->webhook_token }}"
        class="w-full mt-1 px-4 py-3 border rounded-lg bg-slate-50 font-mono text-xs">
    </label>
  </div>

</div>

@endsection
