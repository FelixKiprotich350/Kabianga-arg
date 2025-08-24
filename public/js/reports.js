/**
 * Reports Module JavaScript
 * Handles all report-related functionality for the ARG Portal
 */

class ReportsManager {
    constructor() {
        this.charts = {};
        this.currentFilters = {};
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadInitialData();
    }

    bindEvents() {
        // Tab change events
        document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', (e) => {
                this.handleTabChange(e.target.getAttribute('data-bs-target'));
            });
        });

        // Filter change events
        document.addEventListener('change', (e) => {
            if (e.target.matches('.report-filter')) {
                this.handleFilterChange();
            }
        });
    }

    loadInitialData() {
        this.loadSummaryData();
        this.loadProposalsReport();
    }

    handleTabChange(target) {
        switch(target) {
            case '#projects':
                this.loadProjectsReport();
                break;
            case '#financial':
                this.loadFinancialReport();
                break;
            case '#users':
                this.loadUsersReport();
                break;
            case '#publications':
                this.loadPublicationsReport();
                break;
        }
    }

    async loadSummaryData() {
        try {
            const response = await fetch('/api/v1/reports/summary');
            const data = await response.json();
            
            this.updateSummaryCards(data.totals);
        } catch (error) {
            console.error('Error loading summary data:', error);
            this.showError('Failed to load summary data');
        }
    }

    updateSummaryCards(totals) {
        const elements = {
            'total-proposals': totals.proposals,
            'total-projects': totals.projects,
            'total-funding': 'KES ' + this.formatNumber(totals.funding),
            'total-publications': totals.publications,
            'active-users': totals.active_users
        };

        Object.entries(elements).forEach(([id, value]) => {
            const element = document.getElementById(id);
            if (element) {
                element.textContent = value;
            }
        });
    }

    async loadProposalsReport() {
        const filters = this.getProposalFilters();
        const params = new URLSearchParams(filters);
        
        try {
            // Load proposals by school
            const schoolResponse = await fetch(`/api/v1/reports/proposals/by-school?${params}`);
            const schoolData = await schoolResponse.json();
            this.createChart('proposalsBySchoolChart', 'bar', schoolData, 'Proposals by Department');

            // Load proposals by theme
            const themeResponse = await fetch(`/api/v1/reports/proposals/by-theme?${params}`);
            const themeData = await themeResponse.json();
            this.createChart('proposalsByThemeChart', 'doughnut', themeData, 'Proposals by Theme');

        } catch (error) {
            console.error('Error loading proposals report:', error);
            this.showError('Failed to load proposals report');
        }
    }

    async loadProjectsReport() {
        const filters = this.getProjectFilters();
        const params = new URLSearchParams(filters);
        
        try {
            const response = await fetch(`/api/v1/reports/projects?${params}`);
            const data = await response.json();
            
            // Status chart
            const statusData = {
                labels: Object.keys(data.status_breakdown),
                datasets: [{
                    data: Object.values(data.status_breakdown),
                    backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545']
                }]
            };
            this.createChart('projectStatusChart', 'pie', statusData, 'Projects by Status');
            
            // Theme chart
            const themeData = {
                labels: Object.keys(data.projects_by_theme),
                datasets: [{
                    data: Object.values(data.projects_by_theme),
                    backgroundColor: this.generateColors(Object.keys(data.projects_by_theme).length)
                }]
            };
            this.createChart('projectsByThemeChart', 'doughnut', themeData, 'Projects by Theme');
            
            // Update projects table
            this.populateProjectsTable(data.projects);
            
        } catch (error) {
            console.error('Error loading projects report:', error);
            this.showError('Failed to load projects report');
        }
    }

    async loadFinancialReport() {
        const filters = this.getFinancialFilters();
        const params = new URLSearchParams(filters);
        
        try {
            const response = await fetch(`/api/v1/reports/financial?${params}`);
            const data = await response.json();
            
            // Update financial summary cards
            document.getElementById('financial-total').textContent = 'KES ' + this.formatNumber(data.total_funding);
            document.getElementById('financial-average').textContent = 'KES ' + this.formatNumber(data.average_funding);
            document.getElementById('financial-count').textContent = data.funding_count;
            document.getElementById('financial-utilization').textContent = data.budget_utilization + '%';
            
            // Monthly funding chart
            const monthlyData = {
                labels: data.funding_by_month.labels,
                datasets: [{
                    label: 'Funding Amount (KES)',
                    data: data.funding_by_month.data,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    fill: true
                }]
            };
            this.createChart('fundingByMonthChart', 'line', monthlyData, 'Monthly Funding Trends');
            
        } catch (error) {
            console.error('Error loading financial report:', error);
            this.showError('Failed to load financial report');
        }
    }

    async loadUsersReport() {
        const filters = this.getUserFilters();
        const params = new URLSearchParams(filters);
        
        try {
            const response = await fetch(`/api/v1/reports/users?${params}`);
            const data = await response.json();
            
            // Update user summary
            document.getElementById('users-total').textContent = data.total_users;
            document.getElementById('users-active').textContent = data.active_users;
            
            // Role distribution chart
            const roleData = {
                labels: Object.keys(data.role_distribution),
                datasets: [{
                    data: Object.values(data.role_distribution),
                    backgroundColor: this.generateColors(Object.keys(data.role_distribution).length)
                }]
            };
            this.createChart('roleDistributionChart', 'doughnut', roleData, 'Role Distribution');
            
            // Users table
            this.populateUsersTable(data.users);
            
        } catch (error) {
            console.error('Error loading users report:', error);
            this.showError('Failed to load users report');
        }
    }

    async loadPublicationsReport() {
        const filters = this.getPublicationFilters();
        const params = new URLSearchParams(filters);
        
        try {
            const response = await fetch(`/api/v1/reports/publications?${params}`);
            const data = await response.json();
            
            // Publications by year
            const yearData = {
                labels: Object.keys(data.publications_by_year),
                datasets: [{
                    label: 'Publications',
                    data: Object.values(data.publications_by_year),
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            };
            this.createChart('publicationsByYearChart', 'bar', yearData, 'Publications by Year');
            
            // Publications by theme
            const themeData = {
                labels: Object.keys(data.publications_by_theme),
                datasets: [{
                    data: Object.values(data.publications_by_theme),
                    backgroundColor: this.generateColors(Object.keys(data.publications_by_theme).length)
                }]
            };
            this.createChart('publicationsByThemeChart', 'pie', themeData, 'Publications by Theme');
            
            // Publications table
            this.populatePublicationsTable(data.recent_publications);
            
        } catch (error) {
            console.error('Error loading publications report:', error);
            this.showError('Failed to load publications report');
        }
    }

    createChart(canvasId, type, data, title) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return;
        
        // Destroy existing chart if it exists
        if (this.charts[canvasId]) {
            this.charts[canvasId].destroy();
        }
        
        this.charts[canvasId] = new Chart(ctx.getContext('2d'), {
            type: type,
            data: data,
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: title
                    },
                    legend: {
                        display: type !== 'line'
                    }
                },
                scales: type === 'line' || type === 'bar' ? {
                    y: {
                        beginAtZero: true
                    }
                } : {}
            }
        });
    }

    populateProjectsTable(projects) {
        const tbody = document.querySelector('#projectsTable tbody');
        if (!tbody) return;
        
        tbody.innerHTML = '';
        
        projects.forEach(project => {
            const row = tbody.insertRow();
            row.innerHTML = `
                <td>${project.number || 'N/A'}</td>
                <td>${project.title}</td>
                <td>${project.applicant}</td>
                <td><span class="badge bg-${this.getStatusColor(project.status)}">${project.status}</span></td>
                <td>${project.theme}</td>
                <td>${project.grant}</td>
                <td>${project.created_at}</td>
            `;
        });
    }

    populateUsersTable(users) {
        const tbody = document.querySelector('#usersTable tbody');
        if (!tbody) return;
        
        tbody.innerHTML = '';
        
        users.forEach(user => {
            const row = tbody.insertRow();
            row.innerHTML = `
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td>${user.role}</td>
                <td>${user.department}</td>
                <td>${user.proposal_count}</td>
                <td>${user.approved_proposals}</td>
                <td>${user.success_rate}%</td>
                <td>${user.active_projects}</td>
            `;
        });
    }

    populatePublicationsTable(publications) {
        const tbody = document.querySelector('#publicationsTable tbody');
        if (!tbody) return;
        
        tbody.innerHTML = '';
        
        publications.forEach(pub => {
            const row = tbody.insertRow();
            row.innerHTML = `
                <td>${pub.title}</td>
                <td>${pub.authors}</td>
                <td>${pub.year}</td>
                <td>${pub.publisher}</td>
                <td>${pub.theme}</td>
                <td>${pub.applicant}</td>
            `;
        });
    }

    getProposalFilters() {
        return {
            filtergrant: this.getElementValue('proposal-grant-filter', 'all'),
            filtertheme: this.getElementValue('proposal-theme-filter', 'all'),
            filterdepartment: this.getElementValue('proposal-department-filter', 'all')
        };
    }

    getProjectFilters() {
        return {
            status: this.getElementValue('project-status-filter', 'all'),
            grant: this.getElementValue('project-grant-filter', 'all')
        };
    }

    getFinancialFilters() {
        return {
            grant: this.getElementValue('financial-grant-filter', 'all'),
            year: this.getElementValue('financial-year-filter', 'all')
        };
    }

    getUserFilters() {
        return {
            department: this.getElementValue('user-department-filter', 'all'),
            role: this.getElementValue('user-role-filter', 'all')
        };
    }

    getPublicationFilters() {
        return {
            year: this.getElementValue('publication-year-filter', 'all'),
            theme: this.getElementValue('publication-theme-filter', 'all')
        };
    }

    getElementValue(id, defaultValue = '') {
        const element = document.getElementById(id);
        return element ? element.value : defaultValue;
    }

    async exportReport(type) {
        const formData = new FormData();
        formData.append('type', type);
        
        // Add current filters based on report type
        let filters = {};
        switch(type) {
            case 'proposals':
                filters = this.getProposalFilters();
                break;
            case 'projects':
                filters = this.getProjectFilters();
                break;
            case 'financial':
                filters = this.getFinancialFilters();
                break;
            case 'users':
                filters = this.getUserFilters();
                break;
            case 'publications':
                filters = this.getPublicationFilters();
                break;
        }
        
        Object.entries(filters).forEach(([key, value]) => {
            formData.append(key, value);
        });
        
        try {
            const response = await fetch('/api/v1/reports/export', {
                method: 'POST',
                body: formData
            });
            
            if (!response.ok) {
                throw new Error('Export failed');
            }
            
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = `${type}_report_${new Date().toISOString().split('T')[0]}.pdf`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            
        } catch (error) {
            console.error('Export error:', error);
            this.showError('Failed to export report. Please try again.');
        }
    }

    refreshReports() {
        this.loadSummaryData();
        const activeTab = document.querySelector('.nav-link.active').getAttribute('data-bs-target');
        
        switch(activeTab) {
            case '#proposals':
                this.loadProposalsReport();
                break;
            case '#projects':
                this.loadProjectsReport();
                break;
            case '#financial':
                this.loadFinancialReport();
                break;
            case '#users':
                this.loadUsersReport();
                break;
            case '#publications':
                this.loadPublicationsReport();
                break;
        }
    }

    formatNumber(num) {
        return new Intl.NumberFormat().format(num);
    }

    generateColors(count) {
        const colors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
            '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384'
        ];
        return colors.slice(0, count);
    }

    getStatusColor(status) {
        const colors = {
            'ACTIVE': 'success',
            'COMPLETED': 'primary',
            'PAUSED': 'warning',
            'CANCELLED': 'danger',
            'APPROVED': 'success',
            'REJECTED': 'danger',
            'PENDING': 'warning'
        };
        return colors[status] || 'secondary';
    }

    showError(message) {
        // You can implement a toast notification system here
        console.error(message);
        alert(message);
    }

    showSuccess(message) {
        // You can implement a toast notification system here
        console.log(message);
    }
}

// Global functions for backward compatibility
let reportsManager;

document.addEventListener('DOMContentLoaded', function() {
    reportsManager = new ReportsManager();
});

function loadProposalsReport() {
    if (reportsManager) reportsManager.loadProposalsReport();
}

function loadProjectsReport() {
    if (reportsManager) reportsManager.loadProjectsReport();
}

function loadFinancialReport() {
    if (reportsManager) reportsManager.loadFinancialReport();
}

function loadUsersReport() {
    if (reportsManager) reportsManager.loadUsersReport();
}

function loadPublicationsReport() {
    if (reportsManager) reportsManager.loadPublicationsReport();
}

function exportReport(type) {
    if (reportsManager) reportsManager.exportReport(type);
}

function refreshReports() {
    if (reportsManager) reportsManager.refreshReports();
}