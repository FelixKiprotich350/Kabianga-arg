/**
 * Page-specific data loaders for Kabianga ARG Portal
 * Each function loads data for specific pages using the API service
 */

// Dashboard Page Loader
async function loadDashboardData() {
    try {
        const statsContainer = document.getElementById('dashboard-stats');
        const chartContainer = document.getElementById('dashboard-chart');
        const activityContainer = document.getElementById('recent-activity');
        
        if (statsContainer) {
            statsContainer.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Loading statistics...</p></div>';
        }
        
        const [stats, chartData, recentActivity] = await Promise.all([
            API.getDashboardStats(),
            API.getDashboardChart(),
            API.getRecentActivity()
        ]);

        if (stats && stats.success) {
            renderDashboardStats(stats.data);
        }
        if (chartData) {
            renderDashboardChart(chartData);
        }
        if (recentActivity && recentActivity.success) {
            renderRecentActivity(recentActivity.data);
        }
        
    } catch (error) {
        console.error('Dashboard load error:', error);
        const statsContainer = document.getElementById('dashboard-stats');
        if (statsContainer) {
            statsContainer.innerHTML = '<div class="alert alert-danger">Failed to load dashboard data</div>';
        }
    }
}

// Proposals Page Loader
async function loadProposalsData(type = 'all') {
    try {
        const loadingState = document.getElementById('loadingState');
        const emptyState = document.getElementById('emptyState');
        const tableBody = document.getElementById('applicationsTableBody');
        
        if (loadingState) loadingState.style.display = 'block';
        if (emptyState) emptyState.style.display = 'none';
        if (tableBody) tableBody.innerHTML = '';
        
        let proposals;
        switch(type) {
            case 'my':
                proposals = await API.getMyProposals();
                break;
            default:
                proposals = await API.getAllProposals();
        }

        if (loadingState) loadingState.style.display = 'none';
        
        if (proposals && proposals.success && proposals.data) {
            if (proposals.data.length === 0) {
                if (emptyState) emptyState.style.display = 'block';
            } else {
                renderProposalsList(proposals.data);
            }
        } else {
            if (emptyState) emptyState.style.display = 'block';
        }
        
    } catch (error) {
        console.error('Proposals load error:', error);
        const loadingState = document.getElementById('loadingState');
        const emptyState = document.getElementById('emptyState');
        if (loadingState) loadingState.style.display = 'none';
        if (emptyState) {
            emptyState.innerHTML = '<div class="alert alert-danger">Failed to load proposals</div>';
            emptyState.style.display = 'block';
        }
    }
}

// Single Proposal Page Loader
async function loadProposalDetails(proposalId) {
    try {
        ARGPortal.showLoading(document.getElementById('proposal-details'));
        
        const [proposal, collaborators, publications, expenditures, workplans, researchDesign] = await Promise.all([
            API.getProposal(proposalId),
            API.getProposalCollaborators(proposalId),
            API.getProposalPublications(proposalId),
            API.getProposalExpenditures(proposalId),
            API.getProposalWorkplans(proposalId),
            API.getProposalResearchDesign(proposalId)
        ]);

        renderProposalDetails(proposal);
        renderCollaborators(collaborators);
        renderPublications(publications);
        renderExpenditures(expenditures);
        renderWorkplans(workplans);
        renderResearchDesign(researchDesign);
        
    } catch (error) {
        ARGPortal.showError('Failed to load proposal details');
        console.error('Proposal details load error:', error);
    }
}

// Projects Page Loader
async function loadProjectsData(type = 'all') {
    try {
        ARGPortal.showLoading(document.getElementById('projects-content'));
        
        let projects;
        switch(type) {
            case 'my':
                projects = await API.getMyProjects();
                break;
            case 'active':
                projects = await API.getActiveProjects();
                break;
            case 'my-active':
                projects = await API.getMyActiveProjects();
                break;
            default:
                projects = await API.getAllProjects();
        }

        renderProjectsList(projects);
        
    } catch (error) {
        ARGPortal.showError('Failed to load projects');
        console.error('Projects load error:', error);
    }
}

// Single Project Page Loader
async function loadProjectDetails(projectId) {
    try {
        ARGPortal.showLoading(document.getElementById('project-details'));
        
        const [project, progress, funding] = await Promise.all([
            API.getProject(projectId),
            API.getProjectProgress(projectId),
            API.getProjectFunding(projectId)
        ]);

        renderProjectDetails(project);
        renderProjectProgress(progress);
        renderProjectFunding(funding);
        
    } catch (error) {
        ARGPortal.showError('Failed to load project details');
        console.error('Project details load error:', error);
    }
}

// Users Management Page Loader
async function loadUsersData() {
    try {
        ARGPortal.showLoading(document.getElementById('users-content'));
        
        const users = await API.getAllUsers();
        renderUsersList(users);
        
    } catch (error) {
        ARGPortal.showError('Failed to load users');
        console.error('Users load error:', error);
    }
}

// Schools Page Loader
async function loadSchoolsData() {
    try {
        ARGPortal.showLoading(document.getElementById('schools-content'));
        
        const schools = await API.getAllSchools();
        renderSchoolsList(schools);
        
    } catch (error) {
        ARGPortal.showError('Failed to load schools');
        console.error('Schools load error:', error);
    }
}

