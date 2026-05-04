<nav class="theme-navbar navbar">
    <div class="container">
        <a href="/" class="navbar-brand"><?= e(setting('site_name', 'Domain Reseller')) ?></a>
        <div class="navbar-menu">
            <a href="/">Home</a>
            <a href="/search">Domains</a>
            <a href="/transfer">Transfer</a>
            <?php if (is_logged_in()): ?>
                <a href="/account">My Account</a>
                <a href="/cart"><i class="fas fa-shopping-cart"></i></a>
                <a href="/logout">Logout</a>
            <?php else: ?>
                <a href="/login">Login</a>
                <a href="/register" class="btn btn-primary btn-sm">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
