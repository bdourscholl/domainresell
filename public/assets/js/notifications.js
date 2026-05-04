/**
 * Domain Reseller - Notifications JS
 * Handles real-time notification polling for admin panel
 */

(function() {
    var pollInterval = 30000; // 30 seconds
    var notificationBadge = document.getElementById('notificationBadge');

    function checkNotifications() {
        fetch('/admin/notifications/check', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.count > 0 && notificationBadge) {
                notificationBadge.textContent = data.count;
                notificationBadge.style.display = 'inline-block';
            }
        })
        .catch(function() { /* silently fail */ });
    }

    if (document.querySelector('.admin-body')) {
        setInterval(checkNotifications, pollInterval);
    }
})();
