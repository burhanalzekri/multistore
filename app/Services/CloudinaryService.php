<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    protected ?Cloudinary $cloudinary = null;
    protected string $folder;

    public function __construct()
    {
        $this->folder = config('services.cloudinary.folder', 'multistore');
    }

    /**
     * إنشاء عميل Cloudinary عند الطلب فقط (lazy)
     */
    protected function client(): Cloudinary
    {
        if ($this->cloudinary instanceof Cloudinary) {
            return $this->cloudinary;
        }

        $cloudName = config('services.cloudinary.cloud_name');
        $apiKey    = config('services.cloudinary.api_key');
        $apiSecret = config('services.cloudinary.api_secret');

        if (empty($cloudName) || empty($apiKey) || empty($apiSecret)) {
            throw new \RuntimeException(
                'Cloudinary credentials are not configured. ' .
                'Please set CLOUDINARY_CLOUD_NAME, CLOUDINARY_API_KEY, and CLOUDINARY_API_SECRET.'
            );
        }

        Configuration::instance([
            'cloud' => [
                'cloud_name' => $cloudName,
                'api_key'    => $apiKey,
                'api_secret' => $apiSecret,
            ],
            'url' => ['secure' => true],
        ]);

        $this->cloudinary = new Cloudinary();

        return $this->cloudinary;
    }

    /**
     * هل الإعدادات مكتملة؟
     */
    public function isConfigured(): bool
    {
        return !empty(config('services.cloudinary.cloud_name'))
            && !empty(config('services.cloudinary.api_key'))
            && !empty(config('services.cloudinary.api_secret'));
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

            $result = $this->client()->uploadApi()->upload(
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

    public function delete(string $publicId, string $resourceType = 'image'): bool
    {
        try {
            $this->client()->uploadApi()->destroy($publicId, [
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

    public function isCloudinaryUrl(?string $url): bool
    {
        return $url && str_contains($url, 'res.cloudinary.com');
    }

    public function extractPublicId(string $url): ?string
    {
        if (preg_match('#/(?:image|video)/upload/(?:v\d+/)?(.+?)\.[a-z0-9]+$#i', $url, $m)) {
            return $m[1];
        }
        return null;
    }
}
