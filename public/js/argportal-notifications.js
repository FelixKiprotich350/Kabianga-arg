/**
 * ARGPortal Notification System
 * Provides consistent notification methods across the application
 */
window.ARGPortal = (function() {
    'use strict';

    // Private methods
    function showNotification(message, type = 'info', duration = 3000, position = 'topright') {
        // Always use existing toast system
        if (typeof showtoastmessage === 'function') {
            showtoastmessage({
                message: message,
                type: type
            });
        }
    }

    function createCustomNotification(message, type, duration, position = 'topright') {
        // Create notification container if it doesn't exist
        const containerId = `argportal-notifications-${position}`;
        let container = document.getElementById(containerId);
        if (!container) {
            container = document.createElement('div');
            container.id = containerId;
            container.style.cssText = `
                position: fixed;
                ${getPositionStyles(position)}
                z-index: 9999;
                max-width: 400px;
            `;
            document.body.appendChild(container);
        }

        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'toast';
        notification.setAttribute('role', 'alert');
        
        notification.innerHTML = `
            <div class="toast-body d-flex align-items-center">
                <i class="bi bi-${getTypeIcon(type)} text-${getBootstrapClass(type)} me-3" style="font-size: 1.2rem;"></i>
                <div class="flex-grow-1">${message}</div>
                <button type="button" class="btn-close ms-2" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        const bsToast = new bootstrap.Toast(notification);
        bsToast.show();
        
        notification.addEventListener('hidden.bs.toast', function() {
            notification.remove();
        });

        container.appendChild(notification);
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

    function getPositionStyles(position) {
        const positions = {
            'topright': 'top: 20px; right: 20px;',
            'topleft': 'top: 20px; left: 20px;',
            'bottomright': 'bottom: 20px; right: 20px;',
            'bottomleft': 'bottom: 20px; left: 20px;'
        };
        return positions[position] || positions['topright'];
    }

    function getTypeIcon(type) {
        const icons = {
            'success': 'check-circle-fill',
            'error': 'exclamation-triangle-fill',
            'warning': 'exclamation-triangle-fill',
            'info': 'info-circle-fill'
        };
        return icons[type] || icons['info'];
    }

    // Public API
    const api = {
        // Success notifications
        showSuccess: function(message, duration = 3000, position = 'topright') {
            showNotification(message, 'success', duration, position);
        },

        // Error notifications
        showError: function(message, duration = 5000, position = 'topright') {
            showNotification(message, 'error', duration, position);
        },

        // Warning notifications
        showWarning: function(message, duration = 4000, position = 'topright') {
            showNotification(message, 'warning', duration, position);
        },

        // Info notifications
        showInfo: function(message, duration = 3000, position = 'topright') {
            showNotification(message, 'info', duration, position);
        },

        // Generic notification method
        notify: function(message, type = 'info', duration = 3000, position = 'topright') {
            showNotification(message, type, duration, position);
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
        },

        // Utility functions from modern-app.js
        showLoading: function(element) {
            if (element) {
                element.innerHTML = '<div class="text-center"><div class="spinner-border spinner-border-sm" role="status"></div> Loading...</div>';
            }
        },
        
        formatNumber: function(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        
        debounce: function(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    };
    
    return api;
})();

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    initializeSidebar();
    initializeTooltips();
    initializeAnimations();
    
    // Add custom styles for notifications
    const style = document.createElement('style');
    style.textContent = `
        [id^="argportal-notifications-"] {
            pointer-events: none;
        }
        
        [id^="argportal-notifications-"] .toast {
            pointer-events: auto;
            margin-bottom: 10px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-radius: 8px;
        }
        
        [id^="argportal-notifications-"] .toast-body {
            padding: 12px 16px;
            background: white;
            border-radius: 8px;
        }
    `;
    document.head.appendChild(style);
});

// Sidebar functionality
function initializeSidebar() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            if (overlay) {
                overlay.classList.toggle('active');
            }
        });
    }
    
    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    }
    
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('active');
            if (overlay) {
                overlay.classList.remove('active');
            }
        }
    });
}

// Initialize Bootstrap tooltips
function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Initialize animations
function initializeAnimations() {
    const cards = document.querySelectorAll('.stats-card, .form-card, .table-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('fade-in');
    });
}

// Enhanced form handling
document.addEventListener('submit', function(e) {
    const form = e.target;
    if (form.classList.contains('ajax-form')) {
        e.preventDefault();
        handleAjaxForm(form);
    }
});

function handleAjaxForm(form) {
    const submitBtn = form.querySelector('[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
    submitBtn.disabled = true;
    
    const method = form.method.toUpperCase();
    const formData = new FormData(form);
    
    let url = form.action;
    let fetchOptions = {
        method: method,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    };
    
    if (method === 'GET' || method === 'HEAD') {
        // For GET requests, append form data to URL
        const params = new URLSearchParams(formData);
        url += (url.includes('?') ? '&' : '?') + params.toString();
    } else {
        // For POST/PUT/PATCH/DELETE, add body and CSRF token
        fetchOptions.body = formData;
        fetchOptions.headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    }
    
    fetch(url, fetchOptions)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.ARGPortal.showSuccess(data.message || 'Operation completed successfully');
            if (data.redirect) {
                setTimeout(() => window.location.href = data.redirect, 1500);
            }
        } else {
            window.ARGPortal.showError(data.message || 'An error occurred');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.ARGPortal.showError('An unexpected error occurred');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}