/* ═══════════════════════════════════════════
   MultiStore — Animations
   Count-up + Reveal on scroll
   ═══════════════════════════════════════════ */
(function() {
    'use strict';
    
    // ═══ Count-up ═══
    function animateCount(el) {
        var target = +el.dataset.count;
        if (!target || target <= 0) return;
        var duration = 2000;
        var start = performance.now();
        function frame(now) {
            var progress = Math.min((now - start) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.floor(target * eased).toLocaleString('en-US');
            if (progress < 1) requestAnimationFrame(frame);
            else el.textContent = target.toLocaleString('en-US');
        }
        requestAnimationFrame(frame);
    }
    
    // ═══ Reveal on scroll ═══
    function initAnimations() {
        // Count-up
        if ('IntersectionObserver' in window) {
            var countObs = new IntersectionObserver(function(entries) {
                entries.forEach(function(e) {
                    if (e.isIntersecting) {
                        animateCount(e.target);
                        countObs.unobserve(e.target);
                    }
                });
            }, { threshold: 0.4 });
            document.querySelectorAll('[data-count]').forEach(function(el) {
                countObs.observe(el);
            });
            
            // Reveal
            var revealObs = new IntersectionObserver(function(entries) {
                entries.forEach(function(e) {
                    if (e.isIntersecting) {
                        e.target.classList.add('ms-visible');
                        revealObs.unobserve(e.target);
                    }
                });
            }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
            
            // Apply reveal to common elements
            document.querySelectorAll(
                '.reveal, .ms-reveal, .section-head, .feature-card, .step, .num-card, ' +
                '.intro-card, .audience-card, .testimonial, .price-card, .benefit, ' +
                '.benefits > .container > .benefit-grid > *'
            ).forEach(function(el) {
                if (!el.classList.contains('ms-reveal')) {
                    el.classList.add('ms-reveal');
                }
                revealObs.observe(el);
            });
        }
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAnimations);
    } else {
        initAnimations();
    }
})();
