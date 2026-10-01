<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * 📤 إرسال رأي جديد (عام — من Landing)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:120',
            'role'    => 'nullable|string|max:120',
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:500',
        ], [
            'name.required'    => 'الاسم مطلوب',
            'rating.required'  => 'التقييم مطلوب',
            'comment.required' => 'الرأي مطلوب',
            'comment.min'      => 'الرأي قصير جداً (10 أحرف على الأقل)',
        ]);

        // منع السبام — نفس IP خلال 24 ساعة
        $existing = Testimonial::where('ip', $request->ip())
            ->where('created_at', '>', now()->subDay())
            ->exists();

        if ($existing) {
            return back()->with('testimonial_error', '⏳ يمكنك إرسال رأي واحد كل 24 ساعة');
        }

        $data['ip'] = $request->ip();
        $data['is_visible'] = false; // يحتاج موافقة الأدمن

        Testimonial::create($data);

        return back()->with('testimonial_success', '✅ شكراً! سيُنشر رأيك بعد المراجعة');
    }

    /**
     * 📥 إدارة الآراء (للتاجر)
     */
    public function index(Request $request)
    {
        $query = Testimonial::latest();

        if ($request->get('filter') === 'visible') {
            $query->where('is_visible', true);
        } elseif ($request->get('filter') === 'hidden') {
            $query->where('is_visible', false);
        }

        $testimonials = $query->paginate(20)->withQueryString();

        $stats = [
            'total'    => Testimonial::count(),
            'visible'  => Testimonial::where('is_visible', true)->count(),
            'hidden'   => Testimonial::where('is_visible', false)->count(),
        ];

        return view('dashboard.testimonials.index', compact('testimonials', 'stats'));
    }

    /**
     * 👁️ إظهار/إخفاء
     */
    public function toggle(Testimonial $testimonial)
    {
        $testimonial->update(['is_visible' => !$testimonial->is_visible]);
        $msg = $testimonial->is_visible ? '👁️ تم إظهار الرأي' : '🙈 تم إخفاء الرأي';
        return back()->with('success', $msg);
    }

    /**
     * 🗑️ حذف
     */
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return back()->with('success', '🗑️ تم حذف الرأي');
    }
}
