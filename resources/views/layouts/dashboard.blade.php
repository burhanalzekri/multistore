<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'لوحة التحكم')</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
  * { font-family: Cairo, sans-serif; box-sizing: border-box; }
  body { margin: 0; background: #f5f7fa; }
</style>
@stack('styles')

{{-- ═══ MultiStore Theme System ═══ --}}
<link rel="stylesheet" href="{{ asset('css/themes.css') }}?v=1">
</head>
<body>

  {{-- 🔔 التنبيهات المنبثقة --}}
  @include('components.toast')

  @yield('content')

  @stack('scripts')
<script src="{{ asset('js/ms-image-editor-v3.js') }}?v=1" defer></script>
<script src="{{ asset('js/ms-image-preview.js') }}?v=1" defer></script>
<script src="{{ asset('js/ui-feedback.js') }}" defer></script>

{{-- ═══ MultiStore Theme Switcher ═══ --}}
@include('components.theme-switcher')
<script src="{{ asset('js/theme-manager.js') }}?v=1" defer></script>
<script src="{{ asset('js/animations.js') }}?v=1" defer></script>
<script src="{{ asset('js/explainer.js') }}?v=1" defer></script>
</body>
</html>
