<nav class="navbar">
    <div class="container">
        <a href="/" class="navbar-brand"><?= e(setting('site_name', 'Domain Reseller')) ?></a>
        <div class="navbar-menu">
            <a href="/" class="nav-link"><?= __('general.home') ?></a>
            <a href="/search" class="nav-link"><?= __('general.domains') ?></a>
            <a href="/transfer" class="nav-link"><?= __('general.transfer') ?></a>
            <?php if (is_logged_in()): ?>
                <a href="/account" class="nav-link"><?= __('general.my_account') ?></a>
                <a href="/cart" class="nav-link"><i class="fas fa-shopping-cart"></i> <?= __('general.cart') ?></a>
                <?php if (is_admin()): ?>
                    <a href="/admin" class="nav-link nav-admin"><?= __('general.admin') ?></a>
                <?php endif; ?>
                <a href="/logout" class="nav-link"><?= __('general.logout') ?></a>
            <?php else: ?>
                <a href="/login" class="nav-link"><?= __('general.login') ?></a>
                <a href="/register" class="btn btn-primary btn-sm"><?= __('general.register') ?></a>
            <?php endif; ?>
            <?= partial('lang-switcher') ?>
            <button type="button" class="theme-toggle" data-theme-toggle aria-label="<?= __('general.toggle_theme') ?: 'Toggle theme' ?>" title="<?= __('general.toggle_theme') ?: 'Toggle theme' ?>">
                <i class="fas fa-sun icon-sun"></i>
                <i class="fas fa-moon icon-moon"></i>
            </button>
        </div>
    </div>
</nav>
