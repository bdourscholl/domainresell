<div>
    <h3>Active Font</h3>
    <div class="card">
        <p>Current: <strong><?= e($active_font['font_name'] ?? 'Tiro Bangla') ?></strong></p>
        <form action="/admin/appearance/font" method="POST">
            <?= csrf_field() ?>
            <div class="form-group">
                <label>Select Font</label>
                <select name="font_id" class="form-control">
                    <?php foreach ($fonts as $f): ?>
                    <option value="<?= $f['id'] ?>" <?= ($f['is_active'] ?? 0) ? 'selected' : '' ?>><?= e($f['font_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button class="btn btn-primary">Update Font</button>
        </form>
    </div>
</div>
