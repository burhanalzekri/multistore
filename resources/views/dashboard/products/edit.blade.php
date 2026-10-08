@extends('layouts.app')
@section('title', 'تعديل: ' . $product->name)
@section('page-title', '✏️ تعديل المنتج')
@section('page-subtitle', 'عدّل بيانات "' . $product->name . '"')

@section('content')

<form method="POST" action="/dashboard/products/{{ $product->id }}" enctype="multipart/form-data" id="productForm"
      x-data="imageUpload()" class="ez-form">
  @csrf
  @method('PUT')

  {{-- ═══ 1) الباركوود ═══ --}}
  <div class="ez-card">
    <div class="ez-card-title">🏷️ الباركوود والرمز الخاص بالمنتج</div>

    <div class="ez-segmented">
      <button type="button" class="ez-seg-btn active" onclick="setBarcodeOption('auto', this)">✨ توليد تلقائي آمن</button>
      <button type="button" class="ez-seg-btn" onclick="setBarcodeOption('scan', this)">📷 مسح / إدخال باركوود</button>
    </div>

    <input type="hidden" name="barcode_mode" id="barcodeModeInput" value="auto">

    <div id="bc-auto-box" class="ez-auto-section">
      <div class="ez-auto-actions">
        <button type="button" onclick="generateUniqueBarcode()" class="ez-btn-gen">⚡ توليد باركوود جديد</button>
        <span class="ez-help-text">الباركوود الحالي: <strong id="currentBarcode">—</strong></span>
      </div>
    </div>

    <div id="bc-scan-box" style="display:none;" class="ez-scan-section">
      <div class="ez-scan-input-wrap">
        <input type="text" id="scanBarcodeInput" name="barcode" value="{{ old('barcode', $product->barcode) }}" placeholder="امسح أو اكتب..." class="ez-input ez-input-lg" oninput="verifyBarcodeUniqueness(this.value)">
        <button type="button" onclick="openBarcodeScanner()" class="ez-btn-icon">📷 الكاميرا</button>
      </div>
      <div id="barcodeStatusMsg" class="ez-status-msg"></div>
    </div>

    <div id="barcodePreviewCard" class="ez-barcode-preview-card" style="display:{{ $product->barcode ? 'block' : 'none' }};">
      <div class="ez-preview-head">
        <span class="ez-preview-title">🔍 معاينة الباركوود:</span>
        <button type="button" onclick="printBarcodeLabel()" class="ez-btn-print">🖨️ طباعة</button>
      </div>
      <div class="ez-preview-body">
        <svg id="barcodeCanvas"></svg>
        <input type="hidden" name="final_barcode" id="finalBarcodeInput" value="{{ old('barcode', $product->barcode) }}">
      </div>
    </div>
  </div>

  {{-- ═══ 2) البيانات الأساسية + الصور ═══ --}}
  <div class="ez-card">
    <div class="ez-card-title">📝 تفاصيل المنتج</div>

    <div class="ez-form-group">
      <label class="ez-label">اسم المنتج <span class="req">*</span></label>
      <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="ez-input">
    </div>

    <div class="ez-grid-2">
      <div class="ez-form-group">
        <label class="ez-label">السعر (ر.س) <span class="req">*</span></label>
        <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0" step="0.01" class="ez-input">
      </div>
      <div class="ez-form-group">
        <label class="ez-label">السعر قبل الخصم</label>
        <input type="number" name="compare_price" value="{{ old('compare_price', $product->compare_price) }}" min="0" step="0.01" class="ez-input">
      </div>
    </div>

    <div class="ez-form-group">
      <label class="ez-label">المخزون <span class="req">*</span></label>
      <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required min="0" class="ez-input">
    </div>

    <div class="ez-form-group">
      <label class="ez-label">التصنيف</label>
      <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
        <div id="catChips" class="ez-chips-wrap" style="flex:1;min-width:0;"></div>
        <button type="button" onclick="openAddCategory()" class="ez-btn-new-cat">➕ صنف جديد</button>
      </div>
      <input type="hidden" name="category_id" id="categoryIdInput" value="{{ old('category_id', $product->category_id) }}">
    </div>

    <div class="ez-form-group">
      <label class="ez-label">الوصف</label>
      <textarea name="description" rows="4" class="ez-input" style="min-height:100px;resize:vertical;">{{ old('description', $product->description) }}</textarea>
    </div>

    <div class="ez-grid-2">
      <div>
        <label class="ez-label">الصورة الرئيسية</label>
        <div class="ez-image-upload-box" style="position:relative;">
          <img src="{{ $product->image_url ?? '' }}" id="mainPreviewImg" style="width:100%;height:100%;object-fit:cover;display:{{ $product->image ? 'block' : 'none' }};">
          <label for="mainImageInput" class="ez-upload-label" style="position:absolute;bottom:0;left:0;right:0;background:rgba(255,255,255,0.9);">📤 استبدال الصورة</label>
          <input type="file" name="image" id="mainImageInput" accept="image/*" style="position:absolute;opacity:0;width:1px;height:1px;pointer-events:none;">
        </div>
      </div>
      <div>
        <label class="ez-label">صور إضافية ({{ $product->gallery ? count($product->gallery) : 0 }} حالياً)</label>
        <label for="galleryInput" class="ez-upload-label compact">📁 إضافة صور جديدة</label>
        <input type="file" name="gallery[]" id="galleryInput" accept="image/*" multiple style="display:none;">
        <div class="ez-gallery-grid">
          @if($product->gallery)
            @foreach($product->gallery as $g)
              <img src="{{ str_starts_with($g, 'http') ? $g : asset('storage/' . $g) }}" class="ez-gallery-img">
            @endforeach
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- ═══ 3) فيديو ═══ --}}
  <div class="ez-card">
    <div class="ez-card-title">🎥 فيديو المنتج (اختياري)</div>
    <div class="ez-grid-2">
      <div class="ez-form-group">
        <label class="ez-label">ملف الفيديو</label>
        <input type="file" name="video" accept="video/mp4,video/webm" class="ez-input">
        @if($product->video)
          <div class="ez-help-text" style="color:#16a34a;">✅ يوجد فيديو محفوظ</div>
        @endif
      </div>
      <div class="ez-form-group">
        <label class="ez-label">صورة الغلاف</label>
        <input type="file" name="video_poster" accept="image/*" class="ez-input">
      </div>
    </div>
  </div>

  {{-- ═══ 4) نوع المخزون ═══ --}}
  <div class="ez-card">
    <div class="ez-card-title">📊 نوع المخزون المتعدد (Variants)</div>

    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:16px;">
      <label class="ez-vtype">
        <input type="radio" name="variant_type" value="simple" onchange="setVariantType('simple')" {{ $product->variants->count() == 0 ? 'checked' : '' }} style="display:none">
        <div class="ez-vtype-inner"><span style="font-size:22px;">📦</span><span>بسيط</span></div>
      </label>
      <label class="ez-vtype">
        <input type="radio" name="variant_type" value="colors" onchange="setVariantType('colors')" style="display:none">
        <div class="ez-vtype-inner"><span style="font-size:22px;">🎨</span><span>ألوان فقط</span></div>
      </label>
      <label class="ez-vtype">
        <input type="radio" name="variant_type" value="sizes" onchange="setVariantType('sizes')" style="display:none">
        <div class="ez-vtype-inner"><span style="font-size:22px;">📏</span><span>مقاسات فقط</span></div>
      </label>
      <label class="ez-vtype">
        <input type="radio" name="variant_type" value="both" onchange="setVariantType('both')" style="display:none">
        <div class="ez-vtype-inner"><span style="font-size:22px;">👕</span><span>ألوان + مقاسات</span></div>
      </label>
    </div>

    <div id="ve-colors" class="ez-vsection" style="display:none;">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
        <span style="font-weight:900;font-size:13px;">{{ optional($shop)->variantIcon1() ?? "🎨" }} {{ optional($shop)->variantLabel1() ?? "الألوان" }}</span>
        <button type="button" onclick="veAddColor()" class="ez-btn-sm">+ لون</button>
      </div>
      <div id="ve-colors-list"></div>
    </div>

    <div id="ve-sizes" class="ez-vsection" style="display:none;">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
        <span style="font-weight:900;font-size:13px;">{{ optional($shop)->variantIcon2() ?? "📏" }} {{ optional($shop)->variantLabel2() ?? "المقاسات" }}</span>
        <button type="button" onclick="veAddSize()" class="ez-btn-sm">+ مقاس</button>
      </div>
      <div id="ve-sizes-list"></div>
    </div>

    <div id="ve-both" class="ez-vsection" style="display:none;">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
        <span style="font-weight:900;font-size:13px;">{{ optional($shop)->variantIcon1() ?? "🎨" }} {{ optional($shop)->variantLabel1() ?? "الألوان" }}</span>
        <button type="button" onclick="veBothAddColor()" class="ez-btn-sm">+ لون</button>
      </div>
      <div id="ve-both-colors"></div>

      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;margin-top:14px;">
        <span style="font-weight:900;font-size:13px;">{{ optional($shop)->variantIcon2() ?? "📏" }} {{ optional($shop)->variantLabel2() ?? "المقاسات" }}</span>
        <button type="button" onclick="veBothAddSize()" class="ez-btn-sm">+ مقاس</button>
      </div>
      <div id="ve-both-sizes"></div>
      <div id="ve-grid" style="margin-top:14px;"></div>
    </div>

    <div id="ve-summary" class="ez-summary-banner" style="display:{{ $product->variants->count() > 0 ? 'flex' : 'none' }};">
      <div style="display:flex;align-items:center;gap:8px;">
        <span>✅</span><span>إجمالي المخزون:</span>
      </div>
      <span id="ve-total" class="ez-summary-badge">0 قطعة</span>
    </div>
  </div>

  {{-- ═══ 5) أزرار ═══ --}}
  <div class="ez-actions">
    <button type="submit" class="ez-btn-save">💾 حفظ التعديلات</button>
    <a href="/dashboard/products" class="ez-btn-cancel">إلغاء</a>
  </div>

