<?php
namespace App\Http\Controllers;

use App\Models\PriceAlert;
use Illuminate\Http\Request;

class PriceAlertController extends Controller
{
    public function store(Request $request, $productId) {
        $data = $request->validate([
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:30',
            'target_price' => 'required|numeric|min:0',
        ]);
        $data['product_id'] = $productId;
        if (auth()->check()) $data['user_id'] = auth()->id();

        PriceAlert::create($data);
        return back()->with('success', '✅ سنُعلمك عند انخفاض السعر!');
    }
}
