document.addEventListener('DOMContentLoaded', function() {
    // Vérifier le thème actuel
    const darkMode = document.body.classList.contains('dark-mode');
    if (darkMode) {
        document.documentElement.setAttribute('data-theme', 'dark');
    }
}); 