</form>

{{-- Modal: إضافة صنف --}}
<div id="addCategoryModal" class="ez-modal" style="display:none;">
  <div class="ez-modal-box">
    <div class="ez-modal-head">
      <h3>➕ إضافة صنف جديد</h3>
      <button type="button" onclick="closeAddCategory()" class="ez-close">×</button>
    </div>
    <p class="ez-modal-subtitle">سيُضاف الصنف فوراً لقائمة تصنيفات متجرك</p>
    <div class="ez-form-group">
      <label class="ez-label">اسم الصنف <span class="req">*</span></label>
      <input type="text" id="newCatName" class="ez-input" onkeydown="if(event.key==='Enter'){event.preventDefault();saveCategory();}">
    </div>
    <div class="ez-form-group">
      <label class="ez-label">أيقونة</label>
      <div id="catIconPicker" class="ez-icon-grid">
        <button type="button" class="ez-icon-btn active" data-icon="📂" onclick="pickCatIcon(this)">📂</button>
        <button type="button" class="ez-icon-btn" data-icon="🍯" onclick="pickCatIcon(this)">🍯</button>
        <button type="button" class="ez-icon-btn" data-icon="👕" onclick="pickCatIcon(this)">👕</button>
        <button type="button" class="ez-icon-btn" data-icon="👟" onclick="pickCatIcon(this)">👟</button>
        <button type="button" class="ez-icon-btn" data-icon="📱" onclick="pickCatIcon(this)">📱</button>
        <button type="button" class="ez-icon-btn" data-icon="💄" onclick="pickCatIcon(this)">💄</button>
        <button type="button" class="ez-icon-btn" data-icon="🎁" onclick="pickCatIcon(this)">🎁</button>
        <button type="button" class="ez-icon-btn" data-icon="🛍️" onclick="pickCatIcon(this)">🛍️</button>
      </div>
    </div>
    <div id="catError" class="ez-error" style="display:none;"></div>
    <div class="ez-modal-footer">
      <button type="button" onclick="closeAddCategory()" class="ez-btn-cancel-modal">إلغاء</button>
      <button type="button" id="saveCatBtn" onclick="saveCategory()" class="ez-btn-save-modal">💾 حفظ</button>
    </div>
  </div>
</div>

{{-- Modal: Scanner --}}
<div id="barcodeScannerModal" class="ez-modal" style="display:none;">
  <div class="ez-modal-box">
    <div class="ez-modal-head">
      <h3>📷 مسح الباركوود</h3>
      <button type="button" onclick="closeInlineScanner()" class="ez-close">×</button>
    </div>
    <div id="inlineReader" style="height:230px;background:#000;border-radius:12px;overflow:hidden;"></div>
  </div>
