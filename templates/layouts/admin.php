<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title ?? 'Admin Panel') ?> - <?= e(setting('site_name', 'Domain Reseller')) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <?= partial('admin-sidebar') ?>
        <div class="admin-main">
            <header class="admin-header">
                <div class="admin-header-left">
                    <button class="sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
                    <h2><?= e($page_title ?? 'Dashboard') ?></h2>
                </div>
                <div class="admin-header-right">
                    <span class="admin-user"><i class="fas fa-user"></i> <?= e($session_user_name ?? 'Admin') ?></span>
                    <a href="/" class="btn btn-sm" target="_blank"><i class="fas fa-external-link-alt"></i> View Site</a>
                    <a href="/logout" class="btn btn-sm btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </header>

            <?php $flash_success = get_flash('success'); ?>
            <?php $flash_error = get_flash('error'); ?>
            <?php if ($flash_success): ?>
                <div class="alert alert-success"><?= e($flash_success) ?></div>
            <?php endif; ?>
            <?php if ($flash_error): ?>
                <div class="alert alert-error"><?= e($flash_error) ?></div>
            <?php endif; ?>

            <div class="admin-content">
                <?= $content ?? '' ?>
            </div>
        </div>
    </div>

    <script src="<?= asset('js/admin.js') ?>"></script>
</body>
</html>
