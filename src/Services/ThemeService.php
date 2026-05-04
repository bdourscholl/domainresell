<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Theme;
use App\Models\SiteSetting;
use ZipArchive;

class ThemeService
{
    public function install(array $file): array
    {
        if ($file['type'] !== 'application/zip' && $file['type'] !== 'application/x-zip-compressed') {
            return ['success' => false, 'error' => 'Only ZIP files are accepted'];
        }

        $tempPath = BASE_PATH . '/storage/uploads/themes/' . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $tempPath);

        $zip = new ZipArchive();
        if ($zip->open($tempPath) !== true) {
            unlink($tempPath);
            return ['success' => false, 'error' => 'Invalid ZIP file'];
        }

        $themeJson = $zip->getFromName('theme.json');
        if (!$themeJson) {
            $zip->close();
            unlink($tempPath);
            return ['success' => false, 'error' => 'theme.json not found in ZIP'];
        }

        $meta = json_decode($themeJson, true);
        if (!$meta || empty($meta['name'])) {
            $zip->close();
            unlink($tempPath);
            return ['success' => false, 'error' => 'Invalid theme.json'];
        }

        $slug = preg_replace('/[^a-z0-9\-]/', '', strtolower(str_replace(' ', '-', $meta['name'])));
        $themePath = BASE_PATH . '/themes/' . $slug;

        if (!is_dir($themePath)) {
            mkdir($themePath, 0755, true);
        }

        $zip->extractTo($themePath);
        $zip->close();
        unlink($tempPath);

        $existing = Theme::findBySlug($slug);
        if ($existing) {
            Theme::update((int) $existing['id'], [
                'name' => $meta['name'],
                'version' => $meta['version'] ?? '1.0.0',
                'author' => $meta['author'] ?? '',
                'description' => $meta['description'] ?? '',
            ]);
        } else {
            Theme::create([
                'name' => $meta['name'],
                'slug' => $slug,
                'version' => $meta['version'] ?? '1.0.0',
                'author' => $meta['author'] ?? '',
                'description' => $meta['description'] ?? '',
            ]);
        }

        // Copy assets to public
        $assetsSrc = $themePath . '/assets';
        $assetsDst = BASE_PATH . '/public/assets/themes/' . $slug;
        if (is_dir($assetsSrc)) {
            $this->copyDir($assetsSrc, $assetsDst);
        }

        return ['success' => true, 'slug' => $slug];
    }

    public function activateForHome(string $slug): void
    {
        $theme = Theme::findBySlug($slug);
        if ($theme) {
            Theme::activateForHome((int) $theme['id']);
            SiteSetting::set('active_theme_home', $slug, 'appearance');
        }
    }

    public function activateForAccount(string $slug): void
    {
        $theme = Theme::findBySlug($slug);
        if ($theme) {
            Theme::activateForAccount((int) $theme['id']);
            SiteSetting::set('active_theme_account', $slug, 'appearance');
        }
    }

    public function delete(string $slug): void
    {
        $theme = Theme::findBySlug($slug);
        if ($theme && $slug !== 'default') {
            Theme::delete((int) $theme['id']);
            $this->removeDir(BASE_PATH . '/themes/' . $slug);
            $this->removeDir(BASE_PATH . '/public/assets/themes/' . $slug);
        }
    }

    private function copyDir(string $src, string $dst): void
    {
        if (!is_dir($dst)) mkdir($dst, 0755, true);
        foreach (scandir($src) as $item) {
            if ($item === '.' || $item === '..') continue;
            $s = $src . '/' . $item;
            $d = $dst . '/' . $item;
            is_dir($s) ? $this->copyDir($s, $d) : copy($s, $d);
        }
    }

    private function removeDir(string $dir): void
    {
        if (!is_dir($dir)) return;
        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') continue;
            $path = $dir . '/' . $item;
            is_dir($path) ? $this->removeDir($path) : unlink($path);
        }
        rmdir($dir);
    }
}
