<div class="account-page">
    <div class="container">
        <div class="account-grid">
            <aside class="account-sidebar">
                <nav class="account-nav">
                    <a href="/account" class="account-link active"><i class="fas fa-tachometer-alt"></i> <?= __('general.dashboard') ?></a>
                    <a href="/account/domains" class="account-link"><i class="fas fa-globe"></i> <?= __('general.my_domains') ?></a>
                    <a href="/account/orders" class="account-link"><i class="fas fa-shopping-bag"></i> <?= __('general.orders') ?></a>
                    <a href="/account/invoices" class="account-link"><i class="fas fa-file-invoice"></i> <?= __('general.invoices') ?></a>
                    <a href="/account/wallet" class="account-link"><i class="fas fa-wallet"></i> <?= __('general.wallet') ?></a>
                    <a href="/account/affiliate" class="account-link"><i class="fas fa-link"></i> <?= __('general.affiliate') ?></a>
                    <a href="/account/tickets" class="account-link"><i class="fas fa-headset"></i> <?= __('general.tickets') ?></a>
                    <a href="/account/verification" class="account-link"><i class="fas fa-id-card"></i> <?= __('general.verification') ?></a>
                    <a href="/account/profile" class="account-link"><i class="fas fa-user"></i> <?= __('general.profile') ?></a>
                </nav>
            </aside>
            <div class="account-content">
                <h2><?= __('general.dashboard') ?></h2>
                <div class="dashboard-stats">
                    <div class="stat-card"><div class="stat-value"><?= $domain_count ?? 0 ?></div><div class="stat-label"><?= __('general.my_domains') ?></div></div>
                </div>
                <div class="dashboard-sections">
                    <div class="section">
                        <h3><?= __('general.recent_domains') ?></h3>
                        <?php if (!empty($domains)): ?>
                        <table class="table">
                            <thead><tr><th>Domain</th><th>Status</th><th>Expires</th></tr></thead>
                            <tbody>
                            <?php foreach ($domains as $d): ?>
                            <tr><td><?= e($d['domain_name']) ?></td><td><?= e($d['status']) ?></td><td><?= e($d['expiry_date'] ?? '-') ?></td></tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php else: ?><p>No domains yet.</p><?php endif; ?>
                    </div>
                    <div class="section">
                        <h3><?= __('general.recent_orders') ?></h3>
                        <?php if (!empty($orders)): ?>
                        <table class="table">
                            <thead><tr><th>Order #</th><th>Total</th><th>Status</th></tr></thead>
                            <tbody>
                            <?php foreach ($orders as $o): ?>
                            <tr><td><a href="/account/orders/<?= e($o['id']) ?>"><?= e($o['order_number']) ?></a></td><td><?= format_currency((float) $o['total']) ?></td><td><?= e($o['status']) ?></td></tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php else: ?><p>No orders yet.</p><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
