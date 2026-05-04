<!DOCTYPE html>
<?php
    $serverThemePref = null;
    if (function_exists('current_user')) {
        $u = current_user();
        if ($u && !empty($u['theme_preference'])) {
            $serverThemePref = $u['theme_preference'];
        }
    }
?>
<html lang="<?= e($locale ?? 'en') ?>"<?= $serverThemePref && in_array($serverThemePref, ['light','dark'], true) ? ' data-theme="' . e($serverThemePref) . '"' : '' ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#6366f1" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0b1120" media="(prefers-color-scheme: dark)">
    <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
    <title><?= e($page_title ?? setting('site_name', 'Domain Reseller')) ?></title>
    <meta name="description" content="<?= e($meta_description ?? '') ?>">
    <script>
        // Apply persisted theme as early as possible to avoid FOUC
        (function () {
            try {
                var html = document.documentElement;
                if (html.hasAttribute('data-theme')) return;
                var stored = localStorage.getItem('theme');
                var resolved;
                if (stored === 'light' || stored === 'dark') {
                    resolved = stored;
                } else {
                    resolved = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                }
                html.setAttribute('data-theme', resolved);
            } catch (e) { /* localStorage unavailable */ }
        })();
    </script>
    <?php $activeFont = $font ?? null; ?>
    <?php if ($activeFont && ($activeFont['font_type'] ?? '') === 'google' && !empty($activeFont['font_url'])): ?>
        <link href="<?= e($activeFont['font_url']) ?>" rel="stylesheet">
    <?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Tiro+Bangla:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/fonts.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/theme.css') ?>">
    <style>
        body { font-family: <?= $font_family ?? "'Inter', 'Tiro Bangla', system-ui, -apple-system, sans-serif" ?>; }
        /* Make sure Font Awesome icon fonts always win over body font-family. */
        .fas, .far, .fab, .fa-solid, .fa-regular, .fa-brands { font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands" !important; }
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

    <script src="<?= asset('js/theme.js') ?>"></script>
    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
