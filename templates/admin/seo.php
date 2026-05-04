<div>
    <h3>SEO Settings</h3>
    <form action="/admin/seo" method="POST">
        <?= csrf_field() ?>
        <?php foreach ($seo_settings as $seo): ?>
        <div class="card">
            <h4><?= e($seo['page_key']) ?></h4>
            <div class="form-group"><label>Meta Title</label><input type="text" name="pages[<?= e($seo['page_key']) ?>][meta_title]" class="form-control" value="<?= e($seo['meta_title'] ?? '') ?>"></div>
            <div class="form-group"><label>Meta Description</label><textarea name="pages[<?= e($seo['page_key']) ?>][meta_description]" class="form-control" rows="2"><?= e($seo['meta_description'] ?? '') ?></textarea></div>
            <div class="form-group"><label>Meta Keywords</label><input type="text" name="pages[<?= e($seo['page_key']) ?>][meta_keywords]" class="form-control" value="<?= e($seo['meta_keywords'] ?? '') ?>"></div>
        </div>
        <?php endforeach; ?>
        <button class="btn btn-primary">Save SEO Settings</button>
    </form>
</div>
