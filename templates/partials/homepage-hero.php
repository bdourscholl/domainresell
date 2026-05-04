<?php
    $title = tt('home.hero_title') ?? $hero['section_title'] ?? 'Launch into the future';
    $subtitle = tt('home.hero_subtitle') ?? $hero['section_subtitle'] ?? 'Register, transfer, and manage your domains on the most powerful platform built for Bangladesh and beyond.';
    $eyebrow = tt('home.hero_eyebrow') ?? 'DOMAINS · HOSTING · EMAIL';
?>
<section class="hero-section">
    <div class="hero-aurora" aria-hidden="true">
        <span class="hero-orb hero-orb--a"></span>
        <span class="hero-orb hero-orb--b"></span>
        <span class="hero-orb hero-orb--c"></span>
        <span class="hero-grid"></span>
    </div>
    <div class="container">
        <span class="section-eyebrow hero-eyebrow"><?= e($eyebrow) ?></span>
        <h1 class="hero-title"><?= e($title) ?></h1>
        <p class="hero-subtitle"><?= e($subtitle) ?></p>

        <div class="search-shell">
            <div class="search-mode-toggle" role="tablist" aria-label="<?= e(tt('home.search_mode_label') ?? 'Search mode') ?>">
                <button type="button" role="tab" class="search-mode is-active" data-search-mode="register" aria-selected="true"><?= e(tt('home.mode_register') ?? 'Register') ?></button>
                <button type="button" role="tab" class="search-mode" data-search-mode="transfer" aria-selected="false"><?= e(tt('home.mode_transfer') ?? 'Transfer') ?></button>
            </div>
            <form action="/search" method="GET" class="domain-search-form" id="domainSearchForm">
                <input type="hidden" name="mode" value="register" id="searchModeInput">
                <span class="search-icon" aria-hidden="true"><i class="fas fa-search"></i></span>
                <input type="text" name="q" class="search-input" placeholder="<?= e(__('domain.search_placeholder')) ?>" autocomplete="off" required>
                <button type="submit" class="btn btn-search"><?= e(__('domain.search_button')) ?></button>
            </form>
            <div class="hero-tlds" aria-label="<?= e(tt('home.popular_tlds') ?? 'Popular TLDs') ?>">
                <span class="hero-tld">.com <span class="tld-price">৳950</span></span>
                <span class="hero-tld">.net <span class="tld-price">৳1,100</span></span>
                <span class="hero-tld">.org <span class="tld-price">৳1,050</span></span>
                <span class="hero-tld">.io <span class="tld-price">৳3,500</span></span>
                <span class="hero-tld">.bd <span class="tld-price">৳1,500</span></span>
                <span class="hero-tld">.com.bd <span class="tld-price">৳1,200</span></span>
            </div>
        </div>
    </div>
</section>
