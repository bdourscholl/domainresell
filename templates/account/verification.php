<div class="account-page">
    <div class="container">
        <h2><?= __('general.verification') ?></h2>
        <?php if ($verification): ?>
            <div class="card">
                <p><strong>Status:</strong> <span class="badge badge-<?= $verification['status'] === 'approved' ? 'success' : ($verification['status'] === 'rejected' ? 'danger' : 'warning') ?>"><?= e($verification['status']) ?></span></p>
                <p><strong>Document Type:</strong> <?= e($verification['document_type'] ?? '-') ?></p>
                <p><strong>Submitted:</strong> <?= e($verification['created_at'] ?? '') ?></p>
                <?php if ($verification['status'] === 'rejected' && !empty($verification['rejection_reason'])): ?>
                    <p><strong>Reason:</strong> <?= e($verification['rejection_reason']) ?></p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="card">
                <h3>Submit Identity Verification</h3>
                <form action="/account/verification" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label>Document Type</label>
                        <select name="document_type" class="form-control">
                            <option value="nid">National ID (NID)</option>
                            <option value="passport">Passport</option>
                            <option value="driving_license">Driving License</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Document Number</label>
                        <input type="text" name="document_number" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Front Image</label>
                        <input type="file" name="front_image" class="form-control" accept="image/*" required>
                    </div>
                    <div class="form-group">
                        <label>Back Image (optional)</label>
                        <input type="file" name="back_image" class="form-control" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit for Review</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>
