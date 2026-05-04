/**
 * Domain Reseller - Main JS
 * Modern & Dynamic Interactions
 */

document.addEventListener('DOMContentLoaded', function() {

    // ========================================
    // Scroll Reveal Animation
    // ========================================
    var revealElements = document.querySelectorAll('.reveal');
    var revealObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

    revealElements.forEach(function(el) {
        revealObserver.observe(el);
    });

    // ========================================
    // Animated Counter for Stats
    // ========================================
    var counters = document.querySelectorAll('.stat-value[data-count]');
    var counterObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                counterObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(function(counter) { counterObserver.observe(counter); });

    function animateCounter(el) {
        var text = el.getAttribute('data-count');
        var suffix = text.replace(/[\d,]/g, '');
        var numStr = text.replace(/[^\d]/g, '');
        var target = parseInt(numStr, 10);
        if (isNaN(target)) return;

        var duration = 1500;
        var start = 0;
        var startTime = null;

        function step(timestamp) {
            if (!startTime) startTime = timestamp;
            var progress = Math.min((timestamp - startTime) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3);
            var current = Math.floor(eased * target);

            if (current >= 1000) {
                el.textContent = current.toLocaleString() + suffix;
            } else {
                el.textContent = current + suffix;
            }

            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                el.textContent = text;
            }
        }

        el.textContent = '0' + suffix;
        requestAnimationFrame(step);
    }

    // ========================================
    // Navbar scroll effect
    // ========================================
    var navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.style.boxShadow = '0 4px 20px rgba(0,0,0,0.08)';
            } else {
                navbar.style.boxShadow = 'none';
            }
        });
    }

    // ========================================
    // Alert auto-dismiss with animation
    // ========================================
    document.querySelectorAll('.alert').forEach(function(alert) {
        setTimeout(function() {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(function() { alert.remove(); }, 400);
        }, 5000);
        var close = alert.querySelector('.alert-close');
        if (close) close.addEventListener('click', function() {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(function() { alert.remove(); }, 300);
        });
    });

    // ========================================
    // Announcement dismiss
    // ========================================
    document.querySelectorAll('.announcement-close').forEach(function(btn) {
        btn.addEventListener('click', function() { this.closest('.announcement-bar').remove(); });
    });

    // ========================================
    // FAQ Accordion with smooth animation
    // ========================================
    document.querySelectorAll('.faq-question').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var item = this.closest('.faq-item');
            var wasActive = item.classList.contains('active');

            // Close all other FAQ items
            document.querySelectorAll('.faq-item.active').forEach(function(other) {
                if (other !== item) other.classList.remove('active');
            });

            item.classList.toggle('active');
        });
    });

    // ========================================
    // Domain search input effects
    // ========================================
    var searchForm = document.getElementById('domainSearchForm');
    if (searchForm) {
        var input = searchForm.querySelector('.search-input');
        if (input) {
            input.addEventListener('focus', function() {
                searchForm.style.transform = 'scale(1.01)';
            });
            input.addEventListener('blur', function() {
                searchForm.style.transform = 'scale(1)';
            });
        }
    }

    // Register / Transfer mode toggle in hero
    var modeButtons = document.querySelectorAll('.search-mode-toggle .search-mode');
    var modeInput = document.getElementById('searchModeInput');
    var heroForm = document.getElementById('domainSearchForm');
    if (modeButtons.length && heroForm) {
        modeButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var mode = btn.getAttribute('data-search-mode') || 'register';
                modeButtons.forEach(function (b) {
                    var active = b === btn;
                    b.classList.toggle('is-active', active);
                    b.setAttribute('aria-selected', active ? 'true' : 'false');
                });
                if (modeInput) modeInput.value = mode;
                heroForm.setAttribute('action', mode === 'transfer' ? '/transfer' : '/search');
                var inputEl = heroForm.querySelector('.search-input');
                if (inputEl) {
                    inputEl.setAttribute('placeholder', mode === 'transfer'
                        ? (inputEl.dataset.placeholderTransfer || 'Enter the domain you want to transfer...')
                        : (inputEl.dataset.placeholderRegister || inputEl.getAttribute('placeholder')));
                }
            });
        });
        var heroInput = heroForm.querySelector('.search-input');
        if (heroInput && !heroInput.dataset.placeholderRegister) {
            heroInput.dataset.placeholderRegister = heroInput.getAttribute('placeholder');
        }
    }

    // ========================================
    // Cart add via AJAX
    // ========================================
    document.querySelectorAll('.add-to-cart-ajax').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(form);
            fetch('/cart/add', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    var btn = form.querySelector('button');
                    btn.textContent = 'Added!';
                    btn.disabled = true;
                    btn.style.background = 'linear-gradient(135deg, #22c55e, #16a34a)';
                    setTimeout(function() {
                        btn.textContent = 'Add to Cart';
                        btn.disabled = false;
                        btn.style.background = '';
                    }, 2000);
                }
            });
        });
    });

    // ========================================
    // Copy to clipboard
    // ========================================
    document.querySelectorAll('[data-copy]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var target = document.getElementById(this.dataset.copy);
            if (target) {
                navigator.clipboard.writeText(target.value || target.textContent);
                this.textContent = 'Copied!';
                var self = this;
                setTimeout(function() { self.textContent = 'Copy'; }, 2000);
            }
        });
    });

    // ========================================
    // Smooth scroll for anchor links
    // ========================================
    document.querySelectorAll('a[href^="#"]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            var target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

});
