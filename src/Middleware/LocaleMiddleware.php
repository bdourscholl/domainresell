<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Request;
use App\Core\Session;

class LocaleMiddleware
{
    public function handle(Request $request): void
    {
        $session = new Session();
        $locale = $session->getLocale();

        $supported = ['en', 'bn'];
        if (!in_array($locale, $supported, true)) {
            $locale = 'en';
            $session->setLocale($locale);
        }
    }
}
