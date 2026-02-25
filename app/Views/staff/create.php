<?= $this->include('layouts/header') ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Add New Staff</h1>
                <p class="page-subtitle">Create a new staff member account</p>
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
                    <h5 class="card-title mb-0">Staff Information</h5>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('/staff/store') ?>" method="POST">
                        <?= csrf_field() ?>
                        
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
                                   placeholder="Enter username"
                                   value="<?= old('username') ?>">
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
                                   placeholder="Enter full name"
                                   value="<?= old('full_name') ?>">
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
                                <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Administrator</option>
                                <option value="staff" <?= old('role') === 'staff' ? 'selected' : '' ?>>Staff</option>
                                <option value="inventory" <?= old('role') === 'inventory' ? 'selected' : '' ?>>Inventory Staff</option>
                                <option value="branch_manager" <?= old('role') === 'branch_manager' ? 'selected' : '' ?>>Branch Manager</option>
                                <option value="logistics" <?= old('role') === 'logistics' ? 'selected' : '' ?>>Logistics Coordinator</option>
                                <option value="franchise" <?= old('role') === 'franchise' ? 'selected' : '' ?>>Franchise Manager</option>
                            </select>
                        </div>

                        <!-- Email (Optional) -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-1"></i> Email (Optional)
                            </label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   placeholder="Enter email address"
                                   value="<?= old('email') ?>">
                        </div>

                        <!-- Phone (Optional) -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone me-1"></i> Phone (Optional)
                            </label>
                            <input type="tel" 
                                   class="form-control" 
                                   id="phone" 
                                   name="phone" 
                                   placeholder="Enter phone number"
                                   value="<?= old('phone') ?>">
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                <label class="form-check-label" for="is_active">
                                    <i class="fas fa-check-circle me-1"></i> Active Account
                                </label>
                            </div>
                            <small class="text-muted">Uncheck to create account as inactive</small>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Create Staff Account
                            </button>
                            <a href="<?= site_url('/staff') ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Cancel
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
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const toggle = document.getElementById(fieldId + '-toggle');
    
    if (field.type === 'password') {
        field.type = 'text';
        toggle.classList.remove('fa-eye');
        toggle.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        toggle.classList.remove('fa-eye-slash');
        toggle.classList.add('fa-eye');
    }
}

// Username validation
document.getElementById('username').addEventListener('input', function() {
    const username = this.value;
    const feedback = this.nextElementSibling;
    
    if (username.length > 0 && username.length < 3) {
        this.classList.add('is-invalid');
        this.classList.remove('is-valid');
        if (!feedback || !feedback.classList.contains('invalid-feedback')) {
            const invalidFeedback = document.createElement('div');
            invalidFeedback.className = 'invalid-feedback';
            invalidFeedback.textContent = 'Username must be at least 3 characters long';
            this.parentNode.insertBefore(invalidFeedback, this.nextSibling);
        }
    } else if (username.length >= 3) {
        this.classList.remove('is-invalid');
        this.classList.add('is-valid');
        const invalidFeedback = this.parentNode.querySelector('.invalid-feedback');
        if (invalidFeedback) {
            invalidFeedback.remove();
        }
    }
});

// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    let strength = 0;
    
    if (password.length >= 6) strength++;
    if (password.match(/[a-z]+/)) strength++;
    if (password.match(/[A-Z]+/)) strength++;
    if (password.match(/[0-9]+/)) strength++;
    if (password.match(/[$@#&!]+/)) strength++;
    
    const strengthTexts = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
    const strengthColors = ['#FF4757', '#FFB400', '#FFA502', '#00D68F', '#4A90E2'];
    
    let strengthIndicator = this.parentNode.parentNode.querySelector('.password-strength');
    if (!strengthIndicator) {
        strengthIndicator = document.createElement('div');
        strengthIndicator.className = 'password-strength mt-2';
        this.parentNode.parentNode.appendChild(strengthIndicator);
    }
    
    if (password.length > 0) {
        strengthIndicator.innerHTML = `
            <small>Password Strength: <strong style="color: ${strengthColors[strength]}">${strengthTexts[strength]}</strong></small>
            <div class="progress mt-1" style="height: 4px;">
                <div class="progress-bar" style="width: ${(strength + 1) * 20}%; background-color: ${strengthColors[strength]};"></div>
            </div>
        `;
    } else {
        strengthIndicator.innerHTML = '';
    }
});

// Role change handler
document.getElementById('role').addEventListener('change', function() {
    const role = this.value;
    const emailField = document.getElementById('email');
    
    // Make email required for certain roles
    if (role === 'admin' || role === 'branch_manager') {
        emailField.required = true;
        emailField.previousElementSibling.innerHTML = '<i class="fas fa-envelope me-1"></i> Email <span class="text-danger">*</span>';
    } else {
        emailField.required = false;
        emailField.previousElementSibling.innerHTML = '<i class="fas fa-envelope me-1"></i> Email (Optional)';
    }
});

// Form submission
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const username = document.getElementById('username').value;
    
    if (username.length < 3) {
        e.preventDefault();
        alert('Username must be at least 3 characters long');
        return;
    }
    
    if (password.length < 6) {
        e.preventDefault();
        alert('Password must be at least 6 characters long');
        return;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Creating Account...';
    
    // Reset button after 10 seconds (fallback)
    setTimeout(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }, 10000);
});
</script>
