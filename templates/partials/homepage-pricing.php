<?php if (!empty($featured_tlds)): ?>
<?php
    $eyebrow = tt('home.pricing_eyebrow') ?? 'TRANSPARENT PRICING';
    $title = tt('home.pricing_title') ?? $pricing_section['section_title'] ?? 'A domain for every idea';
    $subtitle = tt('home.pricing_subtitle') ?? $pricing_section['section_subtitle'] ?? 'No hidden fees, no surprise renewals. Pay in Taka via bKash, Nagad, or card.';
?>
<section class="pricing-section">
    <div class="container">
        <div class="section-head">
            <span class="section-eyebrow"><?= e($eyebrow) ?></span>
            <h2 class="section-title"><?= e($title) ?></h2>
            <p class="section-subtitle"><?= e($subtitle) ?></p>
        </div>
        <div class="pricing-grid">
            <?php foreach ($featured_tlds as $i => $tld): ?>
            <div class="pricing-card reveal<?= $tld['tld'] === '.com' ? ' featured' : '' ?>">
                <?php if ($tld['tld'] === '.com'): ?><span class="pricing-badge"><?= e(tt('home.most_popular') ?? 'Most popular') ?></span><?php endif; ?>
                <div class="pricing-tld"><?= e($tld['tld']) ?></div>
                <div class="pricing-amount"><?= format_currency((float) $tld['register_price']) ?><small>/yr</small></div>
                <div class="pricing-renew"><?= e(tt('home.renew_at') ?? 'Renews at') ?> <?= format_currency((float) $tld['renew_price']) ?>/yr</div>
                <a href="/search?q=example<?= e($tld['tld']) ?>" class="btn btn-sm btn-primary"><?= e(tt('home.search_tld') ?? 'Search') ?></a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
