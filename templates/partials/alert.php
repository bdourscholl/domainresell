<?php if (!empty($message)): ?>
<div class="alert alert-<?= e($type ?? 'info') ?>">
    <?= e($message) ?>
    <button class="alert-close">&times;</button>
</div>
<?php endif; ?>
