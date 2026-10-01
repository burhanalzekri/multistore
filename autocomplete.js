<script>
// ═══════════════════════════════════════════════════════════
// 🔍 Autocomplete — بحث فوري مع اقتراحات
// ═══════════════════════════════════════════════════════════
(function() {
  const input = document.getElementById('acInput');
  const dropdown = document.getElementById('acDropdown');
  if (!input || !dropdown) return;

  let debounceTimer = null;
  let currentController = null;
  let activeIndex = -1;
  let results = [];

  function search(q) {
    if (currentController) currentController.abort();
    currentController = new AbortController();

    fetch('/api/search/suggest?q=' + encodeURIComponent(q), {
      signal: currentController.signal,
      headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
      results = data.results || [];
      render(results, q);
    })
    .catch(err => {
      if (err.name === 'AbortError') return;
      console.error('Search error:', err);
    });
  }

  function render(items, q) {
    if (!items.length) {
      dropdown.innerHTML = '<div class="ac-empty">لا توجد نتائج لـ "' + escapeHtml(q) + '"</div>';
      dropdown.classList.add('show');
      input.setAttribute('aria-expanded', 'true');
      activeIndex = -1;
      return;
    }

    let html = '';
    items.forEach((item, i) => {
      const img = item.image
        ? '<img src="' + item.image + '" alt="" loading="lazy">'
        : '<div class="ac-noimg">📦</div>';
      const stockBadge = item.stock > 0 ? '' : '<span class="ac-out">نفذ</span>';
      html += '<a href="' + item.url + '" class="ac-item" data-index="' + i + '">' +
        '<div class="ac-item-img">' + img + '</div>' +
        '<div class="ac-item-body">' +
        '<div class="ac-item-name">' + highlight(item.name, q) + '</div>' +
        '<div class="ac-item-meta">' +
        '<span class="ac-item-price">' + item.price + ' ر.ي</span>' +
        stockBadge +
        '</div></div></a>';
    });

    html += '<a href="/shop?q=' + encodeURIComponent(q) + '" class="ac-all">🔍 عرض كل النتائج لـ "' + escapeHtml(q) + '"</a>';

    dropdown.innerHTML = html;
    dropdown.classList.add('show');
    input.setAttribute('aria-expanded', 'true');
    activeIndex = -1;

    dropdown.querySelectorAll('.ac-item').forEach(el => {
      el.addEventListener('mouseenter', () => {
        activeIndex = parseInt(el.dataset.index);
        updateActive();
      });
    });
  }

  function highlight(text, q) {
    if (!q) return escapeHtml(text);
    const safeText = escapeHtml(text);
    const safeQ = escapeHtml(q).replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    return safeText.replace(new RegExp('(' + safeQ + ')', 'gi'), '<mark>$1</mark>');
  }

  function escapeHtml(s) {
    return String(s).replace(/[&<>"']/g, c => ({
      '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    })[c]);
  }

  function updateActive() {
    dropdown.querySelectorAll('.ac-item').forEach((el, i) => {
      el.classList.toggle('active', i === activeIndex);
    });
  }

  function close() {
    dropdown.classList.remove('show');
    input.setAttribute('aria-expanded', 'false');
    activeIndex = -1;
  }

  input.addEventListener('input', function() {
    const q = this.value.trim();
    clearTimeout(debounceTimer);
    if (q.length < 2) { close(); return; }
    debounceTimer = setTimeout(() => search(q), 250);
  });

  input.addEventListener('keydown', function(e) {
    const items = dropdown.querySelectorAll('.ac-item');
    if (e.key === 'ArrowDown') {
      e.preventDefault();
      if (!dropdown.classList.contains('show')) return;
      activeIndex = Math.min(activeIndex + 1, items.length - 1);
      updateActive();
      items[activeIndex]?.scrollIntoView({ block: 'nearest' });
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      activeIndex = Math.max(activeIndex - 1, -1);
      updateActive();
      items[activeIndex]?.scrollIntoView({ block: 'nearest' });
    } else if (e.key === 'Enter') {
      if (activeIndex >= 0 && items[activeIndex]) {
        e.preventDefault();
        window.location.href = items[activeIndex].href;
      }
    } else if (e.key === 'Escape') {
      close();
      input.blur();
    }
  });

  document.addEventListener('click', function(e) {
    if (!input.contains(e.target) && !dropdown.contains(e.target)) close();
  });

  input.addEventListener('blur', function() {
    setTimeout(() => {
      if (!dropdown.matches(':hover')) close();
    }, 200);
  });

})();
</script>
