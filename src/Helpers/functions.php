<?php

declare(strict_types=1);

use App\Core\App;
use App\Core\Session;
use App\Core\View;

function url(string $path = ''): string
{
    $baseUrl = rtrim($_ENV['APP_URL'] ?? '', '/');
    return $baseUrl . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

function theme_asset(string $path, string $theme = 'default'): string
{
    return url('assets/themes/' . $theme . '/' . ltrim($path, '/'));
}

function csrf_field(): string
{
    $session = new Session();
    $token = $session->getCsrfToken();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}

function csrf_token(): string
{
    $session = new Session();
    return $session->getCsrfToken();
}

function old(string $key, string $default = ''): string
{
    return htmlspecialchars($_POST[$key] ?? $default, ENT_QUOTES, 'UTF-8');
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function __(string $key, array $replace = []): string
{
    static $translations = null;
    static $currentLocale = null;

    $session = new Session();
    $locale = $session->getLocale();

    if ($translations === null || $currentLocale !== $locale) {
        $currentLocale = $locale;
        $translations = [];

        $langPath = BASE_PATH . '/resources/lang/' . $locale;
        if (is_dir($langPath)) {
            foreach (glob($langPath . '/*.php') as $file) {
                $group = basename($file, '.php');
                $translations[$group] = require $file;
            }
        }
    }

    $parts = explode('.', $key, 2);
    $group = $parts[0];
    $item = $parts[1] ?? $key;

    $text = $translations[$group][$item] ?? $key;

    foreach ($replace as $placeholder => $value) {
        $text = str_replace('{{' . $placeholder . '}}', (string) $value, $text);
    }

    return $text;
}

/**
 * Translate a key, returning null if the key is missing (so callers can
 * fall back via the `??` operator). `__()` returns the raw key on miss
 * which makes it awkward to use as a fallback chain.
 */
function tt(string $key, array $replace = []): ?string
{
    $text = __($key, $replace);
    return $text === $key ? null : $text;
}

function setting(string $key, string $default = ''): string
{
    try {
        $app = App::getInstance();
        $db = $app->getDb();
        $result = $db->queryOne(
            "SELECT setting_value FROM site_settings WHERE setting_key = ?",
            [$key]
        );
        return $result['setting_value'] ?? $default;
    } catch (\Exception $e) {
        return $default;
    }
}

function format_currency(float $amount, ?string $symbol = null): string
{
    $symbol = $symbol ?? setting('currency_symbol', '৳');
    return $symbol . number_format($amount, 2);
}

function flash(string $type, string $message): void
{
    $session = new Session();
    $session->flash($type, $message);
}

function get_flash(string $type): ?string
{
    $session = new Session();
    return $session->getFlash($type);
}

function is_logged_in(): bool
{
    $session = new Session();
    return $session->isLoggedIn();
}

function current_user_id(): ?int
{
    $session = new Session();
    return $session->getUserId();
}

function current_user_role(): ?string
{
    $session = new Session();
    return $session->getUserRole();
}

function current_user(): ?array
{
    static $cache = null;
    static $cachedId = null;
    $id = current_user_id();
    if (!$id) return null;
    if ($cache !== null && $cachedId === $id) return $cache;
    try {
        $cache = \App\Models\User::find($id);
        $cachedId = $id;
    } catch (\Throwable $e) {
        $cache = null;
    }
    return $cache;
}

function is_admin(): bool
{
    return in_array(current_user_role(), ['admin', 'moderator'], true);
}

function generate_order_number(): string
{
    $prefix = setting('order_prefix', 'ORD-');
    return $prefix . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
}

function generate_invoice_number(): string
{
    $prefix = setting('invoice_prefix', 'INV-');
    return $prefix . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
}

function time_ago(string $datetime): string
{
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 60) {
        return 'just now';
    }
    if ($diff < 3600) {
        $mins = floor($diff / 60);
        return $mins . ' min' . ($mins > 1 ? 's' : '') . ' ago';
    }
    if ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    }
    if ($diff < 2592000) {
        $days = floor($diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    }

    return date('M j, Y', $timestamp);
}

function partial(string $name, array $data = []): string
{
    return View::partial($name, $data);
}
