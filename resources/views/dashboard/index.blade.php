@extends('layouts.app')

@section('title', 'الرئيسية')
@section('page-title', 'لوحة التحكم')
@section('page-subtitle', 'نظرة شاملة على متجرك')

@section('content')

<!-- Welcome Banner -->
<div class="bg-gradient-to-l from-amber-500 via-orange-500 to-amber-600 rounded-3xl p-6 lg:p-8 text-white mb-6 shadow-xl animate-slideUp relative overflow-hidden">
  <div class="absolute -top-10 -left-10 w-40 h-40 bg-white/10 rounded-full"></div>
  <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-white/10 rounded-full"></div>
  <div class="relative">
    <div class="text-sm opacity-90 mb-1">مرحبًا بك 👋</div>
    <h2 class="text-2xl lg:text-3xl font-black mb-2">{{ auth()->user()->name ?? 'مدير المتجر' }}</h2>
    <p class="opacity-90 text-sm">لديك <b>{{ $stats['orders'] }}</b> طلبًا و <b>{{ $stats['sms'] }}</b> رسالة SMS</p>
  </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-6">
  <div class="bg-white rounded-2xl p-4 lg:p-5 shadow-sm card-hover animate-slideUp delay-100">
    <div class="flex items-center justify-between mb-3">
      <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center shadow-lg">
        <i data-lucide="trending-up" class="w-6 h-6 text-white"></i>
      </div>
      <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-full">+12%</span>
    </div>
    <div class="text-xs text-slate-500 mb-1">المبيعات</div>
    <div class="text-xl lg:text-2xl font-black text-slate-800">
      {{ number_format($stats['sales']) }} <span class="text-xs">ريال</span>
    </div>
  </div>

  <div class="bg-white rounded-2xl p-4 lg:p-5 shadow-sm card-hover animate-slideUp delay-200">
    <div class="flex items-center justify-between mb-3">
      <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center shadow-lg">
        <i data-lucide="shopping-bag" class="w-6 h-6 text-white"></i>
      </div>
    </div>
    <div class="text-xs text-slate-500 mb-1">الطلبات</div>
    <div class="text-xl lg:text-2xl font-black text-slate-800">{{ $stats['orders'] }}</div>
  </div>

  <div class="bg-white rounded-2xl p-4 lg:p-5 shadow-sm card-hover animate-slideUp delay-300">
    <div class="flex items-center justify-between mb-3">
      <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-lg">
        <i data-lucide="clock" class="w-6 h-6 text-white"></i>
      </div>
    </div>
    <div class="text-xs text-slate-500 mb-1">بانتظار الدفع</div>
    <div class="text-xl lg:text-2xl font-black text-slate-800">{{ $stats['pending'] }}</div>
  </div>

  <div class="bg-white rounded-2xl p-4 lg:p-5 shadow-sm card-hover animate-slideUp delay-400">
    <div class="flex items-center justify-between mb-3">
      <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center shadow-lg">
        <i data-lucide="message-circle" class="w-6 h-6 text-white"></i>
      </div>
    </div>
    <div class="text-xs text-slate-500 mb-1">رسائل SMS</div>
    <div class="text-xl lg:text-2xl font-black text-slate-800">{{ $stats['sms'] }}</div>
  </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-6">
  <a href="/dashboard/products/create" class="bg-white rounded-2xl p-4 shadow-sm card-hover group">
    <div class="w-10 h-10 rounded-xl bg-blue-50 group-hover:bg-blue-500 flex items-center justify-center transition-colors mb-3">
      <i data-lucide="plus" class="w-5 h-5 text-blue-600 group-hover:text-white transition-colors"></i>
    </div>
    <div class="font-bold text-sm">منتج جديد</div>
    <div class="text-xs text-slate-400 mt-1">أضف منتجًا للمتجر</div>
  </a>

  <a href="/dashboard/orders" class="bg-white rounded-2xl p-4 shadow-sm card-hover group">
    <div class="w-10 h-10 rounded-xl bg-amber-50 group-hover:bg-amber-500 flex items-center justify-center transition-colors mb-3">
      <i data-lucide="list" class="w-5 h-5 text-amber-600 group-hover:text-white transition-colors"></i>
    </div>
    <div class="font-bold text-sm">الطلبات</div>
    <div class="text-xs text-slate-400 mt-1">تابع طلباتك</div>
  </a>

  <a href="/dashboard/sms" class="bg-white rounded-2xl p-4 shadow-sm card-hover group">
    <div class="w-10 h-10 rounded-xl bg-purple-50 group-hover:bg-purple-500 flex items-center justify-center transition-colors mb-3">
      <i data-lucide="message-square" class="w-5 h-5 text-purple-600 group-hover:text-white transition-colors"></i>
    </div>
    <div class="font-bold text-sm">الرسائل</div>
    <div class="text-xs text-slate-400 mt-1">راجع SMS الواردة</div>
  </a>

  <a href="/shop" target="_blank" class="bg-white rounded-2xl p-4 shadow-sm card-hover group">
    <div class="w-10 h-10 rounded-xl bg-green-50 group-hover:bg-green-500 flex items-center justify-center transition-colors mb-3">
      <i data-lucide="store" class="w-5 h-5 text-green-600 group-hover:text-white transition-colors"></i>
    </div>
    <div class="font-bold text-sm">المتجر</div>
    <div class="text-xs text-slate-400 mt-1">شاهد متجرك</div>
  </a>
