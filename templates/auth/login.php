<div class="auth-page">
    <div class="container">
        <div class="auth-card">
            <h2><?= __('auth.login_title') ?></h2>
            <form action="/login" method="POST">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label><?= __('auth.email') ?></label>
                    <input type="email" name="email" class="form-control" value="<?= e(old('email')) ?>" required>
                </div>
                <div class="form-group">
                    <label><?= __('auth.password') ?></label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group form-flex">
                    <label><input type="checkbox" name="remember"> <?= __('auth.remember_me') ?></label>
                    <a href="/forgot-password"><?= __('auth.forgot_password') ?></a>
                </div>
                <button type="submit" class="btn btn-primary btn-block"><?= __('auth.login_button') ?></button>
            </form>
            <?= partial('social-login-buttons') ?>
            <p class="auth-link"><?= __('auth.no_account') ?> <a href="/register"><?= __('auth.register_now') ?></a></p>
        </div>
    </div>
</div>
