<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title>لا يوجد متجر</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;900&display=swap" rel="stylesheet">
<style>body{font-family:'Cairo',sans-serif}</style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
<div class="text-center max-w-md">
  <div class="text-7xl mb-4">🏪</div>
  <h1 class="text-2xl font-black mb-4">لا يوجد متجر نشط</h1>
  <p class="text-slate-500 mb-6">لم يتم إنشاء متجر بعد. أنشئ حسابًا وسيظهر متجرك هنا.</p>
  <a href="/register" class="inline-block px-8 py-3 bg-amber-600 text-white rounded-xl font-bold">
    🚀 أنشئ متجرك
  </a>
</div>

  {{-- 🔔 التنبيهات --}}
  @include('components.toast')

@include('components.floating-actions')
</body>
</html>
