<?php $__env->startSection('title', 'Research Themes'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Research Themes</h4>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#themeModal">
                            <i class="fa fa-plus"></i> Add Theme
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="themesTable">
                                <thead>
                                    <tr>
                                        <th>Theme Name</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-center">
                                        <td colspan="4" class="text-muted">Loading themes...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Theme Modal -->
    <div class="modal fade" id="themeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="themeModalTitle">Add Research Theme</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="themeForm">
                    <div class="modal-body">
                        <input type="hidden" id="themeId" name="themeid">
                        <div class="mb-3">
                            <label for="themename" class="form-label">Theme Name</label>
                            <input type="text" class="form-control" id="themename" name="themename" required>
                        </div>
                        <div class="mb-3">
                            <label for="themedescription" class="form-label">Description</label>
                            <textarea class="form-control" id="themedescription" name="themedescription" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="applicablestatus" class="form-label">Status</label>
                            <select class="form-control" id="applicablestatus" name="applicablestatus" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Theme</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let themes = [];
        let editingThemeId = null;

        document.addEventListener('DOMContentLoaded', function() {
            loadThemes();

            document.getElementById('themeForm').addEventListener('submit', function(e) {
                e.preventDefault();
                saveTheme();
            });
        });

        function loadThemes() {
            fetch('/api/v1/themes')
                .then(response => response.json())
                .then(data => {
                    themes = data.data;
                    updateThemesTable();
                })
                .catch(error => {
                    console.error('Error loading themes:', error);
                    ARGPortal.showError('Error loading themes');
                });
        }

        function updateThemesTable() {
            const tbody = document.querySelector('#themesTable tbody');

            if (themes.length === 0) {
                tbody.innerHTML = '<tr class="text-center"><td colspan="4" class="text-muted">No themes found</td></tr>';
                return;
            }

            tbody.innerHTML = themes.map(theme => `
        <tr>
            <td>${theme.themename}</td>
            <td>${theme.themedescription}</td>
            <td>
                <span class="badge ${theme.applicablestatus === 'Active' ? 'bg-success' : 'bg-secondary'}">
                    ${theme.applicablestatus}
                </span>
            </td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="editTheme(${theme.themeid})">
                    <i class="fa fa-edit"></i> Edit
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteTheme(${theme.themeid})">
                    <i class="fa fa-trash"></i> Delete
                </button>
            </td>
        </tr>
    `).join('');
        }

        function editTheme(id) {
            const theme = themes.find(t => t.themeid == id);
            if (!theme) return;

            editingThemeId = id;
            document.getElementById('themeModalTitle').textContent = 'Edit Research Theme';
            document.getElementById('themeId').value = theme.themeid;
            document.getElementById('themename').value = theme.themename;
            document.getElementById('themedescription').value = theme.themedescription;
            document.getElementById('applicablestatus').value = theme.applicablestatus;

            new bootstrap.Modal(document.getElementById('themeModal')).show();
        }

        function saveTheme() {
            const formData = new FormData(document.getElementById('themeForm'));
            const url = editingThemeId ? `/api/v1/themes/${editingThemeId}` : '/api/v1/themes';
            const method = editingThemeId ? 'PUT' : 'POST';

            fetch(url, {
                    method: method,
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        ARGPortal.showSuccess(data.message);
                        bootstrap.Modal.getInstance(document.getElementById('themeModal')).hide();
                        resetForm();
                        loadThemes();
                    } else {
                        ARGPortal.showError(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error saving theme:', error);
                    ARGPortal.showError('Error saving theme');
                });
        }

        function deleteTheme(id) {
            if (!confirm('Are you sure you want to delete this theme?')) return;

            fetch(`/api/v1/themes/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        ARGPortal.showSuccess(data.message);
                        loadThemes();
                    } else {
                        ARGPortal.showError(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error deleting theme:', error);
                    ARGPortal.showError('Error deleting theme');
                });
        }

        function resetForm() {
            editingThemeId = null;
            document.getElementById('themeModalTitle').textContent = 'Add Research Theme';
            document.getElementById('themeForm').reset();
            document.getElementById('themeId').value = '';
        }

        // Reset form when modal is hidden
        document.getElementById('themeModal').addEventListener('hidden.bs.modal', function() {
            resetForm();
        });


    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/felix/projects/kabianga-research-portal/Kabianga-arg-final/resources/views/pages/themes/index.blade.php ENDPATH**/ ?>