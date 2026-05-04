<div>
    <h3>Order #<?= e($order['order_number'] ?? '') ?></h3>
    <div class="card">
        <p><strong>Customer:</strong> <?= e($order['user_name'] ?? '-') ?></p>
        <p><strong>Status:</strong> <?= e($order['status'] ?? '') ?></p>
        <p><strong>Payment:</strong> <?= e($order['payment_status'] ?? '') ?> (<?= e($order['payment_method'] ?? '') ?>)</p>
        <p><strong>Total:</strong> <?= format_currency((float) ($order['total'] ?? 0)) ?></p>
        <form action="/admin/orders/<?= $order['id'] ?>/status" method="POST" class="inline-form">
            <?= csrf_field() ?>
            <select name="status" class="form-control">
                <option <?= ($order['status'] ?? '') === 'pending' ? 'selected' : '' ?>>pending</option>
                <option <?= ($order['status'] ?? '') === 'processing' ? 'selected' : '' ?>>processing</option>
                <option <?= ($order['status'] ?? '') === 'completed' ? 'selected' : '' ?>>completed</option>
                <option <?= ($order['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>cancelled</option>
            </select>
            <button class="btn btn-primary">Update Status</button>
        </form>
    </div>
    <?php if (!empty($items)): ?>
    <h4>Items</h4>
    <table class="table">
        <thead><tr><th>Domain</th><th>Type</th><th>Years</th><th>Price</th></tr></thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr><td><?= e($item['domain_name']) ?></td><td><?= e($item['item_type']) ?></td><td><?= e($item['years']) ?></td><td><?= format_currency((float) $item['price']) ?></td></tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
