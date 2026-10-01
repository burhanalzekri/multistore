<?php
namespace App\Http\Controllers;

use App\Models\Product;

class CompareController extends Controller
{
    public function index() {
        $ids = session('compare', []);
        $products = Product::withoutGlobalScope('tenant')->whereIn('id', $ids)->get();
        return view('storefront.compare', compact('products'));
    }

    public function toggle($id) {
        $compare = session('compare', []);
        if (in_array($id, $compare)) {
            $compare = array_diff($compare, [$id]);
        } else {
            if (count($compare) >= 4) {
                return back()->with('error', 'الحد الأقصى 4 منتجات');
            }
            $compare[] = (int) $id;
        }
        session(['compare' => array_values($compare)]);
        return back()->with('success', 'تم التحديث');
    }

    public function clear() {
        session()->forget('compare');
        return back()->with('success', 'تم إخلاء المقارنة');
    }
}