// Departments Page Loader
async function loadDepartmentsData() {
    try {
        ARGPortal.showLoading(document.getElementById('departments-content'));
        
        const departments = await API.getAllDepartments();
        renderDepartmentsList(departments);
        
    } catch (error) {
        ARGPortal.showError('Failed to load departments');
        console.error('Departments load error:', error);
    }
}

// Grants Page Loader
async function loadGrantsData() {
    try {
        ARGPortal.showLoading(document.getElementById('grants-content'));
        
        const grants = await API.getAllGrants();
        renderGrantsList(grants);
        
    } catch (error) {
        ARGPortal.showError('Failed to load grants');
        console.error('Grants load error:', error);
    }
}

// Reports Page Loader
async function loadReportsData() {
    try {
        ARGPortal.showLoading(document.getElementById('reports-content'));
        
        const [allProposals, bySchool, byTheme, byGrant] = await Promise.all([
            API.getAllProposalsReport(),
            API.getProposalsBySchool(),
            API.getProposalsByTheme(),
            API.getProposalsByGrant()
        ]);

        renderReportsData({
            allProposals,
            bySchool,
            byTheme,
            byGrant
        });
        
    } catch (error) {
        ARGPortal.showError('Failed to load reports');
        console.error('Reports load error:', error);
    }
}

// Monitoring Page Loader
async function loadMonitoringData() {
    try {
        ARGPortal.showLoading(document.getElementById('monitoring-content'));
        
        const monitoringData = await API.getMonitoringHome();
        renderMonitoringHome(monitoringData);
        
    } catch (error) {
        ARGPortal.showError('Failed to load monitoring data');
        console.error('Monitoring load error:', error);
    }
}

// Settings Page Loader
async function loadSettingsData() {
    try {
        ARGPortal.showLoading(document.getElementById('settings-content'));
        
        const [settings, themes, permissions, financialYears] = await Promise.all([
            API.getAllSettings(),
            API.getAllThemes(),
            API.getAllPermissions(),
            API.getAllFinancialYears()
        ]);

        renderSettingsData({
            settings,
            themes,
            permissions,
            financialYears
        });
        
    } catch (error) {
        ARGPortal.showError('Failed to load settings');
        console.error('Settings load error:', error);
    }
}

// Search functionality
async function performSearch(query, type) {
    try {
        let results;
        
        switch(type) {
            case 'proposals':
                results = await API.getAllProposals(query);
                break;
            case 'projects':
                results = await API.getAllProjects(query);
                break;
            case 'users':
                results = await API.getAllUsers(query);
                break;
            case 'schools':
                results = await API.getAllSchools(query);
                break;
            case 'departments':
                results = await API.getAllDepartments(query);
                break;
            case 'grants':
                results = await API.getAllGrants(query);
                break;
            default:
                throw new Error('Invalid search type');
        }

        return results;
        
    } catch (error) {
        ARGPortal.showError('Search failed');
        console.error('Search error:', error);
        return [];
    }
}

// Users Data Loader
async function loadUsersData() {
    try {
        ARGPortal.showLoading(document.getElementById('usersTableBody'));
        
        const [users, departments] = await Promise.all([
            API.getAllUsers(),
            API.getAllDepartments()
        ]);

        DataRenderers.renderUsersList(users);
        populateDepartmentSelect(departments);
        
    } catch (error) {
        ARGPortal.showError('Failed to load users');
        console.error('Users load error:', error);
    }
}

function populateDepartmentSelect(departments) {
    const select = document.querySelector('select[name="departmentidfk"]');
    if (select) {
        departments.forEach(dept => {
            const option = document.createElement('option');
            option.value = dept.id || dept.departmentid;
            option.textContent = dept.name || dept.departmentname;
            select.appendChild(option);
        });
    }
}

// Auto-load data based on current page
document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname;
    
    // Determine which data to load based on current page
    if (currentPath.includes('/dashboard') || currentPath.includes('/home')) {
        loadDashboardData();
    } else if (currentPath.includes('/proposals/allproposals')) {
        loadProposalsData('all');
    } else if (currentPath.includes('/proposals/myapplications')) {
        loadProposalsData('my');
    } else if (currentPath.includes('/proposals/view/')) {
        const proposalId = currentPath.split('/').pop();
        loadProposalDetails(proposalId);
    } else if (currentPath.includes('/projects/allprojects')) {
        loadProjectsData('all');
    } else if (currentPath.includes('/projects/myprojects')) {
        loadProjectsData('my');
    } else if (currentPath.includes('/projects/') && currentPath.includes('/view/')) {
        const projectId = currentPath.split('/').pop();
        loadProjectDetails(projectId);
    } else if (currentPath.includes('/users/manage')) {
        loadUsersData();
    } else if (currentPath.includes('/schools')) {
        loadSchoolsData();
    } else if (currentPath.includes('/departments')) {
        loadDepartmentsData();
    } else if (currentPath.includes('/grants')) {
        loadGrantsData();
    } else if (currentPath.includes('/reports')) {
        loadReportsData();
    } else if (currentPath.includes('/monitoring')) {
        loadMonitoringData();
    } else if (currentPath.includes('/settings')) {
        loadSettingsData();
    }
});

// Export functions for global use
window.PageLoaders = {
    loadDashboardData,
    loadProposalsData,
    loadProposalDetails,
    loadProjectsData,
    loadProjectDetails,
    loadUsersData,
    loadSchoolsData,
    loadDepartmentsData,
    loadGrantsData,
    loadReportsData,
    loadMonitoringData,
    loadSettingsData,
    performSearch,
    populateDepartmentSelect
};