<div>
    <div class="toolbar">
        <a href="/admin/tickets" class="btn btn-sm <?= empty($status) ? 'btn-primary' : '' ?>">All</a>
        <a href="/admin/tickets?status=open" class="btn btn-sm <?= ($status ?? '') === 'open' ? 'btn-primary' : '' ?>">Open</a>
        <a href="/admin/tickets?status=answered" class="btn btn-sm <?= ($status ?? '') === 'answered' ? 'btn-primary' : '' ?>">Answered</a>
        <a href="/admin/tickets?status=closed" class="btn btn-sm <?= ($status ?? '') === 'closed' ? 'btn-primary' : '' ?>">Closed</a>
    </div>
    <table class="table">
        <thead><tr><th>#</th><th>Subject</th><th>Customer</th><th>Priority</th><th>Status</th><th>Updated</th><th></th></tr></thead>
        <tbody>
            <?php foreach ($tickets as $t): ?>
            <tr>
                <td><?= e($t['id']) ?></td>
                <td><?= e($t['subject']) ?></td>
                <td><?= e($t['user_name'] ?? '-') ?></td>
                <td><?= e($t['priority']) ?></td>
                <td><span class="badge"><?= e($t['status']) ?></span></td>
                <td><?= e($t['updated_at']) ?></td>
                <td><a href="/admin/tickets/<?= $t['id'] ?>" class="btn btn-sm">View</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
