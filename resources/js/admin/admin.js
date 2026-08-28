document.addEventListener('DOMContentLoaded', function () {

    const themeToggle = document.getElementById('themeToggle');

    if (!themeToggle) {
        return;
    }

    const savedTheme = localStorage.getItem('admin-theme');

    applyTheme(savedTheme === 'dark' ? 'dark' : 'light');

    updateThemeIcon();

    themeToggle.addEventListener('click', function () {

        const currentTheme =
            document.documentElement.getAttribute('data-theme');

        if (currentTheme === 'dark') {
            applyTheme('light');
            localStorage.setItem('admin-theme', 'light');
        } else {
            applyTheme('dark');
            localStorage.setItem('admin-theme', 'dark');
        }

        updateThemeIcon();
    });

    function applyTheme(theme) {
        if (theme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
            document.documentElement.setAttribute('data-bs-theme', 'dark');
            return;
        }

        document.documentElement.removeAttribute('data-theme');
        document.documentElement.setAttribute('data-bs-theme', 'light');
    }

    function updateThemeIcon() {

        const isDark =
            document.documentElement.getAttribute('data-theme') === 'dark';

        const icon = themeToggle.querySelector('i');

        if (!icon) {
            return;
        }

        icon.className = isDark
            ? 'bi bi-sun'
            : 'bi bi-moon';
    }
});
