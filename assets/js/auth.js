/**
 * Quick Puff Vape Shop System - Authentication JavaScript
 * Professional login form interactions and validations
 */

$(document).ready(function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize form validation
    initializeFormValidation();
    
    // Password toggle functionality
    initializePasswordToggle();
    
    // Form submission handling
    initializeFormSubmission();
    
    // Auto-hide alerts
    initializeAlertHandling();
    
    // Forgot password handler
    initializeForgotPassword();
    
    // Input field enhancements
    initializeInputEnhancements();
});

/**
 * Initialize form validation
 */
function initializeFormValidation() {
    const form = document.getElementById('loginForm');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    
    // Real-time validation
    usernameInput.addEventListener('blur', function() {
        validateField(this, 'username');
    });
    
    usernameInput.addEventListener('input', function() {
        if (this.classList.contains('is-invalid')) {
            validateField(this, 'username');
        }
    });
    
    passwordInput.addEventListener('blur', function() {
        validateField(this, 'password');
    });
    
    passwordInput.addEventListener('input', function() {
        if (this.classList.contains('is-invalid')) {
            validateField(this, 'password');
        }
    });
    
    // Form submission validation
    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            showFormError('Please fill in all required fields correctly.');
        }
    });
}

/**
 * Validate individual field
 */
function validateField(field, fieldName) {
    const value = field.value.trim();
    const errorElement = document.getElementById(fieldName + 'Error');
    
    // Remove previous validation state
    field.classList.remove('is-invalid', 'is-valid');
    
    if (value === '') {
        field.classList.add('is-invalid');
        if (errorElement) {
            errorElement.textContent = 'Please enter your ' + fieldName;
            errorElement.style.display = 'block';
        }
        return false;
    }
    
    // Specific field validations
    switch(fieldName) {
        case 'username':
            if (value.length < 3) {
                field.classList.add('is-invalid');
                if (errorElement) {
                    errorElement.textContent = 'Username must be at least 3 characters long';
                    errorElement.style.display = 'block';
                }
                return false;
            }
            break;
            
        case 'password':
            if (value.length < 6) {
                field.classList.add('is-invalid');
                if (errorElement) {
                    errorElement.textContent = 'Password must be at least 6 characters long';
                    errorElement.style.display = 'block';
                }
                return false;
            }
            break;
    }
    
    field.classList.add('is-valid');
    if (errorElement) {
        errorElement.style.display = 'none';
    }
    return true;
}

/**
 * Validate entire form
 */
function validateForm() {
    const usernameValid = validateField(document.getElementById('username'), 'username');
    const passwordValid = validateField(document.getElementById('password'), 'password');
    
    return usernameValid && passwordValid;
}

/**
 * Initialize password toggle functionality
 */
function initializePasswordToggle() {
    const toggleButton = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const toggleIcon = toggleButton.querySelector('i');
    
    toggleButton.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Toggle icon
        if (type === 'text') {
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
            toggleButton.setAttribute('title', 'Hide password');
        } else {
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
            toggleButton.setAttribute('title', 'Show password');
        }
        
        // Update tooltip
        const tooltip = bootstrap.Tooltip.getInstance(toggleButton);
        if (tooltip) {
            tooltip.dispose();
        }
        new bootstrap.Tooltip(toggleButton);
    });
    
    // Initialize tooltip for toggle button
    new bootstrap.Tooltip(toggleButton);
}

/**
 * Initialize form submission handling
 */
function initializeFormSubmission() {
    const form = document.getElementById('loginForm');
    const submitButton = document.getElementById('loginBtn');
    const buttonText = submitButton.querySelector('.btn-text');
    const buttonLoader = submitButton.querySelector('.btn-loader');
    
    form.addEventListener('submit', function(e) {
        // Disable submit button and show loading state
        submitButton.disabled = true;
        buttonText.style.display = 'none';
        buttonLoader.style.display = 'inline-block';
        
        // Add loading animation to form
        form.style.opacity = '0.7';
        form.style.pointerEvents = 'none';
        
        // Re-enable after timeout (fallback)
        setTimeout(function() {
            if (submitButton.disabled) {
                resetFormState();
                showFormError('Login is taking longer than expected. Please try again.');
            }
        }, 30000);
    });
}

/**
 * Reset form state after submission
 */
function resetFormState() {
    const form = document.getElementById('loginForm');
    const submitButton = document.getElementById('loginBtn');
    const buttonText = submitButton.querySelector('.btn-text');
    const buttonLoader = submitButton.querySelector('.btn-loader');
    
    submitButton.disabled = false;
    buttonText.style.display = 'inline-block';
    buttonLoader.style.display = 'none';
    form.style.opacity = '1';
    form.style.pointerEvents = 'auto';
}

/**
 * Initialize alert handling
 */
