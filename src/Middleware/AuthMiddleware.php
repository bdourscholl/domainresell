<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class AuthMiddleware
{
    public function handle(Request $request): ?Response
    {
        $session = new Session();

        if (!$session->isLoggedIn()) {
            $session->flash('error', __('auth.login_required'));
            $session->set('redirect_after_login', $request->getUri());
            return Response::redirect('/login');
        }

        return null;
    }
}
