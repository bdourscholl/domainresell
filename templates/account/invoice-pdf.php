<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 8px 12px; border: 1px solid #ddd; text-align: left; }
        th { background: #f5f5f5; }
        .total { font-size: 18px; font-weight: bold; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1><?= e(setting('site_name', 'Domain Reseller')) ?></h1>
        <p>Invoice #<?= e($invoice['invoice_number'] ?? '') ?></p>
        <p>Date: <?= e($invoice['created_at'] ?? '') ?></p>
    </div>
    <table>
        <thead><tr><th>Item</th><th>Type</th><th>Years</th><th>Amount</th></tr></thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?= e($item['domain_name']) ?></td>
                <td><?= e($item['item_type']) ?></td>
                <td><?= e($item['years']) ?></td>
                <td><?= format_currency((float) $item['price']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p class="total">Total: <?= format_currency((float) ($invoice['total'] ?? 0)) ?></p>
</body>
</html>
