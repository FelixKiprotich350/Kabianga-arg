/**
 * API Client for Kabianga ARG System
 * All data operations should use these API endpoints
 */

class ApiClient {
    constructor() {
        this.baseUrl = '/api/v1';
        this.token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    }

    async request(method, endpoint, data = null) {
        const config = {
            method: method.toUpperCase(),
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': this.token
            }
        };

        if (data && (method.toUpperCase() === 'POST' || method.toUpperCase() === 'PUT' || method.toUpperCase() === 'PATCH')) {
            config.body = JSON.stringify(data);
        }

        try {
            const response = await fetch(`${this.baseUrl}${endpoint}`, config);
            const result = await response.json();
            
            if (!response.ok) {
                throw new Error(result.message || 'Request failed');
            }
            
            return result;
        } catch (error) {
            ARGPortal.showError('API Request failed: ' + error.message);
            throw error;
        }
    }

    // Proposal API methods
    async createProposal(proposalData) {
        return this.request('POST', '/proposals', proposalData);
    }

    async updateProposalBasics(id, data) {
        return this.request('PUT', `/proposals/${id}/basic`, data);
    }

    async updateProposalResearch(id, data) {
        return this.request('PUT', `/proposals/${id}/research`, data);
    }

    async submitProposal(id) {
        return this.request('POST', `/proposals/${id}/submit`);
    }

    async getMyProposals() {
        return this.request('GET', '/proposals/my');
    }

    async getAllProposals() {
        return this.request('GET', '/proposals');
    }

    // User API methods
    async getUsers() {
        return this.request('GET', '/users');
    }

    async createUser(userData) {
        return this.request('POST', '/users', userData);
    }

    async updateUser(id, userData) {
        return this.request('PUT', `/users/${id}`, userData);
    }

    // School API methods
    async getSchools() {
        return this.request('GET', '/schools');
    }

    async createSchool(schoolData) {
        return this.request('POST', '/schools', schoolData);
    }

    // Department API methods
    async getDepartments() {
        return this.request('GET', '/departments');
    }

    async createDepartment(deptData) {
        return this.request('POST', '/departments', deptData);
    }

    // Grant API methods
    async getGrants() {
        return this.request('GET', '/grants');
    }

    async createGrant(grantData) {
        return this.request('POST', '/grants', grantData);
    }

    // Dashboard API methods
    async getDashboardStats() {
        return this.request('GET', '/dashboard/stats');
    }

    async getDashboardCharts() {
        return this.request('GET', '/dashboard/charts');
    }

    async getDashboardActivity() {
        return this.request('GET', '/dashboard/activity');
    }

    // Financial Years API methods
    async getFinancialYears() {
        return this.request('GET', '/financial-years');
    }

    async createFinancialYear(yearData) {
        return this.request('POST', '/financial-years', yearData);
    }

    // Settings API methods
    async getSettings() {
        return this.request('GET', '/settings');
    }

    async updateSettings(settingsData) {
        return this.request('PUT', '/settings', settingsData);
    }

    async setCurrentGrant(grantId) {
        return this.request('POST', '/settings/current-grant', { current_grantno: grantId });
    }

    async setCurrentYear(yearId) {
        return this.request('POST', '/settings/current-year', { current_fin_year: yearId });
    }

    // Research Themes API methods
    async getThemes() {
        return this.request('GET', '/themes');
    }

    async createTheme(themeData) {
        return this.request('POST', '/themes', themeData);
    }

    async updateTheme(id, themeData) {
        return this.request('PUT', `/themes/${id}`, themeData);
    }

    async deleteTheme(id) {
        return this.request('DELETE', `/themes/${id}`);
    }

    // Projects API methods
    async getProjects() {
        return this.request('GET', '/projects');
    }

    async getMyProjects() {
        return this.request('GET', '/projects/my');
    }

    async getActiveProjects() {
        return this.request('GET', '/projects/active');
    }

    async getMyActiveProjects() {
        return this.request('GET', '/projects/my-active');
    }

    // Collaborators API methods
    async getCollaborators() {
        return this.request('GET', '/collaborators');
    }

    async createCollaborator(collaboratorData) {
        return this.request('POST', '/collaborators', collaboratorData);
    }

    async updateCollaborator(id, collaboratorData) {
        return this.request('PUT', `/collaborators/${id}`, collaboratorData);
    }

    async deleteCollaborator(id) {
        return this.request('DELETE', `/collaborators/${id}`);
    }

    // Publications API methods
    async getPublications() {
        return this.request('GET', '/publications');
    }

    async createPublication(publicationData) {
        return this.request('POST', '/publications', publicationData);
    }

    async updatePublication(id, publicationData) {
        return this.request('PUT', `/publications/${id}`, publicationData);
    }

    async deletePublication(id) {
        return this.request('DELETE', `/publications/${id}`);
    }

    // Expenditures API methods
    async getExpenditures() {
        return this.request('GET', '/expenditures');
    }

    async createExpenditure(expenditureData) {
        return this.request('POST', '/expenditures', expenditureData);
    }

    async updateExpenditure(id, expenditureData) {
        return this.request('PUT', `/expenditures/${id}`, expenditureData);
    }

    async deleteExpenditure(id) {
        return this.request('DELETE', `/expenditures/${id}`);
    }

    // Reports API methods
    async getAllProposalsReport() {
        return this.request('GET', '/reports/proposals');
    }

    async getProposalsBySchoolReport() {
        return this.request('GET', '/reports/proposals/by-school');
    }

    async getProposalsByThemeReport() {
        return this.request('GET', '/reports/proposals/by-theme');
    }

    async getProposalsByGrantReport() {
        return this.request('GET', '/reports/proposals/by-grant');
    }
}

// Initialize global API client
window.apiClient = new ApiClient();

// Example usage for forms
function handleProposalForm(formElement) {
    formElement.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(formElement);
        const data = Object.fromEntries(formData.entries());
        
        try {
            const result = await window.apiClient.createProposal(data);
            
            if (result.success) {
                // Show success message
                showNotification('success', result.message);
                // Redirect to edit page
                window.location.href = `/proposals/edit/${result.proposal_id}`;
            }
        } catch (error) {
            showNotification('error', error.message);
        }
    });
}

function showNotification(type, message) {
    if (type === 'success') {
        ARGPortal.showSuccess(message);
    } else if (type === 'error') {
        ARGPortal.showError(message);
    } else if (type === 'warning') {
        ARGPortal.showWarning(message);
    } else {
        ARGPortal.showInfo(message);
    }
}