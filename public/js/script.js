// assets/js/script.js
// # Custom JavaScript for UI/UX features (Dark Mode Toggle)

document.addEventListener('DOMContentLoaded', () => {
    const toggleButton = document.getElementById('darkModeToggle');
    const body = document.body;
    const icon = toggleButton ? toggleButton.querySelector('i') : null;

    // # Function to set the theme
    const setTheme = (isDark) => {
        if (isDark) {
            body.classList.add('dark-mode');
            if (icon) icon.className = 'fas fa-sun'; // Change icon to sun
            localStorage.setItem('theme', 'dark');
        } else {
            body.classList.remove('dark-mode');
            if (icon) icon.className = 'fas fa-moon'; // Change icon to moon
            localStorage.setItem('theme', 'light');
        }
    };

    // # Load saved theme from localStorage
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        setTheme(true);
    } else {
        setTheme(false); 
    }

    // # Event listener for the toggle button
    if (toggleButton) {
        toggleButton.addEventListener('click', () => {
            const isDark = body.classList.contains('dark-mode');
            setTheme(!isDark);
        });
    }
});