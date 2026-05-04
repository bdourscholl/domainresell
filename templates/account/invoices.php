<div class="account-page">
    <div class="container">
        <h2><?= __('general.invoices') ?></h2>
        <?php if (empty($invoices)): ?>
            <p>No invoices yet.</p>
        <?php else: ?>
        <table class="table">
            <thead><tr><th>Invoice #</th><th>Date</th><th>Amount</th><th>Status</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($invoices as $inv): ?>
                <tr>
                    <td><?= e($inv['invoice_number']) ?></td>
                    <td><?= e($inv['created_at']) ?></td>
                    <td><?= format_currency((float) $inv['total']) ?></td>
                    <td><span class="badge"><?= e($inv['status']) ?></span></td>
                    <td><a href="/account/invoices/<?= $inv['id'] ?>/download" class="btn btn-sm"><i class="fas fa-download"></i> PDF</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>
