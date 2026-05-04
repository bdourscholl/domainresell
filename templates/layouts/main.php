<!DOCTYPE html>
<html lang="<?= e($locale ?? 'en') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title ?? setting('site_name', 'Domain Reseller')) ?></title>
    <meta name="description" content="<?= e($meta_description ?? '') ?>">
    <?php $activeFont = $font ?? null; ?>
    <?php if ($activeFont && ($activeFont['font_type'] ?? '') === 'google' && !empty($activeFont['font_url'])): ?>
        <link href="<?= e($activeFont['font_url']) ?>" rel="stylesheet">
    <?php endif; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <style>
        body { font-family: <?= $font_family ?? "'Tiro Bangla', serif" ?>; }
        @font-face {
            font-family: 'Tiro Bangla';
            src: url('<?= asset('fonts/TiroBangla/TiroBangla-Regular.ttf') ?>') format('truetype');
            font-weight: normal;
        }
        @font-face {
            font-family: 'Tiro Bangla';
            src: url('<?= asset('fonts/TiroBangla/TiroBangla-Italic.ttf') ?>') format('truetype');
            font-style: italic;
        }
    </style>
</head>
<body>
    <?= partial('announcement-bar', ['announcements' => $announcements ?? []]) ?>
    <?= partial('navbar') ?>

    <?php $flash_success = get_flash('success'); ?>
    <?php $flash_error = get_flash('error'); ?>
    <?php $flash_warning = get_flash('warning'); ?>
    <?php if ($flash_success): ?>
        <div class="alert alert-success"><?= e($flash_success) ?></div>
    <?php endif; ?>
    <?php if ($flash_error): ?>
        <div class="alert alert-error"><?= e($flash_error) ?></div>
    <?php endif; ?>
    <?php if ($flash_warning): ?>
        <div class="alert alert-warning"><?= e($flash_warning) ?></div>
    <?php endif; ?>

    <main class="main-content">
        <?= $content ?? '' ?>
    </main>

    <?= partial('footer') ?>

    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
