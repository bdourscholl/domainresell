<div class="account-page">
    <div class="container">
        <h2><?= __('general.wallet') ?></h2>
        <div class="card">
            <div class="wallet-balance">
                <h3>Balance: <?= format_currency($balance ?? 0) ?></h3>
            </div>
        </div>
        <h3>Transaction History</h3>
        <?php if (empty($transactions)): ?>
            <p>No transactions yet.</p>
        <?php else: ?>
        <table class="table">
            <thead><tr><th>Date</th><th>Type</th><th>Amount</th><th>Description</th></tr></thead>
            <tbody>
                <?php foreach ($transactions as $t): ?>
                <tr>
                    <td><?= e($t['created_at']) ?></td>
                    <td><?= e($t['type']) ?></td>
                    <td class="<?= $t['type'] === 'credit' ? 'text-success' : 'text-danger' ?>">
                        <?= $t['type'] === 'credit' ? '+' : '-' ?><?= format_currency((float) $t['amount']) ?>
                    </td>
                    <td><?= e($t['description']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>
