(function() {
  'use strict';
  var DEBOUNCE_MS = 250;
  var MIN_CHARS = 2;
  var cache = {};
  var debounceTimer = null;
  var activeIndex = -1;

  function init() {
    var input = document.getElementById('searchInput');
    if (!input) return;
    var wrapper = input.closest('.sh-header-search') || input.parentElement;
    if (!wrapper) return;
    var dropdown = document.createElement('div');
    dropdown.className = 'ac-dropdown';
    dropdown.id = 'acDropdown';
    wrapper.style.position = 'relative';
    wrapper.appendChild(dropdown);
    input.addEventListener('input', onInput);
    input.addEventListener('keydown', onKeyDown);
    document.addEventListener('click', function(e) {
      if (!wrapper.contains(e.target)) hide();
    });
  }

  function onInput() {
    var input = document.getElementById('searchInput');
    var q = input.value.trim();
    if (q.length < MIN_CHARS) { hide(); return; }
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(function() { fetchResults(q); }, DEBOUNCE_MS);
  }

  function fetchResults(q) {
    var dropdown = document.getElementById('acDropdown');
    if (!dropdown) return;
    if (cache[q]) { render(cache[q], q); return; }
    dropdown.innerHTML = '<div class="ac-loading"><div class="ac-spinner"></div> جاري البحث...</div>';
    dropdown.classList.add('show');
    fetch('/api/search/suggest?q=' + encodeURIComponent(q))
      .then(function(r) { return r.json(); })
      .then(function(data) { cache[q] = data; render(data, q); })
      .catch(function() { dropdown.innerHTML = '<div class="ac-empty">تعذر تحميل النتائج</div>'; });
  }

  function render(data, q) {
    var dropdown = document.getElementById('acDropdown');
    if (!dropdown) return;
    if (!data.results || data.results.length === 0) {
      dropdown.innerHTML = '<div class="ac-empty">لا توجد نتائج</div>';
      dropdown.classList.add('show');
      return;
    }
    var html = '<div class="ac-header">' + data.count + ' نتيجة</div><div class="ac-list">';
    data.results.forEach(function(p, i) {
      html += '<a href="' + p.url + '" class="ac-item" data-index="' + i + '">';
      html += '<div class="ac-thumb">';
      if (p.image) html += '<img src="' + p.image + '" onerror="this.parentNode.innerHTML=\'\uD83D\uDCE6\'">';
      else html += '<span>\uD83D\uDCE6</span>';
      html += '</div><div class="ac-info"><div class="ac-name">' + hl(p.name, q) + '</div>';
      html += '<div class="ac-price">' + p.price + ' ر.ي</div></div><div class="ac-arrow">←</div></a>';
    });
    html += '</div><a href="/demo-shop?q=' + encodeURIComponent(q) + '" class="ac-all">\uD83D\uDD0D عرض كل النتائج</a>';
    dropdown.innerHTML = html;
    dropdown.classList.add('show');
    activeIndex = -1;
  }

  function hl(text, q) {
    if (!q) return esc(text);
    try {
      var r = new RegExp('(' + q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
      return esc(text).replace(r, '<mark>$1</mark>');
    } catch(e) { return esc(text); }
  }
  function esc(s) { var d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

  function onKeyDown(e) {
    var d = document.getElementById('acDropdown');
    if (!d || !d.classList.contains('show')) return;
    var items = d.querySelectorAll('.ac-item');
    if (e.key === 'ArrowDown') { e.preventDefault(); activeIndex = Math.min(activeIndex + 1, items.length - 1); up(items); }
    else if (e.key === 'ArrowUp') { e.preventDefault(); activeIndex = Math.max(activeIndex - 1, -1); up(items); }
    else if (e.key === 'Enter' && activeIndex >= 0 && items[activeIndex]) { e.preventDefault(); items[activeIndex].click(); }
    else if (e.key === 'Escape') { hide(); }
  }
  function up(items) { items.forEach(function(it, i) { it.classList.toggle('active', i === activeIndex); }); }
  function hide() { var d = document.getElementById('acDropdown'); if (d) d.classList.remove('show'); activeIndex = -1; }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();
