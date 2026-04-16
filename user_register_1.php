<?php 
$pageTitle = "Register";
include('header_1.php'); 
?>

<section class="register-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="register-card shadow-lg">
                    <div class="register-header">
                        <h2>Create Account</h2>
                    </div>
                    
                    <div class="register-body">
                        <form action="user_register_2.php" method="post" id="registerForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0">
                                            <i class="fas fa-user text-primary"></i>
                                        </span>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               placeholder="Enter your full name" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0">
                                            <i class="fas fa-envelope text-primary"></i>
                                        </span>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               placeholder="Enter your email" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="access_code" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0">
                                            <i class="fas fa-lock text-primary"></i>
                                        </span>
                                        <input type="password" class="form-control" id="access_code" 
                                               name="access_code" placeholder="Create a password" required>
                                        <button class="btn btn-outline-secondary" type="button" 
                                                onclick="togglePassword('access_code', 'toggleIcon1')">
                                            <i class="fas fa-eye" id="toggleIcon1"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="confirm_password" class="form-label">Confirm Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0">
                                            <i class="fas fa-lock text-primary"></i>
                                        </span>
                                        <input type="password" class="form-control" id="confirm_password" 
                                               placeholder="Confirm your password" required>
                                        <button class="btn btn-outline-secondary" type="button" 
                                                onclick="togglePassword('confirm_password', 'toggleIcon2')">
                                            <i class="fas fa-eye" id="toggleIcon2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0">
                                            <i class="fas fa-phone text-primary"></i>
                                        </span>
                                        <input type="tel" class="form-control" id="phone" name="phone" 
                                               placeholder="Enter your phone number" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="dob" class="form-label">Date of Birth</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0">
                                            <i class="fas fa-calendar text-primary"></i>
                                        </span>
                                        <input type="date" class="form-control" id="dob" name="dob" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0">
                                            <i class="fas fa-venus-mars text-primary"></i>
                                        </span>
                                        <select class="form-select" id="gender" name="gender" required>
                                            <option value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 py-3 mb-3">
                                <i class="fas fa-user-plus me-2"></i>Create Account
                            </button>
                            
                            <div class="text-center">
                                <p class="mb-0">Already have an account? 
                                    <a href="login.php" class="text-primary fw-bold">Sign in here</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function togglePassword(fieldId, iconId) {
    const password = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);
    
    if (password.type === 'password') {
        password.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        password.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Password match validation
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const password = document.getElementById('access_code').value;
    const confirm = document.getElementById('confirm_password').value;
    
    if (password !== confirm) {
        e.preventDefault();
        alert('Passwords do not match!');
    }
});
</script>