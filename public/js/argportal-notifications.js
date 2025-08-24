/**
 * ARGPortal Notification System
 * Provides consistent notification methods across the application
 */
window.ARGPortal = (function() {
    'use strict';

    // Private methods
    function showNotification(message, type = 'info', duration = 3000) {
        // Use existing toast system if available
        if (typeof showtoastmessage === 'function') {
            showtoastmessage({
                message: message,
                type: type
            });
            return;
        }

        // Fallback to custom notification
        createCustomNotification(message, type, duration);
    }

    function createCustomNotification(message, type, duration) {
        // Create notification container if it doesn't exist
        let container = document.getElementById('argportal-notifications');
        if (!container) {
            container = document.createElement('div');
            container.id = 'argportal-notifications';
            container.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                max-width: 400px;
            `;
            document.body.appendChild(container);
        }

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${getBootstrapClass(type)} alert-dismissible fade show`;
        notification.style.cssText = `
            margin-bottom: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        `;
        
        notification.innerHTML = `
            <strong>${getTypeLabel(type)}:</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        container.appendChild(notification);

        // Auto-remove after duration
        setTimeout(() => {
            if (notification.parentNode) {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 150);
            }
        }, duration);
    }

    function getBootstrapClass(type) {
        const classMap = {
            'success': 'success',
            'error': 'danger',
            'warning': 'warning',
            'info': 'info'
        };
        return classMap[type] || 'info';
    }

    function getTypeLabel(type) {
        const labelMap = {
            'success': 'Success',
            'error': 'Error',
            'warning': 'Warning',
            'info': 'Info'
        };
        return labelMap[type] || 'Info';
    }

    // Public API
    return {
        // Success notifications
        showSuccess: function(message, duration = 3000) {
            showNotification(message, 'success', duration);
        },

        // Error notifications
        showError: function(message, duration = 5000) {
            showNotification(message, 'error', duration);
        },

        // Warning notifications
        showWarning: function(message, duration = 4000) {
            showNotification(message, 'warning', duration);
        },

        // Info notifications
        showInfo: function(message, duration = 3000) {
            showNotification(message, 'info', duration);
        },

        // Generic notification method
        notify: function(message, type = 'info', duration = 3000) {
            showNotification(message, type, duration);
        },

        // Proposal-specific notifications
        proposal: {
            submitted: function(proposalTitle) {
                showNotification(`Proposal "${proposalTitle}" has been submitted successfully`, 'success');
            },
            
            saved: function() {
                showNotification('Proposal saved successfully', 'success');
            },
            
            approved: function(proposalTitle) {
                showNotification(`Proposal "${proposalTitle}" has been approved`, 'success');
            },
            
            rejected: function(proposalTitle, reason) {
                const message = reason ? 
                    `Proposal "${proposalTitle}" was rejected: ${reason}` : 
                    `Proposal "${proposalTitle}" was rejected`;
                showNotification(message, 'error');
            },
            
            changesRequested: function(proposalTitle) {
                showNotification(`Changes requested for proposal "${proposalTitle}"`, 'warning');
            }
        },

        // User-specific notifications
        user: {
            loggedIn: function(username) {
                showNotification(`Welcome back, ${username}!`, 'success');
            },
            
            loggedOut: function() {
                showNotification('You have been logged out successfully', 'info');
            },
            
            profileUpdated: function() {
                showNotification('Profile updated successfully', 'success');
            },
            
            passwordChanged: function() {
                showNotification('Password changed successfully', 'success');
            }
        },

        // System notifications
        system: {
            maintenance: function(message) {
                showNotification(`System Maintenance: ${message}`, 'warning', 10000);
            },
            
            update: function(message) {
                showNotification(`System Update: ${message}`, 'info');
            },
            
            error: function(message) {
                showNotification(`System Error: ${message}`, 'error');
            }
        },

        // Research-specific notifications
        research: {
            projectCreated: function(projectTitle) {
                showNotification(`Research project "${projectTitle}" created successfully`, 'success');
            },
            
            progressUpdated: function() {
                showNotification('Research progress updated successfully', 'success');
            },
            
            fundingApproved: function(amount) {
                showNotification(`Funding of ${amount} approved for your research`, 'success');
            },
            
            deadlineReminder: function(deadline, days) {
                showNotification(`Reminder: ${deadline} deadline in ${days} days`, 'warning');
            }
        }
    };
})();

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    // Add custom styles for notifications
    const style = document.createElement('style');
    style.textContent = `
        #argportal-notifications .alert {
            animation: slideInRight 0.3s ease-out;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    `;
    document.head.appendChild(style);
});