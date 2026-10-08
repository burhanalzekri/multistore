{{-- ═══════════════════════════════════════════
     Theme Switcher Component
     4 أوضاع: نهار / ليل / مطر / غروب
═══════════════════════════════════════════ --}}
<div class="ms-theme-switcher" id="msThemeSwitcher">
    <div class="ms-theme-menu" id="msThemeMenu">
        <button class="ms-theme-option active" data-theme-set="light" type="button">
            <span class="ms-theme-option-icon">☀️</span>
            <span>النهار</span>
        </button>
        <button class="ms-theme-option" data-theme-set="dark" type="button">
            <span class="ms-theme-option-icon">🌙</span>
            <span>الليل</span>
        </button>
        <button class="ms-theme-option" data-theme-set="rain" type="button">
            <span class="ms-theme-option-icon">🌧️</span>
            <span>المطر</span>
        </button>
        <button class="ms-theme-option" data-theme-set="sunset" type="button">
            <span class="ms-theme-option-icon">🌅</span>
            <span>الغروب</span>
        </button>
    </div>
    <button class="ms-theme-toggle" id="msThemeToggle" type="button" aria-label="تبديل الأوضاع">🎨</button>
</div>
