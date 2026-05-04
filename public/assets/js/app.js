/**
 * Domain Reseller - Main JS
 */

document.addEventListener('DOMContentLoaded', function() {

    // Alert auto-dismiss
    document.querySelectorAll('.alert').forEach(function(alert) {
        setTimeout(function() { alert.style.opacity = '0'; setTimeout(function() { alert.remove(); }, 300); }, 5000);
        var close = alert.querySelector('.alert-close');
        if (close) close.addEventListener('click', function() { alert.remove(); });
    });

    // Announcement dismiss
    document.querySelectorAll('.announcement-close').forEach(function(btn) {
        btn.addEventListener('click', function() { this.closest('.announcement-bar').remove(); });
    });

    // FAQ Accordion
    document.querySelectorAll('.faq-question').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var item = this.closest('.faq-item');
            item.classList.toggle('active');
        });
    });

    // Domain search AJAX (optional enhancement)
    var searchForm = document.getElementById('domainSearchForm');
    if (searchForm) {
        var input = searchForm.querySelector('.search-input');
        var timer;
        // Live search typing indicator
        if (input) {
            input.addEventListener('input', function() {
                clearTimeout(timer);
                timer = setTimeout(function() {
                    // Could implement live search suggestions here
                }, 500);
            });
        }
    }

    // Cart add via AJAX
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
                    setTimeout(function() { btn.textContent = 'Add to Cart'; btn.disabled = false; }, 2000);
                }
            });
        });
    });

    // Copy to clipboard
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

});
