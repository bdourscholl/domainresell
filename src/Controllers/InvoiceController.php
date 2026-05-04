<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Models\Invoice;
use App\Services\InvoiceService;

class InvoiceController
{
    public function index(Request $request): Response
    {
        $invoices = Invoice::findByUser(current_user_id());
        return View::renderWithLayout('account/invoices', ['invoices' => $invoices], 'main');
    }

    public function download(Request $request): Response
    {
        $invoiceId = (int) $request->param('id');
        $invoice = Invoice::find($invoiceId);

        if (!$invoice || (int) $invoice['user_id'] !== current_user_id()) {
            flash('error', 'Invoice not found.');
            return Response::redirect('/account/invoices');
        }

        $invoiceService = new InvoiceService();
        $pdfPath = $invoiceService->generatePdf($invoiceId);

        return Response::download($pdfPath, "invoice-{$invoice['invoice_number']}.pdf");
    }
}
