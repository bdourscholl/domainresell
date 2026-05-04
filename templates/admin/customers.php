<div>
    <div class="toolbar">
        <form action="/admin/customers" method="GET" class="inline-form">
            <input type="text" name="search" class="form-control" value="<?= e($search ?? '') ?>" placeholder="Search customers...">
            <button class="btn btn-primary">Search</button>
        </form>
    </div>
    <table class="table">
        <thead><tr><th>Name</th><th>Email</th><th>Status</th><th>Domains</th><th>Joined</th><th></th></tr></thead>
        <tbody>
            <?php foreach ($customers as $c): ?>
            <tr>
                <td><?= e($c['name']) ?></td>
                <td><?= e($c['email']) ?></td>
                <td><span class="badge badge-<?= $c['status'] === 'active' ? 'success' : 'danger' ?>"><?= e($c['status']) ?></span></td>
                <td><?= e($c['domain_count'] ?? 0) ?></td>
                <td><?= e($c['created_at']) ?></td>
                <td><a href="/admin/customers/<?= $c['id'] ?>" class="btn btn-sm">View</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
