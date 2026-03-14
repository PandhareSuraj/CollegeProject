/**
 * Unified Theme Management System
 * Works across all pages (welcome, dashboards, auth)
 */
const ThemeManager = {
    LIGHT: 'light',
    DARK: 'dark',
    STORAGE_KEY: 'ccsms-theme',
    
    init() {
        // Detect user's theme preference
        let savedTheme = localStorage.getItem(this.STORAGE_KEY);
        
        if (!savedTheme) {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            savedTheme = prefersDark ? this.DARK : this.LIGHT;
        }
        
        this.setTheme(savedTheme, false);
        this.setupThemeToggle();
        
        // Retry setup after a short delay in case DOM is still loading
        setTimeout(() => {
            this.setupThemeToggle();
        }, 100);
    },
    
    setTheme(theme, saveToStorage = true) {
        const htmlRoot = document.getElementById('html-root');
        
        if (!htmlRoot) return;
        
        if (theme === this.DARK) {
            htmlRoot.classList.add('dark');
            htmlRoot.removeAttribute('data-theme');
            document.documentElement.setAttribute('data-theme', 'dark');
        } else {
            htmlRoot.classList.remove('dark');
            htmlRoot.removeAttribute('data-theme');
            document.documentElement.setAttribute('data-theme', 'light');
        }
        
        if (saveToStorage) {
            localStorage.setItem(this.STORAGE_KEY, theme);
        }
        
        // Update theme icons
        this.updateThemeIcons(theme);
        
        // Also trigger a small delay to ensure DOM is ready for icon updates
        setTimeout(() => {
            this.updateThemeIcons(theme);
        }, 50);
    },
    
    toggleTheme() {
        const htmlRoot = document.getElementById('html-root');
        const currentTheme = htmlRoot?.classList.contains('dark') ? this.DARK : this.LIGHT;
        const newTheme = currentTheme === this.DARK ? this.LIGHT : this.DARK;
        this.setTheme(newTheme, true);
    },
    
    updateThemeIcons(theme) {
        // Update moon/sun icons for theme toggle button
        // Try both ID and class selectors for compatibility
        const moonIcons = document.querySelectorAll('#icon-moon, .icon-moon');
        const sunIcons = document.querySelectorAll('#icon-sun, .icon-sun');
        
        if (moonIcons.length > 0 || sunIcons.length > 0) {
            moonIcons.forEach(icon => {
                if (icon) {
                    icon.style.display = theme === this.DARK ? 'none' : 'block';
                }
            });
            
            sunIcons.forEach(icon => {
                if (icon) {
                    icon.style.display = theme === this.LIGHT ? 'none' : 'block';
                }
            });
        }
    },
    
    setupThemeToggle() {
        // Find and setup theme toggle button
        const themeToggle = document.getElementById('themeToggle');
        
        if (themeToggle && !themeToggle.hasAttribute('data-theme-listener-attached')) {
            themeToggle.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.toggleTheme();
            });
            
            // Mark that we've attached the listener to prevent duplicate listeners
            themeToggle.setAttribute('data-theme-listener-attached', 'true');
        }
    }
};

// Initialize theme when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    ThemeManager.init();

    
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
