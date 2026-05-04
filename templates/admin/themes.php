<div>
    <div class="card">
        <h3>Upload Theme</h3>
        <form action="/admin/themes/upload" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="form-group"><input type="file" name="theme_zip" class="form-control" accept=".zip" required></div>
            <button class="btn btn-primary">Install Theme</button>
        </form>
    </div>
    <h3>Installed Themes</h3>
    <div class="themes-grid">
        <?php foreach ($themes as $t): ?>
        <div class="theme-card">
            <h4><?= e($t['name']) ?></h4>
            <p>Author: <?= e($t['author'] ?? '-') ?> | Version: <?= e($t['version'] ?? '-') ?></p>
            <p>
                <?php if ($t['is_active_home'] ?? 0): ?><span class="badge badge-success">Active (Home)</span><?php endif; ?>
                <?php if ($t['is_active_account'] ?? 0): ?><span class="badge badge-success">Active (Account)</span><?php endif; ?>
            </p>
            <div class="theme-actions">
                <form action="/admin/themes/activate-home" method="POST" class="inline-form"><?= csrf_field() ?><input type="hidden" name="slug" value="<?= e($t['slug']) ?>"><button class="btn btn-sm btn-primary">Set Home</button></form>
                <form action="/admin/themes/activate-account" method="POST" class="inline-form"><?= csrf_field() ?><input type="hidden" name="slug" value="<?= e($t['slug']) ?>"><button class="btn btn-sm btn-primary">Set Account</button></form>
                <?php if ($t['slug'] !== 'default'): ?>
                <form action="/admin/themes/<?= e($t['slug']) ?>/delete" method="POST" class="inline-form"><?= csrf_field() ?><button class="btn btn-sm btn-danger">Delete</button></form>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
