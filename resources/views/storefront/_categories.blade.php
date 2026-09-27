{{-- مكوّن التصنيفات — فلترة بـ category_id --}}
@php
  $currentCat = request('category');
@endphp

<nav class="sh-nav" id="catsNav">
  <a href="{{ url()->current() }}"
     class="sh-pill {{ !$currentCat ? 'active' : '' }}">
    الكل
  </a>

  @foreach($categories as $cat)
    <a href="{{ url()->current() }}?category={{ $cat->id }}"
       class="sh-pill {{ (string)$currentCat === (string)$cat->id ? 'active' : '' }}">
      {{ $cat->name }}
    </a>
  @endforeach
</nav>
