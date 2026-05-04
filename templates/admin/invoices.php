<div>
    <table class="table">
        <thead><tr><th>Invoice #</th><th>Customer</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
        <tbody>
            <?php foreach ($invoices as $inv): ?>
            <tr>
                <td><?= e($inv['invoice_number']) ?></td>
                <td><?= e($inv['user_name'] ?? '-') ?></td>
                <td><?= format_currency((float) $inv['total']) ?></td>
                <td><span class="badge"><?= e($inv['status']) ?></span></td>
                <td><?= e($inv['created_at']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
