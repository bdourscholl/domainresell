<?php
    $eyebrow = tt('home.testimonials_eyebrow') ?? 'TRUSTED BY';
    $title = tt('home.testimonials_title') ?? $testimonials_section['section_title'] ?? 'Loved by founders across Bangladesh';
    $subtitle = tt('home.testimonials_subtitle') ?? 'Stories from teams that ship faster with us.';
?>
<section class="testimonials-section">
    <div class="container">
        <div class="section-head">
            <span class="section-eyebrow"><?= e($eyebrow) ?></span>
            <h2 class="section-title"><?= e($title) ?></h2>
            <p class="section-subtitle"><?= e($subtitle) ?></p>
        </div>
        <div class="testimonials-grid">
            <div class="testimonial-card reveal">
                <div class="testimonial-stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                <p>"Great domain registration service with excellent support. The bKash payment option is very convenient for us in Bangladesh!"</p>
                <div class="testimonial-author">Rahim K.</div>
                <div class="testimonial-role">Business Owner</div>
            </div>
            <div class="testimonial-card reveal reveal-delay-1">
                <div class="testimonial-stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                <p>"Fast domain registration and easy DNS management. I've transferred all my domains here and the process was seamless."</p>
                <div class="testimonial-author">Sarah M.</div>
                <div class="testimonial-role">Web Developer</div>
            </div>
            <div class="testimonial-card reveal reveal-delay-2">
                <div class="testimonial-stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i></div>
                <p>"Affordable pricing and the referral program is a great bonus. The admin panel makes managing everything so easy!"</p>
                <div class="testimonial-author">Tanvir H.</div>
                <div class="testimonial-role">Startup Founder</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <span class="section-eyebrow cta-eyebrow"><?= e(tt('home.cta_eyebrow') ?? 'BLAST OFF') ?></span>
        <h2><?= e(tt('home.cta_title') ?? 'Your domain odyssey starts here') ?></h2>
        <p><?= e(tt('home.cta_subtitle') ?? 'Create a free account and grab your domain in seconds.') ?></p>
        <a href="/register" class="btn btn-cta"><?= e(tt('home.cta_button') ?? 'Create Free Account') ?> <i class="fas fa-arrow-right"></i></a>
    </div>
</section>
