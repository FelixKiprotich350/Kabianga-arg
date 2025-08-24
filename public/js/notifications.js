// Notifications functionality
class NotificationManager {
    constructor() {
        this.init();
    }

    init() {
        this.loadNotificationCount();
        this.setupEventListeners();
        
        // Auto-refresh every 30 seconds
        setInterval(() => {
            this.loadNotificationCount();
        }, 30000);
    }

    setupEventListeners() {
        const dropdown = document.getElementById('notificationDropdown');
        if (dropdown) {
            dropdown.addEventListener('click', () => {
                this.loadRecentNotifications();
            });
        }
    }

    async loadNotificationCount() {
        try {
            const response = await fetch('/notifications/count');
            const data = await response.json();
            this.updateNotificationBadge(data.count);
        } catch (error) {
            console.error('Error loading notification count:', error);
        }
    }

    async loadRecentNotifications() {
        try {
            const response = await fetch('/notifications/recent');
            const notifications = await response.json();
            this.renderNotifications(notifications);
        } catch (error) {
            console.error('Error loading notifications:', error);
            this.renderError();
        }
    }

    updateNotificationBadge(count) {
        const badge = document.getElementById('notificationBadge');
        if (badge) {
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'inline';
            } else {
                badge.style.display = 'none';
            }
        }
    }

    renderNotifications(notifications) {
        const container = document.getElementById('notificationsList');
        if (!container) return;

        if (notifications.length === 0) {
            container.innerHTML = `
                <li class="text-center py-3 text-muted">
                    <i class="bi bi-bell-slash"></i>
                    <div>No new notifications</div>
                </li>
            `;
            return;
        }

        const notificationItems = notifications.map(notification => {
            const isUnread = !notification.read_at;
            const timeAgo = this.formatTimeAgo(new Date(notification.created_at));
            
            return `
                <li class="dropdown-item-text border-bottom ${isUnread ? 'bg-light' : ''}" style="white-space: normal;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="fw-bold small ${isUnread ? 'text-primary' : 'text-muted'}">${notification.title}</div>
                            <div class="small text-muted">${notification.message}</div>
                            <div class="small text-muted">
                                <i class="bi bi-clock me-1"></i>${timeAgo}
                            </div>
                        </div>
                        ${isUnread ? `
                            <button class="btn btn-sm btn-outline-primary ms-2" 
                                    onclick="markNotificationRead('${notification.id}')" 
                                    style="font-size: 0.7rem;">
                                <i class="bi bi-check"></i>
                            </button>
                        ` : ''}
                    </div>
                </li>
            `;
        }).join('');

        container.innerHTML = notificationItems;
    }

    renderError() {
        const container = document.getElementById('notificationsList');
        if (container) {
            container.innerHTML = `
                <li class="text-center py-3 text-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                    <div>Error loading notifications</div>
                </li>
            `;
        }
    }

    formatTimeAgo(date) {
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);
        
        if (diffInSeconds < 60) return 'Just now';
        if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
        if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
        if (diffInSeconds < 604800) return `${Math.floor(diffInSeconds / 86400)}d ago`;
        
        return date.toLocaleDateString();
    }
}

// Global functions for notification actions
async function markNotificationRead(id) {
    try {
        const response = await fetch(`/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        });
        
        if (response.ok) {
            // Reload notifications
            window.notificationManager.loadRecentNotifications();
            window.notificationManager.loadNotificationCount();
        }
    } catch (error) {
        console.error('Error marking notification as read:', error);
    }
}

async function markAllNotificationsRead() {
    try {
        const response = await fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        });
        
        if (response.ok) {
            // Reload notifications
            window.notificationManager.loadRecentNotifications();
            window.notificationManager.loadNotificationCount();
        }
    } catch (error) {
        console.error('Error marking all notifications as read:', error);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.notificationManager = new NotificationManager();
});