</div>

<!-- Recent Orders -->
<div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-4 animate-slideUp">
  <div class="p-5 border-b border-slate-100 flex justify-between items-center">
    <div class="flex items-center gap-2">
      <i data-lucide="clock" class="w-5 h-5 text-amber-600"></i>
      <h2 class="font-black">آخر الطلبات</h2>
    </div>
    <a href="/dashboard/orders" class="text-sm text-amber-600 font-bold hover:underline">عرض الكل ←</a>
  </div>

  <div class="divide-y divide-slate-100">
    @forelse($recentOrders as $o)
    <a href="/dashboard/orders/{{ $o->id }}" class="flex items-center gap-4 p-4 hover:bg-slate-50 transition">
      <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0">
        <i data-lucide="package" class="w-6 h-6 text-amber-600"></i>
      </div>
      <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2 mb-1">
          <span class="font-bold text-sm">{{ $o->customer_name }}</span>
          <span class="text-xs text-slate-400 font-mono">{{ $o->order_number }}</span>
        </div>
        <div class="text-xs text-slate-500">{{ $o->created_at->diffForHumans() }}</div>
      </div>
      <div class="text-left flex-shrink-0">
        <div class="font-black text-amber-600">{{ number_format($o->total) }}</div>
        <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-xs font-bold {{ \App\Support\StatusHelper::badge($o->status) }}">
          {{ \App\Support\StatusHelper::label($o->status) }}
        </span>
      </div>
    </a>
    @empty
    <div class="text-center py-12">
      <div class="text-5xl mb-3">🛒</div>
      <div class="font-bold text-slate-700 mb-1">لا توجد طلبات بعد</div>
      <div class="text-sm text-slate-400 mb-4">عندما يُنشئ العملاء طلبات، ستظهر هنا</div>
      <a href="/shop" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 text-white rounded-xl text-sm font-bold">
        <i data-lucide="external-link" class="w-4 h-4"></i>
        شاهد متجرك
      </a>
    </div>
    @endforelse
  </div>
</div>

<!-- Recent SMS -->
<div class="bg-white rounded-2xl shadow-sm overflow-hidden animate-slideUp">
  <div class="p-5 border-b border-slate-100 flex justify-between items-center">
    <div class="flex items-center gap-2">
      <i data-lucide="message-square" class="w-5 h-5 text-purple-600"></i>
      <h2 class="font-black">آخر رسائل SMS</h2>
    </div>
    <a href="/dashboard/sms" class="text-sm text-amber-600 font-bold hover:underline">عرض الكل ←</a>
  </div>

  <div class="divide-y divide-slate-100">
    @forelse($smsList as $s)
    <a href="/dashboard/sms/{{ $s->id }}" class="flex items-center gap-4 p-4 hover:bg-slate-50 transition">
      <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center flex-shrink-0">
        <i data-lucide="message-circle" class="w-6 h-6 text-purple-600"></i>
      </div>
      <div class="flex-1 min-w-0">
        <div class="font-bold text-sm mb-1 font-mono">{{ $s->sender_phone }}</div>
        <div class="text-xs text-slate-500 truncate">{{ Str::limit($s->raw_body, 50) }}</div>
      </div>
      <div class="text-left flex-shrink-0">
        <div class="font-black text-slate-800">{{ $s->parsed_amount ? number_format($s->parsed_amount) : '—' }}</div>
        <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-xs font-bold {{ \App\Support\StatusHelper::badge($s->status) }}">
          {{ \App\Support\StatusHelper::label($s->status) }}
        </span>
      </div>
    </a>
    @empty
    <div class="text-center py-12">
      <div class="text-5xl mb-3">📱</div>
      <div class="font-bold text-slate-700 mb-1">لا توجد رسائل بعد</div>
      <div class="text-sm text-slate-400">الرسائل الواردة ستظهر هنا تلقائيًا</div>
    </div>
    @endforelse
  </div>
</div>

@endsection
