<?php
ob_start();
/**
 * Main Portfolio Page (index.php)
 * Name: Sean Andrie Cubio
 * Tech Stack: PHP, MySQL, Vanilla CSS & JS
 */

require_once __DIR__ . '/db.php';

// Auto-sync uploaded profile photo and project preview images to assets directory
$assets_dir = __DIR__ . '/assets/images';
if (!is_dir($assets_dir)) {
    @mkdir($assets_dir, 0777, true);
}
$videos_dir = __DIR__ . '/assets/videos';
if (!is_dir($videos_dir)) {
    @mkdir($videos_dir, 0777, true);
}

$sync_map = [
    'profile.png'           => 'C:/Users/Administrator/.gemini/antigravity-ide/brain/b24e7995-1cee-479e-95d2-2e3c6f14f9a9/.user_uploaded/media_1792758513313.png',
    'tapseafo.jpg'          => 'C:/Users/Administrator/.gemini/antigravity-ide/brain/b24e7995-1cee-479e-95d2-2e3c6f14f9a9/tapseafo_preview_1792758023433.jpg',
    'hotel_booking.jpg'     => 'C:/Users/Administrator/.gemini/antigravity-ide/brain/b24e7995-1cee-479e-95d2-2e3c6f14f9a9/hotel_booking_preview_1792758047169.jpg',
    'arduino_dispenser.jpg' => 'C:/Users/Administrator/.gemini/antigravity-ide/brain/b24e7995-1cee-479e-95d2-2e3c6f14f9a9/arduino_dispenser_preview_1792758144860.jpg',
    'interview_poster.jpg'  => 'C:/Users/Administrator/.gemini/antigravity-ide/brain/1cdb46c1-c28d-45c6-a2ef-10366b661c00/interview_poster_1793198684346.jpg',
    'resume.png'            => file_exists('C:/Users/Administrator/.gemini/antigravity-ide/brain/1cdb46c1-c28d-45c6-a2ef-10366b661c00/.user_uploaded/media_1793197474146.png')
                                ? 'C:/Users/Administrator/.gemini/antigravity-ide/brain/1cdb46c1-c28d-45c6-a2ef-10366b661c00/.user_uploaded/media_1793197474146.png'
                                : (file_exists('C:/Users/Administrator/.gemini/antigravity-ide/brain/1cdb46c1-c28d-45c6-a2ef-10366b661c00/.user_uploaded/media_1793197228911.png')
                                    ? 'C:/Users/Administrator/.gemini/antigravity-ide/brain/1cdb46c1-c28d-45c6-a2ef-10366b661c00/.user_uploaded/media_1793197228911.png'
                                    : 'C:/Users/Administrator/.gemini/antigravity-ide/brain/1cdb46c1-c28d-45c6-a2ef-10366b661c00/.user_uploaded/media_1793195555550.png')
];

foreach ($sync_map as $dest_filename => $source_path) {
    $dest_file = $assets_dir . '/' . $dest_filename;
    if (file_exists($source_path)) {
        if (!file_exists($dest_file) || filesize($dest_file) !== filesize($source_path)) {
            @copy($source_path, $dest_file);
        }
    }
}

// Auto-sync andrie.mp4 to assets/videos/andrie.mp4 if present in root
if (file_exists(__DIR__ . '/andrie.mp4')) {
    $dest_video = $videos_dir . '/andrie.mp4';
    if (!file_exists($dest_video) || filesize($dest_video) !== filesize(__DIR__ . '/andrie.mp4')) {
        @copy(__DIR__ . '/andrie.mp4', $dest_video);
    }
}

// Modal login submission handler
$login_error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_submit'])) {
    $u = isset($_POST['username']) ? trim($_POST['username']) : '';
    $p = isset($_POST['password']) ? trim($_POST['password']) : '';
    $auth = authenticateUser($u, $p, $pdo);
    if ($auth['success']) {
        $_SESSION['logged_in'] = true;
        $_SESSION['user'] = $auth['user'];
        $_SESSION['username'] = $auth['user']['username'];
        header("Location: admin.php?welcome=1");
        exit;
    } else {
        $login_error = $auth['message'];
    }
}

// Form submission handler
$feedback = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $name    = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
    $email   = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
    $subject = isset($_POST['subject']) ? trim(strip_tags($_POST['subject'])) : '';
    $message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $feedback = [
            'status' => 'error',
            'message' => 'Please fill in all required fields.'
        ];
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $feedback = [
            'status' => 'error',
            'message' => 'Please enter a valid email address.'
        ];
    } else {
        if ($pdo && $db_connected) {
            try {
                $stmt = $pdo->prepare("INSERT INTO `messages` (`name`, `email`, `subject`, `message`) VALUES (:name, :email, :subject, :message)");
                $stmt->execute([
                    ':name'    => $name,
                    ':email'   => $email,
                    ':subject' => $subject,
                    ':message' => $message
                ]);
                $feedback = [
                    'status' => 'success',
                    'message' => 'Thank you, ' . htmlspecialchars($name) . '! Your message has been saved to the database. I will get back to you shortly.'
                ];
            } catch (PDOException $e) {
                $feedback = [
                    'status' => 'error',
                    'message' => 'Failed to save message to database: ' . htmlspecialchars($e->getMessage())
                ];
            }
        } else {
            // Graceful response when viewing in preview without active DB
            $feedback = [
                'status' => 'success',
                'message' => 'Thank you, ' . htmlspecialchars($name) . '! Your message was received (MySQL is currently offline, but the form handler worked properly!).'
            ];
        }
    }
}

