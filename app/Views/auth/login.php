<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Quick Puff Vape Shop System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom Auth CSS -->
    <link href="<?= base_url('assets/css/auth.css') ?>" rel="stylesheet">
</head>
<body>
    <!-- Main Container -->
    <div class="auth-container">
        <!-- Left Side - Branding -->
        <div class="branding-section">
            <div class="branding-content">
                <!-- Logo -->
                <div class="brand-logo">
                    <div class="logo-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                </div>
                
                <!-- System Name -->
                <h1 class="brand-title">Quick Puff Vape Shop</h1>
                <p class="brand-subtitle">SYSTEM</p>
            </div>
            
            <!-- Smoke Effects -->
            <div class="smoke-layer smoke-1"></div>
            <div class="smoke-layer smoke-2"></div>
            <div class="smoke-layer smoke-3"></div>
            <div class="smoke-layer smoke-4"></div>
        </div>
        
        <!-- Right Side - Login Form -->
        <div class="login-section">
            <div class="login-container">
                <div class="login-card">
                    <!-- Login Header -->
                    <div class="login-header">
                        <h2 class="login-title">Sign In</h2>
                        <p class="login-subtitle">Sign in to your account</p>
                    </div>
                    
                    <!-- Flash Messages -->
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                                <div class="flex-grow-1">
                                    <?= session()->getFlashdata('error') ?>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-check-circle me-2 mt-1"></i>
                                <div class="flex-grow-1">
                                    <?= session()->getFlashdata('success') ?>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Login Form -->
                    <form action="<?= site_url('/auth/attemptLogin') ?>" method="post" id="loginForm" class="login-form">
                        <?= csrf_field() ?>
                        
                        <!-- Username Field -->
                        <div class="form-group mb-4">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" 
                                       class="form-control" 
                                       id="username" 
                                       name="username" 
                                       placeholder="Enter your username" 
                                       value="<?= old('username') ?>" 
                                       required 
                                       autocomplete="username">
                                <span class="input-group-text info-icon" data-bs-toggle="tooltip" title="Enter your system username">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </div>
                            <div class="invalid-feedback" id="usernameError">
                                Please enter your username
                            </div>
                        </div>
                        
                        <!-- Password Field -->
                        <div class="form-group mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Enter your password" 
                                       required 
                                       autocomplete="current-password">
                                <button type="button" class="btn btn-outline-secondary password-toggle" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback" id="passwordError">
                                Please enter your password
                            </div>
                        </div>
                        
                        <!-- Remember Me -->
                        <div class="form-group mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rememberMe" name="remember_me">
                                <label class="form-check-label" for="rememberMe">
                                    Remember me
                                </label>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="form-group mb-4">
                            <button type="submit" class="btn btn-login btn-lg w-100" id="loginBtn">
                                <span class="btn-text">SIGN IN</span>
                                <span class="btn-loader" style="display: none;">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Signing in...
                                </span>
                            </button>
                        </div>
                        
                        <!-- Forgot Password -->
                        <div class="text-center">
                            <a href="#" class="forgot-password-link" id="forgotPasswordLink">
                                <i class="fas fa-question-circle me-1"></i>
                                Forgot your password?
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Divider Line -->
        <div class="divider-line"></div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Custom Auth JS -->
    <script src="<?= base_url('assets/js/auth.js') ?>"></script>
</body>
</html>
