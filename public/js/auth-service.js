/**
 * Authentication service for checking permissions and auth status
 */
class AuthService {
    constructor() {
        this.user = null;
        this.permissions = [];
        this.initialized = false;
    }

    async init() {
        if (this.initialized) return;
        
        try {
            const response = await fetch('/api/v1/auth/check', {
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                this.user = data.user;
                this.permissions = data.user?.permissions || [];
                this.initialized = true;
            }
        } catch (error) {
            console.error('Auth init error:', error);
        }
    }

    isAuthenticated() {
        return !!this.user;
    }

    hasPermission(permission) {
        if (!this.user) return false;
        if (this.user.isadmin) return true;
        return this.permissions.includes(permission);
    }

    hasAnyPermission(permissions) {
        if (!this.user) return false;
        if (this.user.isadmin) return true;
        return permissions.some(p => this.permissions.includes(p));
    }

    hasAllPermissions(permissions) {
        if (!this.user) return false;
        if (this.user.isadmin) return true;
        return permissions.every(p => this.permissions.includes(p));
    }

    getUser() {
        return this.user;
    }

    isAdmin() {
        return this.user?.isadmin || false;
    }
}

// Global instance
window.Auth = new AuthService();

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    Auth.init();
});