</div>

<style>
  .ez-form { max-width: 760px; margin: 0 auto; display: flex; flex-direction: column; gap: 16px; font-family: inherit; }
  .ez-card { background: #fff; border-radius: 16px; padding: 20px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
  .ez-card-title { font-size: 15px; font-weight: 800; color: #1e293b; margin-bottom: 14px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px; }
  .ez-segmented { display: flex; gap: 8px; background: #f8fafc; padding: 4px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 14px; }
  .ez-seg-btn { flex: 1; padding: 10px; border: 0; background: transparent; border-radius: 9px; font-size: 13px; font-weight: 800; color: #64748b; cursor: pointer; font-family: inherit; }
  .ez-seg-btn.active { background: #fff; color: #d97706; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
  .ez-btn-gen { padding: 12px 20px; background: linear-gradient(135deg, #10b981, #059669); color: #fff; border: 0; border-radius: 10px; font-size: 13px; font-weight: 800; cursor: pointer; font-family: inherit; }
  .ez-auto-actions { display: flex; flex-direction: column; gap: 8px; }
  .ez-help-text { font-size: 11px; color: #64748b; font-weight: 700; }
  .ez-scan-input-wrap { display: flex; gap: 8px; }
  .ez-btn-icon { padding: 0 16px; background: #0f172a; color: #fff; border: 0; border-radius: 10px; font-size: 12px; font-weight: 800; cursor: pointer; font-family: inherit; white-space: nowrap; }
  .ez-barcode-preview-card { background: #fffdf5; border: 1.5px solid #fde68a; border-radius: 12px; padding: 16px; margin-top: 14px; text-align: center; }
  .ez-preview-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
  .ez-preview-title { font-size: 13px; font-weight: 800; color: #92400e; }
  .ez-btn-print { padding: 6px 12px; background: #fff; border: 1px solid #f59e0b; color: #b45309; border-radius: 8px; font-size: 11px; font-weight: 800; cursor: pointer; }
  .ez-preview-body { background: #fff; padding: 10px; border-radius: 8px; border: 1px solid #fef3c7; display: inline-block; }
  .ez-status-msg { font-size: 12px; font-weight: 800; margin-top: 4px; }
  .ez-status-msg.ok { color: #16a34a; } .ez-status-msg.err { color: #dc2626; }
  .ez-form-group { margin-bottom: 12px; }
  .ez-label { font-size: 12px; font-weight: 800; color: #475569; display: block; margin-bottom: 6px; }
  .req { color: #ef4444; }
  .ez-input { width: 100%; padding: 10px 14px; border: 1.5px solid #cbd5e1; border-radius: 10px; font-size: 13px; font-weight: 700; color: #0f172a; outline: none; font-family: inherit; }
  .ez-input:focus { border-color: #f59e0b; }
  .ez-input-lg { padding: 12px; font-size: 14px; text-align: center; font-family: monospace; }
  .ez-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
  .ez-chips-wrap { display: flex; gap: 6px; flex-wrap: wrap; }
  .ez-chip { padding: 6px 12px; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 12px; font-weight: 800; color: #475569; cursor: pointer; }
  .ez-chip.active { background: #f59e0b; color: #fff; border-color: #f59e0b; }
  .ez-btn-new-cat { padding: 9px 16px; background: linear-gradient(135deg, #0f172a, #334155); color: #fff; border: 0; border-radius: 10px; font-size: 12px; font-weight: 800; cursor: pointer; font-family: inherit; white-space: nowrap; }
  .ez-image-upload-box { height: 130px; border: 2px dashed #cbd5e1; border-radius: 12px; overflow: hidden; display: grid; place-items: center; background: #f8fafc; }
  .ez-upload-label { display: block; padding: 8px; text-align: center; color: #d97706; font-size: 12px; font-weight: 800; cursor: pointer; }
  .ez-upload-label.compact { background: #f1f5f9; border-radius: 8px; color: #475569; padding: 8px; margin-bottom: 8px; }
  .ez-gallery-grid { display: flex; gap: 6px; overflow-x: auto; flex-wrap: wrap; }
  .ez-gallery-img { width: 50px; height: 50px; border-radius: 8px; object-fit: cover; border: 1px solid #e2e8f0; }
  .ez-vtype { cursor: pointer; }
  .ez-vtype input { display: none; }
  .ez-vtype-inner { padding: 14px 8px; border: 2px solid #e2e8f0; border-radius: 14px; text-align: center; display: flex; flex-direction: column; gap: 4px; align-items: center; background: #fff; font-size: 12px; font-weight: 900; }
  .ez-vtype input:checked + .ez-vtype-inner { border-color: #f59e0b; background: #fff7ed; }
  .ez-vsection { background: #f8fafc; border-radius: 14px; padding: 14px; margin-bottom: 12px; border: 1px solid #e2e8f0; }
  .ez-btn-sm { padding: 6px 12px; background: #0f172a; color: #fff; border: 0; border-radius: 8px; font-size: 11px; font-weight: 800; cursor: pointer; font-family: inherit; }
  .ve-row { display: grid; gap: 8px; align-items: center; background: #fff; padding: 8px; border-radius: 10px; margin-bottom: 6px; border: 1px solid #e2e8f0; }
  .ve-del { width: 34px; height: 34px; border: 0; background: #fee2e2; color: #ef4444; border-radius: 8px; font-size: 16px; font-weight: 900; cursor: pointer; }
  .ez-summary-banner { padding: 14px 18px; background: #f0fdf4; border: 1.5px solid #bbf7d0; border-radius: 12px; font-weight: 900; color: #166534; display: flex; justify-content: space-between; align-items: center; margin-top: 14px; }
  .ez-summary-badge { background: #16a34a; color: #fff; padding: 5px 14px; border-radius: 10px; font-size: 12px; }
  .ez-actions { display: flex; gap: 10px; margin-top: 8px; }
  .ez-btn-save { flex: 2; padding: 14px; background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; border: 0; border-radius: 12px; font-size: 14px; font-weight: 900; cursor: pointer; font-family: inherit; }
  .ez-btn-cancel { flex: 1; padding: 14px; background: #f1f5f9; color: #475569; border: 0; border-radius: 12px; font-size: 13px; font-weight: 800; text-decoration: none; text-align: center; }
  .ez-modal { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(6px); z-index: 10000; display: flex; align-items: center; justify-content: center; padding: 20px; }
  .ez-modal-box { background: #fff; border-radius: 20px; padding: 24px; max-width: 420px; width: 100%; }
  .ez-modal-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }
  .ez-modal-head h3 { font-size: 16px; font-weight: 900; color: #0f172a; margin: 0; }
  .ez-modal-subtitle { font-size: 12px; color: #64748b; margin: 0 0 16px 0; font-weight: 700; }
  .ez-icon-grid { display: flex; gap: 8px; flex-wrap: wrap; }
  .ez-icon-btn { width: 42px; height: 42px; border: 1.5px solid #e2e8f0; border-radius: 12px; background: #fff; font-size: 20px; cursor: pointer; }
  .ez-icon-btn.active { border-color: #f59e0b; background: #fff7ed; }
  .ez-modal-footer { display: flex; gap: 10px; margin-top: 20px; }
  .ez-btn-cancel-modal { flex: 1; padding: 12px; background: #f1f5f9; color: #475569; border: 0; border-radius: 10px; font-size: 13px; font-weight: 800; cursor: pointer; }
  .ez-btn-save-modal { flex: 2; padding: 12px; background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; border: 0; border-radius: 10px; font-size: 13px; font-weight: 900; cursor: pointer; }
  .ez-error { background: #fef2f2; color: #dc2626; padding: 10px 14px; border-radius: 10px; font-size: 12px; font-weight: 800; margin-top: 12px; }
  .ez-close { border: 0; background: #f1f5f9; border-radius: 50%; width: 28px; height: 28px; cursor: pointer; font-size: 18px; }
  @media(max-width: 600px) { .ez-grid-2 { grid-template-columns: 1fr; } }
</style>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.1/dist/cropper.min.css">
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.1/dist/cropper.min.js"></script>
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
  // ====== البيانات الأولية من السيرفر ======
  window._categories = {!! json_encode(($categories ?? collect())->map(function($c) { return ['id'=>$c->id,'name'=>$c->name]; })->values()) !!};
  window._selectedCat = {{ $product->category_id ?? 'null' }};
  window._productBarcode = "{{ $product->barcode ?? '' }}";
  window._existingVariants = {!! json_encode($product->variants->map(function($v) { return ['size'=>$v->size,'color'=>$v->color,'color_hex'=>$v->color_hex ?? '#000000','stock'=>$v->stock,'sku'=>$v->sku]; })->values()) !!};

  // ====== الصور ======
  function imageUpload() { return {}; }
  window.previewMainImage = function(input) {
    if (!input.files || !input.files[0]) return;
    var r = new FileReader();
    r.onload = function(e) {
      var img = document.getElementById('mainPreviewImg');
      img.src = e.target.result;
      img.style.display = 'block';
    };
    r.readAsDataURL(input.files[0]);
  };

  // ====== التصنيفات ======
  window.renderCategories = function() {
    var wrap = document.getElementById('catChips');
    if (!wrap) return;
    var html = '<button type="button" class="ez-chip ' + (window._selectedCat === null ? 'active' : '') + '" onclick="selectCat(null, this)">🚫 بدون</button>';
    window._categories.forEach(function(c) {
      var active = String(window._selectedCat) === String(c.id);
      html += '<button type="button" class="ez-chip ' + (active ? 'active' : '') + '" onclick="selectCat(' + c.id + ', this)">' + c.name + '</button>';
    });
    wrap.innerHTML = html;
  };
  window.selectCat = function(id, btn) {
    window._selectedCat = id;
    document.getElementById('categoryIdInput').value = id || '';
    document.querySelectorAll('.ez-chip').forEach(function(b) { b.classList.remove('active'); });
    if (btn) btn.classList.add('active');
  };
  window.openAddCategory = function() {
    document.getElementById('addCategoryModal').style.display = 'flex';
    document.getElementById('catError').style.display = 'none';
    document.getElementById('newCatName').value = '';
    setTimeout(function() { document.getElementById('newCatName').focus(); }, 100);
  };
  window.closeAddCategory = function() { document.getElementById('addCategoryModal').style.display = 'none'; };
  window.pickCatIcon = function(btn) {
    document.querySelectorAll('#catIconPicker .ez-icon-btn').forEach(function(b) { b.classList.remove('active'); });
    btn.classList.add('active');
  };
  window.saveCategory = async function() {
    var name = document.getElementById('newCatName').value.trim();
    var iconEl = document.querySelector('#catIconPicker .ez-icon-btn.active');
    var icon = iconEl ? iconEl.dataset.icon : '📂';
    var err = document.getElementById('catError');
    var btn = document.getElementById('saveCatBtn');
    if (!name) { err.textContent = 'اكتب اسم الصنف'; err.style.display = 'block'; return; }
    err.style.display = 'none';
    btn.disabled = true; btn.textContent = '⏳';
    try {
      var resp = await fetch('/dashboard/categories/quick', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '', 'Accept': 'application/json' },
        body: JSON.stringify({ name: name, icon: icon })
      });
      var data = await resp.json();
      if (data.ok && data.category) {
        window._categories.push({ id: data.category.id, name: data.category.name });
        window._selectedCat = data.category.id;
        document.getElementById('categoryIdInput').value = data.category.id;
        window.renderCategories();
        window.closeAddCategory();
      } else {
        err.textContent = data.message || 'خطأ'; err.style.display = 'block';
      }
    } catch(e) { err.textContent = 'خطأ اتصال: ' + e.message; err.style.display = 'block'; }
    btn.disabled = false; btn.textContent = '💾 حفظ';
  };

  // ====== الباركوود ======
  window.setBarcodeOption = function(mode, btn) {
    document.getElementById('barcodeModeInput').value = mode;
    document.querySelectorAll('.ez-seg-btn').forEach(function(b) { b.classList.remove('active'); });
    btn.classList.add('active');
    document.getElementById('bc-auto-box').style.display = mode === 'auto' ? 'block' : 'none';
    document.getElementById('bc-scan-box').style.display = mode === 'scan' ? 'block' : 'none';
  };
  window.generateUniqueBarcode = function() {
    var code = '200' + Math.floor(100000000 + Math.random() * 900000000);
    renderBarcodePreview(code);
  };
  window.verifyBarcodeUniqueness = function(code) {
    var msg = document.getElementById('barcodeStatusMsg');
    if (!code || code.length < 3) { msg.textContent = ''; return; }
    fetch('/dashboard/products/check-barcode?code=' + encodeURIComponent(code))
      .then(function(r) { return r.json(); })
      .then(function(data) {
        if (data.exists && code !== window._productBarcode) {
          msg.textContent = '⚠️ مستخدم مسبقاً'; msg.className = 'ez-status-msg err';
        } else {
          msg.textContent = '✅ متاح'; msg.className = 'ez-status-msg ok';
          renderBarcodePreview(code);
        }
      }).catch(function() { renderBarcodePreview(code); });
  };
  window.renderBarcodePreview = function(code) {
    document.getElementById('barcodePreviewCard').style.display = 'block';
    document.getElementById('finalBarcodeInput').value = code;
    document.getElementById('currentBarcode').textContent = code;
    try { JsBarcode("#barcodeCanvas", code, { format: "CODE128", width: 2, height: 50, displayValue: true }); } catch(e) {}
  };
  window.printBarcodeLabel = function() {
    var win = window.open('', '_blank', 'width=400,height=300');
    var svg = document.getElementById('barcodeCanvas').outerHTML;
    win.document.write('<html><body style="text-align:center;padding:20px;">' + svg + '</body></html>');
    win.document.close();
    setTimeout(function() { win.print(); }, 300);
  };

  // ====== Scanner ======
  var inlineScanner = null;
  window.openBarcodeScanner = function() {
    document.getElementById('barcodeScannerModal').style.display = 'flex';
    if (!inlineScanner) inlineScanner = new Html5Qrcode('inlineReader');
    inlineScanner.start({ facingMode: 'environment' }, { fps: 10, qrbox: { width: 200, height: 120 } },
      function(text) { document.getElementById('scanBarcodeInput').value = text; verifyBarcodeUniqueness(text); closeInlineScanner(); },
      function() {}
    ).catch(function() { closeInlineScanner(); });
  };
  window.closeInlineScanner = function() {
    document.getElementById('barcodeScannerModal').style.display = 'none';
    if (inlineScanner) inlineScanner.stop().catch(function(){});
  };

  // ====== Variants ======
  var veType = 'simple', veColors = [], veSizes = [], veBothColors = [], veBothSizes = [];

  window.setVariantType = function(type) {
    veType = type;
    document.getElementById('ve-colors').style.display = type === 'colors' ? 'block' : 'none';
    document.getElementById('ve-sizes').style.display = type === 'sizes' ? 'block' : 'none';
    document.getElementById('ve-both').style.display = type === 'both' ? 'block' : 'none';
    if (type === 'colors' && !veColors.length) veAddColor();
    if (type === 'sizes' && !veSizes.length) veAddSize();
    if (type === 'both' && !veBothColors.length && !veBothSizes.length) { veBothAddColor(); veBothAddSize(); }
    // 🧹 تفريغ الأقسام غير النشطة من hidden inputs
    cleanupInactiveVariantInputs();
    updateTotal();
  };

  window.cleanupInactiveVariantInputs = function() {
    // نمسح hidden inputs من الأقسام المخفية
    if (veType !== 'colors') {
      var el = document.getElementById('ve-colors-list');
      if (el) el.innerHTML = '';
    }
    if (veType !== 'sizes') {
      var el = document.getElementById('ve-sizes-list');
      if (el) el.innerHTML = '';
    }
    if (veType !== 'both') {
      var el1 = document.getElementById('ve-both-colors');
      var el2 = document.getElementById('ve-both-sizes');
      var el3 = document.getElementById('ve-grid');
      if (el1) el1.innerHTML = '';
      if (el2) el2.innerHTML = '';
      if (el3) el3.innerHTML = '';
    }
  };

  window.veAddColor = function(name, hex, stock) { veColors.push({ name: name||'', hex: hex||'#000000', stock: stock||0 }); renderColors(); };
  window.veDelColor = function(i) { veColors.splice(i,1); renderColors(); };
  function renderColors() {
    document.getElementById('ve-colors-list').innerHTML = veColors.map(function(c,i) {
      return '<div class="ve-row" style="grid-template-columns:42px 1fr 100px 34px;">' +
        '<input type="color" value="' + c.hex + '" onchange="veColors[' + i + '].hex=this.value" style="width:40px;height:40px;border:0;border-radius:8px;">' +
        '<input type="text" placeholder="اسم اللون" value="' + c.name + '" class="ez-input" style="padding:8px;" oninput="veColors[' + i + '].name=this.value;syncColorsHidden()">' +
        '<input type="number" min="0" placeholder="الكمية" value="' + c.stock + '" class="ez-input" style="padding:8px;" oninput="veColors[' + i + '].stock=parseInt(this.value)||0;updateTotal()">' +
        '<button type="button" class="ve-del" onclick="veDelColor(' + i + ')">×</button>' +
        '<input type="hidden" name="variants[' + i + '][color]" value="' + c.name + '">' +
        '<input type="hidden" name="variants[' + i + '][color_hex]" value="' + c.hex + '">' +
        '<input type="hidden" name="variants[' + i + '][stock]" value="' + c.stock + '">' +
        '</div>';
    }).join('');
  }

  window.veAddSize = function(name, stock) { veSizes.push({ name: name||'', stock: stock||0 }); renderSizes(); };
  window.veDelSize = function(i) { veSizes.splice(i,1); renderSizes(); };
  function renderSizes() {
    document.getElementById('ve-sizes-list').innerHTML = veSizes.map(function(s,i) {
      return '<div class="ve-row" style="grid-template-columns:1fr 100px 34px;">' +
        '<input type="text" placeholder="المقاس" value="' + s.name + '" class="ez-input" style="padding:8px;" oninput="veSizes[' + i + '].name=this.value;syncSizesHidden()">' +
        '<input type="number" min="0" placeholder="الكمية" value="' + s.stock + '" class="ez-input" style="padding:8px;" oninput="veSizes[' + i + '].stock=parseInt(this.value)||0;updateTotal()">' +
        '<button type="button" class="ve-del" onclick="veDelSize(' + i + ')">×</button>' +
        '<input type="hidden" name="variants[' + i + '][size]" value="' + s.name + '">' +
        '<input type="hidden" name="variants[' + i + '][stock]" value="' + s.stock + '">' +
        '</div>';
    }).join('');
  }

  window.veBothAddColor = function(name, hex) { veBothColors.push({ name: name||'', hex: hex||'#000000' }); renderBoth(); };
  window.veBothDelColor = function(i) { veBothColors.splice(i,1); renderBoth(); };
  window.veBothAddSize = function(name) { veBothSizes.push(name||''); renderBoth(); };
  window.veBothDelSize = function(i) { veBothSizes.splice(i,1); renderBoth(); };
  function renderBoth() {
    document.getElementById('ve-both-colors').innerHTML = veBothColors.map(function(c,i) {
      return '<div class="ve-row" style="grid-template-columns:42px 1fr 34px;">' +
        '<input type="color" value="' + c.hex + '" onchange="veBothColors[' + i + '].hex=this.value;renderBoth()" style="width:40px;height:40px;border:0;border-radius:8px;">' +
        '<input type="text" placeholder="اسم اللون" value="' + c.name + '" class="ez-input" style="padding:8px;" oninput="veBothColors[' + i + '].name=this.value;syncBothHidden()">' +
        '<button type="button" class="ve-del" onclick="veBothDelColor(' + i + ')">×</button>' +
        '</div>';
    }).join('');
    document.getElementById('ve-both-sizes').innerHTML = veBothSizes.map(function(s,i) {
      return '<div class="ve-row" style="grid-template-columns:1fr 34px;">' +
        '<input type="text" placeholder="المقاس" value="' + s + '" class="ez-input" style="padding:8px;" oninput="veBothSizes[' + i + ']=this.value;syncBothHidden()">' +
        '<button type="button" class="ve-del" onclick="veBothDelSize(' + i + ')">×</button>' +
        '</div>';
    }).join('');

    if (veBothColors.length && veBothSizes.length) {
      var gh = '<div style="overflow-x:auto;"><table style="width:100%;border-collapse:separate;border-spacing:6px;"><thead><tr><th></th>';
      veBothSizes.forEach(function(s) { gh += '<th style="padding:8px;font-size:11px;font-weight:900;background:#fff;border-radius:8px;border:1px solid #e2e8f0;">' + (s||'—') + '</th>'; });
      gh += '</tr></thead><tbody>';
      veBothColors.forEach(function(c,ci) {
        gh += '<tr><td style="text-align:right;font-size:12px;font-weight:900;color:#334155;white-space:nowrap;padding:4px;">';
        gh += '<span style="display:inline-block;width:14px;height:14px;border-radius:50%;background:' + c.hex + ';vertical-align:middle;margin-inline-end:6px;border:1px solid #cbd5e1;"></span>';
        gh += (c.name || '—') + '</td>';
        veBothSizes.forEach(function(s,si) {
          gh += '<td><input type="number" min="0" value="0" id="grid-' + ci + '_' + si + '" class="ez-input" style="text-align:center;padding:8px;" oninput="updateTotal();syncBothHidden()"></td>';
        });
        gh += '</tr>';
      });
      gh += '</tbody></table></div>';
      gh += '<div style="display:none;">';
      veBothColors.forEach(function(c,ci) {
        veBothSizes.forEach(function(s,si) {
          var idx = ci + '_' + si;
          gh += '<input type="hidden" name="variants[' + idx + '][size]" value="' + s + '">';
          gh += '<input type="hidden" name="variants[' + idx + '][color]" value="' + c.name + '">';
          gh += '<input type="hidden" name="variants[' + idx + '][color_hex]" value="' + c.hex + '">';
          gh += '<input type="hidden" name="variants[' + idx + '][stock]" id="grid-h-' + idx + '" value="0">';
        });
      });
      gh += '</div>';
      document.getElementById('ve-grid').innerHTML = gh;
    } else {
      document.getElementById('ve-grid').innerHTML = '<div style="text-align:center;padding:14px;color:#94a3b8;font-size:12px;">أضف ألواناً ومقاسات لبناء الشبكة</div>';
    }
  }

  window.updateTotal = function() {
    var total = 0;
    if (veType === 'colors') veColors.forEach(function(c) { total += c.stock || 0; });
    else if (veType === 'sizes') veSizes.forEach(function(s) { total += s.stock || 0; });
    else if (veType === 'both') {
      veBothColors.forEach(function(c,ci) {
        veBothSizes.forEach(function(s,si) {
          var el = document.getElementById('grid-' + ci + '_' + si);
          var val = el ? parseInt(el.value) || 0 : 0;
          total += val;
          var h = document.getElementById('grid-h-' + ci + '_' + si);
          if (h) h.value = val;
        });
      });
    }
    var sum = document.getElementById('ve-summary');
    if (sum) sum.style.display = total > 0 ? 'flex' : 'none';
    var t = document.getElementById('ve-total');
    if (t) t.textContent = total + ' قطعة';
  };

  // ====== التهيئة ======
  document.addEventListener('DOMContentLoaded', function() {
    renderCategories();

    // الباركوود الحالي
    if (window._productBarcode) {
      renderBarcodePreview(window._productBarcode);
    } else {
      generateUniqueBarcode();
    }

    // تحميل variants الحالية
    var vs = window._existingVariants;
    if (vs && vs.length) {
      var hasColors = vs.some(function(v) { return v.color; });
      var hasSizes = vs.some(function(v) { return v.size; });
      var type = hasColors && hasSizes ? 'both' : (hasColors ? 'colors' : 'sizes');
      
      if (type === 'colors') {
        document.querySelector('input[name="variant_type"][value="colors"]').checked = true;
        vs.forEach(function(v) { veAddColor(v.color, v.color_hex, v.stock); });
        setVariantType('colors');
      } else if (type === 'sizes') {
        document.querySelector('input[name="variant_type"][value="sizes"]').checked = true;
        vs.forEach(function(v) { veAddSize(v.size, v.stock); });
        setVariantType('sizes');
      } else {
        document.querySelector('input[name="variant_type"][value="both"]').checked = true;
        // استخراج ألوان ومقاسات فريدة
        var colors = [], sizes = [];
        vs.forEach(function(v) {
          if (v.color && !colors.find(function(c) { return c.name === v.color; })) colors.push({ name: v.color, hex: v.color_hex || '#000000' });
          if (v.size && !sizes.includes(v.size)) sizes.push(v.size);
        });
        veBothColors = colors;
        veBothSizes = sizes;
        setVariantType('both');
        // تعبئة الشبكة
        setTimeout(function() {
          vs.forEach(function(v) {
            var ci = veBothColors.findIndex(function(c) { return c.name === v.color; });
            var si = veBothSizes.indexOf(v.size);
            if (ci >= 0 && si >= 0) {
              var el = document.getElementById('grid-' + ci + '_' + si);
              if (el) el.value = v.stock;
            }
          });
          updateTotal();
        }, 100);
      }
    }
  });

  document.addEventListener('keydown', function(e) { if (e.key === 'Escape') { closeInlineScanner(); closeAddCategory(); } });

  
// ═══ ✂️ Cropper.js — اقتصاص الصور ═══
  var cropperInstance = null;
  var cropperTarget = 'main';

  window.openCropper = function(input, target) {
    if (!input.files || !input.files[0]) return;
    cropperTarget = target;
    var file = input.files[0];

    if (file.size > 10 * 1024 * 1024) {
      alert('⚠️ الصورة كبيرة جداً — الحد الأقصى 10MB');
      input.value = '';
      return;
    }

    if (typeof Cropper === 'undefined') {
      alert('⚠️ مكتبة الاقتصاص لم تُحمّل بعد. أعد تحميل الصفحة.');
      return;
    }

    var reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById('cropperModal').style.display = 'flex';
      var img = document.getElementById('cropperImage');
      img.src = e.target.result;

      setTimeout(function() {
        if (cropperInstance) { try { cropperInstance.destroy(); } catch(x){} }
        cropperInstance = new Cropper(img, {
          viewMode: 2,
          dragMode: 'move',
          aspectRatio: NaN,
          autoCropArea: 0.9,
          background: true,
          responsive: true,
          restore: false,
          checkCrossOrigin: false,
          guides: true,
          center: true,
          highlight: false,
          cropBoxMovable: true,
          cropBoxResizable: true,
          toggleDragModeOnDblclick: false,
        });
      }, 150);
    };
    reader.readAsDataURL(file);
  };

  window.closeCropper = function() {
    document.getElementById('cropperModal').style.display = 'none';
    if (cropperInstance) { try { cropperInstance.destroy(); } catch(e){} cropperInstance = null; }
  };

  window.cropperRotate = function(deg) {
    if (cropperInstance) cropperInstance.rotate(deg);
  };

  window.cropperFlip = function(dir) {
    if (!cropperInstance) return;
    var d = cropperInstance.getData();
    if (dir === 'h') cropperInstance.scaleX(d.scaleX === -1 ? 1 : -1);
    else cropperInstance.scaleY(d.scaleY === -1 ? 1 : -1);
  };

  window.cropperReset = function() {
    if (cropperInstance) cropperInstance.reset();
  };

  window.cropperAspect = function(ratio) {
    if (cropperInstance) cropperInstance.setAspectRatio(isNaN(ratio) ? NaN : ratio);
  };

  window.confirmCrop = function() {
    if (!cropperInstance) {
      alert('⚠️ لا يوجد اقتصاص نشط');
      return;
    }

    var canvas = cropperInstance.getCroppedCanvas({
      maxWidth: 1600,
      maxHeight: 1600,
      imageSmoothingEnabled: true,
      imageSmoothingQuality: 'high',
    });

    canvas.toBlob(function(blob) {
      var file = new File([blob], 'cropped_' + Date.now() + '.jpg', { type: 'image/jpeg' });

      // تحديث input file الرئيسي
      var mainInput = document.getElementById('mainImageInput');
      if (mainInput) {
        var dt = new DataTransfer();
        dt.items.add(file);
        mainInput.files = dt.files;
      }

      // معاينة
      var url = URL.createObjectURL(blob);
      if (cropperTarget === 'main') {
        var preview = document.getElementById('mainPreviewImg');
        if (preview) {
          preview.src = url;
          preview.style.display = 'block';
        }
      }

      closeCropper();
    }, 'image/jpeg', 0.92);
  };


  // ═══ 🐛 دالة التشخيص ═══
  window.runDebug = function() {
    var out = [];
    out.push('═══════════════════════════════');
    out.push('🔍 تشخيص نظام الاقتصاص');
    out.push('═══════════════════════════════');
    out.push('');
    out.push('1. Cropper.js مكتبة: ' + (typeof Cropper !== 'undefined' ? '✅ محمّلة' : '❌ مفقودة'));
    out.push('2. openCropper دالة: ' + (typeof window.openCropper === 'function' ? '✅ معرّفة' : '❌ مفقودة'));
    out.push('3. confirmCrop دالة: ' + (typeof window.confirmCrop === 'function' ? '✅ معرّفة' : '❌ مفقودة'));
    out.push('4. cropperModal عنصر: ' + (document.getElementById('cropperModal') ? '✅ موجود' : '❌ مفقود'));
    out.push('5. mainImageInput عنصر: ' + (document.getElementById('mainImageInput') ? '✅ موجود' : '❌ مفقود'));
    out.push('');
    var inp = document.getElementById('mainImageInput');
    out.push('6. onchange attr: ' + (inp ? (inp.getAttribute('onchange') || '⚠️ فارغ') : 'N/A'));
    out.push('7. Scripts محمّلة: ' + document.querySelectorAll('script[src*="cropper"]').length + ' من 2');
    out.push('8. Alpine.js: ' + (typeof Alpine !== 'undefined' ? '✅ محمّل' : '❌ مفقود'));
    out.push('');
    out.push('═══════════════════════════════');
    out.push('9. اختبار فتح Modal:');
    try {
      var modal = document.getElementById('cropperModal');
      if (modal) {
        modal.style.display = 'flex';
        setTimeout(function() { modal.style.display = 'none'; }, 2000);
        out.push('   ✅ فتح - انتظر ثانيتين سيُغلق');
      } else {
        out.push('   ❌ Modal غير موجود');
      }
    } catch(e) {
      out.push('   ❌ خطأ: ' + e.message);
    }
    out.push('');
    out.push('═══════════════════════════════');
    out.push('10. تحميل Cropper من CDN:');
    out.push('   جاري الاختبار...');
    
    var panel = document.getElementById('debugPanel');
    var output = document.getElementById('debugOutput');
    output.textContent = out.join('\n');
    panel.style.display = 'block';

    // اختبار CDN
    fetch('https://cdn.jsdelivr.net/npm/cropperjs@1.6.1/dist/cropper.min.js', { method: 'HEAD' })
      .then(function(r) {
        out.push('   ' + (r.ok ? '✅ CDN يعمل (' + r.status + ')' : '❌ CDN فشل (' + r.status + ')'));
        output.textContent = out.join('\n');
      })
      .catch(function(e) {
        out.push('   ❌ CDN لا يستجيب: ' + e.message);
        output.textContent = out.join('\n');
      });
  };

  // ═══ ربط حدث الصورة الرئيسية (fallback للجوال) ═══
  function bindCropperInputs() {
    var mainInp = document.getElementById('mainImageInput');
    if (mainInp && !mainInp.dataset.bound) {
      mainInp.dataset.bound = '1';
      mainInp.removeAttribute('onchange');
      var handler = function() {
        if (typeof window.openCropper === 'function') {
          window.openCropper(mainInp, 'main');
        } else {
          alert('⚠️ openCropper غير معرّف');
        }
      };
      mainInp.addEventListener('change', handler);
      mainInp.addEventListener('input', handler);
      console.log('✅ mainImageInput bound (edit)');
    }

    var galleryInp = document.getElementById('galleryInput');
    if (galleryInp && !galleryInp.dataset.bound) {
      galleryInp.dataset.bound = '1';
      galleryInp.addEventListener('change', function() {
        var files = Array.from(this.files).slice(0, 5);
        var container = this.parentElement.querySelector('.ez-gallery-grid');
        if (container) {
          var oldImages = Array.from(container.querySelectorAll('img'));
          oldImages.forEach(function(img) { img.style.opacity = '0.4'; });
          files.forEach(function(f) {
            var url = URL.createObjectURL(f);
            var img = document.createElement('img');
            img.src = url;
            img.className = 'ez-gallery-img';
            img.style.border = '2px solid #f59e0b';
            container.appendChild(img);
          });
        }
      });
      console.log('✅ galleryInput bound (edit)');
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bindCropperInputs);
  } else {
    setTimeout(bindCropperInputs, 100);
  }
  setTimeout(bindCropperInputs, 500);
  setTimeout(bindCropperInputs, 1500);


  // ═══ 🎯 بناء variants payload عند الإرسال ═══
  (function() {
    var form = document.getElementById('productForm');
    if (!form || form.dataset.variantsBoundV2) return;
    form.dataset.variantsBoundV2 = '1';

    form.addEventListener('submit', function(e) {
      // 1) إزالة كل hidden inputs القديمة
      var old = this.querySelectorAll('input[name^="variants"]');
      for (var i = 0; i < old.length; i++) old[i].remove();

      // 2) بناء payload جديد من المصفوفات
      var idx = 0;
      var html = '';
      function esc(v) { return String(v == null ? '' : v).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;'); }
      function addVariant(size, color, hex, stock) {
        var k = 'v' + (idx++);
        html += '<input type="hidden" name="variants[' + k + '][size]" value="' + esc(size) + '">';
        html += '<input type="hidden" name="variants[' + k + '][color]" value="' + esc(color) + '">';
        html += '<input type="hidden" name="variants[' + k + '][color_hex]" value="' + esc(hex || '#000000') + '">';
        html += '<input type="hidden" name="variants[' + k + '][stock]" value="' + (parseInt(stock, 10) || 0) + '">';
      }

      try {
        if (typeof veType !== 'undefined') {
          if (veType === 'colors' && Array.isArray(veColors)) {
            veColors.forEach(function(c) {
              if (c && c.name) addVariant(null, c.name, c.hex, c.stock);
            });
          } else if (veType === 'sizes' && Array.isArray(veSizes)) {
            veSizes.forEach(function(s) {
              if (s && s.name) addVariant(s.name, null, null, s.stock);
            });
          } else if (veType === 'both' && Array.isArray(veBothColors) && Array.isArray(veBothSizes)) {
            veBothColors.forEach(function(c, ci) {
              if (!c) return;
              veBothSizes.forEach(function(s, si) {
                if (!c.name && !s) return;
                var gridEl = document.getElementById('grid-' + ci + '_' + si);
                var stock = gridEl ? (parseInt(gridEl.value, 10) || 0) : 0;
                addVariant(s, c.name, c.hex, stock);
              });
            });
          }
        }
      } catch(err) {
        console.error('Variants build error:', err);
      }

      if (html) {
        var container = document.createElement('div');
        container.style.display = 'none';
        container.innerHTML = html;
        this.appendChild(container);
      }
    });
  })();

</script>

{{-- Modal: اقتصاص الصورة --}}
<div id="cropperModal" class="ez-modal" style="display:none;">
  <div class="ez-modal-box" style="max-width:600px;">
    <div class="ez-modal-head">
      <h3>✂️ اقتصاص الصورة</h3>
      <button type="button" onclick="closeCropper()" class="ez-close">×</button>
    </div>
    <p class="ez-modal-subtitle">اسحب الزوايا لاقتصاص الصورة، ثم اضغط "تأكيد"</p>

    <div style="max-height:400px;overflow:hidden;background:#0f172a;border-radius:12px;margin-bottom:16px;">
      <img id="cropperImage" style="max-width:100%;display:block;">
    </div>

    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px;">
      <button type="button" onclick="cropperRotate(-90)" class="ez-btn-sm">↺ -90°</button>
      <button type="button" onclick="cropperRotate(90)" class="ez-btn-sm">↻ +90°</button>
      <button type="button" onclick="cropperFlip('h')" class="ez-btn-sm">⇋ عكس أفقي</button>
      <button type="button" onclick="cropperFlip('v')" class="ez-btn-sm">⇅ عكس رأسي</button>
      <button type="button" onclick="cropperReset()" class="ez-btn-sm">🔄 إعادة</button>
    </div>

    <div style="display:flex;gap:8px;margin-bottom:12px;flex-wrap:wrap;">
      <button type="button" onclick="cropperAspect(1)" class="ez-btn-sm">⬛ مربع (1:1)</button>
      <button type="button" onclick="cropperAspect(4/3)" class="ez-btn-sm">▭ 4:3</button>
      <button type="button" onclick="cropperAspect(16/9)" class="ez-btn-sm">▭ 16:9</button>
      <button type="button" onclick="cropperAspect(NaN)" class="ez-btn-sm">🔓 حر</button>
    </div>

    <div class="ez-modal-footer">
      <button type="button" onclick="closeCropper()" class="ez-btn-cancel-modal">إلغاء</button>
      <button type="button" onclick="confirmCrop()" class="ez-btn-save-modal">✂️ تأكيد الاقتصاص</button>
    </div>
  </div>
</div>


@endsection
