<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\SiteSetting;

class RegistrarController
{
    public function index(Request $request): Response
    {
        $registrars = \App\Core\App::getInstance()->config('registrars');
        return View::renderWithLayout('admin/registrars', ['registrars' => $registrars], 'admin');
    }

    public function update(Request $request): Response
    {
        $defaultRegistrar = $request->post('default_registrar', 'namecheap');
        SiteSetting::set('default_registrar', $defaultRegistrar, 'registrar');
        flash('success', 'Registrar settings updated.');
        return Response::redirect('/admin/registrars');
    }
}
