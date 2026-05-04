<div>
    <div class="toolbar">
        <a href="/admin/orders" class="btn btn-sm <?= empty($status) ? 'btn-primary' : '' ?>">All</a>
        <a href="/admin/orders?status=pending" class="btn btn-sm <?= ($status ?? '') === 'pending' ? 'btn-primary' : '' ?>">Pending</a>
        <a href="/admin/orders?status=processing" class="btn btn-sm <?= ($status ?? '') === 'processing' ? 'btn-primary' : '' ?>">Processing</a>
        <a href="/admin/orders?status=completed" class="btn btn-sm <?= ($status ?? '') === 'completed' ? 'btn-primary' : '' ?>">Completed</a>
    </div>
    <table class="table">
        <thead><tr><th>Order #</th><th>Customer</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th><th></th></tr></thead>
        <tbody>
            <?php foreach ($orders as $o): ?>
            <tr>
                <td><?= e($o['order_number']) ?></td>
                <td><?= e($o['user_name'] ?? '-') ?></td>
                <td><?= format_currency((float) $o['total']) ?></td>
                <td><?= e($o['payment_status']) ?></td>
                <td><span class="badge"><?= e($o['status']) ?></span></td>
                <td><?= e($o['created_at']) ?></td>
                <td><a href="/admin/orders/<?= $o['id'] ?>" class="btn btn-sm">View</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
