<?php
    $eyebrow = tt('home.products_eyebrow') ?? 'YOUR LAUNCHPAD';
    $title = tt('home.products_title') ?? 'Everything for your domain odyssey';
    $subtitle = tt('home.products_subtitle') ?? 'Register a name, route DNS, lock down WHOIS privacy, and accept payments in Taka — all from one console.';
?>
<section class="products-section">
    <div class="container">
        <div class="section-head">
            <span class="section-eyebrow"><?= e($eyebrow) ?></span>
            <h2 class="section-title"><?= e($title) ?></h2>
            <p class="section-subtitle"><?= e($subtitle) ?></p>
        </div>
        <div class="product-grid">
            <a class="product-tile reveal" href="/search">
                <div class="product-tile__icon"><i class="fas fa-globe"></i></div>
                <h3 class="product-tile__title"><?= e(tt('home.product_domains_title') ?? 'Domains') ?></h3>
                <p class="product-tile__description"><?= e(tt('home.product_domains_description') ?? 'Register from 500+ TLDs including .bd and .com.bd with bulk-friendly pricing.') ?></p>
                <span class="product-tile__cta"><?= e(tt('home.learn_more') ?? 'Search domains') ?> <i class="fas fa-arrow-right"></i></span>
            </a>
            <a class="product-tile reveal reveal-delay-1" href="/account/dns-records">
                <div class="product-tile__icon product-tile__icon--blue"><i class="fas fa-server"></i></div>
                <h3 class="product-tile__title"><?= e(tt('home.product_dns_title') ?? 'DNS & Nameservers') ?></h3>
                <p class="product-tile__description"><?= e(tt('home.product_dns_description') ?? 'A, CNAME, MX, TXT, SRV — managed live with instant propagation across the globe.') ?></p>
                <span class="product-tile__cta"><?= e(tt('home.learn_more') ?? 'Explore DNS') ?> <i class="fas fa-arrow-right"></i></span>
            </a>
            <a class="product-tile reveal reveal-delay-2" href="/transfer">
                <div class="product-tile__icon product-tile__icon--green"><i class="fas fa-shield-halved"></i></div>
                <h3 class="product-tile__title"><?= e(tt('home.product_privacy_title') ?? 'WHOIS Privacy') ?></h3>
                <p class="product-tile__description"><?= e(tt('home.product_privacy_description') ?? 'Free privacy protection on every supported TLD — toggle anytime from the panel.') ?></p>
                <span class="product-tile__cta"><?= e(tt('home.learn_more') ?? 'See privacy') ?> <i class="fas fa-arrow-right"></i></span>
            </a>
            <a class="product-tile reveal reveal-delay-3" href="/account/wallet">
                <div class="product-tile__icon product-tile__icon--orange"><i class="fas fa-wallet"></i></div>
                <h3 class="product-tile__title"><?= e(tt('home.product_wallet_title') ?? 'bKash & Nagad Wallet') ?></h3>
                <p class="product-tile__description"><?= e(tt('home.product_wallet_description') ?? 'Pay in Taka via bKash, Nagad, SSLCommerz, or top up your wallet for one-click renewals.') ?></p>
                <span class="product-tile__cta"><?= e(tt('home.learn_more') ?? 'View wallet') ?> <i class="fas fa-arrow-right"></i></span>
            </a>
        </div>
    </div>
</section>
