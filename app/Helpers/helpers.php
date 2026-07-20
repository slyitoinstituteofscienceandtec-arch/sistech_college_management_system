<?php

if (!function_exists('file_url')) {
    function file_url(?string $path): string
    {
        if ($path && (str_starts_with($path, 'http://') || str_starts_with($path, 'https://'))) {
            return $path;
        }

        return asset('storage/' . ($path ?? ''));
    }
}
