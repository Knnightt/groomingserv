// Auth Pages JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Password visibility toggle
    const passwordToggles = document.querySelectorAll('.password-toggle');
    
    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
    
    // Form validation enhancements
    const forms = document.querySelectorAll('.auth-form form');
    
    forms.forEach(form => {
        // Real-time password strength check for registration
        const passwordInput = form.querySelector('input[name="registration_form[password][first]"]');
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                const strengthMeter = document.querySelector('.password-strength');
                
                if (!strengthMeter) {
                    const strengthDiv = document.createElement('div');
                    strengthDiv.className = 'password-strength mt-2';
                    this.parentElement.appendChild(strengthDiv);
                }
                
                const strengthDiv = this.parentElement.querySelector('.password-strength');
                const strength = checkPasswordStrength(password);
                
                strengthDiv.innerHTML = `
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar ${strength.color}" role="progressbar" 
                             style="width: ${strength.percentage}%"></div>
                    </div>
                    <small class="text-${strength.color}">${strength.text}</small>
                `;
            });
        }
        
        // Confirm password validation
        const confirmPassword = form.querySelector('input[name="registration_form[password][second]"]');
        if (confirmPassword) {
            confirmPassword.addEventListener('input', function() {
                const password = form.querySelector('input[name="registration_form[password][first]"]').value;
                
                if (this.value !== password) {
                    this.setCustomValidity("Passwords don't match");
                } else {
                    this.setCustomValidity('');
                }
            });
        }
    });
    
    // Social login button animations
    const socialButtons = document.querySelectorAll('.social-btn');
    socialButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });
});

// Password strength checker
function checkPasswordStrength(password) {
    let score = 0;
    
    // Length check
    if (password.length >= 8) score += 1;
    if (password.length >= 12) score += 1;
    
    // Character variety checks
    if (/[a-z]/.test(password)) score += 1;
    if (/[A-Z]/.test(password)) score += 1;
    if (/[0-9]/.test(password)) score += 1;
    if (/[^a-zA-Z0-9]/.test(password)) score += 1;
    
    // Determine strength level
    if (score <= 2) {
        return {
            percentage: 25,
            text: 'Weak',
            color: 'danger'
        };
    } else if (score <= 4) {
        return {
            percentage: 50,
            text: 'Fair',
            color: 'warning'
        };
    } else if (score <= 5) {
        return {
            percentage: 75,
            text: 'Good',
            color: 'info'
        };
    } else {
        return {
            percentage: 100,
            text: 'Strong',
            color: 'success'
        };
    }
}

// Auto-hide alerts after 5 seconds
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    });
}, 5000);