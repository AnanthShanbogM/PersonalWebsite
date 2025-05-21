// Theme toggle functionality
const themeToggle = document.getElementById('theme-toggle');
const currentTheme = localStorage.getItem('theme') || 'light';

// Set initial theme
document.documentElement.setAttribute('data-theme', currentTheme);

// Update theme icon function
function updateThemeIcon() {
    const icon = themeToggle.querySelector('i');
    if (document.documentElement.getAttribute('data-theme') === 'dark') {
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
    } else {
        icon.classList.remove('fa-sun');
        icon.classList.add('fa-moon');
    }
}

// Add click event listener
themeToggle.addEventListener('click', () => {
    const newTheme = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
    document.documentElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    updateThemeIcon();
});

// Initialize theme icon
updateThemeIcon();
