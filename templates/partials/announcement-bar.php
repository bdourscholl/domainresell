<?php if (!empty($announcements)): ?>
<?php foreach ($announcements as $announcement): ?>
<div class="announcement-bar announcement-<?= e($announcement['type'] ?? 'info') ?>">
    <div class="container">
        <strong><?= e($announcement['title']) ?></strong>: <?= e($announcement['content']) ?>
        <button class="announcement-close">&times;</button>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>
