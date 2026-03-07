/**
 * Theme Toggle Functionality
 */
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('themeToggle');
    const htmlRoot = document.getElementById('html-root');

    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const isDark = htmlRoot.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');

            // Update icon based on theme
            const moonIcon = document.getElementById('icon-moon');
            const sunIcon = document.getElementById('icon-sun');
            if (moonIcon && sunIcon) {
                moonIcon.style.display = isDark ? 'none' : 'block';
                sunIcon.style.display = isDark ? 'block' : 'none';
            }
        });
    }

    /**
     * College Menu Toggle
     */
    const collegeBtn = document.getElementById('collegeBtn');
    const collegeMenu = document.getElementById('collegeMenu');
    const collegeArrow = document.getElementById('collegeArrow');

    if (collegeBtn) {
        collegeBtn.addEventListener('click', function() {
            const isOpen = collegeMenu.style.display === 'flex';
            collegeMenu.style.display = isOpen ? 'none' : 'flex';
            collegeArrow.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
        });
    }

    /**
     * Dismiss alerts after 5 seconds
     */
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.3s';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    /**
     * Initialize Multi-Tab Auth
     * Store current user authentication info when page loads
     */
    initializeMultiTabAuth();
});

/**
 * Initialize or restore multi-tab authentication
 */
function initializeMultiTabAuth() {
    // Check if user is currently authenticated on the server
    const authElement = document.getElementById('multi-tab-init-data');
    
    if (authElement && window.multiTabAuth) {
        try {
            const authData = JSON.parse(authElement.textContent);
            if (authData && authData.role && authData.user) {
                // Store this auth in the current tab
                window.multiTabAuth.storeTabAuth(authData);
            }
        } catch (e) {
            console.warn('Failed to parse multi-tab auth data:', e);
        }
    }
}
