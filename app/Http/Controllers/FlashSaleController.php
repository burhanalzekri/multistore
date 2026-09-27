<?php
namespace App\Http\Controllers;

use App\Models\FlashSale;
use App\Models\Product;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function index() {
        $sales = FlashSale::with('product')->latest()->paginate(20);
        return view('dashboard.flash-sales.index', compact('sales'));
    }

    public function create() {
        $products = Product::where('is_active', true)->get();
        return view('dashboard.flash-sales.create', compact('products'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_price' => 'required|numeric|min:0',
            'max_qty' => 'required|integer|min:0',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
        ]);
        $data['is_active'] = true;
        FlashSale::create($data);
        return redirect('/dashboard/flash-sales')->with('success', 'تم إنشاء العرض');
    }

    public function destroy(FlashSale $flashSale) {
        $flashSale->delete();
        return back()->with('success', 'تم الحذف');
    }
}
