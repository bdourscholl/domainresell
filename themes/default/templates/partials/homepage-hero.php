<section class="theme-hero hero-section">
    <div class="container">
        <h1><?= e($hero['section_title'] ?? 'Find Your Perfect Domain Name') ?></h1>
        <p><?= e($hero['section_subtitle'] ?? 'Start your online journey today') ?></p>
        <form action="/search" method="GET" class="domain-search-form">
            <input type="text" name="q" placeholder="Search domain names..." class="search-input" required>
            <button type="submit" class="btn btn-primary btn-search">Search</button>
        </form>
    </div>
</section>
