<?php
    $eyebrow = tt('home.faq_eyebrow') ?? 'GOT QUESTIONS?';
    $title = tt('home.faq_title') ?? $faq_section['section_title'] ?? 'Everything you wanted to ask';
    $subtitle = tt('home.faq_subtitle') ?? 'Quick answers about registration, transfers, payments, and more.';
?>
<section class="faq-section">
    <div class="container">
        <div class="section-head">
            <span class="section-eyebrow"><?= e($eyebrow) ?></span>
            <h2 class="section-title"><?= e($title) ?></h2>
            <p class="section-subtitle"><?= e($subtitle) ?></p>
        </div>
        <div class="faq-accordion">
            <div class="faq-item reveal">
                <button class="faq-question">How do I register a domain? <i class="fas fa-chevron-down"></i></button>
                <div class="faq-answer"><p>Search for your desired domain name, add it to your cart, and complete the checkout process. Registration is instant and your domain is ready to use immediately!</p></div>
            </div>
            <div class="faq-item reveal reveal-delay-1">
                <button class="faq-question">Can I transfer my domain here? <i class="fas fa-chevron-down"></i></button>
                <div class="faq-answer"><p>Yes! Enter your domain name and EPP/Auth code on our transfer page. The transfer process usually takes 5-7 days and comes with a free 1-year extension.</p></div>
            </div>
            <div class="faq-item reveal reveal-delay-2">
                <button class="faq-question">What payment methods do you accept? <i class="fas fa-chevron-down"></i></button>
                <div class="faq-answer"><p>We accept bKash, Nagad, Stripe, PayPal, Razorpay, SSLCommerz, and bank transfers. Choose whatever works best for you!</p></div>
            </div>
            <div class="faq-item reveal reveal-delay-3">
                <button class="faq-question">Do you offer WHOIS privacy? <i class="fas fa-chevron-down"></i></button>
                <div class="faq-answer"><p>Yes, free WHOIS privacy protection is available for all supported domain extensions. Enable or disable it anytime from your account panel.</p></div>
            </div>
        </div>
    </div>
</section>
