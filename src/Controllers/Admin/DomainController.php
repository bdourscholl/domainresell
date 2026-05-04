<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\Domain;

class DomainController
{
    public function index(Request $request): Response
    {
        $search = $request->get('search', '');
        $domains = Domain::all(50, 0, $search);
        return View::renderWithLayout('admin/domains', ['domains' => $domains, 'search' => $search], 'admin');
    }
}
