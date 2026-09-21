<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WishlistController extends Controller
{
    private function sessionId(): string
    {
        if (!session()->has('wishlist_id')) {
            session(['wishlist_id' => Str::random(32)]);
        }
        return session('wishlist_id');
    }

    public function index()
    {
        $ids = Wishlist::where('session_id', $this->sessionId())->pluck('product_id');
        $products = Product::withoutGlobalScope('tenant')->whereIn('id', $ids)->get();
        return view('storefront.wishlist', compact('products'));
    }

    public function toggle(Request $request, $id)
    {
        $sid = $this->sessionId();
        $product = Product::withoutGlobalScope('tenant')->findOrFail($id);

        $existing = Wishlist::where('session_id', $sid)->where('product_id', $id)->first();

        if ($existing) {
            $existing->delete();
            return back()->with('success', 'تم الحذف من المفضلة');
        }

        Wishlist::create([
            'shop_id' => $product->shop_id,
            'product_id' => $id,
            'session_id' => $sid,
        ]);

        return back()->with('success', 'أُضيف للمفضلة ❤️');
    }
}
