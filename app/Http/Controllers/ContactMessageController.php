<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    /**
     * 💾 استقبال رسالة تواصل جديدة
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:120',
            'email'   => 'nullable|email|max:190',
            'phone'   => 'nullable|string|max:40',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|min:5|max:2000',
        ], [
            'name.required'    => 'الاسم مطلوب',
            'email.email'      => 'البريد الإلكتروني غير صحيح',
            'message.required' => 'الرسالة مطلوبة',
            'message.min'      => 'الرسالة قصيرة جداً',
        ]);

        // اربطها بالمتجر الحالي إن وُجد
        $shopId = null;
        try {
            $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
            $shopId = $shop?->id;
        } catch (\Throwable $e) {}

        $data['shop_id'] = $shopId;

        ContactMessage::create($data);

        return back()->with('contact_success', '✅ تم إرسال رسالتك — سنتواصل معك قريباً');
    }

    /**
     * 📥 قائمة الرسائل (للتاجر)
     */
    public function index(Request $request)
    {
        $shop = $this->currentShop();

        $query = ContactMessage::where('shop_id', $shop->id)->latest();

        if ($request->get('filter') === 'unread') {
            $query->where('is_read', false);
        }

        $messages = $query->paginate(20)->withQueryString();

        $stats = [
            'total'  => ContactMessage::where('shop_id', $shop->id)->count(),
            'unread' => ContactMessage::where('shop_id', $shop->id)->where('is_read', false)->count(),
            'today'  => ContactMessage::where('shop_id', $shop->id)->whereDate('created_at', today())->count(),
        ];

        return view('dashboard.contact-messages.index', compact('messages', 'stats'));
    }

    /**
     * 👁️ عرض رسالة + تعليم مقروءة
     */
    public function show(ContactMessage $message)
    {
        if (!$message->is_read) {
            $message->update(['is_read' => true, 'read_at' => now()]);
        }

        return view('dashboard.contact-messages.show', compact('message'));
    }

    /**
     * 🗑️ حذف رسالة
     */
    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return redirect('/dashboard/contact-messages')->with('success', '🗑️ تم حذف الرسالة');
    }

    protected function currentShop()
    {
        $shop = auth()->user()?->shop
            ?? app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();

        if (!$shop) abort(403, 'لا يوجد متجر');
        return $shop;
    }
}
