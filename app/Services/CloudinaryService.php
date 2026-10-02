<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    protected Cloudinary $cloudinary;
    protected string $folder;

    public function __construct()
    {
        Configuration::instance([
            'cloud' => [
                'cloud_name' => config('services.cloudinary.cloud_name'),
                'api_key'    => config('services.cloudinary.api_key'),
                'api_secret' => config('services.cloudinary.api_secret'),
            ],
            'url' => ['secure' => true],
        ]);

        $this->cloudinary = new Cloudinary();
        $this->folder     = config('services.cloudinary.folder', 'multistore');
    }

    /**
     * رفع صورة إلى Cloudinary
     */
    public function upload(UploadedFile $file, string $subfolder = 'products'): array
    {
        return $this->doUpload($file, 'image', $subfolder);
    }

    /**
     * رفع فيديو إلى Cloudinary
     */
    public function uploadVideo(UploadedFile $file, string $subfolder = 'videos'): array
    {
        return $this->doUpload($file, 'video', $subfolder);
    }

    /**
     * الرفع الفعلي
     */
    protected function doUpload(UploadedFile $file, string $resourceType, string $subfolder): array
    {
        try {
            $options = [
                'folder'        => "{$this->folder}/{$subfolder}",
                'resource_type' => $resourceType,
            ];

            if ($resourceType === 'image') {
                $options['transformation'] = [
                    'quality'      => 'auto',
                    'fetch_format' => 'auto',
                ];
            }

            $result = $this->cloudinary->uploadApi()->upload(
                $file->getRealPath(),
                $options
            );

            return [
                'success'   => true,
                'url'       => $result['secure_url'],
                'public_id' => $result['public_id'],
                'format'    => $result['format'] ?? null,
                'bytes'     => $result['bytes'] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::error('Cloudinary upload failed', [
                'message' => $e->getMessage(),
                'file'    => $file->getClientOriginalName(),
                'type'    => $resourceType,
            ]);

            return [
                'success' => false,
                'error'   => $e->getMessage(),
            ];
        }
    }

    /**
     * حذف ملف من Cloudinary عبر public_id
     */
    public function delete(string $publicId, string $resourceType = 'image'): bool
    {
        try {
            $this->cloudinary->uploadApi()->destroy($publicId, [
                'resource_type' => $resourceType,
            ]);
            return true;
        } catch (\Throwable $e) {
            Log::error('Cloudinary delete failed', [
                'public_id' => $publicId,
                'message'   => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * حذف ملف عبر الرابط الكامل (يكتشف النوع تلقائياً)
     */
    public function deleteByUrl(?string $url): bool
    {
        if (!$url || !$this->isCloudinaryUrl($url)) {
            return false;
        }

        $publicId = $this->extractPublicId($url);
        if (!$publicId) {
            return false;
        }

        $resourceType = str_contains($url, '/video/upload/') ? 'video' : 'image';
        return $this->delete($publicId, $resourceType);
    }

    /**
     * هل الرابط من Cloudinary؟
     */
    public function isCloudinaryUrl(?string $url): bool
    {
        return $url && str_contains($url, 'res.cloudinary.com');
    }

    /**
     * استخراج public_id من رابط Cloudinary
     */
    public function extractPublicId(string $url): ?string
    {
        if (preg_match('#/(?:image|video)/upload/(?:v\d+/)?(.+?)\.[a-z0-9]+$#i', $url, $m)) {
            return $m[1];
        }
        return null;
    }
}
