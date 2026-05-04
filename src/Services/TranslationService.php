<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Session;

class TranslationService
{
    private array $translations = [];
    private string $locale;

    public function __construct()
    {
        $session = new Session();
        $this->locale = $session->getLocale();
        $this->loadTranslations();
    }

    private function loadTranslations(): void
    {
        $langPath = BASE_PATH . '/resources/lang/' . $this->locale;
        if (!is_dir($langPath)) return;

        foreach (glob($langPath . '/*.php') as $file) {
            $group = basename($file, '.php');
            $this->translations[$group] = require $file;
        }
    }

    public function get(string $key, array $replace = []): string
    {
        $parts = explode('.', $key, 2);
        $group = $parts[0];
        $item = $parts[1] ?? $key;

        $text = $this->translations[$group][$item] ?? $key;

        foreach ($replace as $placeholder => $value) {
            $text = str_replace('{{' . $placeholder . '}}', (string) $value, $text);
        }

        return $text;
    }

    public function setLocale(string $locale): void
    {
        $session = new Session();
        $session->setLocale($locale);
        $this->locale = $locale;
        $this->translations = [];
        $this->loadTranslations();
    }

    public function getLocale(): string
    {
        return $this->locale;
    }
}
