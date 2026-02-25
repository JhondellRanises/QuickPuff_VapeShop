<?= $this->include('layouts/header') ?>

<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Edit Staff Account</h1>
                <p class="page-subtitle">Update staff details and permissions</p>
            </div>
            <a href="<?= site_url('/staff') ?>" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>
                Back to Staff
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-edit me-2"></i>Edit Staff Information
                        <span class="badge bg-info float-end">ID: <?= $staff['id'] ?></span>
                    </h5>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('/staff/update/' . $staff['id']) ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" value="<?= esc($staff['username']) ?>" readonly>
                                <span class="input-group-text bg-warning"><i class="fas fa-lock"></i></span>
                            </div>
                            <small class="text-muted">Username cannot be changed</small>
                        </div>

                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                <input
                                    type="text"
                                    class="form-control <?= (session()->getFlashdata('error')) ? 'is-invalid' : '' ?>"
                                    id="full_name"
                                    name="full_name"
                                    value="<?= old('full_name', $staff['full_name']) ?>"
                                    required
                                    placeholder="Enter full name"
                                >
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input
                                    type="password"
                                    class="form-control"
                                    id="password"
                                    name="password"
                                    placeholder="Leave blank to keep current password"
                                >
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small class="text-muted">Enter a new password only if you want to change it (min. 6 characters)</small>
                        </div>

                        <div class="mb-4">
                            <label for="role" class="form-label">Role *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="staff" <?= (old('role', $staff['role']) === 'staff') ? 'selected' : '' ?>>
                                        Staff - Can access POS and Dashboard
                                    </option>
                                    <option value="admin" <?= (old('role', $staff['role']) === 'admin') ? 'selected' : '' ?>>
                                        Admin - Full system access
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <label class="form-label">Current Status</label>
                                <div>
                                    <span class="badge bg-<?= $staff['is_active'] ? 'success' : 'secondary' ?> fs-6">
                                        <?= $staff['is_active'] ? 'Active' : 'Inactive' ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Member Since</label>
                                <div class="text-muted">
                                    <?= date('M j, Y', strtotime($staff['created_at'])) ?>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Staff Account
                            </button>
                            <a href="<?= site_url('/staff') ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->include('layouts/footer') ?>

<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordField = document.getElementById('password');
    const icon = this.querySelector('i');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

document.querySelector('form').addEventListener('submit', function(e) {
    const fullName = document.getElementById('full_name').value.trim();
    const password = document.getElementById('password').value;
    const role = document.getElementById('role').value;

    if (fullName === '') {
        e.preventDefault();
        alert('Full name is required');
        return;
    }

    if (password !== '' && password.length < 6) {
        e.preventDefault();
        alert('Password must be at least 6 characters long');
        return;
    }

    if (role === '') {
        e.preventDefault();
        alert('Please select a role');
        return;
    }
});
</script>
