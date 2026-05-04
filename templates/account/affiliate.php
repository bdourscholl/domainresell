<div class="account-page">
    <div class="container">
        <h2><?= __('general.affiliate') ?></h2>
        <div class="card">
            <h3>Your Referral Link</h3>
            <div class="referral-link">
                <input type="text" readonly class="form-control" value="<?= url('register?ref=' . ($referral_code ?? '')) ?>" id="refLink">
                <button class="btn btn-primary" onclick="navigator.clipboard.writeText(document.getElementById('refLink').value)">Copy</button>
            </div>
            <div class="affiliate-stats">
                <p><strong>Total Earnings:</strong> <?= format_currency((float) ($affiliate['total_earnings'] ?? 0)) ?></p>
                <p><strong>Paid:</strong> <?= format_currency((float) ($affiliate['paid_earnings'] ?? 0)) ?></p>
                <p><strong>Pending:</strong> <?= format_currency((float) ($affiliate['pending_earnings'] ?? 0)) ?></p>
            </div>
        </div>
        <?php if (!empty($transactions)): ?>
        <h3>Commission History</h3>
        <table class="table">
            <thead><tr><th>Date</th><th>Amount</th><th>Description</th></tr></thead>
            <tbody>
                <?php foreach ($transactions as $t): ?>
                <tr>
                    <td><?= e($t['created_at']) ?></td>
                    <td><?= format_currency((float) $t['amount']) ?></td>
                    <td><?= e($t['description']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>
