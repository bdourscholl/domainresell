<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\EmailTemplate;

class EmailTemplateController
{
    public function index(Request $request): Response
    {
        $templates = EmailTemplate::all();
        return View::renderWithLayout('admin/email-templates', ['templates' => $templates], 'admin');
    }

    public function edit(Request $request): Response
    {
        $id = (int) $request->param('id');
        $template = EmailTemplate::find($id);
        return View::renderWithLayout('admin/email-templates', ['template' => $template, 'templates' => EmailTemplate::all()], 'admin');
    }

    public function update(Request $request): Response
    {
        $id = (int) $request->param('id');
        EmailTemplate::update($id, [
            'subject' => $request->post('subject'),
            'body_html' => $request->post('body_html'),
            'is_active' => $request->post('is_active', '0') === '1' ? 1 : 0,
        ]);

        flash('success', 'Email template updated.');
        return Response::redirect('/admin/email-templates');
    }
}
