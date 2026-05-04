<?php

declare(strict_types=1);

namespace App\Core;

class Session
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function flash(string $key, mixed $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public function getFlash(string $key, mixed $default = null): mixed
    {
        $value = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }

    public function destroy(): void
    {
        session_destroy();
        $_SESSION = [];
    }

    public function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public function generateCsrfToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $this->set('csrf_token', $token);
        return $token;
    }

    public function getCsrfToken(): string
    {
        if (!$this->has('csrf_token')) {
            return $this->generateCsrfToken();
        }
        return $this->get('csrf_token');
    }

    public function isLoggedIn(): bool
    {
        return $this->has('user_id');
    }

    public function getUserId(): ?int
    {
        return $this->has('user_id') ? (int) $this->get('user_id') : null;
    }

    public function getUserRole(): ?string
    {
        return $this->get('user_role');
    }

    public function getLocale(): string
    {
        return $this->get('locale', $_ENV['APP_LOCALE'] ?? 'en');
    }

    public function setLocale(string $locale): void
    {
        $this->set('locale', $locale);
    }
}
