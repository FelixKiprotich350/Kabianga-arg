/**
 * Centralized API Service for Kabianga ARG Portal
 * Handles all API calls with consistent error handling and loading states
 */
class APIService {
    constructor() {
        this.baseURL = '/api/v1';
        this.token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    }

    // Generic fetch method with error handling
    async fetch(endpoint, options = {}) {
        const config = {
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': this.token,
                'Accept': 'application/json',
                ...options.headers
            },
            ...options
        };

        try {
            const response = await fetch(`${this.baseURL}${endpoint}`, config);
            
            if (!response.ok) {
                if (response.status === 401) {
                    window.location.href = '/login';
                    return;
                }
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }

    // Dashboard APIs
    async getDashboardStats() {
        return this.fetch('/dashboard/stats');
    }

    async getDashboardChart() {
        return this.fetch('/dashboard/charts');
    }

    async getRecentActivity() {
        return this.fetch('/dashboard/activity');
    }

    // User Management APIs
    async getAllUsers(search = '') {
        return this.fetch(`/users${search ? `?search=${search}` : ''}`);
    }

    async getUser(id) {
        return this.fetch(`/users/${id}`);
    }

    async createUser(userData) {
        return this.fetch('/users', {
            method: 'POST',
            body: JSON.stringify(userData)
        });
    }

    async updateUser(id, userData) {
        return this.fetch(`/users/${id}`, {
            method: 'PUT',
            body: JSON.stringify(userData)
        });
    }

    async updateUserPermissions(id, permissions) {
        return this.fetch(`/users/${id}/permissions`, {
            method: 'PATCH',
            body: JSON.stringify({ permissions })
        });
    }

  

    async resetUserPassword(id, password) {
        return this.fetch(`/users/${id}/reset-password`, {
            method: 'POST',
            body: JSON.stringify({ password })
        });
    }

    async disableUser(id) {
        return this.fetch(`/users/${id}/disable`, {
            method: 'PATCH'
        });
    }

    async enableUser(id) {
        return this.fetch(`/users/${id}/enable`, {
            method: 'PATCH'
        });
    }

    // Proposals APIs
    async getAllProposals(search = '') {
        return this.fetch(`/proposals${search ? `?search=${search}` : ''}`);
    }

    async getMyProposals() {
        return this.fetch('/proposals/my');
    }

    async getProposal(id) {
        return this.fetch(`/proposals/${id}`);
    }

    async createProposal(proposalData) {
        return this.fetch('/proposals', {
            method: 'POST',
            body: JSON.stringify(proposalData)
        });
    }

    async updateProposal(id, proposalData) {
        return this.fetch(`/proposals/${id}`, {
            method: 'PUT',
            body: JSON.stringify(proposalData)
        });
    }

    async submitProposal(id) {
        return this.fetch(`/proposals/${id}/submit`, {
            method: 'POST'
        });
    }

    async receiveProposal(id) {
        return this.fetch(`/proposals/${id}/receive`, {
            method: 'POST'
        });
    }

    async approveRejectProposal(id, action, comments = '') {
        return this.fetch(`/proposals/${id}/approve-reject`, {
            method: 'POST',
            body: JSON.stringify({ action, comments })
        });
    }

    // Proposal Components APIs
    async getProposalCollaborators(id) {
        return this.fetch(`/proposals/${id}/collaborators`);
    }

    async getProposalPublications(id) {
        return this.fetch(`/proposals/${id}/publications`);
    }

    async getProposalExpenditures(id) {
        return this.fetch(`/proposals/${id}/expenditures`);
    }

    async getProposalWorkplans(id) {
        return this.fetch(`/proposals/${id}/workplans`);
    }

    async getProposalResearchDesign(id) {
        return this.fetch(`/proposals/${id}/research-design`);
    }

    // Collaborators APIs
    async getAllCollaborators(search = '') {
        return this.fetch(`/collaborators${search ? `?search=${search}` : ''}`);
    }

    async createCollaborator(data) {
        return this.fetch('/collaborators', {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    async updateCollaborator(id, data) {
        return this.fetch(`/collaborators/${id}`, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    }

    async deleteCollaborator(id) {
        return this.fetch(`/collaborators/${id}`, {
            method: 'DELETE'
        });
    }

    // Publications APIs
    async getAllPublications(search = '') {
        return this.fetch(`/publications${search ? `?search=${search}` : ''}`);
    }

    async createPublication(data) {
        return this.fetch('/publications', {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    async updatePublication(id, data) {
        return this.fetch(`/publications/${id}`, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    }

    async deletePublication(id) {
        return this.fetch(`/publications/${id}`, {
            method: 'DELETE'
        });
    }

    // Projects APIs
    async getAllProjects(search = '') {
        return this.fetch(`/projects${search ? `?search=${search}` : ''}`);
    }

    async getMyProjects() {
        return this.fetch('/projects/my');
    }

    async getActiveProjects() {
        return this.fetch('/projects/active');
    }

    async getMyActiveProjects() {
        return this.fetch('/projects/my-active');
    }

    async getProject(id) {
        return this.fetch(`/projects/${id}`);
    }

    async submitProgress(id, progressData) {
        return this.fetch(`/projects/${id}/progress`, {
            method: 'POST',
            body: JSON.stringify(progressData)
        });
    }

    async getProjectProgress(id) {
        return this.fetch(`/projects/${id}/progress`);
    }

    async addProjectFunding(id, fundingData) {
        return this.fetch(`/projects/${id}/funding`, {
            method: 'POST',
            body: JSON.stringify(fundingData)
        });
    }

    async getProjectFunding(id) {
        return this.fetch(`/projects/${id}/funding`);
    }

    // Schools APIs
    async getAllSchools(search = '') {
        return this.fetch(`/schools${search ? `?search=${search}` : ''}`);
    }

    async searchSchools(search) {
        return this.fetch(`/schools/search?search=${search}`);
    }

    async createSchool(data) {
        return this.fetch('/schools', {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    async getSchool(id) {
        return this.fetch(`/schools/view/${id}`);
    }

    async updateSchool(id, data) {
        return this.fetch(`/schools/${id}`, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    }

    // Departments APIs
    async getAllDepartments(search = '') {
        return this.fetch(`/departments${search ? `?search=${search}` : ''}`);
    }

    async createDepartment(data) {
        return this.fetch('/departments', {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    async getDepartment(id) {
        return this.fetch(`/departments/${id}`);
    }

    async updateDepartment(id, data) {
        return this.fetch(`/departments/${id}`, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    }

    // Grants APIs
    async getAllGrants(search = '') {
        return this.fetch(`/grants${search ? `?search=${search}` : ''}`);
    }

    async createGrant(data) {
        return this.fetch('/grants', {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    async getGrant(id) {
        return this.fetch(`/grants/${id}`);
    }

    async updateGrant(id, data) {
        return this.fetch(`/grants/${id}`, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    }

    // Financial Years APIs
    async getAllFinancialYears() {
        return this.fetch('/financial-years');
    }

    async createFinancialYear(data) {
        return this.fetch('/financial-years', {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    // Research Themes APIs
    async getAllThemes() {
        return this.fetch('/themes');
    }

    async createTheme(data) {
        return this.fetch('/themes', {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    async updateTheme(id, data) {
        return this.fetch(`/themes/${id}`, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    }

    async deleteTheme(id) {
        return this.fetch(`/themes/${id}`, {
            method: 'DELETE'
        });
    }

    // Permissions APIs
    async getAllPermissions() {
        return this.fetch('/permissions');
    }

    async getPermissionsByRole(role) {
        return this.fetch(`/permissions/role/${role}`);
    }

    // Settings APIs
    async getAllSettings() {
        return this.fetch('/settings');
    }

    async updateSettings(data) {
        return this.fetch('/settings', {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    }

    // Reports APIs
    async getReportsSummary() {
        return this.fetch('/reports/summary');
    }

    async getProposalsReport() {
        return this.fetch('/reports/proposals');
    }

    async getFinancialReport() {
        return this.fetch('/reports/financial');
    }

    async getProjectsReport() {
        return this.fetch('/reports/projects');
    }

    async exportReport(data) {
        return this.fetch('/reports/export', {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    // Notifications APIs
    async getNotificationTypes() {
        return this.fetch('/notifications/types');
    }

    async getNotificationTypeUsers(id) {
        return this.fetch(`/notifications/types/${id}/users`);
    }

    async addNotifiableUsers(id, users) {
        return this.fetch(`/notifications/types/${id}/users`, {
            method: 'POST',
            body: JSON.stringify({ users })
        });
    }

    async removeNotifiableUser(id, userId) {
        return this.fetch(`/notifications/types/${id}/users`, {
            method: 'DELETE',
            body: JSON.stringify({ userId })
        });
    }
}

// Export for global use
window.APIService = APIService;
window.api = new APIService();

// Export for module use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = APIService;
}hemes() {
        return this.fetch('/themes');
    }

    async createTheme(data) {
        return this.fetch('/themes', {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    async updateTheme(id, data) {
        return this.fetch(`/themes/${id}`, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    }

    async deleteTheme(id) {
        return this.fetch(`/themes/${id}`, {
            method: 'DELETE'
        });
    }

    // Monitoring APIs
    async getMonitoringHome() {
        return this.fetch('/monitoring');
    }

    async getMonitoringProject(id) {
        return this.fetch(`/monitoring/${id}`);
    }

    async addMonitoringReport(id, reportData) {
        return this.fetch(`/monitoring/${id}/report`, {
            method: 'POST',
            body: JSON.stringify(reportData)
        });
    }

    async getMonitoringReports(id) {
        return this.fetch(`/monitoring/${id}/reports`);
    }

    // Reports APIs
    async getAllProposalsReport() {
        return this.fetch('/reports/proposals');
    }

    async getProposalsBySchool() {
        return this.fetch('/reports/proposals/by-school');
    }

    async getProposalsByTheme() {
        return this.fetch('/reports/proposals/by-theme');
    }

    async getProposalsByGrant() {
        return this.fetch('/reports/proposals/by-grant');
    }

    // Settings APIs
    async getAllSettings() {
        return this.fetch('/settings');
    }

    async updateSettings(data) {
        return this.fetch('/settings', {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    }

    // Permissions APIs
    async getAllPermissions() {
        return this.fetch('/permissions');
    }

    async getPermissionsByRole(role) {
        return this.fetch(`/permissions/role/${role}`);
    }

    // Notifications APIs
    async getNotificationTypes() {
        return this.fetch('/notifications/types');
    }

    async getNotificationUsers(typeId) {
        return this.fetch(`/notifications/types/${typeId}/users`);
    }

    async addNotificationUsers(typeId, userIds) {
        return this.fetch(`/notifications/types/${typeId}/users`, {
            method: 'POST',
            body: JSON.stringify({ user_ids: userIds })
        });
    }

    async removeNotificationUser(typeId, userId) {
        return this.fetch(`/notifications/types/${typeId}/users`, {
            method: 'DELETE',
            body: JSON.stringify({ user_id: userId })
        });
    }
}

// Create global instance
window.API = new APIService();