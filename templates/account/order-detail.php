<div class="account-page">
    <div class="container">
        <h2>Order #<?= e($order['order_number'] ?? '') ?></h2>
        <div class="card">
            <div class="order-meta">
                <p><strong>Date:</strong> <?= e($order['created_at'] ?? '') ?></p>
                <p><strong>Status:</strong> <?= e($order['status'] ?? '') ?></p>
                <p><strong>Payment:</strong> <?= e($order['payment_status'] ?? '') ?> (<?= e($order['payment_method'] ?? '') ?>)</p>
                <p><strong>Subtotal:</strong> <?= format_currency((float) ($order['subtotal'] ?? 0)) ?></p>
                <?php if (($order['discount'] ?? 0) > 0): ?>
                <p><strong>Discount:</strong> -<?= format_currency((float) $order['discount']) ?></p>
                <?php endif; ?>
                <p><strong>Total:</strong> <?= format_currency((float) ($order['total'] ?? 0)) ?></p>
            </div>
        </div>
        <?php if (!empty($items)): ?>
        <h3>Order Items</h3>
        <table class="table">
            <thead><tr><th>Domain</th><th>Type</th><th>Years</th><th>Price</th></tr></thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= e($item['domain_name']) ?></td>
                    <td><?= ucfirst(e($item['item_type'])) ?></td>
                    <td><?= e($item['years']) ?></td>
                    <td><?= format_currency((float) $item['price']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>
