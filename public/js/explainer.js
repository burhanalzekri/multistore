/* ═══════════════════════════════════════════
   MultiStore — Explainer Modals
   نوافذ شرح الأقسام
   ═══════════════════════════════════════════ */
(function() {
    'use strict';
    
    document.addEventListener('click', function(e) {
        var btn = e.target.closest('[data-explain]');
        if (btn) {
            e.preventDefault();
            e.stopPropagation();
            var id = btn.getAttribute('data-explain');
            var modal = document.querySelector('[data-explain-modal="' + id + '"]');
            if (modal) {
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
            return;
        }
        
        if (e.target.classList.contains('explain-modal')) {
            e.target.classList.remove('show');
            document.body.style.overflow = '';
            return;
        }
        
        if (e.target.closest('.explain-close, .explain-close-btn')) {
            var m = e.target.closest('.explain-modal');
            if (m) {
                m.classList.remove('show');
                document.body.style.overflow = '';
            }
            return;
        }
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.explain-modal.show').forEach(function(m) {
                m.classList.remove('show');
            });
            document.body.style.overflow = '';
        }
    });
})();
