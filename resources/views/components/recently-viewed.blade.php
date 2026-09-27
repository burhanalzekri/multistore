{{-- 🕐 Recently Viewed — يعرض آخر 6 منتجات شاهدها المستخدم --}}
<div id="recentlyViewedContainer" style="display:none">
  <div class="rv-section">
    <div class="rv-header">
      <h2 class="rv-title">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
          <circle cx="12" cy="12" r="10"/>
          <polyline points="12 6 12 12 16 14"/>
        </svg>
        شاهدتها مؤخراً
      </h2>
      <a href="#" onclick="clearRecentlyViewed();return false" class="rv-clear">مسح الكل ×</a>
    </div>
    <div class="rv-grid" id="recentlyViewedGrid"></div>
  </div>
</div>

<script>
(function() {
  'use strict';
  var STORAGE_KEY = 'ms_recently_viewed';
  var MAX_ITEMS = 6;

  function getItems() {
    try {
      return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
    } catch(e) { return []; }
  }

  function saveItems(items) {
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(items.slice(0, MAX_ITEMS)));
    } catch(e) {}
  }

  // تتبع الزيارة الحالية
  function trackCurrentProduct() {
    var el = document.getElementById('recentlyViewedData');
    if (!el) return;
    var id = el.dataset.id;
    var name = el.dataset.name;
    var price = el.dataset.price;
    var image = el.dataset.image;
    var url = el.dataset.url;
    
    if (!id || !name) return;
    
    var items = getItems().filter(function(it) { return String(it.id) !== String(id); });
    items.unshift({
      id: id,
      name: name,
      price: price,
      image: image,
      url: url || ('/product/' + id),
      t: Date.now()
    });
    saveItems(items);
  }

  // عرض Recently Viewed
  function renderRecentlyViewed() {
    var container = document.getElementById('recentlyViewedContainer');
    var grid = document.getElementById('recentlyViewedGrid');
    if (!container || !grid) return;

    var items = getItems();
    var currentId = null;
    var dataEl = document.getElementById('recentlyViewedData');
    if (dataEl) currentId = dataEl.dataset.id;

    // استبعد المنتج الحالي
    var filtered = items.filter(function(it) { return String(it.id) !== String(currentId); });

    if (filtered.length === 0) {
      container.style.display = 'none';
      return;
    }

    var html = '';
    filtered.slice(0, MAX_ITEMS).forEach(function(it) {
      html += '<a href="' + it.url + '" class="rv-card">';
      html += '  <div class="rv-media">';
      if (it.image) {
        html += '    <img src="' + it.image + '" alt="" loading="lazy" onerror="this.parentNode.innerHTML=\'<span>📦</span>\'">';
      } else {
        html += '    <span>📦</span>';
      }
      html += '  </div>';
      html += '  <div class="rv-body">';
      html += '    <div class="rv-name">' + escapeHtml(it.name) + '</div>';
      html += '    <div class="rv-price">' + it.price + ' ر.ي</div>';
      html += '  </div>';
      html += '</a>';
    });
    grid.innerHTML = html;
    container.style.display = 'block';
  }

  function escapeHtml(s) {
    var d = document.createElement('div');
    d.textContent = s;
    return d.innerHTML;
  }

  window.clearRecentlyViewed = function() {
    if (!confirm('مسح قائمة المشاهدات الحديثة؟')) return;
    localStorage.removeItem(STORAGE_KEY);
    var c = document.getElementById('recentlyViewedContainer');
    if (c) c.style.display = 'none';
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
      trackCurrentProduct();
      renderRecentlyViewed();
    });
  } else {
    trackCurrentProduct();
    renderRecentlyViewed();
  }
})();
</script>

<style>
.rv-section {
  background: #fff;
  border-radius: 22px;
  padding: 22px;
  margin: 24px 0;
  border: 1px solid #f0eeea;
  box-shadow: 0 2px 12px rgba(0,0,0,.03);
}
.rv-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 18px;
  padding-bottom: 14px;
  border-bottom: 1px solid #f0eeea;
}
.rv-title {
  font-size: 17px;
  font-weight: 900;
  color: #17202b;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 10px;
}
.rv-title svg { color: #d97706; }
.rv-clear {
  font-size: 12px;
  color: #94a3b8;
  text-decoration: none;
  font-weight: 800;
  padding: 6px 12px;
  background: #f1f5f9;
  border-radius: 10px;
  transition: .2s;
}
.rv-clear:hover {
  background: #fee2e2;
  color: #dc2626;
}
.rv-grid {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  gap: 12px;
}
@media (max-width: 900px) { .rv-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 500px) { .rv-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; } }

.rv-card {
  background: #fff;
  border-radius: 14px;
  overflow: hidden;
  border: 1px solid #f0eeea;
  text-decoration: none;
  color: inherit;
  transition: all .25s;
  display: flex;
  flex-direction: column;
}
.rv-card:hover {
  transform: translateY(-3px);
  border-color: #fbbf24;
  box-shadow: 0 8px 20px rgba(217,119,6,.12);
}
.rv-media {
  aspect-ratio: 1;
  background: linear-gradient(145deg, #f5f2eb, #ebe8df);
  display: grid;
  place-items: center;
  font-size: 36px;
  overflow: hidden;
}
.rv-media img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.rv-body {
  padding: 10px 12px;
}
.rv-name {
  font-size: 12px;
  font-weight: 800;
  color: #17202b;
  line-height: 1.4;
  margin-bottom: 4px;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  min-height: 34px;
}
.rv-price {
  font-size: 13px;
  font-weight: 900;
  color: #d97706;
}

html.dark .rv-section { background: #1a1d21; border-color: #2a2e33; }
html.dark .rv-header { border-bottom-color: #2a2e33; }
html.dark .rv-title { color: #e5e7eb; }
html.dark .rv-card { background: #1a1d21; border-color: #2a2e33; }
html.dark .rv-name { color: #e5e7eb; }
html.dark .rv-media { background: #24282d; }
</style>
