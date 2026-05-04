<?php if (!empty($stats)): ?>
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <?php foreach ($stats as $stat): ?>
            <div class="stat-card">
                <div class="stat-icon"><i class="<?= e($stat['icon'] ?? 'fas fa-chart-line') ?>"></i></div>
                <div class="stat-value"><?= e($stat['stat_value']) ?></div>
                <div class="stat-label"><?= e($stat['stat_label']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
