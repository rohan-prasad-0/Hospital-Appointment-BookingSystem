<?php 
$pageTitle = "Login";
include('header_1.php'); 
?>

<section class="login-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">
                <div class="login-card shadow-lg">
                    <div class="login-header">
                        <h2>Welcome Back!</h2>
                        <p>Please sign in to your account</p>
                    </div>
                
                    <div class="login-body">

                        <div class="alert alert-danger d-flex align-items-center mb-4">
                            <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                            <div> 
                                Invalid email or password. Please try again.
                            </div>
                        </div>

                        <form action="login_2.php" method="post">
                            <div class="mb-4">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="fas fa-envelope text-primary"></i>
                                    </span>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           placeholder="Enter your email" required>
                                </div>
                            </div>
                            
                            <div class="mb-5">
                                <label for="access_code" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="fas fa-lock text-primary"></i>
                                    </span>
                                    <input type="password" class="form-control" id="access_code" 
                                           name="access_code" placeholder="Enter your password" required>
                                    <button class="btn btn-outline-secondary" type="button" 
                                            onclick="togglePassword()">
                                        <i class="fas fa-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 py-3 mb-3">
                                <i class="fas fa-sign-in-alt me-2"></i>Sign In
                            </button>
                            
                            <div class="text-center">
                                <p class="mb-0">Don't have an account? 
                                    <a href="register.php" class="text-primary fw-bold">Register here</a>
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
function togglePassword() {
    const password = document.getElementById('access_code');
    const icon = document.getElementById('toggleIcon');
    
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
</script>