<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(15);
        return view('dashboard.products.index', compact('products'));
    }

    public function create()
    {
        return view('dashboard.products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'compare_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:5120',
            'gallery' => 'nullable|array|max:5',
            'gallery.*' => 'image|max:5120',
        ]);

        $data['slug'] = Str::slug($data['name']) . '-' . uniqid();
        $data['is_active'] = true;

        // رفع الصورة الرئيسية
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // رفع الصور الإضافية
        $gallery = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $file->store('products', 'public');
            }
        }
        $data['images'] = $gallery;

        Product::create($data);

        return redirect('/dashboard/products')->with('success', 'تم إضافة المنتج بنجاح');
    }

    public function show(Product $product)
    {
        return view('dashboard.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('dashboard.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:5120',
            'gallery' => 'nullable|array|max:5',
            'gallery.*' => 'image|max:5120',
        ]);

        // تحديث الصورة الرئيسية
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // إضافة صور جديدة للـ gallery
        if ($request->hasFile('gallery')) {
            $existingImages = $product->images ?? [];
            foreach ($request->file('gallery') as $file) {
                $existingImages[] = $file->store('products', 'public');
            }
            $data['images'] = $existingImages;
        }

        $product->update($data);

        return redirect('/dashboard/products')->with('success', 'تم تحديث المنتج');
    }

    public function destroy(Product $product)
    {
        // احذف الصور
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        if ($product->images) {
            foreach ($product->images as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        $product->delete();
        return redirect('/dashboard/products')->with('success', 'تم حذف المنتج');
    }

    public function deleteImage(Request $request, Product $product)
    {
        $path = $request->input('path');
        if (!$path) return back()->with('error', 'الصورة غير موجودة');

        // إذا كانت الصورة الرئيسية
        if ($product->image === $path) {
            Storage::disk('public')->delete($path);
            $product->update(['image' => null]);
        }
        // أو من الـ gallery
        elseif ($product->images) {
            $images = $product->images;
            $key = array_search($path, $images);
            if ($key !== false) {
                Storage::disk('public')->delete($path);
                unset($images[$key]);
                $product->update(['images' => array_values($images)]);
            }
        }

        return back()->with('success', 'تم حذف الصورة');
    }
}
