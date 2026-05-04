<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <h4><?= e(setting('site_name', 'Domain Reseller')) ?></h4>
                <p><?= e(setting('site_tagline', 'Your Trusted Domain Partner')) ?></p>
            </div>
            <div class="footer-col">
                <h4><?= __('general.quick_links') ?></h4>
                <ul>
                    <li><a href="/search"><?= __('general.search_domains') ?></a></li>
                    <li><a href="/transfer"><?= __('general.transfer_domain') ?></a></li>
                    <li><a href="/register"><?= __('general.create_account') ?></a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4><?= __('general.support') ?></h4>
                <ul>
                    <li><a href="/account/tickets"><?= __('general.support_tickets') ?></a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4><?= __('general.payment_methods') ?></h4>
                <p>bKash | Nagad | Stripe | PayPal</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> <?= e(setting('site_name', 'Domain Reseller')) ?>. All rights reserved.</p>
        </div>
    </div>
</footer>
