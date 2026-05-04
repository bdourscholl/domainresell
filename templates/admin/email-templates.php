<div>
    <?php if (!empty($template)): ?>
    <div class="card">
        <h3>Edit: <?= e($template['name']) ?></h3>
        <form action="/admin/email-templates/<?= $template['id'] ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-group"><label>Subject</label><input type="text" name="subject" class="form-control" value="<?= e($template['subject']) ?>"></div>
            <div class="form-group"><label>Body (HTML)</label><textarea name="body_html" class="form-control" rows="10"><?= e($template['body_html']) ?></textarea></div>
            <div class="form-group"><label><input type="checkbox" name="is_active" value="1" <?= ($template['is_active'] ?? 1) ? 'checked' : '' ?>> Active</label></div>
            <button class="btn btn-primary">Update Template</button>
        </form>
    </div>
    <?php endif; ?>
    <h3>Email Templates</h3>
    <table class="table">
        <thead><tr><th>Name</th><th>Subject</th><th>Active</th><th></th></tr></thead>
        <tbody>
            <?php foreach ($templates as $t): ?>
            <tr>
                <td><?= e($t['name']) ?></td>
                <td><?= e($t['subject']) ?></td>
                <td><?= ($t['is_active'] ?? 1) ? 'Yes' : 'No' ?></td>
                <td><a href="/admin/email-templates/<?= $t['id'] ?>" class="btn btn-sm">Edit</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
