/**
 * Data rendering functions for Kabianga ARG Portal
 * These functions render data received from API calls into HTML
 */

// Dashboard Stats Renderer
function renderDashboardStats(stats) {
    const container = document.getElementById('dashboard-stats');
    if (!container || !stats) return;

    const html = `
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="stats-card text-center">
                    <div class="stats-number text-primary">${stats.proposals?.total || 0}</div>
                    <div class="stats-label">Total Proposals</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card text-center">
                    <div class="stats-number text-success">${stats.proposals?.approved || 0}</div>
                    <div class="stats-label">Approved</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card text-center">
                    <div class="stats-number text-warning">${stats.proposals?.pending || 0}</div>
                    <div class="stats-label">Pending</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card text-center">
                    <div class="stats-number text-danger">${stats.proposals?.rejected || 0}</div>
                    <div class="stats-label">Rejected</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="stats-card text-center">
                    <div class="stats-number text-info">${stats.projects?.total || 0}</div>
                    <div class="stats-label">Total Projects</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stats-card text-center">
                    <div class="stats-number text-success">${stats.projects?.active || 0}</div>
                    <div class="stats-label">Active Projects</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stats-card text-center">
                    <div class="stats-number text-primary">KSh ${(stats.funding?.total || 0).toLocaleString()}</div>
                    <div class="stats-label">Total Funding</div>
                </div>
            </div>
        </div>
    `;
    
    container.innerHTML = html;
}

// Dashboard Chart Renderer
function renderDashboardChart(chartData) {
    const container = document.getElementById('dashboard-chart');
    if (!container || !chartData) {
        if (container) {
            container.innerHTML = '<div class="alert alert-info">No chart data available</div>';
        }
        return;
    }

    // Simple chart rendering - you can integrate with Chart.js or other libraries
    container.innerHTML = '<div class="alert alert-info">Chart data loaded successfully</div>';
}

// Recent Activity Renderer
function renderRecentActivity(activities) {
    const container = document.getElementById('recent-activity');
    if (!container) return;

    if (!activities || activities.length === 0) {
        container.innerHTML = '<div class="text-muted">No recent activities</div>';
        return;
    }

    const html = activities.map(activity => `
        <div class="activity-item d-flex align-items-center mb-3">
            <div class="activity-icon me-3">
                <i class="bi ${activity.type === 'proposal' ? 'bi-file-text' : 'bi-kanban'} text-primary"></i>
            </div>
            <div class="activity-content flex-grow-1">
                <div class="activity-title fw-medium">${activity.title}</div>
                <div class="activity-meta text-muted small">
                    ${activity.user} • ${activity.date}
                </div>
            </div>
            <div class="activity-status">
                <span class="badge bg-${getStatusColor(activity.status)}">${activity.status}</span>
            </div>
        </div>
    `).join('');

    container.innerHTML = html;
}