// Fetch dynamic data from database (or fallbacks)
$profile  = getProfileData($pdo);
$projects = getProjectsData($pdo);
$resume   = getResumeData();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($profile['full_name']); ?> - <?php echo htmlspecialchars($profile['title']); ?>. Explore my portfolio, projects, technical skills, and database-driven web solutions.">
    <meta name="author" content="<?php echo htmlspecialchars($profile['full_name']); ?>">
    <title><?php echo htmlspecialchars($profile['full_name']); ?> | Portfolio</title>

    <!-- Google Fonts: Outfit & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body>

 

    <!-- Navigation Bar -->
    <header class="navbar" id="navbar">
        <div class="container nav-container">
            <a href="#home" class="logo">
                <div class="logo-badge">SAC</div>
                <span><?php echo htmlspecialchars($profile['full_name']); ?></span>
            </a>

            <ul class="nav-links" id="navLinks">
                <li><a href="#home" class="active">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#projects">Projects</a></li>
                <li><a href="#interview">Interview</a></li>
                <li><a href="#contact">Contact</a></li>
                <?php if (!empty($_SESSION['logged_in'])): ?>
                    <li><a href="admin.php" class="nav-admin-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="logout.php" class="nav-logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <?php else: ?>
                    <li><a href="#login" class="nav-login-tab"><i class="fas fa-lock"></i> Login</a></li>
                <?php endif; ?>
            </ul>

            <div class="nav-actions">
                <?php if (!empty($_SESSION['logged_in'])): ?>
                    <a href="admin.php" class="btn btn-primary" style="padding: 9px 18px; font-size: 0.88rem;">
                        <i class="fas fa-user-shield"></i> Admin Panel
                    </a>
                    <a href="logout.php" class="btn btn-secondary" style="padding: 9px 14px; font-size: 0.88rem;" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                <?php else: ?>
                    <a href="#contact" class="btn btn-primary" style="padding: 9px 20px; font-size: 0.88rem;">
                        <i class="fas fa-paper-plane"></i> Contact Me
                    </a>
                <?php endif; ?>
                <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-grid">
                <div class="hero-content">
                    <div class="hero-status-pill">
                        <span class="pulse-dot"></span> <strong>BSIT Student</strong> &bull; Datamex College of St. Adeline &bull; Seeking OJT / Internship
                    </div>
                    
                    <h1 class="hero-title">
                        Hi, I'm <span class="highlight"><?php echo htmlspecialchars($profile['full_name']); ?></span>
                    </h1>
                    
                    <div class="hero-subtitle">
                        <?php echo htmlspecialchars($profile['title']); ?>
                    </div>
                    
                    <p class="hero-description">I am an Information Technology student passionate about developing practical and innovative solutions using
                        <?php echo htmlspecialchars($profile['tagline']); ?> <strong>PHP &amp; MySQL</strong>, responsive web interfaces, and microcontroller logic with <strong>Arduino</strong>.
                    </p>

                    <div class="hero-cta">
                        <a href="#projects" class="btn btn-primary">
                            <i class="fas fa-laptop-code"></i> View Projects
                        </a>
                        <button type="button" class="btn btn-accent resume-open-trigger" id="heroResumeBtn" onclick="openResumeModal();">
                            <i class="fas fa-file-invoice"></i> Quick View Resume
                        </button>
                        <a href="#contact" class="btn btn-secondary">
                            <i class="fas fa-envelope"></i> Contact Me
                        </a>
                    </div>
                </div>

                <div class="hero-visual">
                    <div class="avatar-glow"></div>
                    
                    <!-- Floating Tech Badges -->
                    <div class="float-badge badge-top-left">
                        <i class="fab fa-php"></i>
                        <span>PHP &amp; MySQL</span>
                    </div>

                    <div class="float-badge badge-top-right">
                        <i class="fas fa-microchip"></i>
                        <span>Arduino &amp; IoT</span>
                    </div>

                    <div class="float-badge badge-bottom-right">
                        <i class="fas fa-graduation-cap"></i>
                        <span>BSIT (2023 - Present)</span>
                    </div>

                    <div class="avatar-wrapper">
                        <img src="assets/images/profile.png?v=<?php echo @filemtime($assets_dir . '/profile.png') ?: time(); ?>" onerror="this.onerror=null; this.src='get_image.php?v=<?php echo time(); ?>';" alt="<?php echo htmlspecialchars($profile['full_name']); ?>" class="avatar-photo">
                        <div class="avatar-overlay-card">
                            <div class="avatar-badge-check"><i class="fas fa-check-circle"></i></div>
                            <div>
                                <h4><?php echo htmlspecialchars($profile['full_name']); ?></h4>
                                <p><i class="fas fa-university"></i> Datamex College &bull; BSIT</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Bar (Student Focused) -->
            <div class="stats-bar">
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-graduation-cap"></i></div>
                    <div>
                        <div class="stat-number">BSIT</div>
                        <div class="stat-label">Datamex College (2023-Pres.)</div>
                    </div>
                </div>

                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-laptop-code"></i></div>
                    <div>
                        <div class="stat-number" data-target="3">3+</div>
                        <div class="stat-label">Academic &amp; IoT Projects</div>
                    </div>
                </div>

                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-code"></i></div>
                    <div>
                        <div class="stat-number" data-target="6">6+</div>
                        <div class="stat-label">Technical Skills</div>
                    </div>
                </div>

                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                    <div>
                        <div class="stat-number" data-target="100">100%</div>
                        <div class="stat-label">OJT &amp; Internship Ready</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="section" id="about">
        <div class="container">
            <div class="section-header">
                <span class="section-tag"><i class="fas fa-graduation-cap"></i> Academic Profile &amp; Bio</span>
                <h2 class="section-title">Driven IT Student with Practical Web &amp; IoT Skills</h2>
                <p class="section-desc">Learn about my education at Datamex College of St. Adeline, my technical coursework, and my goal to contribute as an OJT / junior developer.</p>
            </div>

            <div class="about-grid">
                <div class="about-card">
                    <h3 style="font-size: 1.4rem; margin-bottom: 16px;"><i class="fas fa-tools" style="color: var(--primary-blue);"></i> Technical Competencies</h3>
                    <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6;">
                        Throughout my BSIT coursework and self-directed projects, I have developed foundational and hands-on skills in software, database, and hardware systems:
                    </p>

                    <div class="about-highlights">
                        <div class="highlight-box">
                            <i class="fas fa-database"></i>
                            <h4>Relational Databases</h4>
                            <p>Structured MySQL schemas, table relations, query optimization, and phpMyAdmin administration.</p>
                        </div>
                        <div class="highlight-box">
                            <i class="fab fa-php"></i>
                            <h4>PHP Backend Engineering</h4>
                            <p>Modular PHP code, PDO prepared statements for security, session management, and CRUD workflows.</p>
                        </div>
                        <div class="highlight-box">
                            <i class="fas fa-microchip"></i>
                            <h4>IoT &amp; Hardware Prototyping</h4>
                            <p>Sensor integration, Arduino C++ coding, and microcontroller-based automation projects.</p>
                        </div>
                        <div class="highlight-box">
                            <i class="fas fa-file-excel"></i>
                            <h4>Data &amp; Office Operations</h4>
                            <p>Excel spreadsheet organization, file management, accurate data entry, and computer maintenance.</p>
                        </div>
                    </div>
                </div>

                <div class="about-details">
                    <p class="about-bio">
                        <?php echo nl2br(htmlspecialchars($profile['bio'])); ?>
                    </p>

                    <div class="info-list">
                        <div class="info-item">
                            <i class="fas fa-user-graduate"></i>
                            <div>
                                <span class="label">Candidate:</span>
                                <span class="value"><?php echo htmlspecialchars($profile['full_name']); ?></span>
                            </div>
                        </div>

                        <div class="info-item">
                            <i class="fas fa-university"></i>
                            <div>
                                <span class="label">College:</span>
                                <span class="value">Datamex College of St. Adeline</span>
                            </div>
                        </div>

                        <div class="info-item">
                            <i class="fas fa-book-reader"></i>
                            <div>
                                <span class="label">Degree:</span>
                                <span class="value">BS in Information Technology (BSIT)</span>
                            </div>
                        </div>

                        <div class="info-item">
                            <i class="fas fa-school"></i>
                            <div>
                                <span class="label">Senior High:</span>
                                <span class="value">Parañaque National High School - Main</span>
                            </div>
                        </div>

                        <div class="info-item">
                            <i class="fas fa-envelope-open-text"></i>
                            <div>
                                <span class="label">Email:</span>
                                <span class="value"><?php echo htmlspecialchars($profile['email']); ?></span>
                            </div>
                        </div>

                        <div class="info-item">
                            <i class="fas fa-phone-alt"></i>
                            <div>
                                <span class="label">Phone:</span>
                                <span class="value"><?php echo htmlspecialchars($profile['phone']); ?></span>
                            </div>
                        </div>

                        <div class="info-item">
                            <i class="fas fa-map-marked-alt"></i>
                            <div>
                                <span class="label">Location:</span>
                                <span class="value"><?php echo htmlspecialchars($profile['location']); ?></span>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 28px; display: flex; gap: 12px; flex-wrap: wrap;">
                        <button type="button" class="btn btn-primary resume-open-trigger" id="aboutResumeBtn" onclick="openResumeModal();">
                            <i class="fas fa-file-invoice"></i> Quick View Resume
                        </button>
                        <a href="#contact" class="btn btn-secondary">
                            <i class="fas fa-envelope"></i> Inquire for OJT / Internship
                        </a>
                    </div>
                </div>
            </div>

            <!-- On-Page Resume Showcase (Direct Image Resume Display) -->
            <div class="resume-showcase-box" id="resumeSection">
                <div class="resume-showcase-grid">
                    <div class="resume-showcase-preview">
                        <div class="resume-image-frame resume-open-trigger" onclick="openResumeModal();" title="Click to view resume image">
                            <img src="assets/images/resume.png?v=<?php echo @filemtime($assets_dir . '/resume.png') ?: time(); ?>" 
                                 onerror="this.onerror=null; this.src='get_image.php?img=resume&v=<?php echo time(); ?>';" 
                                 alt="Resume of Sean Adrei Cubio" 
                                 class="resume-onpage-img">
                            <div class="resume-frame-overlay">
                                <span class="resume-expand-badge"><i class="fas fa-search-plus"></i> Click for Full Image</span>
                            </div>
                        </div>
                    </div>

                    <div class="resume-showcase-content">
                        <div class="resume-showcase-tag">
                            <i class="fas fa-id-card"></i> Candidate Resume Image
                        </div>
                        <h3 class="resume-showcase-title"><?php echo htmlspecialchars($resume['full_name']); ?></h3>
                        <p class="resume-showcase-subtitle"><?php echo htmlspecialchars($resume['title']); ?></p>

                        <div class="resume-summary-card">
                            <div class="summary-item">
                                <div class="summary-icon"><i class="fas fa-graduation-cap"></i></div>
                                <div>
                                    <h5>College (2023 - Present)</h5>
                                    <p><strong>Datamex College of St. Adeline</strong> &mdash; BS Information Technology (BSIT)</p>
                                </div>
                            </div>

                            <div class="summary-item">
                                <div class="summary-icon"><i class="fas fa-school"></i></div>
                                <div>
                                    <h5>Senior High School (2021 - 2023)</h5>
                                    <p><strong>Parañaque National High School - Main</strong></p>
                                </div>
                            </div>

                            <div class="summary-item">
                                <div class="summary-icon"><i class="fas fa-check-double"></i></div>
                                <div>
                                    <h5>Skills Listed on Resume</h5>
                                    <p>Basic Computer Operation &bull; Excel &bull; File management &bull; Data entry</p>
                                </div>
                            </div>

                            <div class="summary-item">
                                <div class="summary-icon"><i class="fas fa-user-friends"></i></div>
                                <div>
                                    <h5>References &amp; Direct Contact</h5>
                                    <p>Ralph Tuliao (09685487448) &bull; Phone: <strong>09687480382</strong> &bull; Email: <strong>SeanCubio8@gmail.com</strong></p>
                                </div>
                            </div>
                        </div>

                        <div class="resume-showcase-actions">
                            <a href="#contact" class="btn btn-secondary">
                                <i class="fas fa-paper-plane"></i> Contact Sean
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Projects Section -->
    <section class="section section-alt" id="projects">
        <div class="container">
            <div class="section-header">
                <span class="section-tag"><i class="fas fa-laptop-code"></i> Student Portfolio Work</span>
                <h2 class="section-title">Academic &amp; Self-Directed Projects</h2>
                <p class="section-desc">Practical database systems, web applications, and embedded hardware developed during my BSIT degree at Datamex College of St. Adeline.</p>
            </div>

            <!-- Project Filter Buttons -->
            <div class="projects-filter">
                <button class="filter-btn active" data-filter="all">
                    <i class="fas fa-layer-group"></i> All Projects (3)
                </button>
                <button class="filter-btn" data-filter="Web App">
                    <i class="fas fa-utensils"></i> Food Ordering
                </button>
                <button class="filter-btn" data-filter="PHP/MySQL">
                    <i class="fas fa-hotel"></i> Hotel Booking
                </button>
                <button class="filter-btn" data-filter="IoT & Hardware">
                    <i class="fas fa-microchip"></i> Arduino IoT
                </button>
            </div>

            <!-- Projects Grid -->
            <div class="projects-grid">
                <?php foreach ($projects as $index => $proj): 
                    $pTitle = (string)($proj['title'] ?? '');
                    $pDesc = (string)($proj['description'] ?? '');
                    $pCategory = (string)($proj['category'] ?? 'Web App');
                    $pTech = (string)($proj['tech_stack'] ?? '');
                    
                    // Determine project visual assets & metadata
                    $imgKey = 'tapseafo';
                    $iconClass = 'fas fa-utensils';
                    $themeClass = 'theme-food';
                    $typeBadge = 'Online Food Platform';
                    $keyFeatures = ['Interactive Seafood Menu', 'Dynamic Cart & Checkout', 'Admin Dispatch Panel'];

                    if (stripos($pTitle, 'Hotel') !== false) {
                        $imgKey = 'hotel_booking';
                        $iconClass = 'fas fa-hotel';
                        $themeClass = 'theme-hotel';
                        $typeBadge = 'Hospitality Reservation';
                        $keyFeatures = ['Real-time Suite Availability', 'Instant Room Booking', 'Automated Invoices'];
                    } elseif (stripos($pTitle, 'Arduino') !== false || stripos($pTitle, 'Water') !== false) {
                        $imgKey = 'arduino_dispenser';
                        $iconClass = 'fas fa-faucet-drip';
                        $themeClass = 'theme-iot';
                        $typeBadge = 'Smart Hardware & IoT';
                        $keyFeatures = ['Ultrasonic Distance Sensor', 'Automated Solenoid Valve', 'Touchless Dispense'];
                    }

                    $imgSrc = "assets/images/{$imgKey}.jpg";
                    $imgFallback = "get_image.php?img={$imgKey}";
                ?>
                    <div class="project-card <?php echo $themeClass; ?>" data-category="<?php echo htmlspecialchars($pCategory); ?>">
                        <div class="project-thumb">
                            <img src="<?php echo $imgSrc; ?>" 
                                 onerror="this.onerror=null;this.src='<?php echo $imgFallback; ?>';" 
                                 alt="<?php echo htmlspecialchars($pTitle); ?>" 
                                 class="project-img" 
                                 loading="lazy">
                            <div class="project-thumb-overlay"></div>
                            
                            <a href="<?php echo htmlspecialchars($proj['demo_url']); ?>" class="project-thumb-btn" target="_blank" rel="noopener noreferrer">
                                <i class="fas fa-play"></i> Live Demo
                            </a>

                            <div class="project-badge-top">
                                <span class="project-category-badge">
                                    <i class="<?php echo $iconClass; ?>"></i> <?php echo htmlspecialchars($pCategory); ?>
                                </span>
                                <span class="project-index-num">0<?php echo $index + 1; ?></span>
                            </div>

                            <div class="project-system-tag">
                                <i class="fas fa-layer-group"></i> <?php echo $typeBadge; ?>
                            </div>
                        </div>

                        <div class="project-body">
                            <div class="project-meta-bar">
                                <span class="project-live-badge"><span class="live-dot"></span> Featured System</span>
                                <span class="project-spec-badge"><i class="fas fa-shield-alt"></i> Production Architecture</span>
                            </div>

                            <h3 class="project-title"><?php echo htmlspecialchars($pTitle); ?></h3>
                            <p class="project-desc"><?php echo htmlspecialchars($pDesc); ?></p>

                            <!-- Modern Key Capabilities -->
                            <div class="project-feature-list">
                                <?php foreach ($keyFeatures as $feature): ?>
                                    <div class="feature-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span><?php echo htmlspecialchars($feature); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Tech Stack Badges -->
                            <div class="project-tech">
                                <?php 
                                $tags = !empty($pTech) ? explode(',', $pTech) : [];
                                foreach ($tags as $tag): 
                                ?>
                                    <span class="tech-tag"><?php echo trim(htmlspecialchars($tag)); ?></span>
                                <?php endforeach; ?>
                            </div>

                            <!-- Project Interactive Action Buttons -->
                            <div class="project-actions">
                                <a href="<?php echo htmlspecialchars($proj['demo_url']); ?>" class="btn-project-demo" target="_blank" rel="noopener noreferrer">
                                    <i class="fas fa-play-circle"></i> Live Demo
                                    <i class="fas fa-external-link-alt demo-ext-icon"></i>
                                </a>
                                <button type="button" class="btn-project-preview" onclick="openDemoModal('<?php echo htmlspecialchars($proj['demo_url']); ?>', '<?php echo htmlspecialchars(addslashes($pTitle)); ?>');">
                                    <i class="fas fa-expand-alt"></i> Preview
                                </button>
                            </div>

                            <!-- Modern Project Footer Bar (Student Context) -->
                            <div class="project-card-footer">
                                <div class="project-status-pill">
                                    <i class="fas fa-graduation-cap"></i> BSIT Project &bull; Fully Tested
                                </div>
                                <div class="project-author-pill">
                                    <i class="fas fa-user-graduate"></i> Sean Adrei Cubio
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ==============================================================
         MOCK INTERVIEW SECTION (VIDEO LAYOUT & COMPANION)
         ============================================================== -->
    <section class="section section-alt" id="interview">
        <div class="container">
            <div class="section-header">
                <span class="section-tag"><i class="fas fa-video"></i> Video Assessment</span>
                <h2 class="section-title">Technical Mock Interview</h2>
                <p class="section-desc">Watch my recorded mock interview session covering technical problem solving, full-stack PHP &amp; MySQL architectures, and career dedication.</p>
            </div>

            <div class="interview-layout">
                <!-- Left: Video Player Card -->
                <div class="interview-video-card">
                    <!-- Browser / Player Top Bar -->
                    <div class="interview-video-header">
                        <div class="interview-status-tag">
                            <span class="live-dot"></span> Recorded Session
                        </div>
                        <div class="interview-video-meta">
                            <span class="video-badge"><i class="fas fa-user-graduate"></i> BSIT Candidate</span>
                            <span class="video-badge"><i class="fas fa-tachometer-alt"></i> 1080p HD</span>
                        </div>
                    </div>

                    <!-- Video Container -->
                    <div class="video-player-container" id="videoContainer">
                        <?php 
                        $video_sources = [
                            'andrie.mp4',
                            'assets/videos/andrie.mp4',
                            'assets/videos/mock_interview.mp4',
                            'assets/videos/mock_interview.webm'
                        ];
                        $active_video = null;
                        foreach ($video_sources as $vsrc) {
                            if (file_exists(__DIR__ . '/' . $vsrc)) {
                                $active_video = $vsrc;
                                break;
                            }
                        }
                        $has_video = ($active_video !== null);
                        ?>

                        <video id="interviewVideo" 
                               class="interview-video-element" 
                               controls 
                               playsinline 
                               preload="metadata" 
                               poster="assets/images/interview_poster.jpg?v=<?php echo @filemtime($assets_dir . '/interview_poster.jpg') ?: time(); ?>">
                            <?php if ($has_video): ?>
                                <source src="<?php echo htmlspecialchars($active_video); ?>?v=<?php echo @filemtime(__DIR__ . '/' . $active_video) ?: time(); ?>" type="video/mp4">
                            <?php endif; ?>
                            <source src="andrie.mp4" type="video/mp4">
                            <source src="assets/videos/andrie.mp4" type="video/mp4">
                            <source src="assets/videos/mock_interview.mp4" type="video/mp4">
                            Your browser does not support HTML5 video.
                        </video>

                        <!-- Custom Big Play Overlay Button (before playing) -->
                        <div class="video-play-overlay" id="videoPlayOverlay" onclick="toggleInterviewVideoPlay();">
                            <div class="play-pulse-btn">
                                <i class="fas fa-play"></i>
                            </div>
                            <div class="play-label">
                                <strong>Watch Mock Interview</strong>
                                <span>Click to Play / Pause</span>
                            </div>
                        </div>

                        <?php if (!$has_video): ?>
                        <!-- Informative Notice For The User on Where To Place Video -->
                        <div class="video-drop-notice">
                            <div class="notice-icon"><i class="fas fa-film"></i></div>
                            <div class="notice-text">
                                <strong>Video Slot Ready:</strong> Place your video file at <code>assets/videos/mock_interview.mp4</code> or <code>andrie.mp4</code> to stream your recording directly here.
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Video Quick Controls Bar -->
                    <div class="interview-player-bar">
                        <div class="player-controls-left">
                            <button type="button" class="player-btn" id="videoPlayBtn" onclick="toggleInterviewVideoPlay();" title="Play / Pause">
                                <i class="fas fa-play" id="playBtnIcon"></i> <span id="playBtnText">Play</span>
                            </button>
                            <button type="button" class="player-btn" id="videoMuteBtn" onclick="toggleInterviewVideoMute();" title="Mute / Unmute">
                                <i class="fas fa-volume-up" id="muteBtnIcon"></i>
                            </button>
                            <span class="video-timer" id="videoTimer">00:00 / --:--</span>
                        </div>

                        <div class="player-controls-right">
                            <button type="button" class="player-btn" id="videoSpeedBtn" onclick="cycleVideoSpeed();" title="Playback Speed">
                                <span id="speedText">1.0x</span>
                            </button>
                            <button type="button" class="player-btn" id="videoFullscreenBtn" onclick="toggleInterviewFullscreen();" title="Fullscreen">
                                <i class="fas fa-expand"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Video Source & Replacement Info Card -->
                    <div class="video-info-banner">
                        <i class="fas fa-check-circle" style="color: #10b981;"></i>
                        <span><strong>Interview Video Loaded:</strong> Playing <code><?php echo htmlspecialchars($active_video ?: 'andrie.mp4'); ?></code> (<?php echo htmlspecialchars($profile['full_name']); ?>). Full HD playback, chapters, and speed controls active.</span>
                    </div>
                </div>

                <!-- Right: Interview Companion & Question Chapters -->
                <div class="interview-companion-card">
                    <div class="companion-header">
                        <div class="candidate-avatar-mini">
                            <img src="assets/images/profile.png?v=<?php echo @filemtime($assets_dir . '/profile.png') ?: time(); ?>" alt="<?php echo htmlspecialchars($profile['full_name']); ?>">
                        </div>
                        <div>
                            <h3 class="candidate-name"><?php echo htmlspecialchars($profile['full_name']); ?></h3>
                            <p class="candidate-role"><?php echo htmlspecialchars($profile['title']); ?> &bull; BSIT Candidate</p>
                        </div>
                    </div>

                    <div class="interview-brief">
                        <p>This mock session replicates a real-world technical panel interview, assessing coding concepts, database normalization, system architecture, and team communication skills.</p>
                    </div>



                    <!-- Candidate Assessment Matrix -->
                    <div class="assessment-pills">
                        <span class="assessment-pill"><i class="fas fa-check-circle"></i> Clean Code</span>
                        <span class="assessment-pill"><i class="fas fa-check-circle"></i> Database Logic</span>
                        <span class="assessment-pill"><i class="fas fa-check-circle"></i> Clear Communication</span>
                        <span class="assessment-pill"><i class="fas fa-check-circle"></i> Work Ethic</span>
                    </div>

                    <!-- Companion Actions -->
                    <div class="companion-actions">
                        <a href="#contact" class="btn btn-primary" style="flex: 1; justify-content: center;">
                            <i class="fas fa-calendar-check"></i> Schedule Live Interview
                        </a>
                        <button type="button" class="btn btn-secondary resume-open-trigger" onclick="openResumeModal();" style="justify-content: center;">
                            <i class="fas fa-file-invoice"></i> View Resume
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="section" id="contact">
        <div class="container">
            <div class="section-header">
                <span class="section-tag"><i class="fas fa-envelope-open-text"></i> Connect &amp; Opportunities</span>
                <h2 class="section-title">Get In Touch with Sean</h2>
                <p class="section-desc">Interested in discussing an internship, OJT opportunity, entry-level web position, or tech collaboration? Feel free to reach out directly or send a message below.</p>
            </div>

            <div class="contact-grid">
                <!-- Contact Info Cards -->
                <div class="contact-info">
                    <div class="contact-card">
                        <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                        <div class="contact-meta">
                            <h4>Email Address</h4>
                            <p><a href="mailto:<?php echo htmlspecialchars($profile['email']); ?>"><?php echo htmlspecialchars($profile['email']); ?></a></p>
                        </div>
                    </div>

                    <div class="contact-card">
                        <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                        <div class="contact-meta">
                            <h4>Phone & Viber / WhatsApp</h4>
                            <p><?php echo htmlspecialchars($profile['phone']); ?></p>
                        </div>
                    </div>

                    <div class="contact-card">
                        <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="contact-meta">
                            <h4>Location</h4>
                            <p><?php echo htmlspecialchars($profile['location']); ?></p>
                        </div>
                    </div>

                    <div class="contact-card">
                        <div class="contact-icon"><i class="fas fa-database"></i></div>
                        <div class="contact-meta">
                            <h4>Database Storage</h4>
                            <p>Messages submitted through this form are safely recorded in the MySQL <code>messages</code> table.</p>
                        </div>
                    </div>
                </div>

                <!-- Contact Form Box -->
                <div class="contact-form-box">
                    <?php if ($feedback): ?>
                        <div class="alert-message alert-<?php echo $feedback['status']; ?>">
                            <i class="fas <?php echo $feedback['status'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                            <?php echo $feedback['message']; ?>
                        </div>
                    <?php endif; ?>

                    <form action="#contact" method="POST">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name" class="form-label">Your Full Name *</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">Your Email Address *</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="subject" class="form-label">Subject *</label>
                            <input type="text" id="subject" name="subject" class="form-control" placeholder="Internship / OJT Inquiry or Project Discussion" required value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="message" class="form-label">Your Message *</label>
                            <textarea id="message" name="message" class="form-control" placeholder="Write your message or project details here..." required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                        </div>

                        <button type="submit" name="send_message" class="btn btn-primary" style="width: 100%; padding: 14px;">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- ==============================================================
         ADMINISTRATOR LOGIN SECTION (#login)
         ============================================================== -->
    <section class="section section-alt" id="login">
        <div class="container">
            <div class="section-header">
                <span class="section-tag"><i class="fas fa-user-shield"></i> Administration Portal</span>
                <h2 class="section-title">Administrator Login</h2>
                <p class="section-desc">Authorized access for Sean Andrie Cubio to manage incoming contact inquiries, portfolio settings, and project telemetry.</p>
            </div>

            <div class="login-section-card">
                <div class="login-section-grid">
                    <!-- Left: Info & Demo Credentials -->
                    <div class="login-info-box">
                        <div class="login-info-badge">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h3>Admin Access Credentials</h3>
                        <p class="login-info-desc">Use the pre-configured credentials below to sign in to the administrative control panel.</p>

                        <div class="cred-pill-box">
                            <div class="cred-pill-title">
                                <i class="fas fa-key"></i> Authorized Credentials
                            </div>
                            <div class="cred-row">
                                <span class="cred-label">Username:</span>
                                <span class="cred-val">andrie123</span>
                            </div>
                            <div class="cred-row">
                                <span class="cred-label">Password:</span>
                                <span class="cred-val">andrie12345</span>
                            </div>
                        </div>

                        <div class="login-info-actions">
                            <button type="button" class="btn btn-sm btn-accent" onclick="autofillSectionCreds();" title="Populate fields with demo credentials">
                                <i class="fas fa-magic"></i> Auto-fill Credentials
                            </button>
                            <a href="login.php" class="btn btn-sm btn-outline-light">
                                <i class="fas fa-external-link-alt"></i> Full Page Login
                            </a>
                        </div>

                        <div class="admin-perks-list">
                            <div class="perk-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Review &amp; reply to submitted contact inquiries</span>
                            </div>
                            <div class="perk-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Manage live project showcases &amp; IoT demonstrations</span>
                            </div>
                            <div class="perk-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Inspect MySQL database status &amp; real-time telemetry</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Sign In Form -->
                    <div class="login-form-box">
                        <?php if (!empty($_SESSION['logged_in'])): ?>
                            <!-- Logged In State Card -->
                            <div class="logged-in-status-card">
                                <div class="logged-in-icon">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <h4>You are logged in as <strong>andrie123</strong></h4>
                                <p>You have full administrative privileges to the portfolio dashboard.</p>
                                
                                <div class="logged-in-btn-group">
                                    <a href="admin.php" class="btn btn-primary">
                                        <i class="fas fa-tachometer-alt"></i> Open Admin Dashboard
                                    </a>
                                    <a href="logout.php" class="btn btn-secondary">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </a>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Form State -->
                            <div class="login-form-header">
                                <h3>Admin Sign In</h3>
                                <p>Enter your administrator credentials to proceed</p>
                            </div>

                            <?php if (!empty($login_error)): ?>
                                <div class="alert-message alert-error" style="margin-bottom: 20px;">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span><?php echo htmlspecialchars($login_error); ?></span>
                                </div>
                            <?php endif; ?>

                            <form action="index.php#login" method="POST" id="sectionLoginForm">
                                <input type="hidden" name="login_submit" value="1">
                                
                                <div class="form-group">
                                    <label for="secUsername" class="form-label">Username *</label>
                                    <div class="input-wrapper">
                                        <input type="text" id="secUsername" name="username" class="form-control" placeholder="andrie123" required autocomplete="username" value="andrie123">
                                        <i class="fas fa-user input-icon"></i>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="secPassword" class="form-label">Password *</label>
                                    <div class="input-wrapper">
                                        <input type="password" id="secPassword" name="password" class="form-control" placeholder="andrie12345" required autocomplete="current-password" value="andrie12345">
                                        <i class="fas fa-lock input-icon"></i>
                                        <button type="button" class="toggle-password-btn" onclick="toggleSecPassword();" title="Show / Hide Password">
                                            <i class="fas fa-eye" id="secEyeIcon"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="login-form-options">
                                    <label class="remember-check">
                                        <input type="checkbox" name="remember" checked>
                                        <span>Keep me logged in</span>
                                    </label>
                                    <button type="button" class="btn-text-modal" onclick="openLoginModal(event);">
                                        <i class="fas fa-window-restore"></i> Quick Popup
                                    </button>
                                </div>

                                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-weight: 700; margin-top: 6px;">
                                    <i class="fas fa-sign-in-alt"></i> Sign In to Administrator Dashboard
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="#home" class="logo">
                        <div class="logo-badge">SAC</div>
                        <span><?php echo htmlspecialchars($profile['full_name']); ?></span>
                    </a>
                    <p>
                        An Information Technology (BSIT) student portfolio built with clean PHP, structured MySQL, and modern responsive CSS showcasing coursework systems, IoT hardware, and technical mock assessments.
                    </p>
                </div>

                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="#home"><i class="fas fa-chevron-right"></i> Home</a></li>
                        <li><a href="#about"><i class="fas fa-chevron-right"></i> About Me</a></li>
                        <li><a href="#projects"><i class="fas fa-chevron-right"></i> Projects</a></li>
                        <li><a href="#interview"><i class="fas fa-chevron-right"></i> Mock Interview</a></li>
                        <li><a href="#contact"><i class="fas fa-chevron-right"></i> Contact</a></li>
                        <li><a href="#login"><i class="fas fa-chevron-right"></i> Admin Login</a></li>
                    </ul>
                </div>

            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <strong><?php echo htmlspecialchars($profile['full_name']); ?></strong>. All rights reserved.</p>
                <p style="color: var(--text-dim); font-size: 13px;">Built with PHP, MySQL &amp; Vanilla CSS</p>
            </div>
        </div>
    </footer>

    <!-- ==============================================================
         QUICK VIEW RESUME MODAL
         ============================================================== -->
    <div class="resume-modal-overlay" id="resumeModal" role="dialog" aria-modal="true" aria-labelledby="resumeModalTitle" onclick="if(event.target === this || event.target.id === 'resumeModalBackdrop') closeResumeModal();">
        <div class="resume-modal-backdrop" id="resumeModalBackdrop"></div>
        <div class="resume-modal-dialog" onclick="event.stopPropagation();">
            <!-- Modal Header -->
            <div class="resume-modal-header">
                <div class="resume-modal-title-box">
                    <div class="resume-modal-icon">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <div>
                        <h3 id="resumeModalTitle" class="resume-modal-title"><?php echo htmlspecialchars($resume['full_name']); ?> &mdash; Resume</h3>
                        <span class="resume-modal-subtitle">Bachelor of Science in Information Technology (BSIT)</span>
                    </div>
                </div>

                <!-- Action Controls -->
                <div class="resume-modal-actions">
                    <div class="resume-zoom-bar">
                        <button type="button" class="modal-tool-btn" id="resumeZoomOut" title="Zoom Out" aria-label="Zoom Out">
                            <i class="fas fa-search-minus"></i>
                        </button>
                        <span class="zoom-indicator" id="resumeZoomLevel">100%</span>
                        <button type="button" class="modal-tool-btn" id="resumeZoomIn" title="Zoom In" aria-label="Zoom In">
                            <i class="fas fa-search-plus"></i>
                        </button>
                        <button type="button" class="modal-tool-btn" id="resumeZoomReset" title="Fit" aria-label="Fit">
                            <i class="fas fa-expand-arrows-alt"></i> Fit
                        </button>
                    </div>

                    <a href="assets/images/resume.png" target="_blank" class="modal-tool-btn" title="Open Full Image in New Tab">
                        <i class="fas fa-external-link-alt"></i> <span>Open Full</span>
                    </a>
                    <button type="button" class="modal-tool-btn" id="printResumeBtn" title="Print Resume">
                        <i class="fas fa-print"></i>
                    </button>
                    <button type="button" class="modal-tool-btn modal-close-btn" id="closeResumeModal" onclick="closeResumeModal();" aria-label="Close Modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body (Resume Image Viewer) -->
            <div class="resume-modal-body" id="resumeModalBody">
                <div class="resume-image-container" id="resumeImageContainer">
                    <img src="assets/images/resume.png?v=<?php echo @filemtime($assets_dir . '/resume.png') ?: time(); ?>" 
                         onerror="this.onerror=null; this.src='get_image.php?img=resume&v=<?php echo time(); ?>';" 
                         alt="Resume of <?php echo htmlspecialchars($resume['full_name']); ?>" 
                         id="resumePreviewImg"
                         class="resume-preview-img">
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="resume-modal-footer">
                <div class="resume-footer-info">
                    <i class="fas fa-check-circle" style="color: #10b981;"></i> 
                    <span><strong><?php echo htmlspecialchars($resume['full_name']); ?></strong> &bull; Phone: <strong>09687480382</strong> &bull; Email: <strong>SeanCubio8@gmail.com</strong></span>
                </div>
                <div class="resume-footer-btns">
                    <button type="button" class="btn btn-primary btn-sm" onclick="closeResumeModal(); window.location.href='#contact';">
                        <i class="fas fa-paper-plane"></i> Contact Sean
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="closeResumeModal();">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ==============================================================
         LIVE DEMO INTERACTIVE PREVIEW MODAL
         ============================================================== -->
    <div class="demo-modal-overlay" id="demoModal" role="dialog" aria-modal="true" aria-labelledby="demoModalTitle" onclick="if(event.target === this || event.target.id === 'demoModalBackdrop') closeDemoModal();">
        <div class="demo-modal-backdrop" id="demoModalBackdrop"></div>
        <div class="demo-modal-dialog" onclick="event.stopPropagation();">
            <!-- Modal Header -->
            <div class="demo-modal-header">
                <div class="demo-modal-title-box">
                    <div class="demo-modal-icon">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <div>
                        <h3 id="demoModalTitle" class="demo-modal-title">Live System Demonstration</h3>
                        <span class="demo-modal-subtitle">Interactive Project Showcase &bull; Sean Adrei Cubio (BSIT)</span>
                    </div>
                </div>

                <div class="demo-modal-actions">
                    <a href="#" id="demoModalExternalBtn" target="_blank" class="modal-tool-btn" title="Open Full Demo in New Tab" rel="noopener noreferrer">
                        <i class="fas fa-external-link-alt"></i> <span>Open Full Screen</span>
                    </a>
                    <button type="button" class="modal-tool-btn modal-close-btn" onclick="closeDemoModal();" aria-label="Close Preview">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body (Iframe) -->
            <div class="demo-modal-body">
                <div class="demo-iframe-wrapper">
                    <div class="demo-iframe-loader" id="demoIframeLoader">
                        <div class="loader-spinner"></div>
                        <span>Loading Live System Demo...</span>
                    </div>
                    <iframe id="demoModalIframe" src="" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="demo-modal-footer">
                <div class="demo-footer-info">
                    <span class="live-dot" style="display:inline-block; margin-right: 6px;"></span>
                    <span>Interactive Prototype Running Live &bull; Built with PHP, MySQL &amp; IoT Architecture</span>
                </div>
                <div class="demo-footer-btns">
                    <a href="#" id="demoModalLaunchBtn" target="_blank" class="btn btn-primary btn-sm" rel="noopener noreferrer">
                        <i class="fas fa-external-link-alt"></i> Open Full Page
                    </a>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="closeDemoModal();">
                        Close Preview
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ==============================================================
         ADMINISTRATOR LOGIN MODAL
         ============================================================== -->
    <div class="login-modal-overlay" id="loginModal" role="dialog" aria-modal="true" aria-labelledby="loginModalTitle" onclick="if(event.target === this || event.target.id === 'loginModalBackdrop') closeLoginModal();">
        <div class="login-modal-backdrop" id="loginModalBackdrop"></div>
        <div class="login-modal-dialog" onclick="event.stopPropagation();">
            <!-- Modal Header -->
            <div class="login-modal-header">
                <div class="login-modal-title-box">
                    <div class="login-modal-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <h3 id="loginModalTitle" class="login-modal-title">Admin Sign In</h3>
                        <span class="login-modal-subtitle">Sean Andrie Cubio &bull; Portfolio Management</span>
                    </div>
                </div>
                <button type="button" class="modal-tool-btn modal-close-btn" onclick="closeLoginModal();" aria-label="Close Login">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="login-modal-body">
                <!-- Hint & 1-Click Autofill Pill -->
                <div class="login-hint-pill">
                    <div class="hint-text">
                        <i class="fas fa-key"></i> User: <code>andrie123</code> &bull; Pass: <code>andrie12345</code>
                    </div>
                    <button type="button" class="btn-modal-autofill" onclick="autofillModalCreds();">
                        Autofill
                    </button>
                </div>

                <?php if (!empty($login_error)): ?>
                    <div class="login-modal-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo htmlspecialchars($login_error); ?></span>
                    </div>
                <?php endif; ?>

                <form action="index.php" method="POST" id="modalLoginForm">
                    <input type="hidden" name="login_submit" value="1">
                    
                    <div class="modal-form-group">
                        <label for="modalUsername" class="modal-form-label">Username</label>
                        <div class="modal-input-wrap">
                            <i class="fas fa-user modal-field-icon"></i>
                            <input type="text" id="modalUsername" name="username" class="modal-form-control" placeholder="andrie123" required autocomplete="username" value="andrie123">
                        </div>
                    </div>

                    <div class="modal-form-group">
                        <label for="modalPassword" class="modal-form-label">Password</label>
                        <div class="modal-input-wrap">
                            <i class="fas fa-lock modal-field-icon"></i>
                            <input type="password" id="modalPassword" name="password" class="modal-form-control" placeholder="andrie12345" required autocomplete="current-password" value="andrie12345">
                            <button type="button" class="modal-pwd-toggle" onclick="toggleModalPassword();" title="Toggle visibility">
                                <i class="fas fa-eye" id="modalEyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="modal-form-row">
                        <label class="remember-label">
                            <input type="checkbox" name="remember" checked> Keep me signed in
                        </label>
                        <a href="login.php" class="modal-direct-link">Full page login &rarr;</a>
                    </div>

                    <button type="submit" class="btn btn-primary modal-login-submit">
                        <i class="fas fa-sign-in-alt"></i> Sign In to Dashboard
                    </button>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="login-modal-footer">
                <i class="fas fa-shield-alt" style="color: #0ea5e9;"></i>
                <span>Authorized portfolio administrator portal.</span>
            </div>
        </div>
    </div>

    <!-- Modal Core Inline Functions (Instant, Zero Cache Issues) -->
    <script>
        function openResumeModal() {
            var m = document.getElementById('resumeModal');
            if (m) {
                m.classList.add('active');
                m.style.setProperty('display', 'flex', 'important');
                document.body.classList.add('resume-modal-open');
                var imgCont = document.getElementById('resumeImageContainer');
                if (imgCont) {
                    imgCont.style.maxWidth = '700px';
                }
                var zoomLvl = document.getElementById('resumeZoomLevel');
                if (zoomLvl) {
                    zoomLvl.textContent = '100%';
                }
                if (typeof resetResumeZoom === 'function') {
                    resetResumeZoom();
                }
            }
        }
        function closeResumeModal() {
            var m = document.getElementById('resumeModal');
            if (m) {
                m.classList.remove('active');
                m.style.setProperty('display', 'none', 'important');
                document.body.classList.remove('resume-modal-open');
            }
        }
        window.openResumeModal = openResumeModal;
        window.closeResumeModal = closeResumeModal;
        window.closeResumeModalFunction = closeResumeModal;

        function openDemoModal(url, title) {
            var modal = document.getElementById('demoModal');
            var iframe = document.getElementById('demoModalIframe');
            var titleEl = document.getElementById('demoModalTitle');
            var extBtn = document.getElementById('demoModalExternalBtn');
            var launchBtn = document.getElementById('demoModalLaunchBtn');
            var loader = document.getElementById('demoIframeLoader');

            if (!modal || !iframe) {
                window.open(url, '_blank');
                return;
            }

            if (titleEl && title) titleEl.textContent = title + ' - Live Demo';
            if (extBtn) extBtn.href = url;
            if (launchBtn) launchBtn.href = url;
            if (loader) loader.style.display = 'flex';

            iframe.onload = function() {
                if (loader) loader.style.display = 'none';
            };

            iframe.src = url;
            modal.classList.add('active');
            modal.style.setProperty('display', 'flex', 'important');
            document.body.classList.add('demo-modal-open');
        }

        function closeDemoModal() {
            var modal = document.getElementById('demoModal');
            var iframe = document.getElementById('demoModalIframe');
            if (modal) {
                modal.classList.remove('active');
                modal.style.setProperty('display', 'none', 'important');
                document.body.classList.remove('demo-modal-open');
            }
            if (iframe) {
                iframe.src = 'about:blank';
            }
        }
        window.openDemoModal = openDemoModal;
        window.closeDemoModal = closeDemoModal;

        function openLoginModal(e) {
            if (e && e.preventDefault) e.preventDefault();
            var m = document.getElementById('loginModal');
            if (m) {
                m.classList.add('active');
                m.style.setProperty('display', 'flex', 'important');
                document.body.classList.add('login-modal-open');
                var u = document.getElementById('modalUsername');
                if (u) setTimeout(function(){ u.focus(); }, 120);
            }
        }

        function closeLoginModal() {
            var m = document.getElementById('loginModal');
            if (m) {
                m.classList.remove('active');
                m.style.setProperty('display', 'none', 'important');
                document.body.classList.remove('login-modal-open');
            }
        }

        function autofillModalCreds() {
            var u = document.getElementById('modalUsername');
            var p = document.getElementById('modalPassword');
            if (u) u.value = 'andrie123';
            if (p) p.value = 'andrie12345';
        }

        function toggleModalPassword() {
            var p = document.getElementById('modalPassword');
            var icon = document.getElementById('modalEyeIcon');
            if (!p) return;
            if (p.type === 'password') {
                p.type = 'text';
                if (icon) icon.className = 'fas fa-eye-slash';
            } else {
                p.type = 'password';
                if (icon) icon.className = 'fas fa-eye';
            }
        }

        function autofillSectionCreds() {
            var u = document.getElementById('secUsername');
            var p = document.getElementById('secPassword');
            if (u) u.value = 'andrie123';
            if (p) p.value = 'andrie12345';
        }

        function toggleSecPassword() {
            var p = document.getElementById('secPassword');
            var icon = document.getElementById('secEyeIcon');
            if (!p) return;
            if (p.type === 'password') {
                p.type = 'text';
                if (icon) icon.className = 'fas fa-eye-slash';
            } else {
                p.type = 'password';
                if (icon) icon.className = 'fas fa-eye';
            }
        }

        window.openLoginModal = openLoginModal;
        window.closeLoginModal = closeLoginModal;
        window.autofillModalCreds = autofillModalCreds;
        window.toggleModalPassword = toggleModalPassword;
        window.autofillSectionCreds = autofillSectionCreds;
        window.toggleSecPassword = toggleSecPassword;

        function toggleInterviewVideoPlay() {
            var v = document.getElementById('interviewVideo');
            var overlay = document.getElementById('videoPlayOverlay');
            if (!v) return;
            if (v.paused || v.ended) {
                v.play().then(function() {
                    if (overlay) overlay.classList.add('playing');
                }).catch(function() {});
            } else {
                v.pause();
                if (overlay) overlay.classList.remove('playing');
            }
        }
        function toggleInterviewVideoMute() {
            var v = document.getElementById('interviewVideo');
            if (v) v.muted = !v.muted;
        }
        function seekInterviewVideo(sec) {
            var v = document.getElementById('interviewVideo');
            var overlay = document.getElementById('videoPlayOverlay');
            if (v) {
                v.currentTime = sec;
                v.play().catch(function() {});
                if (overlay) overlay.classList.add('playing');
            }
        }
        function cycleVideoSpeed() {
            var v = document.getElementById('interviewVideo');
            var st = document.getElementById('speedText');
            if (!v) return;
            var rates = [1, 1.25, 1.5, 2];
            var cur = rates.indexOf(v.playbackRate);
            var next = rates[(cur + 1) % rates.length];
            v.playbackRate = next;
            if (st) st.textContent = next + 'x';
        }
        function toggleInterviewFullscreen() {
            var v = document.getElementById('interviewVideo');
            if (v && v.requestFullscreen) v.requestFullscreen();
        }
        window.toggleInterviewVideoPlay = toggleInterviewVideoPlay;
        window.toggleInterviewVideoMute = toggleInterviewVideoMute;
        window.seekInterviewVideo = seekInterviewVideo;
        window.cycleVideoSpeed = cycleVideoSpeed;
        window.toggleInterviewFullscreen = toggleInterviewFullscreen;
    </script>

    <?php if (!empty($login_error) || isset($_GET['login'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof openLoginModal === 'function') {
                openLoginModal();
            }
        });
    </script>
    <?php endif; ?>

    <!-- Back to Top Floating Button -->
    <button class="back-to-top" id="backToTop" aria-label="Back to Top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Main JavaScript File -->
    <script src="assets/js/main.js?v=<?php echo time(); ?>"></script>
</body>
</html>
