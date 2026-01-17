<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Illuminate\Http\UploadedFile;

class CloudinaryService
{
    protected $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => config('services.cloudinary.cloud_name'),
                'api_key' => config('services.cloudinary.api_key'),
                'api_secret' => config('services.cloudinary.api_secret'),
            ],
            'url' => [
                'secure' => config('services.cloudinary.secure'),
            ],
        ]);
    }

    /**
     * Upload an image to Cloudinary
     *
     * @param UploadedFile $file
     * @param string $folder Folder name in Cloudinary (e.g., 'news', 'projects', 'gallery')
     * @param array $options Additional upload options
     * @return array Contains 'url' and 'public_id'
     */
    public function uploadImage(UploadedFile $file, string $folder = 'uploads', array $options = []): array
    {
        $defaultOptions = [
            'folder' => "nptf/{$folder}",
            'resource_type' => 'auto',
            'transformation' => [
                'quality' => 'auto:good',
                'fetch_format' => 'auto',
            ],
        ];

        $uploadOptions = array_merge($defaultOptions, $options);

        try {
            $result = $this->cloudinary->uploadApi()->upload(
                $file->getRealPath(),
                $uploadOptions
            );

            return [
                'url' => $result['secure_url'],
                'public_id' => $result['public_id'],
            ];
        } catch (\Exception $e) {
            \Log::error('Cloudinary upload error: ' . $e->getMessage());
            throw new \Exception('Failed to upload image to Cloudinary: ' . $e->getMessage());
        }
    }

    /**
     * Upload a video to Cloudinary
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param array $options
     * @return array
     */
    public function uploadVideo(UploadedFile $file, string $folder = 'uploads', array $options = []): array
    {
        $defaultOptions = [
            'folder' => "nptf/{$folder}",
            'resource_type' => 'video',
        ];

        $uploadOptions = array_merge($defaultOptions, $options);

        try {
            $result = $this->cloudinary->uploadApi()->upload(
                $file->getRealPath(),
                $uploadOptions
            );

            return [
                'url' => $result['secure_url'],
                'public_id' => $result['public_id'],
            ];
        } catch (\Exception $e) {
            \Log::error('Cloudinary video upload error: ' . $e->getMessage());
            throw new \Exception('Failed to upload video to Cloudinary: ' . $e->getMessage());
        }
    }

    /**
     * Delete an image from Cloudinary
     *
     * @param string $publicId The public_id of the image to delete
     * @return bool
     */
    public function deleteImage(string $publicId): bool
    {
        try {
            $result = $this->cloudinary->uploadApi()->destroy($publicId);
            return $result['result'] === 'ok';
        } catch (\Exception $e) {
            \Log::error('Cloudinary delete error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a video from Cloudinary
     *
     * @param string $publicId
     * @return bool
     */
    public function deleteVideo(string $publicId): bool
    {
        try {
            $result = $this->cloudinary->uploadApi()->destroy($publicId, ['resource_type' => 'video']);
            return $result['result'] === 'ok';
        } catch (\Exception $e) {
            \Log::error('Cloudinary video delete error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Extract public_id from Cloudinary URL
     *
     * @param string $url
     * @return string|null
     */
    public function getPublicIdFromUrl(string $url): ?string
    {
        // Extract public_id from URL
        // Example URL: https://res.cloudinary.com/demo/image/upload/v1234567890/nptf/news/sample.jpg
        // Public ID: nptf/news/sample

        if (preg_match('/\/upload\/(?:v\d+\/)?(.+)\.\w+$/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
