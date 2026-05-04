<div>
    <div class="toolbar">
        <form action="/admin/domains" method="GET" class="inline-form">
            <input type="text" name="search" class="form-control" value="<?= e($search ?? '') ?>" placeholder="Search domains...">
            <button class="btn btn-primary">Search</button>
        </form>
    </div>
    <table class="table">
        <thead><tr><th>Domain</th><th>Owner</th><th>Registrar</th><th>Status</th><th>Expires</th></tr></thead>
        <tbody>
            <?php foreach ($domains as $d): ?>
            <tr>
                <td><?= e($d['domain_name']) ?></td>
                <td><?= e($d['user_name'] ?? '-') ?></td>
                <td><?= e($d['registrar'] ?? '-') ?></td>
                <td><span class="badge"><?= e($d['status']) ?></span></td>
                <td><?= e($d['expiry_date'] ?? '-') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
