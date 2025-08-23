@extends('layouts.app')

@section('title', isset($prop) ? 'Edit Proposal - UoK ARG Portal' : 'New Proposal - UoK ARG Portal')

@section('content')
<div class="container-fluid">
    @if (isset($grants))
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>{{ isset($prop) ? 'Edit Proposal' : 'New Proposal' }}</h2>
                <p class="text-muted">{{ isset($prop) ? $prop->proposalcode : 'Create a new research proposal' }}</p>
            </div>
            <a href="{{ route('pages.proposals.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>

        <div class="row">
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <h6>Progress</h6>
                        <div class="nav flex-column nav-pills">
                            <button class="nav-link active" data-step="basic"><i class="bi bi-person-fill me-2"></i>Basic Details</button>
                            <button class="nav-link" data-step="research"><i class="bi bi-search me-2"></i>Research Info</button>
                            <button class="nav-link" data-step="collaboration"><i class="bi bi-people-fill me-2"></i>Collaborators</button>
                            <button class="nav-link" data-step="publications"><i class="bi bi-journal-text me-2"></i>Publications</button>
                            <button class="nav-link" data-step="finance"><i class="bi bi-currency-dollar me-2"></i>Budget</button>
                            <button class="nav-link" data-step="design"><i class="bi bi-gear-fill me-2"></i>Design</button>
                            <button class="nav-link" data-step="workplan"><i class="bi bi-calendar-check me-2"></i>Workplan</button>
                            <button class="nav-link" data-step="submit"><i class="bi bi-check-circle-fill me-2"></i>Submit</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <!-- Basic Details -->
                <div class="card step-content" id="basic-content">
                    <div class="card-body">
                        <h5>Basic Details</h5>
                        <form method="POST" action="{{ isset($prop) ? route('route.proposals.updatebasicdetails', ['id' => $prop->proposalid]) : route('route.proposals.post') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Grant</label>
                                    <select name="grantnofk" class="form-select" required>
                                        <option value="">Select Grant</option>
                                        @foreach ($grants as $grant)
                                            <option value="{{ $grant->grantid }}">{{ $grant->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Theme</label>
                                    <select name="themefk" class="form-select" required>
                                        <option value="">Select Theme</option>
                                        @foreach ($themes as $theme)
                                            <option value="{{ $theme->themeid }}">{{ $theme->themename }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Department</label>
                                    <select name="departmentfk" class="form-select" required>
                                        <option value="">Select Department</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->depid }}">{{ $department->shortname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Qualification</label>
                                    <input type="text" name="highestqualification" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="tel" name="cellphone" class="form-control" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Save & Continue</button>
                        </form>
                    </div>
                </div>

                <!-- Research Details -->
                <div class="card step-content d-none" id="research-content">
                    <div class="card-body">
                        <h5>Research Details</h5>
                        <form method="POST" action="{{ isset($prop) ? route('route.proposals.updateresearchdetails', ['id' => $prop->proposalid]) : '' }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Research Title</label>
                                <input type="text" name="researchtitle" class="form-control" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="commencingdate" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" name="terminationdate" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Objectives</label>
                                <textarea name="objectives" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Hypothesis</label>
                                <textarea name="hypothesis" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" onclick="showStep('basic')">Previous</button>
                                <button type="submit" class="btn btn-primary">Save & Continue</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Collaborators -->
                <div class="card step-content d-none" id="collaboration-content">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Collaborators</h5>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#collaboratorModal">
                                <i class="bi bi-plus"></i> Add Collaborator
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped" id="collaboratorsTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Institution</th>
                                        <th>Role</th>
                                        <th>Email</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-center">
                                        <td colspan="5" class="text-muted">No collaborators added yet</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" onclick="showStep('research')">Previous</button>
                            <button type="button" class="btn btn-primary" onclick="showStep('publications')">Continue</button>
                        </div>
                    </div>
                </div>

                <!-- Publications -->
                <div class="card step-content d-none" id="publications-content">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Publications</h5>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#publicationModal">
                                <i class="bi bi-plus"></i> Add Publication
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped" id="publicationsTable">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Authors</th>
                                        <th>Journal</th>
                                        <th>Year</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-center">
                                        <td colspan="5" class="text-muted">No publications added yet</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" onclick="showStep('collaboration')">Previous</button>
                            <button type="button" class="btn btn-primary" onclick="showStep('finance')">Continue</button>
                        </div>
                    </div>
                </div>

                <!-- Budget -->
                <div class="card step-content d-none" id="finance-content">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Budget</h5>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#budgetModal">
                                <i class="bi bi-plus"></i> Add Budget Item
                            </button>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Total Budget</label>
                                <input type="number" id="totalBudget" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Currency</label>
                                <select class="form-select">
                                    <option value="KES">KES</option>
                                    <option value="USD">USD</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped" id="budgetTable">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-center">
                                        <td colspan="4" class="text-muted">No budget items added yet</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" onclick="showStep('publications')">Previous</button>
                            <button type="button" class="btn btn-primary" onclick="showStep('design')">Continue</button>
                        </div>
                    </div>
                </div>

                <!-- Research Design -->
                <div class="card step-content d-none" id="design-content">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Research Design</h5>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#designModal">
                                <i class="bi bi-plus"></i> Add Design Item
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped" id="designTable">
                                <thead>
                                    <tr>
                                        <th>Component</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-center">
                                        <td colspan="3" class="text-muted">No design components added yet</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" onclick="showStep('finance')">Previous</button>
                            <button type="button" class="btn btn-primary" onclick="showStep('workplan')">Continue</button>
                        </div>
                    </div>
                </div>

                <!-- Workplan -->
                <div class="card step-content d-none" id="workplan-content">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Work Plan</h5>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#workplanModal">
                                <i class="bi bi-plus"></i> Add Activity
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped" id="workplanTable">
                                <thead>
                                    <tr>
                                        <th>Activity</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Duration</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-center">
                                        <td colspan="5" class="text-muted">No activities added yet</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" onclick="showStep('design')">Previous</button>
                            <button type="button" class="btn btn-primary" onclick="showStep('submit')">Continue</button>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="card step-content d-none" id="submit-content">
                    <div class="card-body">
                        <h5>Submit Proposal</h5>
                        <div class="alert alert-info">
                            <h6>Review Your Proposal</h6>
                            <p>Please review all sections before submitting. Once submitted, you will not be able to edit the proposal.</p>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6>Summary</h6>
                                        <p><strong>Collaborators:</strong> <span id="collaboratorCount">0</span></p>
                                        <p><strong>Budget Items:</strong> <span id="budgetCount">0</span></p>
                                        <p><strong>Design Components:</strong> <span id="designCount">0</span></p>
                                        <p><strong>Activities:</strong> <span id="workplanCount">0</span></p>
                                        <p><strong>Total Budget:</strong> KES <span id="totalBudgetDisplay">0</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6>Declaration</h6>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="declaration">
                                            <label class="form-check-label" for="declaration">
                                                I declare that the information provided is accurate and complete.
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-secondary" onclick="showStep('workplan')">Previous</button>
                            <button type="button" class="btn btn-success" onclick="submitProposal()">Submit Proposal</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            <h4>No Active Grants</h4>
            <p>There are currently no active grants available for proposal submission.</p>
        </div>
    @endif
</div>

<!-- Modals -->
<!-- Collaborator Modal -->
<div class="modal fade" id="collaboratorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Collaborator</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="collaboratorForm">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Institution</label>
                        <input type="text" class="form-control" name="institution" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-select" name="role" required>
                            <option value="">Select Role</option>
                            <option value="Co-Investigator">Co-Investigator</option>
                            <option value="Research Assistant">Research Assistant</option>
                            <option value="Consultant">Consultant</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="addCollaborator()">Add</button>
            </div>
        </div>
    </div>
</div>

<!-- Publication Modal -->
<div class="modal fade" id="publicationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Publication</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="publicationForm">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Authors</label>
                        <input type="text" class="form-control" name="authors" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Journal/Conference</label>
                        <input type="text" class="form-control" name="journal" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Year</label>
                            <input type="number" class="form-control" name="year" min="1900" max="2030" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Type</label>
                            <select class="form-select" name="type" required>
                                <option value="">Select Type</option>
                                <option value="Journal Article">Journal Article</option>
                                <option value="Conference Paper">Conference Paper</option>
                                <option value="Book Chapter">Book Chapter</option>
                                <option value="Book">Book</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="addPublication()">Add</button>
            </div>
        </div>
    </div>
</div>

<!-- Budget Modal -->
<div class="modal fade" id="budgetModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Budget Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="budgetForm">
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-select" name="category" required>
                            <option value="">Select Category</option>
                            <option value="Personnel">Personnel</option>
                            <option value="Equipment">Equipment</option>
                            <option value="Supplies">Supplies</option>
                            <option value="Travel">Travel</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" class="form-control" name="description" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" class="form-control" name="amount" step="0.01" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="addBudgetItem()">Add</button>
            </div>
        </div>
    </div>
</div>

<!-- Design Modal -->
<div class="modal fade" id="designModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Design Component</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="designForm">
                    <div class="mb-3">
                        <label class="form-label">Component</label>
                        <select class="form-select" name="component" required>
                            <option value="">Select Component</option>
                            <option value="Methodology">Methodology</option>
                            <option value="Data Collection">Data Collection</option>
                            <option value="Analysis Plan">Analysis Plan</option>
                            <option value="Ethics">Ethics</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="addDesignItem()">Add</button>
            </div>
        </div>
    </div>
</div>

<!-- Workplan Modal -->
<div class="modal fade" id="workplanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="workplanForm">
                    <div class="mb-3">
                        <label class="form-label">Activity</label>
                        <input type="text" class="form-control" name="activity" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" name="end_date" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="2" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="addWorkplanItem()">Add</button>
            </div>
        </div>
    </div>
</div>

<script>
let collaborators = [];
let budgetItems = [];
let designItems = [];
let workplanItems = [];

function showStep(step) {
    document.querySelectorAll('.step-content').forEach(content => {
        content.classList.add('d-none');
    });
    
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });
    
    document.getElementById(step + '-content').classList.remove('d-none');
    document.querySelector(`[data-step="${step}"]`).classList.add('active');
    
    if (step === 'submit') {
        updateSummary();
    }
}

