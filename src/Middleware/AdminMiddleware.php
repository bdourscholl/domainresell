<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class AdminMiddleware
{
    public function handle(Request $request): ?Response
    {
        $session = new Session();
        $role = $session->getUserRole();

        if (!in_array($role, ['admin', 'moderator'], true)) {
            return Response::redirect('/account');
        }

        return null;
    }
}
