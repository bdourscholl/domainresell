<?php if (!empty($featured_tlds)): ?>
<section class="pricing-section">
    <div class="container">
        <h2 class="section-title"><?= e($pricing_section['section_title'] ?? 'Domain Pricing') ?></h2>
        <p class="section-subtitle"><?= e($pricing_section['section_subtitle'] ?? 'Transparent pricing for every domain extension') ?></p>
        <div class="pricing-grid">
            <?php foreach ($featured_tlds as $i => $tld): ?>
            <div class="pricing-card reveal<?= $tld['tld'] === '.com' ? ' featured' : '' ?>">
                <div class="pricing-tld"><?= e($tld['tld']) ?></div>
                <div class="pricing-amount"><?= format_currency((float) $tld['register_price']) ?><small>/yr</small></div>
                <div class="pricing-renew">Renew: <?= format_currency((float) $tld['renew_price']) ?>/yr</div>
                <a href="/search?q=example<?= e($tld['tld']) ?>" class="btn btn-sm btn-primary">Search</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