document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function() {
        const step = this.getAttribute('data-step');
        showStep(step);
    });
});

function addCollaborator() {
    const form = document.getElementById('collaboratorForm');
    const formData = new FormData(form);
    const collaborator = Object.fromEntries(formData);
    
    collaborators.push(collaborator);
    updateCollaboratorsTable();
    form.reset();
    bootstrap.Modal.getInstance(document.getElementById('collaboratorModal')).hide();
}

function updateCollaboratorsTable() {
    const tbody = document.querySelector('#collaboratorsTable tbody');
    if (collaborators.length === 0) {
        tbody.innerHTML = '<tr class="text-center"><td colspan="5" class="text-muted">No collaborators added yet</td></tr>';
        return;
    }
    
    tbody.innerHTML = collaborators.map((collab, index) => `
        <tr>
            <td>${collab.name}</td>
            <td>${collab.institution}</td>
            <td>${collab.role}</td>
            <td>${collab.email}</td>
            <td><button class="btn btn-sm btn-danger" onclick="removeCollaborator(${index})">Remove</button></td>
        </tr>
    `).join('');
}

function removeCollaborator(index) {
    collaborators.splice(index, 1);
    updateCollaboratorsTable();
}

function addBudgetItem() {
    const form = document.getElementById('budgetForm');
    const formData = new FormData(form);
    const item = Object.fromEntries(formData);
    
    budgetItems.push(item);
    updateBudgetTable();
    updateTotalBudget();
    form.reset();
    bootstrap.Modal.getInstance(document.getElementById('budgetModal')).hide();
}

