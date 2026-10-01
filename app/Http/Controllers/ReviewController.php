<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function store(Request $request, $productId)
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'nullable|string|max:30',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $product = Product::withoutGlobalScope('tenant')->findOrFail($productId);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = 'rev-' . $product->id . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('reviews', $name, 'public');
            $imagePath = 'reviews/' . $name;
        }

        Review::withoutGlobalScope('tenant')->create([
            'product_id' => $product->id,
            'shop_id' => $product->shop_id,
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'] ?? null,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
            'image' => $imagePath,
            'is_approved' => true,
        ]);

        // دعم AJAX
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok' => true,
                'message' => 'شكراً لتقييمك! ⭐',
                'review' => [
                    'name' => $data['customer_name'],
                    'rating' => $data['rating'],
                    'comment' => $data['comment'] ?? '',
                ],
            ]);
        }

        return back()->with('success', 'شكرًا لتقييمك! ⭐');
    }
}
