<div>
    <table class="table">
        <thead><tr><th>User</th><th>Document</th><th>Number</th><th>Status</th><th>Submitted</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach ($verifications as $v): ?>
            <tr>
                <td><?= e($v['user_name'] ?? '-') ?></td>
                <td><?= e($v['document_type'] ?? '-') ?></td>
                <td><?= e($v['document_number'] ?? '-') ?></td>
                <td><span class="badge"><?= e($v['status']) ?></span></td>
                <td><?= e($v['created_at']) ?></td>
                <td>
                    <form action="/admin/verifications/<?= $v['id'] ?>/approve" method="POST" class="inline-form">
                        <?= csrf_field() ?>
                        <button class="btn btn-sm btn-success">Approve</button>
                    </form>
                    <form action="/admin/verifications/<?= $v['id'] ?>/reject" method="POST" class="inline-form">
                        <?= csrf_field() ?>
                        <input type="text" name="reason" placeholder="Reason" class="form-control">
                        <button class="btn btn-sm btn-danger">Reject</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
