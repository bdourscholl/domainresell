<div class="account-page">
    <div class="container">
        <h2><?= __('general.orders') ?></h2>
        <?php if (empty($orders)): ?>
            <p>No orders yet.</p>
        <?php else: ?>
        <table class="table">
            <thead><tr><th>Order #</th><th>Date</th><th>Total</th><th>Payment</th><th>Status</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($orders as $o): ?>
                <tr>
                    <td><?= e($o['order_number']) ?></td>
                    <td><?= e($o['created_at']) ?></td>
                    <td><?= format_currency((float) $o['total']) ?></td>
                    <td><?= e($o['payment_status']) ?></td>
                    <td><span class="badge"><?= e($o['status']) ?></span></td>
                    <td><a href="/account/orders/<?= $o['id'] ?>" class="btn btn-sm">View</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>
