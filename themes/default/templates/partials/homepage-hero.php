<?php
    $title = $hero['section_title'] ?? 'Find Your Perfect Domain';
    // Split the title to highlight the last two words
    $words = explode(' ', $title);
    if (count($words) > 2) {
        $firstPart = implode(' ', array_slice($words, 0, -2));
        $highlightPart = implode(' ', array_slice($words, -2));
    } else {
        $firstPart = '';
        $highlightPart = $title;
    }
    $subtitle = 'Search from hundreds of domain extensions at the best prices. Get started in seconds.';
?>
<section class="hero-section">
    <div class="hero-shapes">
        <div class="hero-shape"></div>
        <div class="hero-shape"></div>
        <div class="hero-shape"></div>
        <div class="hero-shape"></div>
    </div>
    <div class="container">
        <h1><?php if ($firstPart): ?><?= e($firstPart) ?> <?php endif; ?><span class="highlight"><?= e($highlightPart) ?></span></h1>
        <p class="hero-subtitle"><?= e($subtitle) ?></p>
        <div class="search-box">
            <form action="/search" method="GET" class="domain-search-form" id="domainSearchForm">
                <input type="text" name="q" class="search-input" placeholder="<?= __('domain.search_placeholder') ?>" autocomplete="off" required>
                <button type="submit" class="btn btn-search"><?= __('domain.search_button') ?></button>
            </form>
        </div>
        <div class="hero-tlds">
            <span class="hero-tld">.com <span class="tld-price">৳950</span></span>
            <span class="hero-tld">.net <span class="tld-price">৳1,100</span></span>
            <span class="hero-tld">.org <span class="tld-price">৳1,050</span></span>
            <span class="hero-tld">.io <span class="tld-price">৳3,500</span></span>
            <span class="hero-tld">.xyz <span class="tld-price">৳200</span></span>
            <span class="hero-tld">.dev <span class="tld-price">৳1,200</span></span>
        </div>
    </div>
</section>
