<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

if (! function_exists('product_image_tracked_directory')) {
    function product_image_tracked_directory(): string
    {
        return 'assets/product-images';
    }
}

if (! function_exists('product_image_legacy_directory')) {
    function product_image_legacy_directory(): string
    {
        return 'uploads/products';
    }
}

if (! function_exists('normalize_product_image_path')) {
    function normalize_product_image_path($imageUrl, bool $migrateLegacy = false): ?string
    {
        $imageUrl = trim((string) ($imageUrl ?? ''));
        if ($imageUrl === '') {
            return null;
        }

        if (preg_match('#^(?:https?:)?//#i', $imageUrl) || strpos($imageUrl, 'data:image') === 0) {
            return $imageUrl;
        }

        $normalizedPath = ltrim(str_replace('\\', '/', $imageUrl), '/');
        $trackedDirectory = trim(str_replace('\\', '/', product_image_tracked_directory()), '/');
        $legacyDirectory = trim(str_replace('\\', '/', product_image_legacy_directory()), '/');

        if ($trackedDirectory !== '' && str_starts_with($normalizedPath, $trackedDirectory . '/')) {
            return $normalizedPath;
        }

        if ($legacyDirectory === '' || ! str_starts_with($normalizedPath, $legacyDirectory . '/')) {
            return $normalizedPath;
        }

        $fileName = basename($normalizedPath);
        if ($fileName === '' || $fileName === '.' || $fileName === '..') {
            return $normalizedPath;
        }

        $targetRelativePath = $trackedDirectory . '/' . $fileName;
        $targetAbsolutePath = rtrim(FCPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $targetRelativePath);
        if (is_file($targetAbsolutePath)) {
            return $targetRelativePath;
        }

        if (! $migrateLegacy) {
            return $normalizedPath;
        }

        $legacyAbsolutePath = rtrim(FCPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $normalizedPath);
        if (! is_file($legacyAbsolutePath)) {
            return $normalizedPath;
        }

        $targetDirectory = dirname($targetAbsolutePath);
        if (! is_dir($targetDirectory) && ! mkdir($targetDirectory, 0775, true) && ! is_dir($targetDirectory)) {
            return $normalizedPath;
        }

        if (@copy($legacyAbsolutePath, $targetAbsolutePath)) {
            return $targetRelativePath;
        }

        return $normalizedPath;
    }
}

if (! function_exists('product_image_url')) {
    function product_image_url($imageUrl): ?string
    {
        $normalizedPath = normalize_product_image_path($imageUrl, true);
        if ($normalizedPath === null || $normalizedPath === '') {
            return null;
        }

        if (preg_match('#^(?:https?:)?//#i', $normalizedPath) || strpos($normalizedPath, 'data:image') === 0) {
            return $normalizedPath;
        }

        return base_url(ltrim($normalizedPath, '/'));
    }
}
