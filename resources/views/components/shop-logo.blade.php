@props([
    'size' => 40,          // الحجم بالبكسل
    'variant' => 'default', // default | white | dark
    'rounded' => 'xl',     // sm | md | lg | xl | 2xl | full
    'showText' => false,   // عرض الاسم بجانب الشعار
    'name' => null,        // اسم المتجر (اختياري)
    'subtitle' => null,    // نص فرعي
    'link' => null,        // رابط (اختياري)
])

@php
    use App\Models\Shop;
    use Illuminate\Support\Facades\Storage;

    // احصل على المتجر من السياق أو استخدم shop_id
    $shop = null;

    if (auth()->check() && auth()->user()->shop_id) {
        $shop = Shop::find(auth()->user()->shop_id);
    }

    // إذا ما فيه shop — جرب TenantManager
    if (!$shop) {
        try {
            $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        } catch (\Throwable $e) {}
    }

    // شعار افتراضي
    $defaultLogo = asset('images/logo/default.svg');
    $logoUrl = $defaultLogo;
    $shopName = $name ?? ($shop?->name ?? 'MultiStore');
    $subtitleText = $subtitle ?? 'منصة التجارة الإلكترونية';

    // إذا المتجر عنده شعار
    if ($shop && !empty($shop->logo)) {
        $logoPath = $shop->logo;

        // إذا URL كامل (http)
        if (str_starts_with($logoPath, 'http://') || str_starts_with($logoPath, 'https://')) {
            $logoUrl = $logoPath;
        } else {
            // مسار محلي
            try {
                $logoUrl = Storage::url($logoPath);
            } catch (\Throwable $e) {
                $logoUrl = $defaultLogo;
            }
        }
    }

    // خصائص النمط
    $radiusMap = [
        'sm' => '8px',
        'md' => '10px',
        'lg' => '14px',
        'xl' => '16px',
        '2xl' => '22px',
        'full' => '50%',
    ];
    $radius = $radiusMap[$rounded] ?? '16px';

    // نمط الخلفية حسب variant
    $bgStyle = match ($variant) {
        'white' => 'background:rgba(255,255,255,.95);',
        'dark' => 'background:#0f172a;',
        default => 'background:linear-gradient(135deg,#f59e0b,#f97316);',
    };

    // نص اللون حسب variant
    $textColor = match ($variant) {
        'white' => '#0f172a',
        'dark' => '#fff',
        default => '#0f172a',
    };
    $subColor = match ($variant) {
        'white' => '#64748b',
        'dark' => '#94a3b8',
        default => '#64748b',
    };

    $component = $link ? 'a' : 'div';
    $hrefAttr = $link ? 'href="' . $link . '"' : '';
@endphp

<{{ $component }} {!! $hrefAttr !!}
   {{ $attributes->merge(['class' => 'inline-flex items-center gap-3 no-underline']) }}
   style="text-decoration:none;color:inherit;">

    {{-- Logo Image --}}
    <img src="{{ $logoUrl }}"
         alt="{{ $shopName }}"
         width="{{ $size }}"
         height="{{ $size }}"
         style="
             width:{{ $size }}px;
             height:{{ $size }}px;
             border-radius:{{ $radius }};
             object-fit:cover;
             flex-shrink:0;
             {{ $bgStyle }}
             box-shadow: 0 4px 12px rgba(15,23,42,.08), 0 8px 20px rgba(245,158,11,.1);
             transition: all .3s cubic-bezier(.2,.9,.3,1.1);
         "
         onerror="this.onerror=null; this.src='{{ $defaultLogo }}';"
         loading="eager">

    {{-- Text (Optional) --}}
    @if($showText)
        <div style="min-width:0;">
            <div style="
                font-size: {{ max(14, $size * 0.4) }}px;
                font-weight: 900;
                color: {{ $textColor }};
                line-height: 1.2;
                letter-spacing: -0.3px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            ">
                {{ $shopName }}
            </div>
            @if($subtitleText)
                <div style="
                    font-size: {{ max(10, $size * 0.24) }}px;
                    font-weight: 700;
                    color: {{ $subColor }};
                    margin-top: 2px;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                ">
                    {{ $subtitleText }}
                </div>
            @endif
        </div>
    @endif

</{{ $component }}>
