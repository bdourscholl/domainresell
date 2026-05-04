<?php
    $eyebrow = tt('home.features_eyebrow') ?? 'WHY DOMAIN RESELLER';
    $title = tt('home.features_title') ?? $features_section['section_title'] ?? 'Built for serious domain operators';
    $subtitle = tt('home.features_subtitle') ?? 'Everything you need to manage thousands of domains, with the polish of a global registrar.';
?>
<section class="features-section">
    <div class="container">
        <div class="section-head">
            <span class="section-eyebrow"><?= e($eyebrow) ?></span>
            <h2 class="section-title"><?= e($title) ?></h2>
            <p class="section-subtitle"><?= e($subtitle) ?></p>
        </div>
        <div class="features-grid">
            <div class="feature-card reveal">
                <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                <h3>WHOIS Privacy</h3>
                <p>Free privacy protection for your domains. Keep your personal information safe and hidden from public WHOIS lookups.</p>
            </div>
            <div class="feature-card reveal reveal-delay-1">
                <div class="feature-icon"><i class="fas fa-server"></i></div>
                <h3>DNS Management</h3>
                <p>Full A, CNAME, MX, TXT record control. Manage your DNS with an intuitive interface and instant propagation.</p>
            </div>
            <div class="feature-card reveal reveal-delay-2">
                <div class="feature-icon"><i class="fas fa-lock"></i></div>
                <h3>Secure Transfers</h3>
                <p>EPP code based domain transfers with full protection. Move your domains safely with our guided transfer process.</p>
            </div>
            <div class="feature-card reveal reveal-delay-3">
                <div class="feature-icon"><i class="fas fa-headset"></i></div>
                <h3>24/7 Support</h3>
                <p>Tickets backed by instant Telegram and WhatsApp alerts. Get help in Bangla or English, day or night.</p>
            </div>
        </div>
    </div>
</section>