function updateBudgetTable() {
    const tbody = document.querySelector('#budgetTable tbody');
    if (budgetItems.length === 0) {
        tbody.innerHTML = '<tr class="text-center"><td colspan="4" class="text-muted">No budget items added yet</td></tr>';
        return;
    }
    
    tbody.innerHTML = budgetItems.map((item, index) => `
        <tr>
            <td>${item.category}</td>
            <td>${item.description}</td>
            <td>${parseFloat(item.amount).toLocaleString()}</td>
            <td><button class="btn btn-sm btn-danger" onclick="removeBudgetItem(${index})">Remove</button></td>
        </tr>
    `).join('');
}

function removeBudgetItem(index) {
    budgetItems.splice(index, 1);
    updateBudgetTable();
    updateTotalBudget();
}

function updateTotalBudget() {
    const total = budgetItems.reduce((sum, item) => sum + parseFloat(item.amount || 0), 0);
    document.getElementById('totalBudget').value = total.toLocaleString();
}

function addDesignItem() {
    const form = document.getElementById('designForm');
    const formData = new FormData(form);
    const item = Object.fromEntries(formData);
    
    designItems.push(item);
    updateDesignTable();
    form.reset();
    bootstrap.Modal.getInstance(document.getElementById('designModal')).hide();
}

