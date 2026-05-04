/**
 * Domain Reseller - Admin Panel JS
 */

document.addEventListener('DOMContentLoaded', function() {

    // Sidebar toggle
    var toggle = document.getElementById('sidebarToggle');
    var sidebar = document.getElementById('adminSidebar');
    if (toggle && sidebar) {
        toggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
        });
    }

    // Active sidebar link
    var currentPath = window.location.pathname;
    var bestMatch = null;
    var bestLen = -1;
    document.querySelectorAll('.sidebar-link').forEach(function(link) {
        var href = link.getAttribute('href');
        if (!href) return;
        if (href === currentPath || (href !== '/admin' && currentPath.indexOf(href) === 0)) {
            if (href.length > bestLen) {
                bestLen = href.length;
                bestMatch = link;
            }
        }
    });
    if (bestMatch) bestMatch.classList.add('active');

    // Confirm delete actions
    document.querySelectorAll('form').forEach(function(form) {
        var btn = form.querySelector('.btn-danger');
        if (btn && btn.textContent.trim().toLowerCase() === 'delete') {
            form.addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to delete this?')) {
                    e.preventDefault();
                }
            });
        }
    });

    // Alert dismiss
    document.querySelectorAll('.alert').forEach(function(alert) {
        setTimeout(function() { alert.style.opacity = '0'; setTimeout(function() { alert.remove(); }, 300); }, 5000);
    });

});
