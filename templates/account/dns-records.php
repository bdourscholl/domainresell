<div class="account-page">
    <div class="container">
        <h2>DNS Records: <?= e($domain['domain_name'] ?? '') ?></h2>
        <div class="card">
            <form action="/account/domains/<?= e($domain['id']) ?>/dns" method="POST">
                <?= csrf_field() ?>
                <div class="form-row">
                    <div class="form-group">
                        <label>Type</label>
                        <select name="record_type" class="form-control">
                            <option>A</option><option>AAAA</option><option>CNAME</option><option>MX</option><option>TXT</option><option>NS</option><option>SRV</option>
                        </select>
                    </div>
                    <div class="form-group"><label>Name</label><input type="text" name="name" class="form-control" placeholder="@"></div>
                    <div class="form-group"><label>Value</label><input type="text" name="value" class="form-control" required></div>
                    <div class="form-group"><label>TTL</label><input type="number" name="ttl" class="form-control" value="3600"></div>
                    <div class="form-group"><label>Priority</label><input type="number" name="priority" class="form-control" value="0"></div>
                    <button type="submit" class="btn btn-primary">Add Record</button>
                </div>
            </form>
        </div>
        <?php if (!empty($records)): ?>
        <table class="table">
            <thead><tr><th>Type</th><th>Name</th><th>Value</th><th>TTL</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($records as $r): ?>
                <tr>
                    <td><?= e($r['record_type']) ?></td>
                    <td><?= e($r['name']) ?></td>
                    <td><?= e($r['value']) ?></td>
                    <td><?= e($r['ttl']) ?></td>
                    <td>
                        <form action="/account/domains/<?= e($domain['id']) ?>/dns/<?= e($r['id']) ?>/delete" method="POST" class="inline-form">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>
