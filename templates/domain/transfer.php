<div class="transfer-page">
    <div class="container">
        <h2><?= __('domain.transfer_title') ?></h2>
        <p><?= __('domain.transfer_description') ?></p>
        <div class="card">
            <form action="/transfer" method="POST">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label><?= __('domain.domain_name') ?></label>
                    <input type="text" name="domain" class="form-control" placeholder="example.com" required>
                </div>
                <div class="form-group">
                    <label><?= __('domain.epp_code') ?></label>
                    <input type="text" name="epp_code" class="form-control" placeholder="<?= __('domain.epp_placeholder') ?>" required>
                </div>
                <button type="submit" class="btn btn-primary"><?= __('domain.start_transfer') ?></button>
            </form>
        </div>
    </div>
</div>
