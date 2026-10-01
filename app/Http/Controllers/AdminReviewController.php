<?php
namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index()
    {
        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        $shopId = $shop?->id;

        // اعرض كل التقييمات (بدون فلتر tenant) — مع المنتج
        $reviews = Review::withoutGlobalScope('tenant')
            ->with('product')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('dashboard.reviews.index', compact('reviews', 'shop'));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        return back()->with('success', 'تم قبول المراجعة');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'تم حذف المراجعة');
    }

    /**
     * 👁️ إظهار/إخفاء تقييم
     */
    public function toggleVisibility(\App\Models\Review $review)
    {
        $review->update(['is_approved' => !$review->is_approved]);
        $msg = $review->is_approved ? '👁️ تم عرض التقييم' : '🚫 تم إخفاء التقييم';
        return back()->with('success', $msg);
    }
}