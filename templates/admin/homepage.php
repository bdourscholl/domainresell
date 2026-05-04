<div class="admin-homepage">
    <h3>Homepage Statistics</h3>
    <form action="/admin/homepage/stats" method="POST">
        <?= csrf_field() ?>
        <table class="table">
            <thead><tr><th>Label</th><th>Value</th><th>Icon</th><th>Order</th><th>Active</th></tr></thead>
            <tbody>
                <?php foreach ($stats as $stat): ?>
                <tr>
                    <td><input type="text" name="stats[<?= $stat['id'] ?>][label]" class="form-control" value="<?= e($stat['stat_label']) ?>"></td>
                    <td><input type="text" name="stats[<?= $stat['id'] ?>][value]" class="form-control" value="<?= e($stat['stat_value']) ?>"></td>
                    <td><input type="text" name="stats[<?= $stat['id'] ?>][icon]" class="form-control" value="<?= e($stat['icon'] ?? '') ?>"></td>
                    <td><input type="number" name="stats[<?= $stat['id'] ?>][sort_order]" class="form-control" value="<?= e($stat['sort_order'] ?? 0) ?>"></td>
                    <td><input type="checkbox" name="stats[<?= $stat['id'] ?>][active]" <?= ($stat['is_active'] ?? 1) ? 'checked' : '' ?>></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Save Statistics</button>
    </form>

    <h3>Homepage Sections</h3>
    <?php foreach ($sections as $section): ?>
    <div class="card section-card">
        <form action="/admin/homepage/sections/<?= $section['id'] ?>" method="POST">
            <?= csrf_field() ?>
            <h4><?= e($section['section_key']) ?></h4>
            <div class="form-group"><label>Title</label><input type="text" name="section_title" class="form-control" value="<?= e($section['section_title'] ?? '') ?>"></div>
            <div class="form-group"><label>Subtitle</label><input type="text" name="section_subtitle" class="form-control" value="<?= e($section['section_subtitle'] ?? '') ?>"></div>
            <div class="form-group"><label>Content</label><textarea name="content" class="form-control" rows="3"><?= e($section['content'] ?? '') ?></textarea></div>
            <div class="form-row">
                <div class="form-group"><label>Order</label><input type="number" name="sort_order" class="form-control" value="<?= e($section['sort_order'] ?? 0) ?>"></div>
                <div class="form-group"><label><input type="checkbox" name="is_active" value="1" <?= ($section['is_active'] ?? 1) ? 'checked' : '' ?>> Active</label></div>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Update Section</button>
        </form>
    </div>
    <?php endforeach; ?>
</div>
