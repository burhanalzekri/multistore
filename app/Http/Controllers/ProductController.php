<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(20);
        return view('dashboard.products.index', compact('products'));
    }

    public function create()
    {
        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        $categories = \App\Models\Category::withoutGlobalScope('tenant')
            ->where('shop_id', $shop?->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        return view('dashboard.products.create', compact('categories', 'shop'));
    }

    public function store(Request $request)
    {
        // 🧹 تنظيف: إزالة image إذا كان مصفوفة فارغة
        if ($request->has('image') && !$request->hasFile('image')) {
            $request->request->remove('image');
            $request->files->remove('image');
        }
        // نفس الشيء للـ video و video_poster
        foreach (['video', 'video_poster'] as $f) {
            if ($request->has($f) && !$request->hasFile($f)) {
                $request->request->remove($f);
                $request->files->remove($f);
            }
        }
        // gallery: صفّها إذا كانت فارغة
        if ($request->has('gallery')) {
            $g = $request->input('gallery');
            if (!is_array($g) || empty(array_filter($g, 'is_file')) && !$request->hasFile('gallery')) {
                $request->request->remove('gallery');
                $request->files->remove('gallery');
            }
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'compare_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:5120',
            'gallery' => 'nullable|array|max:5',
            'gallery.*' => 'image|max:5120',
            'video' => 'nullable|file|mimetypes:video/mp4,video/webm,video/quicktime|max:51200',
            'video_poster' => 'nullable|image|max:5120',
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

        // رفع الفيديو
        if ($request->hasFile('video')) {
            $data['video'] = $request->file('video')->store('products/videos', 'public');
        }

        // رفع Poster الفيديو
        if ($request->hasFile('video_poster')) {
            $data['video_poster'] = $request->file('video_poster')->store('products', 'public');
        }

        if (empty($data['stock'])) $data['stock'] = 0;
        // 🧹 إزالة الحقول غير الموجودة في DB
        unset($data['variant_type'], $data['variants'], $data['barcode_mode'], $data['final_barcode'], $data['gallery'], $data['_token'], $data['_method']);
        
        $product = Product::create($data);
        $this->saveVariants($product, $request->input('variants'));

        // 📊 إذا كان هناك variants، نحسب المخزون الإجمالي منها
        $variantsCount = $product->variants()->count();
        if ($variantsCount > 0) {
            $total = (int) $product->variants()->sum('stock');
            $product->update(['stock' => $total]);
        }

        // 📊 إذا كان هناك variants، نحسب المخزون الإجمالي منها
        $variantsCount = $product->variants()->count();
        if ($variantsCount > 0) {
            $total = (int) $product->variants()->sum('stock');
            $product->update(['stock' => $total]);
        }

        return redirect('/dashboard/products')->with('success', 'تم إضافة المنتج بنجاح');
    }

    public function show(Product $product)
    {
        return view('dashboard.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        $categories = \App\Models\Category::withoutGlobalScope('tenant')
            ->where('shop_id', $shop?->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        return view('dashboard.products.edit', compact('product', 'categories', 'shop'));
    }

    public function update(Request $request, Product $product)
    {
        // 🧹 نفس التنظيف
        if ($request->has('image') && !$request->hasFile('image')) {
            $request->request->remove('image');
            $request->files->remove('image');
        }
        foreach (['video', 'video_poster'] as $f) {
            if ($request->has($f) && !$request->hasFile($f)) {
                $request->request->remove($f);
                $request->files->remove($f);
            }
        }
        if ($request->has('gallery')) {
            $g = $request->input('gallery');
            if (!is_array($g) || empty($g)) {
                $request->request->remove('gallery');
                $request->files->remove('gallery');
            }
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'compare_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:5120',
            'gallery' => 'nullable|array|max:5',
            'gallery.*' => 'image|max:5120',
            'video' => 'nullable|file|mimetypes:video/mp4,video/webm,video/quicktime|max:51200',
            'video_poster' => 'nullable|image|max:5120',
            'sizes' => 'nullable|string',
            'colors' => 'nullable|string',
            'variant_type' => 'nullable|string|in:simple,colors,sizes,both',
            'variants' => 'nullable|array',
            'variants.*.size' => 'nullable|string|max:50',
            'variants.*.color' => 'nullable|string|max:50',
            'variants.*.stock' => 'nullable|integer|min:0',
            'variants.*.price' => 'nullable|numeric|min:0',
        ]);

        // تحويل النصوص إلى JSON
        if (!empty($data['sizes'])) {
            $data['sizes'] = array_filter(array_map('trim', explode(',', $data['sizes'])));
        }
        if (!empty($data['colors'])) {
            $data['colors'] = array_filter(array_map('trim', explode(',', $data['colors'])));
        }

        // تحديث الصورة الرئيسية
        if ($request->hasFile('image')) {
            if ($product->image && !str_starts_with($product->image, 'http')) {
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

        // تحديث الفيديو
        if ($request->hasFile('video')) {
            if ($product->video && !str_starts_with($product->video, 'http')) {
                Storage::disk('public')->delete($product->video);
            }
            $data['video'] = $request->file('video')->store('products/videos', 'public');
        }

        // تحديث Poster
        if ($request->hasFile('video_poster')) {
            if ($product->video_poster && !str_starts_with($product->video_poster, 'http')) {
                Storage::disk('public')->delete($product->video_poster);
            }
            $data['video_poster'] = $request->file('video_poster')->store('products', 'public');
        }

        // حذف الفيديو
        if ($request->boolean('remove_video') && $product->video) {
            if (!str_starts_with($product->video, 'http')) {
                Storage::disk('public')->delete($product->video);
            }
            $data['video'] = null;
            $data['video_poster'] = null;
        }

        // معالجة صور المعرض — تُحفظ في عمود images
        if ($request->hasFile('gallery')) {
            $existing = $product->images ?? [];
            $newImages = [];
            foreach ($request->file('gallery') as $img) {
                if ($img && $img->isValid()) {
                    $newImages[] = $img->store('products', 'public');
                }
            }
            if (!empty($newImages)) {
                $data['images'] = array_merge($existing, $newImages);
            }
        }
        unset($data['gallery']);  // عمود غير موجود في DB

        if (empty($data['stock'])) $data['stock'] = 0;
        // 🧹 إزالة الحقول غير الموجودة في DB
        unset($data['variant_type'], $data['variants'], $data['barcode_mode'], $data['final_barcode'], $data['gallery'], $data['_token'], $data['_method']);
        
        $product->update($data);
        $this->saveVariants($product, $request->input('variants'));

        return redirect('/dashboard/products')->with('success', 'تم تحديث المنتج');
    }

    public function destroy(Product $product)
    {
        if ($product->image && !str_starts_with($product->image, 'http')) {
            Storage::disk('public')->delete($product->image);
        }
        if ($product->video && !str_starts_with($product->video, 'http')) {
            Storage::disk('public')->delete($product->video);
        }
        $product->delete();
        return back()->with('success', 'تم حذف المنتج');
    }

    /**
     * حفظ Variants (مقاسات × ألوان)
     */
    protected function saveVariants(Product $product, ?array $variants): void
    {
        if (empty($variants)) return;

        // احذف القديمة
        $product->variants()->delete();

        $idx = 0;
        foreach ($variants as $key => $v) {
            if (!is_array($v)) continue;

            $size = trim((string) ($v['size'] ?? ''));
            $color = trim((string) ($v['color'] ?? ''));
            $colorHex = trim((string) ($v['color_hex'] ?? ''));
            $stock = (int) ($v['stock'] ?? 0);
            $price = !empty($v['price']) ? (float) $v['price'] : null;

            // 🧹 تجاهل الصفوف الفارغة بقوة
            if ($size === '' && $color === '') continue;
            // تجاهل الصفوف بلا مخزون أو بيانات (اختياري — لتنظيف البيانات المشوّهة)
            if ($size === '' && $stock === 0) continue;

            // SKU + Barcode
            $sku = $v['sku'] ?? ProductVariant::generateSku($product->id, $size ?: null, $color ?: null, $colorHex);
            $barcode = $v['barcode'] ?? ProductVariant::generateBarcode();

            $product->variants()->create([
                'size' => $size ?: null,
                'color' => $color ?: null,
                'color_hex' => $colorHex ?: null,
                'stock' => $stock,
                'price' => $price,
                'sku' => $sku,
                'barcode' => $barcode,
                'is_active' => true,
            ]);
        }

        // حدّث المخزون الإجمالي
        $totalStock = $product->variants()->sum('stock');
        $product->update(['stock' => $totalStock]);
    }


    /**
     * التحقق من تفرد الباركوود قبل الحفظ
     */
    public function checkBarcode(\Illuminate\Http\Request $request)
    {
        $code = trim($request->query('code', ''));
        if (strlen($code) < 3) {
            return response()->json(['exists' => false, 'code' => $code]);
        }

        $exists = \App\Models\Product::withoutGlobalScope('tenant')
            ->where('barcode', $code)
            ->exists();

        if (!$exists && class_exists('\App\Models\ProductVariant')) {
            try {
                $exists = \App\Models\ProductVariant::where('sku', $code)->exists();
            } catch (\Throwable $e) {}
        }

        return response()->json(['exists' => $exists, 'code' => $code]);
    }

}