// Proposals List Renderer
function renderProposalsList(response) {
    const tableBody = document.getElementById('applicationsTableBody');
    const loadingState = document.getElementById('loadingState');
    const emptyState = document.getElementById('emptyState');
    
    if (loadingState) loadingState.style.display = 'none';
    if (!tableBody) return;

    const proposals = response?.data || response || [];
    
    if (!proposals || proposals.length === 0) {
        if (emptyState) {
            emptyState.style.display = 'block';
        } else {
            tableBody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No proposals found</td></tr>';
        }
        return;
    }

    if (emptyState) emptyState.style.display = 'none';

    const html = proposals.map(proposal => `
        <tr>
            <td>${proposal.proposalid || 'N/A'}</td>
            <td>
                <div class="fw-medium">${proposal.title || proposal.researchtitle || 'Untitled'}</div>
                <div class="text-muted small">${(proposal.abstract || proposal.objectives || '').substring(0, 100)}...</div>
            </td>
            <td>${proposal.theme_name || (proposal.themeitem?.themename) || 'N/A'}</td>
            <td>${proposal.grant_name || (proposal.grantitem?.grantname) || 'N/A'}</td>
            <td>KSh ${(proposal.requested_amount || proposal.grantitem?.amount || 0).toLocaleString()}</td>
            <td>
                <span class="badge bg-${getStatusColor(proposal.approvalstatus)}">${proposal.approvalstatus || 'Unknown'}</span>
            </td>
            <td>${proposal.created_at ? new Date(proposal.created_at).toLocaleDateString() : 'N/A'}</td>
            <td>
                <div class="btn-group btn-group-sm">
                    <a href="/proposals/view/${proposal.proposalid}" class="btn btn-outline-primary">
                        <i class="bi bi-eye"></i>
                    </a>
                    ${proposal.approvalstatus === 'Pending' ? `
                        <a href="/proposals/edit/${proposal.proposalid}" class="btn btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                    ` : ''}
                </div>
            </td>
        </tr>
    `).join('');

    tableBody.innerHTML = html;
}

// Users List Renderer
function renderUsersList(response) {
    const tableBody = document.getElementById('usersTableBody');
    const loadingState = document.getElementById('loadingState');
    
    if (loadingState) loadingState.style.display = 'none';
    if (!tableBody) return;

    const users = response?.data || response || [];
    
    if (!users || users.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No users found</td></tr>';
        return;
    }

    const html = users.map(user => `
        <tr>
            <td>
                <div class="fw-medium">${user.name || 'N/A'}</div>
                <div class="text-muted small">${user.email || 'N/A'}</div>
            </td>
            <td>${user.email || 'N/A'}</td>
            <td>N/A</td>
            <td>${getRoleName(user.role)}</td>
            <td>
                <span class="badge bg-${user.isactive ? 'success' : 'danger'}">
                    ${user.isactive ? 'Active' : 'Inactive'}
                </span>
            </td>
            <td>${user.created_at ? new Date(user.created_at).toLocaleDateString() : 'N/A'}</td>
            <td>
                <div class="btn-group btn-group-sm">
                    <a href="/users/view/${user.userid}" class="btn btn-outline-primary" title="View">
                        <i class="bi bi-eye"></i>
                    </a>
                    <button class="btn btn-outline-secondary" onclick="editUser('${user.userid}')" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-outline-info" onclick="managePermissions('${user.userid}')" title="Permissions">
                        <i class="bi bi-shield-check"></i>
                    </button>
                    <button class="btn btn-outline-warning" onclick="resetPassword('${user.userid}')" title="Reset Password">
                        <i class="bi bi-key"></i>
                    </button>
                    <button class="btn btn-outline-${user.isactive ? 'danger' : 'success'}" onclick="toggleUserStatus('${user.userid}', ${user.isactive})" title="${user.isactive ? 'Disable' : 'Enable'}">
                        <i class="bi bi-${user.isactive ? 'x-circle' : 'check-circle'}"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');

    tableBody.innerHTML = html;
}

function getRoleName(role) {
    switch(role) {
        case 1: return 'Admin';
        case 2: return 'Researcher';
        case 3: return 'Guest';
        default: return 'Unknown';
    }
}

// Helper function to get status color
function getStatusColor(status) {
    if (!status) return 'secondary';
    
    const statusLower = status.toLowerCase();
    switch (statusLower) {
        case 'approved':
        case 'active':
        case 'completed':
            return 'success';
        case 'pending':
        case 'submitted':
            return 'warning';
        case 'rejected':
        case 'cancelled':
        case 'inactive':
            return 'danger';
        default:
            return 'secondary';
    }
}

// Projects List Renderer
function renderProjectsList(projects) {
    const tableBody = document.getElementById('projectsTableBody');
    if (!tableBody) return;

    if (!projects || projects.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No projects found</td></tr>';
        return;
    }

    const html = projects.map(project => `
        <tr>
            <td>${project.researchnumber || 'N/A'}</td>
            <td>
                <div class="fw-medium">${project.title || 'N/A'}</div>
                <div class="text-muted small">${project.description || ''}</div>
            </td>
            <td>${project.researcher || 'N/A'}</td>
            <td>
                <span class="badge bg-${getStatusColor(project.projectstatus)}">${project.projectstatus || 'Unknown'}</span>
            </td>
            <td>${project.created_at ? new Date(project.created_at).toLocaleDateString() : 'N/A'}</td>
            <td>
                <div class="btn-group btn-group-sm">
                    <a href="/projects/view/${project.researchid}" class="btn btn-outline-primary">
                        <i class="bi bi-eye"></i>
                    </a>
                </div>
            </td>
        </tr>
    `).join('');

    tableBody.innerHTML = html;
}

// Export functions for global use
window.DataRenderers = {
    renderDashboardStats,
    renderDashboardChart,
    renderRecentActivity,
    renderProposalsList,
    renderUsersList,
    renderProjectsList,
    getStatusColor,
    getRoleName
};