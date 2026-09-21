@extends('layouts.app')
@section('title', 'تعديل تصنيف')
@section('page-title', '✏️ ' . $category->name)

@section('content')
<form method="POST" action="/dashboard/categories/{{ $category->id }}" class="max-w-2xl space-y-4">
  @csrf @method('PUT')
  <div class="bg-white rounded-2xl p-6 shadow-sm space-y-4">
    <div>
      <label class="text-sm font-bold block mb-2">اسم التصنيف *</label>
      <input type="text" name="name" value="{{ old('name', $category->name) }}" required
        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none">
    </div>
    <div>
      <label class="text-sm font-bold block mb-2">أيقونة</label>
      <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" maxlength="10"
        class="w-full px-4 py-3 border border-slate-200 rounded-xl text-center text-2xl">
    </div>
    <div>
      <label class="text-sm font-bold block mb-2">الوصف</label>
      <textarea name="description" rows="3" class="w-full px-4 py-3 border border-slate-200 rounded-xl">{{ old('description', $category->description) }}</textarea>
    </div>
  </div>
  <div class="flex gap-3">
    <button type="submit" class="flex-1 py-3 bg-amber-600 text-white rounded-xl font-bold">💾 حفظ</button>
    <a href="/dashboard/categories" class="px-6 py-3 bg-slate-100 rounded-xl font-bold">إلغاء</a>
  </div>
</form>
@endsection
