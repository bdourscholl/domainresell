<div>
    <div class="card">
        <h3>Add Moderator</h3>
        <form action="/admin/moderators" method="POST">
            <?= csrf_field() ?>
            <div class="form-row">
                <div class="form-group"><label>Name</label><input type="text" name="name" class="form-control" required></div>
                <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                <div class="form-group"><label>Password</label><input type="password" name="password" class="form-control" required></div>
                <div class="form-group"><label>Role</label><select name="role" class="form-control"><option value="moderator">Moderator</option><option value="admin">Admin</option></select></div>
            </div>
            <button class="btn btn-primary">Create</button>
        </form>
    </div>
    <table class="table">
        <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Joined</th></tr></thead>
        <tbody>
            <?php foreach ($moderators as $m): ?>
            <tr><td><?= e($m['name']) ?></td><td><?= e($m['email']) ?></td><td><?= e($m['role']) ?></td><td><?= e($m['created_at']) ?></td></tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