function initializeAlertHandling() {
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            // Add fade out animation
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            
            setTimeout(function() {
                alert.remove();
            }, 500);
        });
    }, 5000);
    
    // Manual close handlers
    const closeButtons = document.querySelectorAll('.btn-close');
    closeButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const alert = this.closest('.alert');
            alert.style.transition = 'opacity 0.3s ease';
            alert.style.opacity = '0';
            
            setTimeout(function() {
                alert.remove();
            }, 300);
        });
    });
}

/**
 * Initialize forgot password functionality
 */
function initializeForgotPassword() {
    const forgotPasswordLink = document.getElementById('forgotPasswordLink');
    
    forgotPasswordLink.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Show modal or alert with contact information
        showNotification('Please contact your system administrator to reset your password.', 'info');
    });
}

/**
 * Initialize input field enhancements
 */
function initializeInputEnhancements() {
    const inputs = document.querySelectorAll('.form-control');
    
    inputs.forEach(function(input) {
        // Add focus effects
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
        
        // Add character counter for password field
        if (input.id === 'password') {
            const inputGroup = input.closest('.input-group');
            const counter = document.createElement('small');
            counter.className = 'text-muted mt-1 d-block';
            counter.style.fontSize = '0.75rem';
            inputGroup.parentElement.appendChild(counter);
            
            input.addEventListener('input', function() {
                const length = this.value.length;
                counter.textContent = length + ' characters';
                
                if (length >= 6) {
                    counter.classList.remove('text-muted');
                    counter.classList.add('text-success');
                } else {
                    counter.classList.remove('text-success');
                    counter.classList.add('text-muted');
                }
            });
        }
    });
    
    // Add enter key submission enhancement
    const form = document.getElementById('loginForm');
    form.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            
            // Move to next field or submit
            const activeElement = document.activeElement;
            if (activeElement.id === 'username') {
                document.getElementById('password').focus();
            } else if (activeElement.id === 'password') {
                form.submit();
            }
        }
    });
}

/**
 * Show form error message
 */
function showFormError(message) {
    // Remove existing error alerts
    const existingAlerts = document.querySelectorAll('.alert-danger');
    existingAlerts.forEach(function(alert) {
        alert.remove();
    });
    
    // Create new error alert
    const alertHtml = `
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-start">
                <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                <div class="flex-grow-1">
                    ${message}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    `;
    
    // Insert at the top of the form
    const form = document.getElementById('loginForm');
    form.insertAdjacentHTML('beforebegin', alertHtml);
    
    // Initialize close button
    const newAlert = form.previousElementSibling;
    const closeButton = newAlert.querySelector('.btn-close');
    closeButton.addEventListener('click', function() {
        newAlert.style.transition = 'opacity 0.3s ease';
        newAlert.style.opacity = '0';
        setTimeout(function() {
            newAlert.remove();
        }, 300);
    });
    
    // Auto-hide after 5 seconds
    setTimeout(function() {
        if (newAlert.parentNode) {
            newAlert.style.transition = 'opacity 0.5s ease';
            newAlert.style.opacity = '0';
            setTimeout(function() {
                if (newAlert.parentNode) {
                    newAlert.remove();
                }
            }, 500);
        }
    }, 5000);
}

/**
 * Show notification (alternative to alerts)
 */
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? 'rgba(0, 214, 143, 0.9)' : type === 'error' ? 'rgba(255, 71, 87, 0.9)' : 'rgba(74, 144, 226, 0.9)'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        z-index: 9999;
        min-width: 300px;
        max-width: 400px;
        backdrop-filter: blur(10px);
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(function() {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Close button handler
    const closeButton = notification.querySelector('.notification-close');
    closeButton.addEventListener('click', function() {
        notification.style.transform = 'translateX(100%)';
        setTimeout(function() {
            notification.remove();
        }, 300);
    });
    
    // Auto-hide
    setTimeout(function() {
        if (notification.parentNode) {
            notification.style.transform = 'translateX(100%)';
            setTimeout(function() {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }
    }, 5000);
}

/**
 * Keyboard shortcuts
 */
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + Enter to submit form
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        const form = document.getElementById('loginForm');
        if (validateForm()) {
            form.submit();
        }
    }
    
    // Escape to clear form
    if (e.key === 'Escape') {
        document.getElementById('username').value = '';
        document.getElementById('password').value = '';
        document.getElementById('username').focus();
    }
});

/**
 * Performance optimization - Debounce function
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = function() {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Add loading states to inputs
 */
const inputs = document.querySelectorAll('.form-control');
inputs.forEach(function(input) {
    input.addEventListener('focus', function() {
        this.classList.add('input-loading');
        setTimeout(() => {
            this.classList.remove('input-loading');
        }, 300);
    });
});
