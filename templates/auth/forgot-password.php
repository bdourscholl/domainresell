<div class="auth-page">
    <div class="container">
        <div class="auth-card">
            <?php if (!empty($reset) && !empty($token)): ?>
                <h2><?= __('auth.reset_password') ?></h2>
                <form action="/reset-password" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="token" value="<?= e($token) ?>">
                    <div class="form-group">
                        <label><?= __('auth.new_password') ?></label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label><?= __('auth.confirm_password') ?></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block"><?= __('auth.reset_button') ?></button>
                </form>
            <?php else: ?>
                <h2><?= __('auth.forgot_title') ?></h2>
                <p><?= __('auth.forgot_description') ?></p>
                <form action="/forgot-password" method="POST">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label><?= __('auth.email') ?></label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block"><?= __('auth.send_reset_link') ?></button>
                </form>
            <?php endif; ?>
            <p class="auth-link"><a href="/login">&larr; <?= __('auth.back_to_login') ?></a></p>
        </div>
    </div>
</div>