function updateDesignTable() {
    const tbody = document.querySelector('#designTable tbody');
    if (designItems.length === 0) {
        tbody.innerHTML = '<tr class="text-center"><td colspan="3" class="text-muted">No design components added yet</td></tr>';
        return;
    }
    
    tbody.innerHTML = designItems.map((item, index) => `
        <tr>
            <td>${item.component}</td>
            <td>${item.description}</td>
            <td><button class="btn btn-sm btn-danger" onclick="removeDesignItem(${index})">Remove</button></td>
        </tr>
    `).join('');
}

function removeDesignItem(index) {
    designItems.splice(index, 1);
    updateDesignTable();
}

function addWorkplanItem() {
    const form = document.getElementById('workplanForm');
    const formData = new FormData(form);
    const item = Object.fromEntries(formData);
    
    const startDate = new Date(item.start_date);
    const endDate = new Date(item.end_date);
    const duration = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
    item.duration = duration;
    
    workplanItems.push(item);
    updateWorkplanTable();
    form.reset();
    bootstrap.Modal.getInstance(document.getElementById('workplanModal')).hide();
}

function updateWorkplanTable() {
    const tbody = document.querySelector('#workplanTable tbody');
    if (workplanItems.length === 0) {
        tbody.innerHTML = '<tr class="text-center"><td colspan="5" class="text-muted">No activities added yet</td></tr>';
        return;
    }
    
    tbody.innerHTML = workplanItems.map((item, index) => `
        <tr>
            <td>${item.activity}</td>
            <td>${item.start_date}</td>
            <td>${item.end_date}</td>
            <td>${item.duration} days</td>
            <td><button class="btn btn-sm btn-danger" onclick="removeWorkplanItem(${index})">Remove</button></td>
        </tr>
    `).join('');
}

function removeWorkplanItem(index) {
    workplanItems.splice(index, 1);
    updateWorkplanTable();
}

function updateSummary() {
    document.getElementById('collaboratorCount').textContent = collaborators.length;
    document.getElementById('budgetCount').textContent = budgetItems.length;
    document.getElementById('designCount').textContent = designItems.length;
    document.getElementById('workplanCount').textContent = workplanItems.length;
    
    const total = budgetItems.reduce((sum, item) => sum + parseFloat(item.amount || 0), 0);
    document.getElementById('totalBudgetDisplay').textContent = total.toLocaleString();
}

function submitProposal() {
    const declaration = document.getElementById('declaration').checked;
    if (!declaration) {
        alert('Please accept the declaration before submitting.');
        return;
    }
    
    if (confirm('Are you sure you want to submit this proposal? You will not be able to edit it after submission.')) {
        alert('Proposal submitted successfully!');
        window.location.href = '{{ route("pages.proposals.index") }}';
    }
}
</script>
@endsection