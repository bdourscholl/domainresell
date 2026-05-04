<div>
    <div class="card">
        <h3>Create Coupon</h3>
        <form action="/admin/coupons" method="POST">
            <?= csrf_field() ?>
            <div class="form-row">
                <div class="form-group"><label>Code</label><input type="text" name="code" class="form-control" required></div>
                <div class="form-group"><label>Type</label><select name="type" class="form-control"><option value="percentage">Percentage</option><option value="fixed">Fixed</option></select></div>
                <div class="form-group"><label>Value</label><input type="number" name="value" class="form-control" step="0.01" required></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Min Order</label><input type="number" name="min_order" class="form-control" step="0.01"></div>
                <div class="form-group"><label>Max Discount</label><input type="number" name="max_discount" class="form-control" step="0.01"></div>
                <div class="form-group"><label>Max Uses</label><input type="number" name="max_uses" class="form-control"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Valid From</label><input type="date" name="valid_from" class="form-control"></div>
                <div class="form-group"><label>Valid Until</label><input type="date" name="valid_until" class="form-control"></div>
            </div>
            <button type="submit" class="btn btn-primary">Create Coupon</button>
        </form>
    </div>
    <table class="table">
        <thead><tr><th>Code</th><th>Type</th><th>Value</th><th>Uses</th><th>Status</th><th></th></tr></thead>
        <tbody>
            <?php foreach ($coupons as $c): ?>
            <tr>
                <td><strong><?= e($c['code']) ?></strong></td>
                <td><?= e($c['type']) ?></td>
                <td><?= e($c['value']) ?><?= $c['type'] === 'percentage' ? '%' : '' ?></td>
                <td><?= e($c['used_count'] ?? 0) ?>/<?= e($c['max_uses'] ?? '&infin;') ?></td>
                <td><span class="badge badge-<?= ($c['is_active'] ?? 0) ? 'success' : 'danger' ?>"><?= ($c['is_active'] ?? 0) ? 'Active' : 'Inactive' ?></span></td>
                <td>
                    <form action="/admin/coupons/<?= $c['id'] ?>/delete" method="POST" class="inline-form">
                        <?= csrf_field() ?>
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
