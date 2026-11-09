<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>VaccineCare | Vaccination Management System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="CSS/index.css">
</head>

<body>

    <!-- NAVBAR -->

    <header class="site-header">

        <div class="container">

            <nav class="navbar">

                <a href="index.php" class="brand">
                    <span class="brand-icon"><i class="fa-solid fa-heart-pulse"></i></span>
                    <span class="brand-text">VaccineCare</span>
                </a>

                <button type="button" class="menu-toggle" aria-label="Toggle menu"
                        aria-controls="navLinks" aria-expanded="false">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="nav-links" id="navLinks">

                    <a href="#about">About</a>
                    <a href="#features">Features</a>
                    <a href="#how-it-works">How It Works</a>
                    <a href="#portals">Portals</a>

                    <a href="#portals" class="btn btn-primary">
                        Get Started
                    </a>

                </div>

            </nav>

        </div>

    </header>


    <main>

        <!-- HERO SECTION -->

        <div class="container">

            <div class="hero">

                <div class="hero-content">

                    <span class="eyebrow">
                        <i class="fa-solid fa-heart-pulse"></i>
                        Vaccination Management System
                    </span>

                    <h1>
                        Making Vaccination
                        <span>Management Easier.</span>
                    </h1>

                    <p>
                        A centralized web-based platform designed to simplify
                        children's vaccination appointments, coordinate hospitals,
                        and manage vaccination records in one place.
                    </p>

                    <div class="hero-buttons">

                        <a href="#portals" class="btn btn-primary">
                            Explore Portal
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>

                        <a href="#about" class="btn btn-outline-light">
                            Learn More
                        </a>

                    </div>

                </div>


                <div class="hero-visual">

                    <div class="visual-card">

                        <div class="visual-icon">
                            <i class="fa-solid fa-shield-virus"></i>
                        </div>

                        <h3>Protecting Every Child</h3>
                        <p>Appointments and records, organized.</p>

                        <div class="visual-bars">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>

                    </div>

                    <div class="floating-card card-one">
                        <i class="fa-solid fa-calendar-check"></i>
                        Appointment Management
                    </div>

                    <div class="floating-card card-two">
                        <i class="fa-solid fa-file-medical"></i>
                        Vaccination Records
                    </div>

                </div>

            </div>

        </div>


        <!-- PROBLEM SECTION -->

        <section class="section" id="about">

            <div class="container">

                <div class="section-heading">

                    <span class="eyebrow">The Problem</span>

                    <h2>Why Do We Need This System?</h2>

                    <p>
                        Managing vaccination appointments and records can become
                        difficult without an organized digital platform.
                    </p>

                </div>


                <div class="problem-grid">

                    <div class="problem-card">

                        <div class="problem-icon">
                            <i class="fa-solid fa-calendar-xmark"></i>
                        </div>

                        <h3>Appointment Management</h3>

                        <p>
                            Managing appointment requests and keeping track of
                            their status can be challenging.
                        </p>

                    </div>


                    <div class="problem-card">

                        <div class="problem-icon">
                            <i class="fa-solid fa-folder-open"></i>
                        </div>

                        <h3>Record Management</h3>

                        <p>
                            Parents need an organized way to access their
                            children's vaccination history.
                        </p>

                    </div>


                    <div class="problem-card">

                        <div class="problem-icon">
                            <i class="fa-solid fa-hospital"></i>
                        </div>

                        <h3>Hospital Coordination</h3>

                        <p>
                            Hospitals and administrators need a coordinated
                            system to manage vaccination appointments.
                        </p>

                    </div>

                </div>

            </div>

        </section>


        <!-- SOLUTION SECTION -->

        <section class="section section-alt" id="features">

            <div class="container">

                <div class="solution-content">

                    <div>

                        <span class="eyebrow">Our Solution</span>

                        <h2>
                            One Platform.
                            Three Connected Portals.
                        </h2>

                        <p>
                            VaccineCare brings parents, hospitals, and
                            administrators together through a centralized
                            vaccination management platform.
                        </p>

                        <ul class="solution-list">

                            <li>
                                <i class="fa-solid fa-check"></i>
                                Digital appointment booking
                            </li>

                            <li>
                                <i class="fa-solid fa-check"></i>
                                Appointment approval and tracking
                            </li>

                            <li>
                                <i class="fa-solid fa-check"></i>
                                Children's vaccination records
                            </li>

                            <li>
                                <i class="fa-solid fa-check"></i>
                                Hospital and vaccine management
                            </li>

                        </ul>

                    </div>


                    <div class="solution-box">

                        <i class="fa-solid fa-laptop-medical"></i>

                        <h3>Organized Vaccination Management</h3>

                        <p>
                            The system provides separate dashboards for each
                            user role, allowing them to manage their respective
                            responsibilities through one application.
                        </p>

                    </div>

                </div>

            </div>

        </section>


        <!-- HOW IT WORKS -->

        <section class="section" id="how-it-works">

            <div class="container">

                <div class="section-heading">

                    <span class="eyebrow">Workflow</span>

                    <h2>How Does It Work?</h2>

                    <p>
                        A simple appointment workflow connecting parents,
                        administrators, and hospitals.
                    </p>

                </div>


                <div class="steps">

                    <div class="step-card">

                        <div class="step-number">01</div>

                        <h3>Parent Books</h3>

                        <p>
                            Parent selects a child, hospital, vaccine,
                            date and time.
                        </p>

                    </div>


                    <div class="step-card">

                        <div class="step-number">02</div>

                        <h3>Admin Reviews</h3>

                        <p>
                            Administrator reviews the request and
                            approves or rejects it.
                        </p>

                    </div>


                    <div class="step-card">

                        <div class="step-number">03</div>

                        <h3>Hospital Processes</h3>

                        <p>
                            The assigned hospital processes the
                            confirmed appointment.
                        </p>

                    </div>


                    <div class="step-card">

                        <div class="step-number">04</div>

                        <h3>Record Created</h3>

                        <p>
                            Once completed, the vaccination record
                            is saved in the system.
                        </p>

                    </div>

                </div>

            </div>

        </section>


        <!-- USER PORTALS -->

        <section class="section section-alt" id="portals">

            <div class="container">

                <div class="section-heading">

                    <span class="eyebrow">User Portals</span>

                    <h2>Choose Your Portal</h2>

                    <p>
                        Each user has a dedicated portal with
                        role-based access and features.
                    </p>

                </div>


                <div class="role-grid">

                    <!-- PARENT -->

                    <div class="role-card">

                        <div class="role-icon">
                            <i class="fa-solid fa-users"></i>
                        </div>

                        <h3>Parent Portal</h3>

                        <p>
                            Register, manage children's information,
                            book appointments, and view vaccination history.
                        </p>

                        <div class="role-actions">

                            <a href="parent/parent_login.php" class="btn btn-primary">
                                Login
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>

                            <a href="parent/parent_register.php" class="btn btn-ghost">
                                Register
                            </a>

                        </div>

                    </div>


                    <!-- HOSPITAL -->

                    <div class="role-card">

                        <div class="role-icon">
                            <i class="fa-solid fa-hospital"></i>
                        </div>

                        <h3>Hospital Portal</h3>

                        <p>
                            View assigned appointments and manage
                            vaccination completion records.
                        </p>

                        <div class="role-actions">

                            <a href="hospital/hospital_login.php" class="btn btn-primary">
                                Login
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>

                            <a href="hospital/hospital_register.php" class="btn btn-ghost">
                                Register
                            </a>

                        </div>

                    </div>


                    <!-- ADMIN -->

                    <div class="role-card">

                        <div class="role-icon">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>

                        <h3>Admin Portal</h3>

                        <p>
                            Manage hospitals, vaccines, and
                            appointment approval requests.
                        </p>

                        <div class="role-actions">

                            <a href="admin/admin_login.php" class="btn btn-primary">
                                Admin Login
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        <!-- CALL TO ACTION -->

        <section class="section">

            <div class="container">

                <div class="cta">

                    <h2>Better Organization. Better Management.</h2>

                    <p>
                        Bringing the vaccination management process
                        together through one digital platform.
                    </p>

                    <a href="#portals" class="btn btn-light">
                        Explore User Portals
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                </div>

            </div>

        </section>

    </main>


    <!-- FOOTER -->

    <footer class="site-footer">

        <div class="container">

            <div class="footer-content">

                <a href="index.php" class="brand">
                    <span class="brand-icon"><i class="fa-solid fa-heart-pulse"></i></span>
                    <span class="brand-text">VaccineCare</span>
                </a>

                <p>
                    Vaccination Management System
                    | Academic Project | 2510F Batch
                </p>

                <p>
                    &copy; 2026 VaccineCare. All rights reserved.
                </p>

            </div>

        </div>

    </footer>

    <script src="JS/index.js"></script>

</body>
</html>
