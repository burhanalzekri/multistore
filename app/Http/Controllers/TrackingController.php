<?php
namespace App\Http\Controllers;

use App\Services\Recommendation\BehaviorTracker;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function track(Request $request)
    {
        $data = $request->validate([
            'event_type' => 'required|in:view,add_to_cart,remove_from_cart,search,wishlist,purchase',
            'product_id' => 'nullable|integer',
            'category_id' => 'nullable|integer',
            'price' => 'nullable|numeric',
            'query' => 'nullable|string',
            'duration' => 'nullable|integer',
        ]);

        BehaviorTracker::track($data['event_type'], $data);

        return response()->json(['ok' => true]);
    }
}
