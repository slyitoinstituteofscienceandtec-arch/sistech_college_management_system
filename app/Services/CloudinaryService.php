<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    protected string $cloudName = '';
    protected string $apiKey = '';
    protected string $apiSecret = '';

    public function __construct()
    {
        $this->cloudName = config('services.cloudinary.cloud_name') ?? '';
        $this->apiKey = config('services.cloudinary.api_key') ?? '';
        $this->apiSecret = config('services.cloudinary.api_secret') ?? '';
    }

    public function isConfigured(): bool
    {
        return !empty($this->cloudName) && !empty($this->apiKey) && !empty($this->apiSecret);
    }

    public function upload(UploadedFile $file, string $folder = 'uploads'): ?string
    {
        if (!$this->isConfigured()) {
            return null;
        }

        try {
            $timestamp = time();
            $params = ['folder' => $folder, 'timestamp' => $timestamp];
            $signature = $this->signParams($params);

            $response = Http::timeout(30)->attach(
                'file', $file->getContents(), $file->getClientOriginalName()
            )->post("https://api.cloudinary.com/v1_1/{$this->cloudName}/auto/upload", [
                'api_key' => $this->apiKey,
                'timestamp' => $timestamp,
                'signature' => $signature,
                'folder' => $folder,
            ]);

            if ($response->successful()) {
                return $response->json('secure_url');
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Cloudinary upload failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function destroy(string $url): bool
    {
        if (!$this->isConfigured() || empty($url)) {
            return false;
        }

        try {
            $publicId = $this->extractPublicId($url);
            if (!$publicId) {
                return false;
            }

            $timestamp = time();
            $params = ['public_id' => $publicId, 'timestamp' => $timestamp];
            $signature = $this->signParams($params);

            $response = Http::timeout(15)->post("https://api.cloudinary.com/v1_1/{$this->cloudName}/image/destroy", [
                'public_id' => $publicId,
                'api_key' => $this->apiKey,
                'timestamp' => $timestamp,
                'signature' => $signature,
            ]);

            return $response->successful() && $response->json('result') === 'ok';
        } catch (\Exception $e) {
            Log::error('Cloudinary destroy failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getFileUrl(string $path): string
    {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return asset('storage/' . $path);
    }

    protected function signParams(array $params): string
    {
        ksort($params);
        $toSign = collect($params)->map(fn($v, $k) => "{$k}={$v}")->values()->implode('&');
        return sha1($toSign . $this->apiSecret);
    }

    protected function extractPublicId(string $url): ?string
    {
        if (!str_contains($url, 'cloudinary.com')) {
            return null;
        }

        $path = parse_url($url, PHP_URL_PATH);
        if (!$path) {
            return null;
        }

        $path = ltrim($path, '/');
        $path = preg_replace('#^v\d+/#', '', $path);
        $path = preg_replace('/\.[^.]+$/', '', $path);

        return $path ?: null;
    }
}
