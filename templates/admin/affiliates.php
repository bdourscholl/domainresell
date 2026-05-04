<div>
    <div class="card">
        <h3>Affiliate Settings</h3>
        <form action="/admin/affiliates/settings" method="POST">
            <?= csrf_field() ?>
            <div class="form-group"><label><input type="checkbox" name="affiliate_enabled" value="1" <?= setting('affiliate_enabled', '0') === '1' ? 'checked' : '' ?>> Enable Affiliate System</label></div>
            <div class="form-group"><label>Commission Rate (%)</label><input type="number" name="commission_rate" class="form-control" value="<?= e(setting('affiliate_commission_rate', '10')) ?>" step="0.1"></div>
            <button class="btn btn-primary">Save</button>
        </form>
    </div>
    <h3>Affiliates</h3>
    <table class="table">
        <thead><tr><th>User</th><th>Total Earnings</th><th>Paid</th><th>Pending</th><th>Status</th></tr></thead>
        <tbody>
            <?php foreach ($affiliates as $a): ?>
            <tr>
                <td><?= e($a['user_name'] ?? '-') ?></td>
                <td><?= format_currency((float) ($a['total_earnings'] ?? 0)) ?></td>
                <td><?= format_currency((float) ($a['paid_earnings'] ?? 0)) ?></td>
                <td><?= format_currency((float) ($a['pending_earnings'] ?? 0)) ?></td>
                <td><span class="badge"><?= e($a['status'] ?? '-') ?></span></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
