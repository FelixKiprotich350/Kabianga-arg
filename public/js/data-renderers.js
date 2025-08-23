/**
 * Data rendering functions for Kabianga ARG Portal
 * These functions render fetched data into the UI
 */

// Dashboard Renderers
function renderDashboardStats(stats) {
    const statsContainer = document.getElementById('dashboard-stats');
    if (!statsContainer) return;

    statsContainer.innerHTML = `
        <div class="row">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon bg-primary">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stats-content">
                        <h3>${ARGPortal.formatNumber(stats.total_proposals || 0)}</h3>
                        <p>Total Proposals</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon bg-success">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <div class="stats-content">
                        <h3>${ARGPortal.formatNumber(stats.active_projects || 0)}</h3>
                        <p>Active Projects</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon bg-warning">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-content">
                        <h3>${ARGPortal.formatNumber(stats.total_users || 0)}</h3>
                        <p>Total Users</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon bg-info">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stats-content">
                        <h3>KSh ${ARGPortal.formatNumber(stats.total_funding || 0)}</h3>
                        <p>Total Funding</p>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function renderDashboardChart(chartData) {
    const chartContainer = document.getElementById('dashboard-chart');
    if (!chartContainer || !chartData) return;

    // Simple chart implementation - you can replace with Chart.js or similar
    chartContainer.innerHTML = `
        <div class="chart-container">
            <h5>Proposals by Status</h5>
            <div class="chart-bars">
                ${chartData.map(item => `
                    <div class="chart-bar">
                        <div class="bar" style="height: ${(item.count / Math.max(...chartData.map(d => d.count))) * 100}%"></div>
                        <span class="label">${item.status}</span>
                        <span class="value">${item.count}</span>
                    </div>
                `).join('')}
            </div>
        </div>
    `;
}

function renderRecentActivity(activities) {
    const activityContainer = document.getElementById('recent-activity');
    if (!activityContainer) return;

    activityContainer.innerHTML = `
        <div class="activity-list">
            <h5>Recent Activity</h5>
            ${activities.map(activity => `
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-${activity.icon || 'bell'}"></i>
                    </div>
                    <div class="activity-content">
                        <p>${activity.description}</p>
                        <small class="text-muted">${new Date(activity.created_at).toLocaleDateString()}</small>
                    </div>
                </div>
            `).join('')}
        </div>
    `;
}

// Proposals Renderers
function renderProposalsList(proposals) {
    const container = document.getElementById('proposals-list');
    if (!container) return;

    container.innerHTML = `
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Principal Investigator</th>
                        <th>Status</th>
                        <th>Submitted Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    ${proposals.map(proposal => `
                        <tr>
                            <td>${proposal.title}</td>
                            <td>${proposal.principal_investigator}</td>
                            <td><span class="badge bg-${getStatusColor(proposal.status)}">${proposal.status}</span></td>
                            <td>${new Date(proposal.created_at).toLocaleDateString()}</td>
                            <td>
                                <a href="/proposals/view/${proposal.id}" class="btn btn-sm btn-primary">View</a>
                                ${proposal.can_edit ? `<a href="/proposals/edit/${proposal.id}" class="btn btn-sm btn-secondary">Edit</a>` : ''}
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
    `;
}

function renderProposalDetails(proposal) {
    const container = document.getElementById('proposal-basic-info');
    if (!container) return;

    container.innerHTML = `
        <div class="proposal-header">
            <h2>${proposal.title}</h2>
            <span class="badge bg-${getStatusColor(proposal.status)}">${proposal.status}</span>
        </div>
        <div class="proposal-info">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Principal Investigator:</strong> ${proposal.principal_investigator}</p>
                    <p><strong>School:</strong> ${proposal.school}</p>
                    <p><strong>Department:</strong> ${proposal.department}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Research Theme:</strong> ${proposal.research_theme}</p>
                    <p><strong>Grant:</strong> ${proposal.grant}</p>
                    <p><strong>Requested Amount:</strong> KSh ${ARGPortal.formatNumber(proposal.requested_amount)}</p>
                </div>
            </div>
            <div class="proposal-abstract">
                <h5>Abstract</h5>
                <p>${proposal.abstract}</p>
            </div>
        </div>
    `;
}

// Projects Renderers
function renderProjectsList(projects) {
    const container = document.getElementById('projects-list');
    if (!container) return;

    container.innerHTML = `
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Project Title</th>
                        <th>Principal Investigator</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th>Start Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    ${projects.map(project => `
                        <tr>
                            <td>${project.title}</td>
                            <td>${project.principal_investigator}</td>
                            <td><span class="badge bg-${getStatusColor(project.status)}">${project.status}</span></td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar" style="width: ${project.progress || 0}%">${project.progress || 0}%</div>
                                </div>
                            </td>
                            <td>${new Date(project.start_date).toLocaleDateString()}</td>
                            <td>
                                <a href="/projects/view/${project.id}" class="btn btn-sm btn-primary">View</a>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
    `;
}

function renderProjectDetails(project) {
    const container = document.getElementById('project-details-content');
    if (!container) return;

    container.innerHTML = `
        <div class="project-header">
            <h2>${project.title}</h2>
            <span class="badge bg-${getStatusColor(project.status)}">${project.status}</span>
        </div>
        <div class="project-info">
            <div class="row">
                <div class="col-md-8">
                    <div class="project-description">
                        <h5>Project Description</h5>
                        <p>${project.description}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="project-meta">
                        <p><strong>Principal Investigator:</strong> ${project.principal_investigator}</p>
                        <p><strong>Start Date:</strong> ${new Date(project.start_date).toLocaleDateString()}</p>
                        <p><strong>End Date:</strong> ${new Date(project.end_date).toLocaleDateString()}</p>
                        <p><strong>Budget:</strong> KSh ${ARGPortal.formatNumber(project.budget)}</p>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Users Renderers
function renderUsersList(users) {
    const container = document.getElementById('users-list');
    if (!container) return;

    container.innerHTML = `
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>School</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    ${users.map(user => `
                        <tr>
                            <td>${user.first_name} ${user.last_name}</td>
                            <td>${user.email}</td>
                            <td><span class="badge bg-info">${user.role}</span></td>
                            <td>${user.school || 'N/A'}</td>
                            <td><span class="badge bg-${user.is_active ? 'success' : 'danger'}">${user.is_active ? 'Active' : 'Inactive'}</span></td>
                            <td>
                                <a href="/users/view/${user.id}" class="btn btn-sm btn-primary">View</a>
                                <button class="btn btn-sm btn-secondary" onclick="editUser(${user.id})">Edit</button>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
    `;
}

// Generic list renderers
function renderSchoolsList(schools) {
    renderGenericList(schools, 'schools-list', [
        { key: 'name', label: 'School Name' },
        { key: 'code', label: 'Code' },
        { key: 'dean', label: 'Dean' },
        { key: 'departments_count', label: 'Departments' }
    ], '/schools/view/');
}

function renderDepartmentsList(departments) {
    renderGenericList(departments, 'departments-list', [
        { key: 'name', label: 'Department Name' },
        { key: 'code', label: 'Code' },
        { key: 'school_name', label: 'School' },
        { key: 'head', label: 'Head' }
    ], '/departments/view/');
}

function renderGrantsList(grants) {
    renderGenericList(grants, 'grants-list', [
        { key: 'name', label: 'Grant Name' },
        { key: 'financial_year', label: 'Financial Year' },
        { key: 'total_amount', label: 'Total Amount', format: 'currency' },
        { key: 'status', label: 'Status', format: 'badge' }
    ], '/grants/view/');
}

// Generic list renderer
function renderGenericList(items, containerId, columns, viewUrl) {
    const container = document.getElementById(containerId);
    if (!container) return;

    container.innerHTML = `
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        ${columns.map(col => `<th>${col.label}</th>`).join('')}
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    ${items.map(item => `
                        <tr>
                            ${columns.map(col => `
                                <td>${formatCellValue(item[col.key], col.format)}</td>
                            `).join('')}
                            <td>
                                <a href="${viewUrl}${item.id}" class="btn btn-sm btn-primary">View</a>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
    `;
}

// Component renderers
function renderCollaborators(collaborators) {
    const container = document.getElementById('collaborators-list');
    if (!container) return;

    container.innerHTML = `
        <div class="collaborators-section">
            <h5>Collaborators</h5>
            ${collaborators.map(collab => `
                <div class="collaborator-item">
                    <strong>${collab.name}</strong> - ${collab.institution}
                    <br><small>${collab.role}</small>
                </div>
            `).join('')}
        </div>
    `;
}

function renderPublications(publications) {
    const container = document.getElementById('publications-list');
    if (!container) return;

    container.innerHTML = `
        <div class="publications-section">
            <h5>Publications</h5>
            ${publications.map(pub => `
                <div class="publication-item">
                    <strong>${pub.title}</strong>
                    <br><em>${pub.journal}</em> (${pub.year})
                </div>
            `).join('')}
        </div>
    `;
}

function renderExpenditures(expenditures) {
    const container = document.getElementById('expenditures-list');
    if (!container) return;

    const total = expenditures.reduce((sum, exp) => sum + parseFloat(exp.amount), 0);

    container.innerHTML = `
        <div class="expenditures-section">
            <h5>Budget Breakdown</h5>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Description</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${expenditures.map(exp => `
                            <tr>
                                <td>${exp.item}</td>
                                <td>${exp.description}</td>
                                <td>KSh ${ARGPortal.formatNumber(exp.amount)}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                    <tfoot>
                        <tr class="table-info">
                            <th colspan="2">Total</th>
                            <th>KSh ${ARGPortal.formatNumber(total)}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    `;
}

// Utility functions
function getStatusColor(status) {
    const colors = {
        'draft': 'secondary',
        'submitted': 'primary',
        'under_review': 'warning',
        'approved': 'success',
        'rejected': 'danger',
        'active': 'success',
        'completed': 'info',
        'cancelled': 'danger',
        'paused': 'warning'
    };
    return colors[status?.toLowerCase()] || 'secondary';
}

function formatCellValue(value, format) {
    if (!value) return 'N/A';
    
    switch(format) {
        case 'currency':
            return `KSh ${ARGPortal.formatNumber(value)}`;
        case 'badge':
            return `<span class="badge bg-${getStatusColor(value)}">${value}</span>`;
        case 'date':
            return new Date(value).toLocaleDateString();
        default:
            return value;
    }
}

// Export renderers for global use
window.DataRenderers = {
    renderDashboardStats,
    renderDashboardChart,
    renderRecentActivity,
    renderProposalsList,
    renderProposalDetails,
    renderProjectsList,
    renderProjectDetails,
    renderUsersList,
    renderSchoolsList,
    renderDepartmentsList,
    renderGrantsList,
    renderCollaborators,
    renderPublications,
    renderExpenditures
};