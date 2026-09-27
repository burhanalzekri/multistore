<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(20);
        return view('dashboard.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('dashboard.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:10',
            'description' => 'nullable|string',
        ]);

        $data['slug'] = Str::slug($data['name']) . '-' . uniqid();
        $data['is_active'] = true;

        Category::create($data);
        return redirect('/dashboard/categories')->with('success', 'تم إضافة التصنيف');
    }

    public function edit(Category $category)
    {
        return view('dashboard.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:10',
            'description' => 'nullable|string',
        ]);
        $category->update($data);
        return redirect('/dashboard/categories')->with('success', 'تم تحديث التصنيف');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect('/dashboard/categories')->with('success', 'تم حذف التصنيف');
    }

    /**
     * إضافة سريعة من نموذج المنتج
     */
    public function quickStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'icon' => 'nullable|string|max:10',
        ]);

        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        if (!$shop) {
            return response()->json(['ok' => false, 'message' => 'لا يوجد متجر'], 400);
        }

        // تحقق من عدم التكرار
        $existing = \App\Models\Category::withoutGlobalScope('tenant')
            ->where('shop_id', $shop->id)
            ->where('name', $data['name'])
            ->first();

        if ($existing) {
            return response()->json([
                'ok' => true,
                'category' => [
                    'id' => $existing->id,
                    'name' => $existing->name,
                    'icon' => $existing->icon ?? '📂',
                ],
                'message' => 'موجود مسبقاً',
            ]);
        }

        $category = \App\Models\Category::create([
            'shop_id' => $shop->id,
            'name' => $data['name'],
            'slug' => \Illuminate\Support\Str::slug($data['name']) . '-' . uniqid(),
            'icon' => $data['icon'] ?? '📂',
            'is_active' => true,
        ]);

        return response()->json([
            'ok' => true,
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'icon' => $category->icon,
            ],
            'message' => 'تمت الإضافة',
        ]);
    }

}
