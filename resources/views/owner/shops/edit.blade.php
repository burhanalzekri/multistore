@extends('layouts.app')
@section('title', 'تعديل المتجر')
@section('page-title', '✏️ تعديل المتجر')
@section('page-subtitle', '{{ $shop->name }}')
@section('content')
<form method="POST" action="{{ route('owner.shops.update',$shop->id) }}" enctype="multipart/form-data" style="max-width:900px;background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:22px;box-shadow:var(--shadow-sm);">
@csrf @method('PUT')
@if($errors->any())<div style="padding:14px;background:#fee2e2;color:#991b1b;border-radius:12px;margin-bottom:16px;font-size:13px;">{{ $errors->first() }}</div>@endif
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px;">
@foreach([['name','اسم المتجر',$shop->name,'text'],['slug','الرابط المختصر',$shop->slug,'text'],['phone','الهاتف',$shop->phone,'text'],['whatsapp','واتساب',$shop->whatsapp,'text'],['email','البريد',$shop->email,'email'],['address','العنوان',$shop->address,'text'],['city','المدينة',$shop->city,'text'],['country','الدولة',$shop->country ?? 'اليمن','text'],['currency','العملة',$shop->currency ?? 'YER','text']] as $f)
<label style="font-size:12px;font-weight:800;color:var(--text-muted);">{{ $f[1] }}<input name="{{ $f[0] }}" type="{{ $f[3] }}" value="{{ old($f[0],$f[2]) }}" style="display:block;width:100%;margin-top:6px;padding:11px 12px;border:1px solid var(--border);border-radius:11px;background:var(--surface);color:var(--text);font-family:inherit;"></label>
@endforeach
<label style="font-size:12px;font-weight:800;color:var(--text-muted);">الحالة<select name="status" style="display:block;width:100%;margin-top:6px;padding:11px 12px;border:1px solid var(--border);border-radius:11px;background:var(--surface);color:var(--text);font-family:inherit;"><option value="active" @selected(old('status',$shop->status)==='active')>نشط</option><option value="trial" @selected(old('status',$shop->status)==='trial')>تجريبي</option><option value="suspended" @selected(old('status',$shop->status)==='suspended')>معطل</option></select></label>
<label style="font-size:12px;font-weight:800;color:var(--text-muted);">شعار المتجر<input name="logo" type="file" accept="image/*" style="display:block;width:100%;margin-top:6px;padding:9px;border:1px solid var(--border);border-radius:11px;"></label>
<label style="font-size:12px;font-weight:800;color:var(--text-muted);grid-column:1/-1;">الوصف<textarea name="description" rows="4" style="display:block;width:100%;margin-top:6px;padding:11px 12px;border:1px solid var(--border);border-radius:11px;background:var(--surface);color:var(--text);font-family:inherit;">{{ old('description',$shop->description) }}</textarea></label>
</div>
<div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:20px;"><button type="submit" style="border:0;padding:12px 22px;background:#f59e0b;color:white;border-radius:12px;font-family:inherit;font-weight:900;cursor:pointer;">💾 حفظ التعديلات</button><a href="{{ route('owner.shops.show',$shop->id) }}" style="padding:12px 22px;background:var(--border);color:var(--text);border-radius:12px;text-decoration:none;font-weight:900;">إلغاء</a></div>
</form>
@endsection
