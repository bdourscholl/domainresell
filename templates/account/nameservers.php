<div class="account-page">
    <div class="container">
        <h2>Nameservers: <?= e($domain['domain_name'] ?? '') ?></h2>
        <div class="card">
            <form action="/account/domains/<?= e($domain['id']) ?>/nameservers" method="POST">
                <?= csrf_field() ?>
                <?php for ($i = 1; $i <= 4; $i++): ?>
                <div class="form-group">
                    <label>Nameserver <?= $i ?></label>
                    <input type="text" name="ns<?= $i ?>" class="form-control" value="<?= e($nameservers[$i - 1]['nameserver'] ?? '') ?>" placeholder="ns<?= $i ?>.example.com">
                </div>
                <?php endfor; ?>
                <button type="submit" class="btn btn-primary">Update Nameservers</button>
            </form>
        </div>
    </div>
</div>
