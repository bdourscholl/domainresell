<div class="account-page">
    <div class="container">
        <h2>Domain Renewals</h2>
        <?php if (empty($domains)): ?>
            <p>No domains to renew.</p>
        <?php else: ?>
        <table class="table">
            <thead><tr><th>Domain</th><th>Expires</th><th>Status</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($domains as $d): ?>
                <tr>
                    <td><?= e($d['domain_name']) ?></td>
                    <td><?= e($d['expiry_date'] ?? '-') ?></td>
                    <td><?= e($d['status']) ?></td>
                    <td>
                        <form action="/account/renewals/<?= $d['id'] ?>" method="POST" class="inline-form">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm btn-primary">Renew</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>
