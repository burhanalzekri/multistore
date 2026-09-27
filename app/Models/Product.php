<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use BelongsToTenant;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'bool',
        'images' => 'array',
        'sizes' => 'array',
        'colors' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * هل للمنتج فيديو؟
     */
    public function hasVideo(): bool
    {
        return !empty($this->video);
    }

    /**
     * رابط الفيديو الكامل
     */
    public function videoUrl(): ?string
    {
        if (!$this->video) return null;
        if (str_starts_with($this->video, 'http')) return $this->video;
        return \Storage::url($this->video);
    }

    /**
     * رابط الصورة الرئيسية
     */
    public function imageUrl(): ?string
    {
        if (!$this->image) return null;
        if (str_starts_with($this->image, 'http')) return $this->image;
        return \Storage::url($this->image);
    }

    /**
     * كل الصور (رئيسية + إضافية)
     */
    public function allImages(): array
    {
        $urls = [];
        if ($this->imageUrl()) {
            $urls[] = $this->imageUrl();
        }
        if (is_array($this->images)) {
            foreach ($this->images as $img) {
                if (!$img) continue;
                if (str_starts_with($img, 'http')) {
                    $urls[] = $img;
                } else {
                    $urls[] = \Storage::url($img);
                }
            }
        }
        return array_values(array_filter(array_unique($urls)));
    }

    /**
     * مقاسات/ألوان + مخزون
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * إجمالي المخزون (variants أو stock)
     */
    public function totalStock(): int
    {
        if ($this->variants()->exists()) {
            return (int) $this->variants()->sum('stock');
        }
        return (int) $this->stock;
    }

    /**
     * المقاسات المتاحة
     */
    public function availableSizes(): array
    {
        return $this->variants()
            ->whereNotNull('size')
            ->where('is_active', true)
            ->distinct()
            ->pluck('size')
            ->filter()
            ->values()
            ->toArray();
    }

    /**
     * الألوان المتاحة
     */
    public function availableColors(): array
    {
        return $this->variants()
            ->whereNotNull('color')
            ->where('is_active', true)
            ->distinct()
            ->pluck('color')
            ->filter()
            ->values()
            ->toArray();
    }

    /**
     * 🖼️ Accessor — رابط الصورة الجاهز للعرض
     * يتعامل مع:
     * - URLs خارجية (http/https) ← مباشرة
     * - مسارات محلية ← Storage::url
     */
    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }

        // ✅ URL خارجي ← استخدمه مباشرة
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        // ✅ مسار محلي ← عبر Storage
        return \Storage::url($this->image);
    }

    /**
     * 🖼️ Accessor — أول صورة من images المتعددة (أو image الأساسي)
     */
    public function getPrimaryImageUrlAttribute(): ?string
    {
        if (!empty($this->image_url)) {
            return $this->image_url;
        }

        $images = is_array($this->images) ? $this->images : [];
        $first = $images[0] ?? null;

        if (!$first) {
            return null;
        }

        if (str_starts_with($first, 'http://') || str_starts_with($first, 'https://')) {
            return $first;
        }

        return \Storage::url($first);
    }
}