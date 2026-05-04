<div class="account-page">
    <div class="container">
        <h2><?= __('general.my_domains') ?></h2>
        <?php if (empty($domains)): ?>
            <p>You don't have any domains yet. <a href="/search">Search for a domain</a></p>
        <?php else: ?>
        <table class="table">
            <thead>
                <tr><th>Domain</th><th>Status</th><th>Registrar</th><th>Expires</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($domains as $d): ?>
                <tr>
                    <td><?= e($d['domain_name']) ?></td>
                    <td><span class="badge badge-<?= $d['status'] === 'active' ? 'success' : 'warning' ?>"><?= e($d['status']) ?></span></td>
                    <td><?= e($d['registrar'] ?? '-') ?></td>
                    <td><?= e($d['expiry_date'] ?? '-') ?></td>
                    <td>
                        <a href="/account/domains/<?= $d['id'] ?>/nameservers" class="btn btn-sm">NS</a>
                        <a href="/account/domains/<?= $d['id'] ?>/dns" class="btn btn-sm">DNS</a>
                        <a href="/account/domains/<?= $d['id'] ?>/privacy" class="btn btn-sm">Privacy</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>
