// Fonction pour initialiser le thème
function initializeTheme() {
    // Vérifier d'abord le localStorage
    const savedTheme = localStorage.getItem('darkMode');
    if (savedTheme !== null) {
        const darkMode = savedTheme === 'true';
        if (darkMode) {
            document.body.classList.add('dark-mode');
            document.documentElement.setAttribute('data-theme', 'dark');
        }
        return;
    }

    // Si pas de préférence sauvegardée, vérifier la préférence système
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.body.classList.add('dark-mode');
        document.documentElement.setAttribute('data-theme', 'dark');
        localStorage.setItem('darkMode', 'true');
    }
}

// Initialiser le thème au chargement
document.addEventListener('DOMContentLoaded', initializeTheme);

// Écouter les changements de préférence système
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
    if (localStorage.getItem('darkMode') === null) { // Seulement si aucune préférence n'est sauvegardée
        const darkMode = e.matches;
        if (darkMode) {
            document.body.classList.add('dark-mode');
            document.documentElement.setAttribute('data-theme', 'dark');
        } else {
            document.body.classList.remove('dark-mode');
            document.documentElement.setAttribute('data-theme', 'light');
        }
        localStorage.setItem('darkMode', darkMode);
    }
}); 