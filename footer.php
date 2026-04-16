<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5>ABC Virtual</h5>
                <p class="text-light">Providing quality healthcare services with compassion and excellence.</p>
                <div class="social-links">
                    <a href="#" class="me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="me-3"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="me-3"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            
            <div class="col-md-2 mb-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#doctors">Doctors</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            
            <div class="col-md-3 mb-4">
                <h5>Services</h5>
                <ul class="list-unstyled">
                    <li><a href="#">Emergency Care</a></li>
                    <li><a href="#">Outpatient Services</a></li>
                    <li><a href="#">Diagnostic Services</a></li>
                    <li><a href="#">Pharmacy</a></li>
                </ul>
            </div>
            
            <div class="col-md-3 mb-4">
                <h5>Contact Info</h5>
                <ul class="list-unstyled">
                    <li><i class="fas fa-map-marker-alt me-2"></i>123 Kandy, Central</li>
                    <li><i class="fas fa-phone me-2"></i>+94 11 123 4567</li>
                    <li><i class="fas fa-envelope me-2"></i>info@abcvirtual.com</li>
                    <li><i class="fas fa-clock me-2"></i>24/7 Emergency Services</li>
                </ul>
            </div>
        </div>
        
        <hr class="bg-light">
        
        <div class="row">
            <div class="col-md-6">
                <p class="mb-0 text-light">&copy; 2025 ABC Virtual Hospital. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="#" class="text-light me-3">Privacy Policy</a>
                <a href="#" class="text-light">Terms of Use</a>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS -->
<script>
    // Add active class to current nav item
    document.addEventListener('DOMContentLoaded', function() {
        const currentLocation = window.location.pathname.split('/').pop();
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            if(link.getAttribute('href') === currentLocation) {
                link.classList.add('active');
            }
        });
    });
</script>
</body>
</html>