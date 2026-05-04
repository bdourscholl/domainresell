<div>
    <h3>Site Settings</h3>
    <form action="/admin/settings" method="POST">
        <?= csrf_field() ?>
        <?php foreach ($settings as $group => $items): ?>
        <div class="card">
            <h4><?= e(ucfirst($group)) ?></h4>
            <?php foreach ($items as $s): ?>
            <div class="form-group">
                <label><?= e($s['setting_key']) ?></label>
                <input type="text" name="settings[<?= e($s['setting_key']) ?>]" class="form-control" value="<?= e($s['setting_value']) ?>">
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
        <button class="btn btn-primary">Save Settings</button>
    </form>
</div>
