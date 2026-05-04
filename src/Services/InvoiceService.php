<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\User;
use Dompdf\Dompdf;

class InvoiceService
{
    public function generatePdf(int $invoiceId): string
    {
        $invoice = Invoice::find($invoiceId);
        $user = User::find((int) $invoice['user_id']);
        $order = $invoice['order_id'] ? Order::find((int) $invoice['order_id']) : null;
        $items = $order ? Order::getItems((int) $order['id']) : [];

        $html = $this->renderInvoiceHtml($invoice, $user, $items);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfPath = BASE_PATH . '/storage/cache/invoice-' . $invoice['invoice_number'] . '.pdf';
        file_put_contents($pdfPath, $dompdf->output());

        return $pdfPath;
    }

    private function renderInvoiceHtml(array $invoice, array $user, array $items): string
    {
        $symbol = setting('currency_symbol', '৳');
        $siteName = setting('site_name', 'Domain Reseller');
        $itemsHtml = '';

        foreach ($items as $item) {
            $itemsHtml .= "<tr><td>{$item['domain_name']}</td><td>" . ucfirst($item['item_type']) . "</td><td>{$item['years']}yr</td><td>{$symbol}" . number_format((float)$item['price'], 2) . "</td></tr>";
        }

        return <<<HTML
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><style>
body{font-family:Arial,sans-serif;font-size:14px;color:#333}
.header{border-bottom:2px solid #333;padding-bottom:10px;margin-bottom:20px}
table{width:100%;border-collapse:collapse;margin:20px 0}
th,td{border:1px solid #ddd;padding:8px;text-align:left}
th{background:#f5f5f5}
.total{font-size:18px;font-weight:bold;text-align:right;margin-top:20px}
</style></head>
<body>
<div class="header">
<h1>{$siteName}</h1>
<p>Invoice #{$invoice['invoice_number']}</p>
</div>
<p><strong>Bill To:</strong> {$user['name']} ({$user['email']})</p>
<p><strong>Date:</strong> {$invoice['created_at']}</p>
<p><strong>Due Date:</strong> {$invoice['due_date']}</p>
<p><strong>Status:</strong> {$invoice['status']}</p>
<table><thead><tr><th>Domain</th><th>Type</th><th>Period</th><th>Price</th></tr></thead>
<tbody>{$itemsHtml}</tbody></table>
<p class="total">Subtotal: {$symbol}{$invoice['subtotal']}</p>
<p class="total">Discount: -{$symbol}{$invoice['discount']}</p>
<p class="total">Total: {$symbol}{$invoice['total']}</p>
</body></html>
HTML;
    }
}
