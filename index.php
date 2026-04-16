<?php 
$pageTitle = "Home";
include('header.php'); 
?>

<section class="hero-section position-relative overflow-hidden">
    <div class="hero-bg"></div>
    <div class="container position-relative z-index-2">
        <div class="row min-vh-100 align-items-center">

        <div class="col-lg-6 order-first order-lg-last">
                <div class="hero-image-wrapper text-center">
                    <img src="images/doctor-hero.png" alt="Medical" class="img-fluid hero-main-img">
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="display-4 fw-bold mb-4">
                        Caring for You,<br>Every Step of the Way
                    </h1>
                    <p class="lead text-muted mb-4">
                        Experience compassionate healthcare with state-of-the-art facilities and 
                        dedicated specialists available 24/7 for all your medical needs.
                    </p>
                    
                    <div class="d-flex flex-wrap gap-3 mb-5">
                        <a href="login.php" class="btn btn-primary btn-lg px-4 py-3">
                            <i class="fas fa-calendar-check me-2"></i>Book Appointment
                        </a>
                    </div>
                    
                    <div class="stats-cards">
                        <div class="row g-3">
                            <div class="col-6 col-md-4">
                                <div class="stat-card bg-white p-3 rounded-4 shadow-sm">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon-d bg-primary-light rounded-3 me-3">
                                            <i class="fas fa-users text-primary"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold">5000+</h5>
                                            <small class="text-muted">Happy Patients</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-6 col-md-4">
                                <div class="stat-card bg-white p-3 rounded-4 shadow-sm">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon-d bg-success-light rounded-3 me-3">
                                            <i class="fas fa-clock text-success"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold">24/7</h5>
                                            <small class="text-muted">Emergency</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-6 col-md-4 mx-auto">
                                <div class="stat-card bg-white p-3 rounded-4 shadow-sm">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon-d bg-info-light rounded-3 me-3">
                                            <i class="fas fa-user-md text-info"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0 fw-bold">20+</h5>
                                            <small class="text-muted">Specialists</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="services-section py-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">Comprehensive Medical Care</h2>
            <p class="lead text-muted col-lg-8 mx-auto">
                We offer a wide range of medical services to meet all your healthcare needs 
                under one roof.
            </p>
        </div>
        
        <div class="row g-4">
            <div class="col-6 col-lg-3">
                <div class="service-card h-100">
                    <div class="service-icon mb-4">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <h5>Cardiology</h5>
                    <p class="text-muted mb-3 d-none d-md-block">Expert heart care with advanced diagnostic technology</p>
                </div>
            </div>
            
            <div class="col-6 col-lg-3">
                <div class="service-card h-100">
                    <div class="service-icon mb-4">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h5>Neurology</h5>
                    <p class="text-muted mb-3 d-none d-md-block">Specialized care for nervous system disorders</p>
                </div>
            </div>
            
            <div class="col-6 col-lg-3">
                <div class="service-card h-100">
                    <div class="service-icon mb-4">
                        <i class="fas fa-child"></i>
                    </div>
                    <h5>Pediatrics</h5>
                    <p class="text-muted mb-3 d-none d-md-block">Comprehensive healthcare for children of all ages</p>
                </div>
            </div>
            
            <div class="col-6 col-lg-3">
                <div class="service-card h-100">
                    <div class="service-icon mb-4">
                        <i class="fas fa-bone"></i>
                    </div>
                    <h5>Orthopedics</h5>
                    <p class="text-muted mb-3 d-none d-md-block">Expert care for bones, joints, and muscles</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about-section py-5 bg-light">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="about-image-wrapper position-relative">
                    <img src="images/hospital-building.png" alt="Our Hospital" class="img-fluid rounded-4 shadow-lg">
                    
                    <!-- Experience Badge -->
                    <div class="experience-badge bg-primary text-white p-4 rounded-4 shadow-lg">
                        <h3 class="display-4 fw-bold mb-0">25+</h3>
                        <p class="mb-0">Years of Excellence</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 ps-lg-5">
                <h2 class="display-5 fw-bold mb-4">Leading the Way in Medical Excellence</h2>
                <p class="text-muted mb-4">
                    With over 25 years of dedicated service, ABC Virtual Hospital has been at the forefront 
                    of healthcare innovation. Our state-of-the-art facilities and team of experienced 
                    specialists ensure that every patient receives the highest quality care.
                </p>
                
                <div class="row g-4 mb-4">
                    <div class="col-6">
                        <div class="d-flex">
                            <div class="check-icon me-3">
                                <i class="fas fa-check-circle text-primary"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Modern Facilities</h6>
                                <small class="text-muted d-none d-md-block">Latest medical equipment</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex">
                            <div class="check-icon me-3">
                                <i class="fas fa-check-circle text-primary"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Expert Doctors</h6>
                                <small class="text-muted d-none d-md-block">Highly qualified specialists</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex">
                            <div class="check-icon me-3">
                                <i class="fas fa-check-circle text-primary"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">24/7 Emergency</h6>
                                <small class="text-muted d-none d-md-block">Round-the-clock care</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex">
                            <div class="check-icon me-3">
                                <i class="fas fa-check-circle text-primary"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Affordable Care</h6>
                                <small class="text-muted d-none d-md-block">Competitive pricing</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <a href="#about" class="btn btn-primary btn-lg px-5 w-100 w-md-auto">
                    Learn More About Us
                </a>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section py-5 bg-primary">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-8 text-white mb-4 mb-lg-0">
                <h2 class="display-5 fw-bold mb-3">Need Emergency Medical Care?</h2>
                <p class="lead mb-0 opacity-90">Our emergency team is available 24/7 to provide immediate medical attention.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="tel:+1234567890" class="btn btn-light btn-lg px-5 py-3 w-100 w-md-auto">
                    <i class="fas fa-phone-alt me-2"></i>Call Emergency
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section py-5 bg-light">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">What Our Patients Say</h2>
            <p class="lead text-muted col-lg-8 mx-auto">
                Read testimonials from patients who experienced our care firsthand.
            </p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="testimonial-card bg-white p-4 rounded-4 shadow-sm h-100">
                    <div class="rating mb-3">
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                    </div>
                    <p class="text-muted mb-4">"Excellent care from the moment I walked in. The staff was professional and caring. Highly recommend!"</p>
                    <div class="d-flex align-items-center">
                        <img src="images/patient1.png" alt="Patient" class="patient-img rounded-circle me-3" style="width: 50px; height: 50px;">
                        <div>
                            <h6 class="fw-bold mb-1">John Smith</h6>
                            <small class="text-muted">Cardiology Patient</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="testimonial-card bg-white p-4 rounded-4 shadow-sm h-100">
                    <div class="rating mb-3">
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                    </div>
                    <p class="text-muted mb-4">"The pediatric team took wonderful care of my daughter. They made us feel comfortable and well-informed."</p>
                    <div class="d-flex align-items-center">
                        <img src="images/patient2.png" alt="Patient" class="patient-img rounded-circle me-3" style="width: 50px; height: 50px;">
                        <div>
                            <h6 class="fw-bold mb-1">Maria Garcia</h6>
                            <small class="text-muted">Pediatrics Patient</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4 mx-auto">
                <div class="testimonial-card bg-white p-4 rounded-4 shadow-sm h-100">
                    <div class="rating mb-3">
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                    </div>
                    <p class="text-muted mb-4">"Quick response in emergency and excellent follow-up care. Grateful for their professional service."</p>
                    <div class="d-flex align-items-center">
                        <img src="images/patient3.png" alt="Patient" class="patient-img rounded-circle me-3" style="width: 50px; height: 50px;">
                        <div>
                            <h6 class="fw-bold mb-1">Robert Johnson</h6>
                            <small class="text-muted">Emergency Patient</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('footer.php'); ?>