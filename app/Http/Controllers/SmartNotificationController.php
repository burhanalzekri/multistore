<?php
namespace App\Http\Controllers;

use App\Models\CustomerEvent;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class SmartNotificationController extends Controller
{
    public function check()
    {
        $notifications = [];

        if (Auth::check()) {
            $userId = Auth::id();

            // 1. منتجات في المفضلة — تغير السعر
            $wishlistIds = Wishlist::where('user_id', $userId)->pluck('product_id');
            $priceChanges = Product::withoutGlobalScope('tenant')
                ->whereIn('id', $wishlistIds)
                ->where('compare_price', '>', 0)
                ->take(3)
                ->get();

            foreach ($priceChanges as $p) {
                if ($p->compare_price > $p->price) {
                    $notifications[] = [
                        'type' => 'price_drop',
                        'icon' => 'trending-down',
                        'title' => 'انخفض سعر ' . $p->name,
                        'body' => 'الآن ' . number_format($p->price) . ' ريال',
                        'url' => '/product/' . $p->id,
                    ];
                }
            }
        }

        return response()->json(['notifications' => $notifications]);
    }

    public function cartReminder()
    {
        // إذا كانت السلة غير فارغة ومر عليها 30 دقيقة
        $cart = session('cart', []);
        if (!empty($cart) && session('cart_updated_at')) {
            $lastUpdate = session('cart_updated_at');
            $minutes = now()->diffInMinutes($lastUpdate);
            if ($minutes >= 30 && $minutes < 120) {
                return response()->json([
                    'remind' => true,
                    'items' => count($cart),
                    'message' => 'لا تنسَ إتمام طلبك! لديك ' . count($cart) . ' منتج في السلة',
                ]);
            }
        }
        return response()->json(['remind' => false]);
    }
}

