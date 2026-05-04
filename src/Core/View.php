<?php

declare(strict_types=1);

namespace App\Core;

class View
{
    private static ?string $activeHomeTheme = null;
    private static ?string $activeAccountTheme = null;

    public static function render(string $template, array $data = [], string $layout = null): string
    {
        $app = App::getInstance();
        $session = $app->getSession();

        // Inject common data
        $data['session'] = $session;
        $data['csrf_token'] = $session->getCsrfToken();
        $data['locale'] = $session->getLocale();
        $data['app_name'] = $_ENV['APP_NAME'] ?? 'Domain Reseller';
        $data['app_url'] = $_ENV['APP_URL'] ?? '';

        // Load font settings
        $data['font_family'] = self::getActiveFontFamily();

        $content = self::renderTemplate($template, $data);

        if ($layout) {
            $data['content'] = $content;
            return self::renderTemplate('layouts/' . $layout, $data);
        }

        return $content;
    }

    public static function renderWithLayout(string $template, array $data = [], string $layout = 'main'): string
    {
        return self::render($template, $data, $layout);
    }

    private static function renderTemplate(string $template, array $data): string
    {
        $file = self::resolveTemplatePath($template);

        if (!$file) {
            return "<!-- Template not found: {$template} -->";
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $file;
        return ob_get_clean();
    }

    private static function resolveTemplatePath(string $template): ?string
    {
        // Determine section for theme resolution
        $section = self::getTemplateSection($template);
        $themeName = null;

        if ($section === 'account') {
            $themeName = self::$activeAccountTheme ?? self::getActiveTheme('account');
        } elseif ($section !== 'admin' && $section !== 'layouts' && $section !== 'errors') {
            $themeName = self::$activeHomeTheme ?? self::getActiveTheme('home');
        }

        // Try theme template first
        if ($themeName) {
            $themePath = BASE_PATH . '/themes/' . $themeName . '/templates/' . $template . '.php';
            if (file_exists($themePath)) {
                return $themePath;
            }
        }

        // Fall back to base templates
        $basePath = BASE_PATH . '/templates/' . $template . '.php';
        if (file_exists($basePath)) {
            return $basePath;
        }

        return null;
    }

    private static function getTemplateSection(string $template): string
    {
        $parts = explode('/', $template);
        return $parts[0] ?? '';
    }

    private static function getActiveTheme(string $section): ?string
    {
        try {
            $app = App::getInstance();
            $db = $app->getDb();
            $setting = $db->queryOne(
                "SELECT setting_value FROM site_settings WHERE setting_key = ?",
                ["active_theme_{$section}"]
            );
            $theme = $setting['setting_value'] ?? 'default';

            if ($section === 'home') {
                self::$activeHomeTheme = $theme;
            } else {
                self::$activeAccountTheme = $theme;
            }

            return $theme;
        } catch (\Exception $e) {
            return 'default';
        }
    }

    private static function getActiveFontFamily(): string
    {
        try {
            $app = App::getInstance();
            $db = $app->getDb();
            $setting = $db->queryOne(
                "SELECT setting_value FROM site_settings WHERE setting_key = 'active_font'",
            );
            return $setting['setting_value'] ?? "'Tiro Bangla', serif";
        } catch (\Exception $e) {
            return "'Tiro Bangla', serif";
        }
    }

    public static function partial(string $template, array $data = []): string
    {
        return self::renderTemplate('partials/' . $template, $data);
    }
}
