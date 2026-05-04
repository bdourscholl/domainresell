<div>
    <h3><?= e($customer['name'] ?? '') ?></h3>
    <div class="card">
        <p><strong>Email:</strong> <?= e($customer['email'] ?? '') ?></p>
        <p><strong>Phone:</strong> <?= e($customer['phone'] ?? '-') ?></p>
        <p><strong>Status:</strong> <?= e($customer['status'] ?? '') ?></p>
        <p><strong>Joined:</strong> <?= e($customer['created_at'] ?? '') ?></p>
        <form action="/admin/customers/<?= $customer['id'] ?>/status" method="POST" class="inline-form">
            <?= csrf_field() ?>
            <select name="status" class="form-control"><option value="active">Active</option><option value="suspended">Suspended</option></select>
            <button class="btn btn-primary btn-sm">Update</button>
        </form>
    </div>
    <?php if (!empty($domains)): ?>
    <h4>Domains</h4>
    <table class="table">
        <thead><tr><th>Domain</th><th>Status</th><th>Expires</th></tr></thead>
        <tbody>
            <?php foreach ($domains as $d): ?>
            <tr><td><?= e($d['domain_name']) ?></td><td><?= e($d['status']) ?></td><td><?= e($d['expiry_date'] ?? '-') ?></td></tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
