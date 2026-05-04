<div class="account-page">
    <div class="container">
        <h2>WHOIS Privacy: <?= e($domain['domain_name'] ?? '') ?></h2>
        <div class="card">
            <p>WHOIS Privacy is currently: <strong><?= ($domain['whois_privacy'] ?? 0) ? 'Enabled' : 'Disabled' ?></strong></p>
            <form action="/account/domains/<?= e($domain['id']) ?>/privacy" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="enable" value="<?= ($domain['whois_privacy'] ?? 0) ? '0' : '1' ?>">
                <button type="submit" class="btn btn-primary">
                    <?= ($domain['whois_privacy'] ?? 0) ? 'Disable Privacy' : 'Enable Privacy' ?>
                </button>
            </form>
        </div>
    </div>
</div>
