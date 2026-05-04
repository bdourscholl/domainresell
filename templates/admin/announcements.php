<div>
    <div class="card">
        <h3>Create Announcement</h3>
        <form action="/admin/announcements" method="POST">
            <?= csrf_field() ?>
            <div class="form-group"><label>Title</label><input type="text" name="title" class="form-control" required></div>
            <div class="form-group"><label>Content</label><textarea name="content" class="form-control" rows="3" required></textarea></div>
            <div class="form-row">
                <div class="form-group"><label>Type</label><select name="type" class="form-control"><option value="info">Info</option><option value="warning">Warning</option><option value="success">Success</option></select></div>
                <div class="form-group"><label><input type="checkbox" name="show_on_homepage" value="1" checked> Show on Homepage</label></div>
            </div>
            <button class="btn btn-primary">Create</button>
        </form>
    </div>
    <table class="table">
        <thead><tr><th>Title</th><th>Type</th><th>Active</th><th>Date</th><th></th></tr></thead>
        <tbody>
            <?php foreach ($announcements as $a): ?>
            <tr>
                <td><?= e($a['title']) ?></td>
                <td><?= e($a['type']) ?></td>
                <td><?= ($a['is_active'] ?? 0) ? 'Yes' : 'No' ?></td>
                <td><?= e($a['created_at']) ?></td>
                <td>
                    <form action="/admin/announcements/<?= $a['id'] ?>/delete" method="POST" class="inline-form">
                        <?= csrf_field() ?>
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
