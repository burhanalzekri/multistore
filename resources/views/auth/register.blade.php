<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>تسجيل — منصتي</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;900&display=swap" rel="stylesheet">
<style>body{font-family:'Cairo',sans-serif}</style>
</head>
<body class="bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen py-8 px-4">

<div class="w-full max-w-lg mx-auto">
  <div class="text-center mb-6">
    <a href="/" class="inline-flex items-center gap-2">
      <span class="text-4xl">🍯</span>
      <span class="text-2xl font-black text-amber-600">منصتي</span>
    </a>
  </div>

  <form method="POST" action="/register" class="bg-white rounded-3xl shadow-xl p-8">
    @csrf
    <h1 class="text-2xl font-black mb-2 text-center text-slate-800">ابدأ متجرك</h1>
    <p class="text-center text-slate-500 text-sm mb-6">14 يومًا تجريبيًا مجانًا</p>

    @if($errors->any())
      <div class="bg-red-100 text-red-700 p-3 rounded-xl mb-4 text-sm">
        @foreach($errors->all() as $e) <div>• {{ $e }}</div> @endforeach
      </div>
    @endif

    <label class="block mb-4">
      <span class="text-sm font-bold">اسمك</span>
      <input type="text" name="name" value="{{ old('name') }}" required
        class="w-full mt-1 px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none">
    </label>

    <label class="block mb-4">
      <span class="text-sm font-bold">اسم المتجر</span>
      <input type="text" name="shop_name" value="{{ old('shop_name') }}" required placeholder="مثال: متجر العسل"
        class="w-full mt-1 px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none">
    </label>

    <label class="block mb-4">
      <span class="text-sm font-bold">البريد الإلكتروني</span>
      <input type="email" name="email" value="{{ old('email') }}" required
        class="w-full mt-1 px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none">
    </label>

    <label class="block mb-4">
      <span class="text-sm font-bold">رقم الجوال (اختياري)</span>
      <input type="text" name="phone" value="{{ old('phone') }}" placeholder="7XXXXXXXX"
        class="w-full mt-1 px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none">
    </label>

    <label class="block mb-4">
      <span class="text-sm font-bold">كلمة المرور</span>
      <input type="password" name="password" required minlength="6"
        class="w-full mt-1 px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none">
    </label>

    <label class="block mb-6">
      <span class="text-sm font-bold">تأكيد كلمة المرور</span>
      <input type="password" name="password_confirmation" required
        class="w-full mt-1 px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none">
    </label>

    <button class="w-full py-3 bg-amber-600 text-white font-bold rounded-xl hover:bg-amber-700 transition">
      🚀 إنشاء المتجر
    </button>

    <div class="text-center mt-6 text-sm text-slate-500">
      لديك حساب؟ <a href="/login" class="text-amber-600 font-bold">سجّل الدخول</a>
    </div>
  </form>
</div>

</body>
</html>
