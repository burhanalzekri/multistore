/* ═══════════════════════════════════════════
   MultiStore — Theme Manager
   إدارة الأوضاع الأربعة
   ═══════════════════════════════════════════ */
(function() {
    'use strict';
    
    var STORAGE_KEY = 'multistore-theme';
    var themes = ['light', 'dark', 'rain', 'sunset'];
    var icons = { light: '☀️', dark: '🌙', rain: '🌧️', sunset: '🌅' };
    
    function applyTheme(theme) {
        if (themes.indexOf(theme) === -1) theme = 'light';
        
        if (theme === 'light') {
            document.body.removeAttribute('data-ms-theme');
        } else {
            document.body.setAttribute('data-ms-theme', theme);
        }
        
        // Limpiar
        document.querySelectorAll('.ms-theme-option').forEach(function(btn) {
            btn.classList.toggle('active', btn.getAttribute('data-theme-set') === theme);
        });
        
        var toggle = document.getElementById('msThemeToggle');
        if (toggle) toggle.textContent = icons[theme] || '🎨';
        
        try { localStorage.setItem(STORAGE_KEY, theme); } catch(e) {}
    }
    
    function initTheme() {
        var saved = 'light';
        try { saved = localStorage.getItem(STORAGE_KEY) || 'light'; } catch(e) {}
        applyTheme(saved);
    }
    
    // تهيئة فورية (قبل DOM load) لتجنب FOUC
    try {
        var early = localStorage.getItem(STORAGE_KEY);
        if (early && early !== 'light') {
            document.documentElement.setAttribute('data-ms-theme-early', early);
        }
    } catch(e) {}
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTheme);
    } else {
        initTheme();
    }
    
    // Event delegation
    document.addEventListener('click', function(e) {
        if (e.target.closest('#msThemeToggle')) {
            e.preventDefault();
            var menu = document.getElementById('msThemeMenu');
            if (menu) menu.classList.toggle('open');
            return;
        }
        
        var opt = e.target.closest('[data-theme-set]');
        if (opt) {
            e.preventDefault();
            var theme = opt.getAttribute('data-theme-set');
            applyTheme(theme);
            setTimeout(function() {
                var menu = document.getElementById('msThemeMenu');
                if (menu) menu.classList.remove('open');
            }, 200);
            return;
        }
        
        if (!e.target.closest('#msThemeSwitcher')) {
            var menu = document.getElementById('msThemeMenu');
            if (menu) menu.classList.remove('open');
        }
    });
    
    // إظهار المبدّل بعد التمرير
    window.addEventListener('scroll', function() {
        var sw = document.getElementById('msThemeSwitcher');
        if (sw) {
            sw.classList.toggle('visible', window.scrollY > 150);
        }
    }, { passive: true });
    
    // إظهار فوري إن كانت الصفحة قصيرة
    setTimeout(function() {
        var sw = document.getElementById('msThemeSwitcher');
        if (sw && document.body.scrollHeight < window.innerHeight * 1.5) {
            sw.classList.add('visible');
        }
    }, 500);
    
    // تصدير للاستخدام الخارجي
    window.msApplyTheme = applyTheme;
})();
