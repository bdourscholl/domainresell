<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class CsrfMiddleware
{
    public function handle(Request $request): ?Response
    {
        if ($request->getMethod() !== 'POST') {
            return null;
        }

        $session = new Session();
        $token = $request->post('csrf_token') ?? $request->header('X-CSRF-Token');

        if (!$token || $token !== $session->getCsrfToken()) {
            $session->flash('error', 'Invalid security token. Please try again.');
            return Response::redirect($request->getUri());
        }

        return null;
    }
}
