<?php if (setting('social_login_enabled', '0') === '1'): ?>
<div class="social-login">
    <p class="social-divider"><span>or continue with</span></p>
    <div class="social-buttons">
        <a href="/auth/google/redirect" class="btn btn-social btn-google"><i class="fab fa-google"></i> Google</a>
        <a href="/auth/facebook/redirect" class="btn btn-social btn-facebook"><i class="fab fa-facebook-f"></i> Facebook</a>
        <a href="/auth/github/redirect" class="btn btn-social btn-github"><i class="fab fa-github"></i> GitHub</a>
    </div>
</div>
<?php endif; ?>
