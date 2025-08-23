@extends('layouts.app')

@section('title', isset($prop) ? 'Edit Proposal - UoK ARG Portal' : 'New Proposal - UoK ARG Portal')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
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
                                <button class="nav-link active" data-step="basic"><i
                                        class="bi bi-person-fill me-2"></i>Basic Details</button>
                                <button class="nav-link" data-step="research"><i class="bi bi-search me-2"></i>Research
                                    Info</button>
                                <button class="nav-link" data-step="collaboration"><i
                                        class="bi bi-people-fill me-2"></i>Collaborators</button>
                                <button class="nav-link" data-step="publications"><i
                                        class="bi bi-journal-text me-2"></i>Publications</button>
                                <button class="nav-link" data-step="finance"><i
                                        class="bi bi-currency-dollar me-2"></i>Budget</button>
                                <button class="nav-link" data-step="design"><i
                                        class="bi bi-gear-fill me-2"></i>Design</button>
                                <button class="nav-link" data-step="workplan"><i
                                        class="bi bi-calendar-check me-2"></i>Workplan</button>
                                <button class="nav-link" data-step="submit"><i
                                        class="bi bi-check-circle-fill me-2"></i>Submit</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <!-- Basic Details -->
                    <div class="card step-content" id="basic-content">
                        <div class="card-body">
                            <h5>Basic Details</h5>
                            <form method="POST"
                                action="{{ isset($prop) ? route('route.proposals.updatebasicdetails', ['id' => $prop->proposalid]) : route('route.proposals.post') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" class="form-control" value="{{ auth()->user()->name }}"
                                            readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Grant</label>
                                        <select name="grantnofk" class="form-select" required>
                                            <option value="">Select Grant</option>
                                            @foreach ($grants as $grant)
                                                <option value="{{ $grant->grantid }}"
                                                    {{ isset($prop) && $prop->grantnofk == $grant->grantid ? 'selected' : '' }}>
                                                    {{ $grant->grantname ?? $grant->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Theme</label>
                                        <select name="themefk" class="form-select" required>
                                            <option value="">Select Theme</option>
                                            @foreach ($themes as $theme)
                                                <option value="{{ $theme->themeid }}"
                                                    {{ isset($prop) && $prop->themefk == $theme->themeid ? 'selected' : '' }}>
                                                    {{ $theme->themename }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Department</label>
                                        <select name="departmentfk" class="form-select" required>
                                            <option value="">Select Department</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->depid }}"
                                                    {{ isset($prop) && $prop->departmentidfk == $department->depid ? 'selected' : '' }}>
                                                    {{ $department->shortname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Qualification</label>
                                        <input type="text" name="highestqualification" class="form-control"
                                            value="{{ isset($prop) ? $prop->highqualification : '' }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Office Phone</label>
                                        <input type="tel" name="officephone" class="form-control"
                                            value="{{ isset($prop) ? $prop->officephone : '' }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Cell Phone</label>
                                        <input type="tel" name="cellphone" class="form-control"
                                            value="{{ isset($prop) ? $prop->cellphone : '' }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Fax Number</label>
                                        <input type="tel" name="faxnumber" class="form-control"
                                            value="{{ isset($prop) ? $prop->faxnumber : '' }}" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>

                    <!-- Research Details -->
                    <div class="card step-content d-none" id="research-content">
                        <div class="card-body">
                            <h5>Research Details</h5>
                            <form method="POST"
                                action="{{ isset($prop) ? route('route.proposals.updateresearchdetails', ['id' => $prop->proposalid]) : '' }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Research Title</label>
                                    <input type="text" name="researchtitle" class="form-control"
                                        value="{{ isset($prop) ? $prop->researchtitle : '' }}" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" name="commencingdate" class="form-control"
                                            value="{{ isset($prop) ? $prop->commencingdate : '' }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">End Date</label>
                                        <input type="date" name="terminationdate" class="form-control"
                                            value="{{ isset($prop) ? $prop->terminationdate : '' }}" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Objectives</label>
                                    <textarea name="objectives" class="form-control" rows="3" required>{{ isset($prop) ? $prop->objectives : '' }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Hypothesis</label>
                                    <textarea name="hypothesis" class="form-control" rows="3" required>{{ isset($prop) ? $prop->hypothesis : '' }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Significance</label>
                                    <textarea name="significance" class="form-control" rows="3" required>{{ isset($prop) ? $prop->significance : '' }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ethical Considerations</label>
                                    <textarea name="ethicals" class="form-control" rows="3" required>{{ isset($prop) ? $prop->ethicals : '' }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Expected Output</label>
                                    <textarea name="outputs" class="form-control" rows="3" required>{{ isset($prop) ? $prop->expoutput : '' }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Economic Impact</label>
                                    <textarea name="economicimpact" class="form-control" rows="3" required>{{ isset($prop) ? $prop->socio_impact : '' }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Research Findings</label>
                                    <textarea name="res_findings" class="form-control" rows="3" required>{{ isset($prop) ? $prop->res_findings : '' }}</textarea>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary"
                                        onclick="showStep('basic')">Previous</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Collaborators -->
                    <div class="card step-content d-none" id="collaboration-content">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Collaborators</h5>
                                <button class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#collaboratorModal">
                                    <i class="bi bi-plus"></i> Add Collaborator
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped" id="collaboratorsTable">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Institution</th>
                                            <th>Position</th>
                                            <th>Research Area</th>
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
                                <button type="button" class="btn btn-secondary"
                                    onclick="showStep('research')">Previous</button>
                                <button type="button" class="btn btn-primary"
                                    onclick="showStep('publications')">Save</button>
                            </div>
                        </div>
                    </div>

                    <!-- Publications -->
                    <div class="card step-content d-none" id="publications-content">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5>Publications</h5>
                                <button class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#publicationModal">
                                    <i class="bi bi-plus"></i> Add Publication
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped" id="publicationsTable">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Authors</th>
                                            <th>Publisher</th>
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
                                <button type="button" class="btn btn-secondary"
                                    onclick="showStep('collaboration')">Previous</button>
                                <button type="button" class="btn btn-primary"
                                    onclick="showStep('finance')">Save</button>
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
                                    <label class="form-label">Budget Rule Status</label>
                                    <div class="form-control" id="budgetRuleStatus" style="background-color: #e9ecef;">
                                        <span class="text-success">✓ Compliant (60% rule met)</span>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped" id="budgetTable">
                                    <thead>
                                        <tr>
                                            <th>Item Type</th>
                                            <th>Item</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Total</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-center">
                                            <td colspan="6" class="text-muted">No budget items added yet</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary"
                                    onclick="showStep('publications')">Previous</button>
                                <button type="button" class="btn btn-primary" onclick="showStep('design')">Save</button>
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
                                            <th>Summary</th>
                                            <th>Goal</th>
                                            <th>Purpose</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-center">
                                            <td colspan="4" class="text-muted">No design items added yet</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary"
                                    onclick="showStep('finance')">Previous</button>
                                <button type="button" class="btn btn-primary"
                                    onclick="showStep('workplan')">Save</button>
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
                                            <th>Time Period</th>
                                            <th>Responsible Person</th>
                                            <th>Expected Outcome</th>
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
                                <button type="button" class="btn btn-secondary"
                                    onclick="showStep('design')">Previous</button>
                                <button type="button" class="btn btn-primary" onclick="showStep('submit')">Save</button>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="card step-content d-none" id="submit-content">
                        <div class="card-body">
                            <h5>Submit Proposal</h5>
                            <div class="alert alert-info">
                                <h6>Review Your Proposal</h6>
                                <p>Please review all sections before submitting. Once submitted, you will not be able to
                                    edit the proposal.</p>
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
                                            <p><strong>Total Budget:</strong> KES <span id="totalBudgetDisplay">0</span>
                                            </p>
                                            <p><strong>Budget Rule:</strong> <span id="budgetRuleStatus"
                                                    class="text-success">✓ Compliant</span></p>
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
                                <button type="button" class="btn btn-secondary"
                                    onclick="showStep('workplan')">Previous</button>
                                <button type="button" class="btn btn-success" onclick="submitProposal()">Submit
                                    Proposal</button>
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
                            <input type="text" class="form-control" name="collaboratorname" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Institution</label>
                            <input type="text" class="form-control" name="institution" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Position</label>
                            <input type="text" class="form-control" name="position" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Research Area</label>
                            <input type="text" class="form-control" name="researcharea" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Experience</label>
                            <textarea class="form-control" name="experience" rows="2"
                                placeholder="Brief description of relevant experience" required></textarea>
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
                            <label class="form-label">Publisher</label>
                            <input type="text" class="form-control" name="publisher" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Research Area</label>
                            <input type="text" class="form-control" name="researcharea" required>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Year</label>
                                <input type="number" class="form-control" name="year" min="1900" max="2030"
                                    required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Volume</label>
                                <input type="text" class="form-control" name="volume" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Pages</label>
                                <input type="number" class="form-control" name="pages" required>
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
                            <label class="form-label">Item Type</label>
                            <select class="form-select" name="itemtype" required>
                                <option value="">Select Item Type</option>
                                <option value="Facilities/Equipment">Facilities/Equipment</option>
                                <option value="Consumables">Consumables</option>
                                <option value="Personnel/Subsistence">Personnel/Subsistence</option>
                                <option value="Travel/Other">Travel/Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Item Description</label>
                            <input type="text" class="form-control" name="item" required>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Quantity</label>
                                <input type="number" class="form-control" name="quantity" min="1" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Unit Price</label>
                                <input type="number" class="form-control" name="unitprice" step="0.01" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Total</label>
                                <input type="number" class="form-control" name="total" step="0.01" readonly>
                            </div>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Research Design Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="designForm">
                        <div class="mb-3">
                            <label class="form-label">Summary</label>
                            <textarea class="form-control" name="summary" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Indicators</label>
                            <textarea class="form-control" name="indicators" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Verification</label>
                            <textarea class="form-control" name="verification" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Assumptions</label>
                            <textarea class="form-control" name="assumptions" rows="2" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Goal</label>
                                <input type="text" class="form-control" name="goal" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Purpose</label>
                                <input type="text" class="form-control" name="purpose" required>
                            </div>
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
                        <div class="mb-3">
                            <label class="form-label">Time Period</label>
                            <input type="text" class="form-control" name="time" placeholder="e.g., Month 1-3"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Input Required</label>
                            <textarea class="form-control" name="input" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Facilities Needed</label>
                            <textarea class="form-control" name="facilities" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Responsible Person</label>
                            <input type="text" class="form-control" name="bywhom" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Expected Outcome</label>
                            <textarea class="form-control" name="outcome" rows="2" required></textarea>
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
        let publications = [];
        let budgetItems = [];
        let designItems = [];
        let workplanItems = [];
        let proposalId = {{ isset($prop) ? $prop->proposalid : 'null' }};

        // Initialize data when editing
        document.addEventListener('DOMContentLoaded', function() {
            if (proposalId) {
                loadExistingData();
            }
        });

        // Function to show messages
        function showMessage(message, type = 'success') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
            alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

            const container = document.querySelector('.container-fluid');
            container.insertBefore(alertDiv, container.firstChild);

            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }

        function loadExistingData() {
            // Load collaborators
            fetch(`/api/v1/collaborators?proposalid=${proposalId}`)
                .then(response => response.json())
                .then(data => {
                    collaborators = data;
                    updateCollaboratorsTable();
                })
                .catch(error => {
                    console.error('Error loading collaborators:', error);
                });

            // Load publications
            fetch(`/api/v1/publications?proposalid=${proposalId}`)
                .then(response => response.json())
                .then(data => {
                    publications = data;
                    updatePublicationsTable();
                })
                .catch(error => {
                    console.error('Error loading publications:', error);
                });

            // Load expenditures
            fetch(`/expenditures/fetchall?proposalid=${proposalId}`)
                .then(response => response.json())
                .then(data => {
                    budgetItems = data.map(item => ({
                        itemtype: item.itemtype,
                        item: item.item,
                        quantity: item.quantity,
                        unitprice: item.unitprice,
                        total: item.total,
                        expenditureid: item.expenditureid
                    }));
                    updateBudgetTable();
                    updateTotalBudget();
                })
                .catch(error => {
                    console.error('Error loading expenditures:', error);
                });

            // Load research design
            fetch(`/researchdesign/fetchall?proposalid=${proposalId}`)
                .then(response => response.json())
                .then(data => {
                    designItems = data.map(item => ({
                        summary: item.summary,
                        indicators: item.indicators,
                        verification: item.verification,
                        assumptions: item.assumptions,
                        goal: item.goal,
                        purpose: item.purpose,
                        designid: item.designid
                    }));
                    updateDesignTable();
                })
                .catch(error => {
                    console.error('Error loading research design:', error);
                });

            // Load workplan
            fetch(`/workplan/fetchall?proposalid=${proposalId}`)
                .then(response => response.json())
                .then(data => {
                    workplanItems = data.map(item => ({
                        activity: item.activity,
                        time: item.time,
                        input: item.input,
                        facilities: item.facilities,
                        bywhom: item.bywhom,
                        outcome: item.outcome,
                        workplanid: item.workplanid
                    }));
                    updateWorkplanTable();
                })
                .catch(error => {
                    console.error('Error loading workplan:', error);
                });
        }

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

            if (proposalId) {
                // Save to database
                formData.append('proposalidfk', String(proposalId));
                fetch('/api/v1/collaborators', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            collaborator.collaboratorid = data.id;
                            collaborators.push(collaborator);
                            updateCollaboratorsTable();
                            showMessage(data.message, data.type);
                        } else {
                            showMessage(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        showMessage('Error adding collaborator', 'error');
                    });
            } else {
                collaborators.push(collaborator);
                updateCollaboratorsTable();
            }

            form.reset();
            bootstrap.Modal.getInstance(document.getElementById('collaboratorModal')).hide();
        }

        function updateCollaboratorsTable() {
            const tbody = document.querySelector('#collaboratorsTable tbody');
            if (collaborators.length === 0) {
                tbody.innerHTML =
                    '<tr class="text-center"><td colspan="5" class="text-muted">No collaborators added yet</td></tr>';
                return;
            }

            tbody.innerHTML = collaborators.map((collab, index) => `
        <tr>
            <td>${collab.collaboratorname}</td>
            <td>${collab.institution}</td>
            <td>${collab.position}</td>
            <td>${collab.researcharea}</td>
            <td><button class="btn btn-sm btn-danger" onclick="removeCollaborator(${index})">Remove</button></td>
        </tr>
    `).join('');
        }

        function removeCollaborator(index) {
            const collaborator = collaborators[index];
            if (collaborator.collaboratorid) {
                // Delete from database
                fetch(`/api/v1/collaborators/${collaborator.collaboratorid}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        showMessage(data.message, data.type);
                    })
                    .catch(error => {
                        showMessage('Error removing collaborator', 'error');
                    });
            }
            collaborators.splice(index, 1);
            updateCollaboratorsTable();
        }

        function addPublication() {
            const form = document.getElementById('publicationForm');
            const formData = new FormData(form);
            const publication = Object.fromEntries(formData);

            if (proposalId) {
                // Save to database
                formData.append('proposalidfk', String(proposalId));
                fetch('/api/v1/publications', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            publication.publicationid = data.id;
                            publications.push(publication);
                            updatePublicationsTable();
                            showMessage(data.message, data.type);
                        } else {
                            showMessage(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        showMessage('Error adding publication', 'error');
                    });
            } else {
                publications.push(publication);
                updatePublicationsTable();
            }

            form.reset();
            bootstrap.Modal.getInstance(document.getElementById('publicationModal')).hide();
        }

        function updatePublicationsTable() {
            const tbody = document.querySelector('#publicationsTable tbody');
            if (publications.length === 0) {
                tbody.innerHTML =
                    '<tr class="text-center"><td colspan="5" class="text-muted">No publications added yet</td></tr>';
                return;
            }

            tbody.innerHTML = publications.map((pub, index) => `
        <tr>
            <td>${pub.title}</td>
            <td>${pub.authors}</td>
            <td>${pub.publisher}</td>
            <td>${pub.year}</td>
            <td><button class="btn btn-sm btn-danger" onclick="removePublication(${index})">Remove</button></td>
        </tr>
    `).join('');
        }

        function removePublication(index) {
            const publication = publications[index];
            if (publication.publicationid) {
                // Delete from database
                fetch(`/api/v1/publications/${publication.publicationid}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        showMessage(data.message, data.type);
                    })
                    .catch(error => {
                        showMessage('Error removing publication', 'error');
                    });
            }
            publications.splice(index, 1);
            updatePublicationsTable();
        }

        function addBudgetItem() {
            const form = document.getElementById('budgetForm');
            const formData = new FormData(form);
            const item = Object.fromEntries(formData);

            // Calculate total
            item.total = parseFloat(item.quantity) * parseFloat(item.unitprice);

            if (proposalId) {
                // Save to database
                formData.set('total', item.total);
                formData.append('proposalidfk', proposalId);

                fetch('/expenditures/post', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            item.expenditureid = data.id;
                            budgetItems.push(item);
                            updateBudgetTable();
                            updateTotalBudget();
                            showMessage(data.message, data.type);
                        } else {
                            showMessage(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        showMessage('Error adding budget item', 'error');
                    });
            } else {
                budgetItems.push(item);
                updateBudgetTable();
                updateTotalBudget();
            }

            form.reset();
            bootstrap.Modal.getInstance(document.getElementById('budgetModal')).hide();
        }

        function updateBudgetTable() {
            const tbody = document.querySelector('#budgetTable tbody');
            if (budgetItems.length === 0) {
                tbody.innerHTML =
                    '<tr class="text-center"><td colspan="6" class="text-muted">No budget items added yet</td></tr>';
                return;
            }

            tbody.innerHTML = budgetItems.map((item, index) => `
        <tr>
            <td>${item.itemtype}</td>
            <td>${item.item}</td>
            <td>${item.quantity}</td>
            <td>${parseFloat(item.unitprice).toLocaleString()}</td>
            <td>${parseFloat(item.total).toLocaleString()}</td>
            <td><button class="btn btn-sm btn-danger" onclick="removeBudgetItem(${index})">Remove</button></td>
        </tr>
    `).join('');
        }

        function removeBudgetItem(index) {
            const item = budgetItems[index];
            if (item.expenditureid) {
                // Delete from database
                fetch(`/expenditures/delete/${item.expenditureid}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        showMessage(data.message, data.type);
                    })
                    .catch(error => {
                        showMessage('Error removing budget item', 'error');
                    });
            }
            budgetItems.splice(index, 1);
            updateBudgetTable();
            updateTotalBudget();
        }

        function updateTotalBudget() {


            // Get validation status from API if proposal exists
            if (proposalId) {
                fetch(`/api/v1/proposals/${proposalId}/budget-validation`)
                    .then(response => response.json())
                    .then(data => {
                        const statusDiv = document.getElementById('budgetRuleStatus');
                        if (data.is_compliant) {
                            document.getElementById('totalBudget').value = data.total_budget;
                            statusDiv.innerHTML =
                                `<span class="text-success">✓ ${data.status} (${data.message})</span>`;
                        } else {
                            statusDiv.innerHTML = `<span class="text-danger">✗ ${data.status} (${data.message})</span>`;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching budget validation:', error);
                    });
            }
        }

        // Add event listeners for budget calculation
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.querySelector('input[name="quantity"]');
            const unitPriceInput = document.querySelector('input[name="unitprice"]');
            const totalInput = document.querySelector('input[name="total"]');

            function calculateTotal() {
                const quantity = parseFloat(quantityInput.value) || 0;
                const unitPrice = parseFloat(unitPriceInput.value) || 0;
                totalInput.value = (quantity * unitPrice).toFixed(2);
            }

            if (quantityInput && unitPriceInput && totalInput) {
                quantityInput.addEventListener('input', calculateTotal);
                unitPriceInput.addEventListener('input', calculateTotal);
            }
        });

        function addDesignItem() {
            const form = document.getElementById('designForm');
            const formData = new FormData(form);
            const item = Object.fromEntries(formData);

            if (proposalId) {
                // Save to database
                formData.append('proposalidfk', proposalId);

                fetch('/researchdesign/post', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            item.designid = data.id;
                            designItems.push(item);
                            updateDesignTable();
                            showMessage(data.message, data.type);
                        } else {
                            showMessage(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        showMessage('Error adding design item', 'error');
                    });
            } else {
                designItems.push(item);
                updateDesignTable();
            }

            form.reset();
            bootstrap.Modal.getInstance(document.getElementById('designModal')).hide();
        }

        function updateDesignTable() {
            const tbody = document.querySelector('#designTable tbody');
            if (designItems.length === 0) {
                tbody.innerHTML =
                    '<tr class="text-center"><td colspan="4" class="text-muted">No design items added yet</td></tr>';
                return;
            }

            tbody.innerHTML = designItems.map((item, index) => `
        <tr>
            <td>${item.summary}</td>
            <td>${item.goal}</td>
            <td>${item.purpose}</td>
            <td><button class="btn btn-sm btn-danger" onclick="removeDesignItem(${index})">Remove</button></td>
        </tr>
    `).join('');
        }

        function removeDesignItem(index) {
            const item = designItems[index];
            if (item.designid) {
                // Delete from database
                fetch(`/researchdesign/delete/${item.designid}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        showMessage(data.message, data.type);
                    })
                    .catch(error => {
                        showMessage('Error removing design item', 'error');
                    });
            }
            designItems.splice(index, 1);
            updateDesignTable();
        }

        function addWorkplanItem() {
            const form = document.getElementById('workplanForm');
            const formData = new FormData(form);
            const item = Object.fromEntries(formData);

            if (proposalId) {
                // Save to database
                formData.append('proposalidfk', proposalId);

                fetch('/workplan/post', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            item.workplanid = data.id;
                            workplanItems.push(item);
                            updateWorkplanTable();
                            showMessage(data.message, data.type);
                        } else {
                            showMessage(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        showMessage('Error adding workplan item', 'error');
                    });
            } else {
                workplanItems.push(item);
                updateWorkplanTable();
            }

            form.reset();
            bootstrap.Modal.getInstance(document.getElementById('workplanModal')).hide();
        }

        function updateWorkplanTable() {
            const tbody = document.querySelector('#workplanTable tbody');
            if (workplanItems.length === 0) {
                tbody.innerHTML =
                    '<tr class="text-center"><td colspan="5" class="text-muted">No activities added yet</td></tr>';
                return;
            }

            tbody.innerHTML = workplanItems.map((item, index) => `
        <tr>
            <td>${item.activity}</td>
            <td>${item.time}</td>
            <td>${item.bywhom}</td>
            <td>${item.outcome}</td>
            <td><button class="btn btn-sm btn-danger" onclick="removeWorkplanItem(${index})">Remove</button></td>
        </tr>
    `).join('');
        }

        function removeWorkplanItem(index) {
            const item = workplanItems[index];
            if (item.workplanid) {
                // Delete from database
                fetch(`/workplan/delete/${item.workplanid}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        showMessage(data.message, data.type);
                    })
                    .catch(error => {
                        showMessage('Error removing workplan item', 'error');
                    });
            }
            workplanItems.splice(index, 1);
            updateWorkplanTable();
        }

        function updateSummary() {
            document.getElementById('collaboratorCount').textContent = collaborators.length;
            document.getElementById('budgetCount').textContent = budgetItems.length;
            document.getElementById('designCount').textContent = designItems.length;
            document.getElementById('workplanCount').textContent = workplanItems.length;

            const total = budgetItems.reduce((sum, item) => sum + parseFloat(item.total || 0), 0);
            document.getElementById('totalBudgetDisplay').textContent = total.toLocaleString();
        }

        async function submitProposal() {
            const declaration = document.getElementById('declaration').checked;
            if (!declaration) {
                alert('Please accept the declaration before submitting.');
                return;
            }

            // Validate 60% rule using API
            if (proposalId) {
                const response = await fetch(`/api/v1/proposals/${proposalId}/budget-validation`);
                const budgetData = await response.json();

                if (!budgetData.is_compliant) {
                    alert(
                        'Cannot submit proposal: Facilities/Equipment and Consumables must be at least 60% of total budget. Please adjust your budget allocation.'
                    );
                    return;
                }
            }

            if (confirm(
                    'Are you sure you want to submit this proposal? You will not be able to edit it after submission.'
                )) {
                if (proposalId) {
                    fetch(`/proposals/submit/${proposalId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.type === 'success') {
                                alert(data.message);
                                window.location.href = '{{ route('pages.proposals.index') }}';
                            } else {
                                alert(data.message);
                            }
                        });
                } else {
                    alert('Please save the proposal first.');
                }
            }
        }
    </script>
@endsection
