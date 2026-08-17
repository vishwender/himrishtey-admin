document.addEventListener('DOMContentLoaded', function () {

    const themeToggle = document.getElementById('themeToggle');

    if (!themeToggle) {
        return;
    }

    const savedTheme = localStorage.getItem('admin-theme');

    if (savedTheme === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
    }

    updateThemeIcon();

    themeToggle.addEventListener('click', function () {

        const currentTheme =
            document.documentElement.getAttribute('data-theme');

        if (currentTheme === 'dark') {
            document.documentElement.removeAttribute('data-theme');
            localStorage.setItem('admin-theme', 'light');
        } else {
            document.documentElement.setAttribute('data-theme', 'dark');
            localStorage.setItem('admin-theme', 'dark');
        }

        updateThemeIcon();
    });

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