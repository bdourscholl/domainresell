<div>
    <div class="card">
        <h3>Add TLD</h3>
        <form action="/admin/pricing" method="POST" class="inline-form">
            <?= csrf_field() ?>
            <input type="text" name="tld" class="form-control" placeholder=".com" required>
            <input type="number" name="register_price" class="form-control" placeholder="Register" step="0.01" required>
            <input type="number" name="renew_price" class="form-control" placeholder="Renew" step="0.01" required>
            <input type="number" name="transfer_price" class="form-control" placeholder="Transfer" step="0.01" required>
            <label><input type="checkbox" name="is_featured" value="1"> Featured</label>
            <button class="btn btn-primary">Add</button>
        </form>
    </div>
    <table class="table">
        <thead><tr><th>TLD</th><th>Register</th><th>Renew</th><th>Transfer</th><th>Featured</th><th>Active</th><th></th></tr></thead>
        <tbody>
            <?php foreach ($tlds as $t): ?>
            <tr>
                <form action="/admin/pricing/<?= $t['id'] ?>" method="POST">
                    <?= csrf_field() ?>
                    <td><?= e($t['tld']) ?></td>
                    <td><input type="number" name="register_price" class="form-control" value="<?= e($t['register_price']) ?>" step="0.01"></td>
                    <td><input type="number" name="renew_price" class="form-control" value="<?= e($t['renew_price']) ?>" step="0.01"></td>
                    <td><input type="number" name="transfer_price" class="form-control" value="<?= e($t['transfer_price']) ?>" step="0.01"></td>
                    <td><input type="checkbox" name="is_featured" value="1" <?= ($t['is_featured'] ?? 0) ? 'checked' : '' ?>></td>
                    <td><input type="checkbox" name="is_active" value="1" <?= ($t['is_active'] ?? 1) ? 'checked' : '' ?>></td>
                    <td><button class="btn btn-sm btn-primary">Save</button></td>
                </form>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
