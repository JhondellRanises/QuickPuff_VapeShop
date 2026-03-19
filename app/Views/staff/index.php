<?= $this->include('layouts/header') ?>

<style>
    .current-user-row td {
        background-color: rgba(99, 102, 241, 0.14) !important;
        color: #e7eaf6;
    }

    .table-hover .current-user-row:hover td {
        background-color: rgba(99, 102, 241, 0.2) !important;
        color: #ffffff;
    }

    .current-user-row .text-muted {
        color: #c8cede !important;
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Staff Management</h1>
                <p class="page-subtitle">Manage system users and their roles</p>
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    For security reasons, you cannot edit or deactivate your own account
                </small>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-plus me-2"></i>
                Add New Staff
            </button>
        </div>
    </div>

    <!-- Staff Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">All Staff Members</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($staff)): ?>
                            <?php foreach ($staff as $member): ?>
                                <?php 
                                $currentUserId = session()->get('user_id');
                                $isCurrentUser = ($member['id'] == $currentUserId);
                                ?>
                                <tr class="<?= $isCurrentUser ? 'current-user-row' : '' ?>">
                                    <td><?= $member['id'] ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-2" style="width: 32px; height: 32px; font-size: 0.9rem;">
                                                <?= strtoupper(substr($member['full_name'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <?= esc($member['username']) ?>
                                                <?php if ($isCurrentUser): ?>
                                                    <small class="d-block text-muted">
                                                        <i class="fas fa-user-check"></i> You
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= esc($member['full_name']) ?></td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= ucfirst($member['role']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($member['is_active']): ?>
                                            <span class="badge status-active">
                                                <i class="fas fa-check-circle me-1"></i>
                                                Active
                                            </span>
                                        <?php else: ?>
                                            <span class="badge status-inactive">
                                                <i class="fas fa-times-circle me-1"></i>
                                                Inactive
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($member['created_at'])) ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <?php 
                                            $currentUserId = session()->get('user_id');
                                            $isCurrentUser = ($member['id'] == $currentUserId);
                                            ?>
                                            
                                            <?php if (!$isCurrentUser): ?>
                                                <a href="<?= site_url('/staff/edit/' . $member['id']) ?>" 
                                                   class="btn btn-outline-primary btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <?php if ($member['is_active']): ?>
                                                    <a href="<?= site_url('/staff/deactivate/' . $member['id']) ?>" 
                                                       class="btn btn-outline-warning btn-sm" 
                                                       onclick="return confirm('Are you sure you want to deactivate this staff member?')"
                                                       title="Deactivate">
                                                        <i class="fas fa-user-slash"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <a href="<?= site_url('/staff/activate/' . $member['id']) ?>" 
                                                       class="btn btn-outline-success btn-sm" 
                                                       onclick="return confirm('Are you sure you want to activate this staff member?')"
                                                       title="Activate">
                                                        <i class="fas fa-user-check"></i>
                                                    </a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <!-- Current user - show disabled buttons with tooltip -->
                                                <button class="btn btn-outline-secondary btn-sm" 
                                                        disabled 
                                                        title="Cannot edit your own account"
                                                        data-bs-toggle="tooltip">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                
                                                <?php if ($member['is_active']): ?>
                                                    <button class="btn btn-outline-secondary btn-sm" 
                                                            disabled 
                                                            title="Cannot deactivate your own account"
                                                            data-bs-toggle="tooltip">
                                                        <i class="fas fa-user-slash"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-outline-secondary btn-sm" 
                                                            disabled 
                                                            title="Cannot activate your own account"
                                                            data-bs-toggle="tooltip">
                                                        <i class="fas fa-user-check"></i>
                                                    </button>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if ($isCurrentUser): ?>
                                            <small class="text-muted d-block mt-1">
                                                <i class="fas fa-info-circle"></i> Your account
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-users fa-3x mb-3 d-block text-muted"></i>
                                    No staff members found. 
                                    <button class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                        Add your first staff member
                                    </button>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">
                    <i class="fas fa-user-plus me-2"></i>Add New Staff
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addUserForm" action="<?= site_url('/staff/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label">
                            <i class="fas fa-user me-1"></i> Username
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="username" 
                               name="username" 
                               required
                               placeholder="Enter username">
                        <small class="text-muted">Username must be at least 3 characters long</small>
                    </div>

                    <!-- Full Name -->
                    <div class="mb-3">
                        <label for="full_name" class="form-label">
                            <i class="fas fa-id-card me-1"></i> Full Name
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="full_name" 
                               name="full_name" 
                               required
                               placeholder="Enter full name">
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-1"></i> Password
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control password-input" 
                                   id="password" 
                                   name="password" 
                                   required
                                   placeholder="Enter password">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="password-toggle"></i>
                            </button>
                        </div>
                        <small class="text-muted">Password must be at least 6 characters long</small>
                    </div>

                    <!-- Role -->
                    <div class="mb-3">
                        <label for="role" class="form-label">
                            <i class="fas fa-user-tag me-1"></i> Role
                        </label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="admin">Administrator</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">
                                <i class="fas fa-check-circle me-1"></i> Active Account
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Create Staff
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const passwordField = document.getElementById(fieldId);
    const toggleIcon = document.getElementById(fieldId + '-toggle');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

// Simple form submission without AJAX for now
document.getElementById('addUserForm').addEventListener('submit', function(e) {
    console.log('Form submitted normally');
    // Let it submit normally for now
});
</script>

<?= $this->include('layouts/footer') ?>
