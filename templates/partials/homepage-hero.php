<section class="hero-section">
    <div class="container">
        <h1><?= e($hero['section_title'] ?? 'Find Your Perfect Domain') ?></h1>
        <p class="hero-subtitle"><?= e($hero['section_subtitle'] ?? 'Search from hundreds of domain extensions at unbeatable prices') ?></p>
        <div class="search-box">
            <form action="/search" method="GET" class="domain-search-form" id="domainSearchForm">
                <input type="text" name="q" class="search-input" placeholder="<?= __('domain.search_placeholder') ?>" autocomplete="off" required>
                <button type="submit" class="btn btn-primary btn-search"><?= __('domain.search_button') ?></button>
            </form>
        </div>
    </div>
</section>
