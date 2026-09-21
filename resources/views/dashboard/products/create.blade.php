@extends('layouts.app')
@section('title', 'منتج جديد')
@section('page-title', '➕ منتج جديد')

@section('content')
@if($errors->any())
<div class="bg-red-100 text-red-700 p-4 rounded-2xl mb-4 text-sm">@foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach</div>
@endif

<form method="POST" action="/dashboard/products" enctype="multipart/form-data" class="max-w-3xl space-y-4" x-data="imageUpload()">
  @csrf

  <div class="bg-white rounded-2xl p-6 shadow-sm">
    <h2 class="font-black mb-4 flex items-center gap-2"><i data-lucide="image" class="w-5 h-5 text-amber-600"></i> صور المنتج</h2>
    <div class="mb-5">
      <label class="text-sm font-bold block mb-2">الصورة الرئيسية</label>
      <div class="flex items-start gap-4">
        <div class="w-32 h-32 rounded-2xl overflow-hidden bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center border-2 border-dashed border-amber-300 flex-shrink-0">
          <template x-if="mainPreview"><img :src="mainPreview" class="w-full h-full object-cover"></template>
          <template x-if="!mainPreview"><i data-lucide="image-plus" class="w-8 h-8 text-amber-500"></i></template>
        </div>
        <div class="flex-1">
          <input type="file" name="image" accept="image/*" @change="previewMain($event)" class="hidden" x-ref="mainInput">
          <button type="button" @click="$refs.mainInput.click()" class="w-full py-3 px-4 bg-amber-50 hover:bg-amber-100 border-2 border-dashed border-amber-300 rounded-xl font-bold text-amber-700 flex items-center justify-center gap-2">
            <i data-lucide="upload" class="w-5 h-5"></i> اختر صورة رئيسية
          </button>
          <p class="text-xs text-slate-500 mt-2">JPG, PNG — بحد أقصى 5 ميجا</p>
        </div>
      </div>
    </div>
    <div>
      <label class="text-sm font-bold block mb-2">صور إضافية (حتى 5)</label>
      <input type="file" name="gallery[]" accept="image/*" multiple @change="previewGallery($event)" class="hidden" x-ref="galleryInput">
      <button type="button" @click="$refs.galleryInput.click()" class="w-full py-3 px-4 bg-slate-50 hover:bg-slate-100 border-2 border-dashed border-slate-300 rounded-xl font-bold text-slate-700 flex items-center justify-center gap-2 mb-3">
        <i data-lucide="images" class="w-5 h-5"></i> اختر صور إضافية
      </button>
      <div class="grid grid-cols-5 gap-2">
        <template x-for="(img, i) in galleryPreviews" :key="i"><div class="aspect-square rounded-xl overflow-hidden"><img :src="img" class="w-full h-full object-cover"></div></template>
      </div>
    </div>
  </div>

  <div class="bg-white rounded-2xl p-6 shadow-sm space-y-4">
    <h2 class="font-black flex items-center gap-2"><i data-lucide="info" class="w-5 h-5 text-blue-600"></i> البيانات الأساسية</h2>

    <div>
      <label class="text-sm font-bold block mb-2">اسم المنتج *</label>
      <input type="text" name="name" value="{{ old('name') }}" required placeholder="مثال: عسل جبلي أصلي" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none">
    </div>

    <div>
      <label class="text-sm font-bold block mb-2">التصنيف</label>
      <select name="category_id" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none">
        <option value="">— بدون تصنيف —</option>
        @foreach(\App\Models\Category::all() as $c)
        <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>{{ $c->icon ?? '📂' }} {{ $c->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="text-sm font-bold block mb-2">السعر *</label>
        <input type="number" name="price" value="{{ old('price') }}" required min="0" step="0.01" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none">
      </div>
      <div>
        <label class="text-sm font-bold block mb-2">السعر قبل الخصم</label>
        <input type="number" name="compare_price" value="{{ old('compare_price') }}" min="0" step="0.01" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none">
      </div>
    </div>

    <div>
      <label class="text-sm font-bold block mb-2">المخزون *</label>
      <input type="number" name="stock" value="{{ old('stock', 0) }}" required min="0" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none">
    </div>

    <div>
      <label class="text-sm font-bold block mb-2">الوصف</label>
      <textarea name="description" rows="4" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none">{{ old('description') }}</textarea>
    </div>
  </div>

  <div class="flex gap-3">
    <button type="submit" class="flex-1 py-3 bg-amber-600 text-white rounded-xl font-bold">💾 حفظ المنتج</button>
    <a href="/dashboard/products" class="px-6 py-3 bg-slate-100 rounded-xl font-bold">إلغاء</a>
  </div>
</form>

<script>
function imageUpload() {
  return {
    mainPreview: null, galleryPreviews: [],
    previewMain(e) { const f = e.target.files[0]; if (f) { const r = new FileReader(); r.onload = ev => this.mainPreview = ev.target.result; r.readAsDataURL(f); } },
    previewGallery(e) { this.galleryPreviews = []; Array.from(e.target.files).slice(0, 5).forEach(f => { const r = new FileReader(); r.onload = ev => this.galleryPreviews.push(ev.target.result); r.readAsDataURL(f); }); }
  };
}
lucide.createIcons();
</script>
@endsection
