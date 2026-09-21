<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>دخول — منصتي</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;900&display=swap" rel="stylesheet">
<style>body{font-family:'Cairo',sans-serif}</style>
</head>
<body class="bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md">
  <div class="text-center mb-6">
    <a href="/" class="inline-flex items-center gap-2">
      <span class="text-4xl">🍯</span>
      <span class="text-2xl font-black text-amber-600">منصتي</span>
    </a>
  </div>

  <form method="POST" action="/login" class="bg-white rounded-3xl shadow-xl p-8">
    @csrf
    <h1 class="text-2xl font-black mb-6 text-center text-slate-800">تسجيل الدخول</h1>

    @if($errors->any())
      <div class="bg-red-100 text-red-700 p-3 rounded-xl mb-4 text-sm">
        @foreach($errors->all() as $e) <div>• {{ $e }}</div> @endforeach
      </div>
    @endif

    @if(session('success'))
      <div class="bg-green-100 text-green-700 p-3 rounded-xl mb-4 text-sm">{{ session('success') }}</div>
    @endif

    <label class="block mb-4">
      <span class="text-sm font-bold">البريد الإلكتروني</span>
      <input type="email" name="email" value="{{ old('email') }}" required autofocus
        class="w-full mt-1 px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none">
    </label>

    <label class="block mb-4">
      <span class="text-sm font-bold">كلمة المرور</span>
      <input type="password" name="password" required
        class="w-full mt-1 px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none">
    </label>

    <label class="flex items-center gap-2 mb-6">
      <input type="checkbox" name="remember" class="w-4 h-4 accent-amber-600">
      <span class="text-sm text-slate-600">تذكرني</span>
    </label>

    <button class="w-full py-3 bg-amber-600 text-white font-bold rounded-xl hover:bg-amber-700 transition">
      دخول ←
    </button>

    <div class="text-center mt-6 text-sm text-slate-500">
      ليس لديك حساب؟
      <a href="/register" class="text-amber-600 font-bold">أنشئ متجرك الآن</a>
    </div>
  </form>
</div>

</body>
</html>
