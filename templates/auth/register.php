<div class="auth-page">
    <div class="container">
        <div class="auth-card">
            <h2><?= __('auth.register_title') ?></h2>
            <form action="/register" method="POST">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label><?= __('auth.name') ?></label>
                    <input type="text" name="name" class="form-control" value="<?= e(old('name')) ?>" required>
                </div>
                <div class="form-group">
                    <label><?= __('auth.email') ?></label>
                    <input type="email" name="email" class="form-control" value="<?= e(old('email')) ?>" required>
                </div>
                <div class="form-group">
                    <label><?= __('auth.password') ?></label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label><?= __('auth.confirm_password') ?></label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <?php if (!empty($ref)): ?>
                    <input type="hidden" name="ref_code" value="<?= e($ref) ?>">
                <?php endif; ?>
                <button type="submit" class="btn btn-primary btn-block"><?= __('auth.register_button') ?></button>
            </form>
            <?= partial('social-login-buttons') ?>
            <p class="auth-link"><?= __('auth.have_account') ?> <a href="/login"><?= __('auth.login_now') ?></a></p>
        </div>
    </div>
</div>
