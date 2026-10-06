<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected CloudinaryService $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

    public function index()
    {
        $products = Product::latest()->paginate(20);
        return view('dashboard.products.index', compact('products'));
    }

    public function create()
    {
        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        if (!$shop) {
            return view('dashboard.products.no-shop');
        }
        $categories = \App\Models\Category::withoutGlobalScope('tenant')
            ->where('shop_id', $shop?->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        return view('dashboard.products.create', compact('categories', 'shop'));
    }

    public function store(Request $request)
    {
        $shop = app(\App\Services\Tenant\TenantManager::class)->currentOrFallback();
        if (!$shop) {
            return redirect('/super-admin/shops')->with('error', '⚠️ يجب اختيار متجر أولاً لإضافة المنتجات');
        }
        // تنظيف
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

        // رفع الصورة الرئيسية → Cloudinary
        if ($request->hasFile('image')) {
            $up = $this->cloudinary->upload($request->file('image'), 'products');
            if ($up['success']) {
                $data['image'] = $up['url'];
            } else {
                return back()->withInput()->withErrors(['image' => 'فشل رفع الصورة: ' . $up['error']]);
            }
        }

        // رفع صور المعرض → Cloudinary
        $gallery = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $up = $this->cloudinary->upload($file, 'products/gallery');
                if ($up['success']) {
                    $gallery[] = $up['url'];
                }
            }
        }
        $data['images'] = $gallery;

        // رفع الفيديو → Cloudinary
        if ($request->hasFile('video')) {
            $up = $this->cloudinary->uploadVideo($request->file('video'), 'videos');
            if ($up['success']) {
                $data['video'] = $up['url'];
            }
        }

        // رفع Poster الفيديو
        if ($request->hasFile('video_poster')) {
            $up = $this->cloudinary->upload($request->file('video_poster'), 'products/posters');
            if ($up['success']) {
                $data['video_poster'] = $up['url'];
            }
        }

        if (empty($data['stock'])) $data['stock'] = 0;

        unset($data['variant_type'], $data['variants'], $data['barcode_mode'], $data['final_barcode'], $data['gallery'], $data['_token'], $data['_method']);

        $product = Product::create($data);
        $this->saveVariants($product, $request->input('variants'));

        // حساب المخزون من variants
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
        // تنظيف
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

        if (!empty($data['sizes'])) {
            $data['sizes'] = array_filter(array_map('trim', explode(',', $data['sizes'])));
        }
        if (!empty($data['colors'])) {
            $data['colors'] = array_filter(array_map('trim', explode(',', $data['colors'])));
        }

        // تحديث الصورة الرئيسية
        if ($request->hasFile('image')) {
            if ($product->image) {
                $this->cloudinary->deleteByUrl($product->image);
            }
            $up = $this->cloudinary->upload($request->file('image'), 'products');
            if ($up['success']) {
                $data['image'] = $up['url'];
            }
        }

        // إضافة صور جديدة للمعرض
        if ($request->hasFile('gallery')) {
            $existing = $product->images ?? [];
            foreach ($request->file('gallery') as $file) {
                if ($file && $file->isValid()) {
                    $up = $this->cloudinary->upload($file, 'products/gallery');
                    if ($up['success']) {
                        $existing[] = $up['url'];
                    }
                }
            }
            $data['images'] = $existing;
        }

        // تحديث الفيديو
        if ($request->hasFile('video')) {
            if ($product->video) {
                $this->cloudinary->deleteByUrl($product->video);
            }
            $up = $this->cloudinary->uploadVideo($request->file('video'), 'videos');
            if ($up['success']) {
                $data['video'] = $up['url'];
            }
        }

        // تحديث Poster
        if ($request->hasFile('video_poster')) {
            if ($product->video_poster) {
                $this->cloudinary->deleteByUrl($product->video_poster);
            }
            $up = $this->cloudinary->upload($request->file('video_poster'), 'products/posters');
            if ($up['success']) {
                $data['video_poster'] = $up['url'];
            }
        }

        // حذف الفيديو
        if ($request->boolean('remove_video') && $product->video) {
            $this->cloudinary->deleteByUrl($product->video);
            if ($product->video_poster) {
                $this->cloudinary->deleteByUrl($product->video_poster);
            }
            $data['video'] = null;
            $data['video_poster'] = null;
        }

        unset($data['gallery']);

        if (empty($data['stock'])) $data['stock'] = 0;

        unset($data['variant_type'], $data['variants'], $data['barcode_mode'], $data['final_barcode'], $data['gallery'], $data['_token'], $data['_method']);

        $product->update($data);
        $this->saveVariants($product, $request->input('variants'));

        return redirect('/dashboard/products')->with('success', 'تم تحديث المنتج');
    }

    public function destroy(Product $product)
    {
        // حذف الملفات من Cloudinary
        if ($product->image) {
            $this->cloudinary->deleteByUrl($product->image);
        }
        if ($product->video) {
            $this->cloudinary->deleteByUrl($product->video);
        }
        if ($product->video_poster) {
            $this->cloudinary->deleteByUrl($product->video_poster);
        }
        if (!empty($product->images) && is_array($product->images)) {
            foreach ($product->images as $img) {
                $this->cloudinary->deleteByUrl($img);
            }
        }

        $product->delete();
        return back()->with('success', 'تم حذف المنتج');
    }

    protected function saveVariants(Product $product, ?array $variants): void
    {
        if (empty($variants)) return;

        $product->variants()->delete();

        foreach ($variants as $key => $v) {
            if (!is_array($v)) continue;

            $size = trim((string) ($v['size'] ?? ''));
            $color = trim((string) ($v['color'] ?? ''));
            $colorHex = trim((string) ($v['color_hex'] ?? ''));
            $stock = (int) ($v['stock'] ?? 0);
            $price = !empty($v['price']) ? (float) $v['price'] : null;

            if ($size === '' && $color === '') continue;
            if ($size === '' && $stock === 0) continue;

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

        $totalStock = $product->variants()->sum('stock');
        $product->update(['stock' => $totalStock]);
    }

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
