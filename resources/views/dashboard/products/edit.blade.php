@extends('layouts.app')
@section('title', 'تعديل منتج')
@section('page-title', '✏️ تعديل منتج')
@section('page-subtitle', $product->name)

@section('content')

@if($errors->any())
<div class="bg-red-100 text-red-700 p-4 rounded-2xl mb-4 text-sm">
  @foreach($errors->all() as $e) <div>• {{ $e }}</div> @endforeach
</div>
@endif

<form method="POST" action="/dashboard/products/{{ $product->id }}" enctype="multipart/form-data" class="max-w-3xl space-y-4" x-data="imageUpload()">
  @csrf @method('PUT')

  <!-- Current Images -->
  <div class="bg-white rounded-2xl p-6 shadow-sm">
    <h2 class="font-black mb-4 flex items-center gap-2">
      <i data-lucide="image" class="w-5 h-5 text-amber-600"></i>
      صور المنتج
    </h2>

    @if($product->image || $product->images)
    <div class="mb-5">
      <div class="text-sm font-bold mb-3">الصور الحالية</div>
      <div class="grid grid-cols-3 sm:grid-cols-5 gap-2">
        @if($product->image)
        <div class="aspect-square rounded-xl overflow-hidden relative group">
          <img src="{{ Storage::url($product->image) }}" class="w-full h-full object-cover">
          <div class="absolute top-1 right-1 bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">رئيسية</div>
          <form method="POST" action="/dashboard/products/{{ $product->id }}/delete-image" class="absolute top-1 left-1">
            @csrf
            <input type="hidden" name="path" value="{{ $product->image }}">
            <button class="w-6 h-6 bg-red-500 text-white rounded-full text-xs opacity-0 group-hover:opacity-100 transition">✕</button>
          </form>
        </div>
        @endif

        @foreach($product->images ?? [] as $img)
        <div class="aspect-square rounded-xl overflow-hidden relative group">
          <img src="{{ Storage::url($img) }}" class="w-full h-full object-cover">
          <form method="POST" action="/dashboard/products/{{ $product->id }}/delete-image" class="absolute top-1 left-1">
            @csrf
            <input type="hidden" name="path" value="{{ $img }}">
            <button class="w-6 h-6 bg-red-500 text-white rounded-full text-xs opacity-0 group-hover:opacity-100 transition">✕</button>
          </form>
        </div>
        @endforeach
      </div>
    </div>
    @endif

    <!-- Main Image Upload -->
    <div class="mb-5">
      <label class="text-sm font-bold block mb-2">{{ $product->image ? 'استبدال الصورة الرئيسية' : 'إضافة صورة رئيسية' }}</label>
      <div class="flex items-start gap-4">
        <div class="w-32 h-32 rounded-2xl overflow-hidden bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center border-2 border-dashed border-amber-300 flex-shrink-0">
          <template x-if="mainPreview">
            <img :src="mainPreview" class="w-full h-full object-cover">
          </template>
          <template x-if="!mainPreview">
            <i data-lucide="image-plus" class="w-8 h-8 text-amber-500"></i>
          </template>
        </div>
        <div class="flex-1">
          <input type="file" name="image" accept="image/*" @change="previewMain($event)" class="hidden" x-ref="mainInput">
          <button type="button" @click="$refs.mainInput.click()"
                  class="w-full py-3 px-4 bg-amber-50 hover:bg-amber-100 border-2 border-dashed border-amber-300 rounded-xl font-bold text-amber-700 transition flex items-center justify-center gap-2">
            <i data-lucide="upload" class="w-5 h-5"></i>
            اختر صورة
          </button>
        </div>
      </div>
    </div>

    <!-- Gallery Upload -->
    <div>
      <label class="text-sm font-bold block mb-2">إضافة صور جديدة للـ Gallery</label>
      <input type="file" name="gallery[]" accept="image/*" multiple @change="previewGallery($event)" class="hidden" x-ref="galleryInput">
      <button type="button" @click="$refs.galleryInput.click()"
              class="w-full py-3 px-4 bg-slate-50 hover:bg-slate-100 border-2 border-dashed border-slate-300 rounded-xl font-bold text-slate-700 transition flex items-center justify-center gap-2 mb-3">
        <i data-lucide="images" class="w-5 h-5"></i>
        اختر صور إضافية
      </button>
      <div class="grid grid-cols-5 gap-2">
        <template x-for="(img, i) in galleryPreviews" :key="i">
          <div class="aspect-square rounded-xl overflow-hidden">
            <img :src="img" class="w-full h-full object-cover">
          </div>
        </template>
      </div>
    </div>
  </div>

  <!-- Basic Info -->
  <div class="bg-white rounded-2xl p-6 shadow-sm space-y-4">
    <h2 class="font-black flex items-center gap-2">
      <i data-lucide="info" class="w-5 h-5 text-blue-600"></i>
      بيانات المنتج
    </h2>

    <div>
      <label class="text-sm font-bold block mb-2">اسم المنتج *</label>
      <input type="text" name="name" value="{{ old('name', $product->name) }}" required
        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none">
    </div>

    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="text-sm font-bold block mb-2">السعر *</label>
        <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0" step="0.01"
          class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none">
      </div>
      <div>
        <label class="text-sm font-bold block mb-2">المخزون *</label>
        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required min="0"
          class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none">
      </div>
    </div>

    <div>
      <label class="text-sm font-bold block mb-2">الوصف</label>
      <textarea name="description" rows="4"
        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none">{{ old('description', $product->description) }}</textarea>
    </div>
  </div>

  <div class="flex gap-3">
    <button type="submit" class="flex-1 flex items-center justify-center gap-2 py-3 bg-amber-600 text-white rounded-xl font-bold">
      <i data-lucide="save" class="w-5 h-5"></i> حفظ التعديلات
    </button>
    <a href="/dashboard/products" class="px-6 py-3 bg-slate-100 rounded-xl font-bold">إلغاء</a>
  </div>
</form>

<script>
function imageUpload() {
  return {
    mainPreview: null,
    galleryPreviews: [],
    previewMain(e) {
      const f = e.target.files[0];
      if (f) { const r = new FileReader(); r.onload = ev => this.mainPreview = ev.target.result; r.readAsDataURL(f); }
    },
    previewGallery(e) {
      this.galleryPreviews = [];
      Array.from(e.target.files).slice(0, 5).forEach(f => {
        const r = new FileReader(); r.onload = ev => this.galleryPreviews.push(ev.target.result); r.readAsDataURL(f);
      });
    }
  };
}
lucide.createIcons();
</script>

@endsection
