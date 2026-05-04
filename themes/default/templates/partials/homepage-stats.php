<?php if (!empty($stats)): ?>
<section class="theme-stats stats-section">
    <div class="container">
        <div class="stats-grid">
            <?php foreach ($stats as $stat): ?>
            <div class="stat-card">
                <i class="<?= e($stat['icon'] ?? 'fas fa-chart-line') ?>"></i>
                <h3><?= e($stat['stat_value']) ?></h3>
                <p><?= e($stat['stat_label']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
