<?= $this->include('layouts/header') ?>

<!-- Main Content -->
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('/dashboard') ?>">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('/pos') ?>">
                            <i class="fas fa-shopping-cart"></i>
                            Point of Sale
                        </a>
                    </li>

                    <li class="nav-item mt-3">
                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                            <span>Administration</span>
                        </h6>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= site_url('/staff') ?>">
                            <i class="fas fa-users"></i>
                            Staff Management
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Edit Staff Account</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="<?= site_url('/staff') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Staff
                    </a>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-user-edit"></i> Edit Staff Information
                            <span class="badge bg-info float-end">ID: <?= $staff['id'] ?></span>
                        </div>
                        <div class="card-body">
                            <form action="<?= site_url('/staff/update/' . $staff['id']) ?>" method="post">
                                <?= csrf_field() ?>
                                
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control" 
                                               value="<?= esc($staff['username']) ?>" 
                                               readonly>
                                        <span class="input-group-text bg-warning">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                    </div>
                                    <small class="text-muted">Username cannot be changed</small>
                                </div>

                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Full Name *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-id-card"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control <?= (session()->getFlashdata('error')) ? 'is-invalid' : '' ?>" 
                                               id="full_name" 
                                               name="full_name" 
                                               value="<?= old('full_name', $staff['full_name']) ?>" 
                                               required 
                                               placeholder="Enter full name">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" 
                                               class="form-control" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Leave blank to keep current password">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">Enter new password only if you want to change it (min. 6 characters)</small>
                                </div>

                                <div class="mb-4">
                                    <label for="role" class="form-label">Role *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-user-tag"></i>
                                        </span>
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
                                        <i class="fas fa-save"></i> Update Staff Account
                                    </button>
                                    <a href="<?= site_url('/staff') ?>" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-center text-muted py-3 mt-5">
    <div class="container">
        <p>&copy; 2024 Quick Puff Vape Shop. All rights reserved.</p>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).ready(function() {
    // Toggle password visibility
    $('#togglePassword').click(function() {
        const passwordField = $('#password');
        const passwordType = passwordField.attr('type');
        const icon = $(this).find('i');
        
        if (passwordType === 'password') {
            passwordField.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Auto-hide flash messages
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Form validation
    $('form').on('submit', function() {
        const fullName = $('#full_name').val().trim();
        const password = $('#password').val();
        const role = $('#role').val();

        if (fullName === '') {
            alert('Full name is required');
            return false;
        }

        if (password !== '' && password.length < 6) {
            alert('Password must be at least 6 characters long');
            return false;
        }

        if (role === '') {
            alert('Please select a role');
            return false;
        }

        // Confirm password change
        if (password !== '') {
            if (!confirm('Are you sure you want to change the password for this user?')) {
                return false;
            }
        }

        return true;
    });
});
</script>

</body>
</html>
