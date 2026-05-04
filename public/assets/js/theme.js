/**
 * Theme (dark mode) handling — shared across public + admin pages.
 * Persists choice in localStorage and (for logged-in users) on the server.
 */
(function () {
    var THEME_KEY = 'theme';

    function getStoredTheme() {
        try { return localStorage.getItem(THEME_KEY); } catch (e) { return null; }
    }

    function setStoredTheme(value) {
        try { localStorage.setItem(THEME_KEY, value); } catch (e) {}
    }

    function applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
    }

    function currentTheme() {
        return document.documentElement.getAttribute('data-theme') || 'light';
    }

    function persistServer(theme) {
        try {
            var meta = document.querySelector('meta[name="csrf-token"]');
            var csrf = meta ? meta.getAttribute('content') : '';
            fetch('/api/account/theme', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': csrf
                },
                body: JSON.stringify({ theme: theme })
            }).catch(function () {});
        } catch (e) {}
    }

    function toggleTheme() {
        var next = currentTheme() === 'dark' ? 'light' : 'dark';
        applyTheme(next);
        setStoredTheme(next);
        persistServer(next);
    }

    document.addEventListener('click', function (e) {
        var btn = e.target.closest && e.target.closest('[data-theme-toggle]');
        if (btn) {
            e.preventDefault();
            toggleTheme();
        }
    });

    try {
        var media = window.matchMedia('(prefers-color-scheme: dark)');
        var handler = function (e) {
            if (!getStoredTheme()) {
                applyTheme(e.matches ? 'dark' : 'light');
            }
        };
        if (media.addEventListener) media.addEventListener('change', handler);
        else if (media.addListener) media.addListener(handler);
    } catch (e) {}

    window.DRTheme = {
        get: currentTheme,
        set: function (t) { applyTheme(t); setStoredTheme(t); persistServer(t); },
        toggle: toggleTheme
    };
})();
