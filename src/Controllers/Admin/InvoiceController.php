<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\Invoice;

class InvoiceController
{
    public function index(Request $request): Response
    {
        $invoices = Invoice::all();
        return View::renderWithLayout('admin/invoices', ['invoices' => $invoices], 'admin');
    }
}
