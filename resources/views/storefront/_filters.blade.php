{{-- 🎛️ شريط الفلاتر — نظيف ومنظم --}}
@php
  $currentUrl = url()->current();
  $currentQuery = request()->query();
  $hasFilters = request('q') || request('category') || request('min_price') || request('max_price') || (request('sort') && request('sort') !== 'latest');
  
  // رابط مع تحديث معامل واحد
  $withParam = function($key, $value) use ($currentUrl, $currentQuery) {
    $params = $currentQuery;
    if ($value === null || $value === '') {
      unset($params[$key]);
    } else {
      $params[$key] = $value;
    }
    return $currentUrl . ($params ? '?' . http_build_query($params) : '');
  };
@endphp

<div class="sh-filters">
  <div class="sh-filters-header">
    <div class="sh-filters-title">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 6h18M7 12h10M11 18h2"/>
      </svg>
      <span>تصفية النتائج</span>
    </div>
    @if($hasFilters)
      <a href="{{ $currentUrl }}" class="sh-filters-reset">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
        إلغاء الكل
      </a>
    @endif
  </div>

  <div class="sh-filters-grid">
    {{-- الترتيب --}}
    <div class="sh-filter-item">
      <label class="sh-filter-label">الترتيب</label>
      <div class="sh-filter-options">
        @foreach([
          'latest' => 'الأحدث',
          'price_asc' => 'الأرخص',
          'price_desc' => 'الأغلى',
          'best' => 'الأكثر مبيعاً',
        ] as $key => $label)
          <a href="{{ $withParam('sort', $key === 'latest' ? null : $key) }}"
             class="sh-filter-chip {{ (request('sort', 'latest') === $key) ? 'is-active' : '' }}">
            {{ $label }}
          </a>
        @endforeach
      </div>
    </div>

    {{-- السعر --}}
    <div class="sh-filter-item">
      <label class="sh-filter-label">نطاق السعر (ر.ي)</label>
      <form method="GET" action="{{ $currentUrl }}" class="sh-filter-price-form">
        @foreach($currentQuery as $k => $v)
          @if(!in_array($k, ['min_price', 'max_price']) && is_string($v))
            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
          @endif
        @endforeach
        <div class="sh-price-inputs">
          <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="من" min="0" class="sh-price-input">
          <span class="sh-price-sep">—</span>
          <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="إلى" min="0" class="sh-price-input">
          <button type="submit" class="sh-price-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            تطبيق
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
