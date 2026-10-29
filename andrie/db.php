<?php
/**
 * Database Connection File (db.php)
 * Portfolio for Sean Andrie Cubio
 * Compatible with XAMPP (Apache & MySQL)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

$pdo = null;
$db_connected = false;
$db_error = null;
$connected_db = 'andrie_db';

// List of supported database names to auto-detect
$candidate_dbs = ['andrie_db', 'portfolio_db'];

// Common PDO connection options
$pdo_options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Helper function to safely execute multi-statement SQL script
function executeSqlScript($pdo, $sql_content) {
    // Enable multi-statements if supported
    try {
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
    } catch (Exception $opt_e) {}

    // Split SQL by semicolons, preserving statements
    $queries = explode(';', $sql_content);
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            try {
                $pdo->exec($query);
            } catch (PDOException $qe) {
                // Ignore innocuous errors like duplicate table creation
                error_log("SQL Execution Notice: " . $qe->getMessage());
            }
        }
    }
}

// 1. Try connecting to one of the candidate databases
foreach ($candidate_dbs as $dbname) {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . $dbname . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $pdo_options);
        $db_connected = true;
        $connected_db = $dbname;
        break;
    } catch (PDOException $e) {
        // Continue to check next candidate
        $db_error = $e->getMessage();
    }
}

// 2. If neither database exists, attempt auto-creation of andrie_db
if (!$db_connected) {
    try {
        $server_dsn = "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET;
        $temp_pdo = new PDO($server_dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $default_db = 'andrie_db';
        $temp_pdo->exec("CREATE DATABASE IF NOT EXISTS `" . $default_db . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        // Reconnect to the created database
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . $default_db . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $pdo_options);

        // Execute table migrations from database.sql
        $sql_file = __DIR__ . '/database.sql';
        if (file_exists($sql_file)) {
            $sql_content = file_get_contents($sql_file);
            executeSqlScript($pdo, $sql_content);
        }

        $db_connected = true;
        $connected_db = $default_db;
        $db_error = null;
    } catch (PDOException $init_error) {
        $db_error = "Database initialization error: " . $init_error->getMessage();
        $db_connected = false;
    }
}

if (!defined('DB_NAME')) {
    define('DB_NAME', $connected_db);
}

// Auto-sync users table & default admin credentials (andrie123 / andrie12345)
if ($db_connected && $pdo) {
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `username` VARCHAR(50) NOT NULL UNIQUE,
            `password` VARCHAR(255) NOT NULL,
            `role` VARCHAR(20) DEFAULT 'admin',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $u_check = $pdo->prepare("SELECT `id` FROM `users` WHERE `username` = 'andrie123' LIMIT 1");
        $u_check->execute();
        if (!$u_check->fetch()) {
            $u_ins = $pdo->prepare("INSERT INTO `users` (`username`, `password`, `role`) VALUES ('andrie123', 'andrie12345', 'admin')");
            $u_ins->execute();
        }
    } catch (Exception $u_e) {}
}

/**
 * Authentication helper function
 * Accepts credentials and returns validation status
 */
function authenticateUser($username, $password, $pdo = null) {
    $username = trim($username);
    $password = trim($password);

    // 1. Master credentials check (User specification: username 'andrie123', password 'andrie12345')
    if ($username === 'andrie123' && $password === 'andrie12345') {
        return [
            'success' => true,
            'user' => [
                'id' => 1,
                'username' => 'andrie123',
                'role' => 'admin',
                'name' => 'Sean Andrie Cubio'
            ]
        ];
    }

    // 2. Database validation if connected
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `username` = :u LIMIT 1");
            $stmt->execute([':u' => $username]);
            $user = $stmt->fetch();
            if ($user) {
                if ($user['password'] === $password || password_verify($password, $user['password'])) {
                    return [
                        'success' => true,
                        'user' => [
                            'id' => $user['id'],
                            'username' => $user['username'],
                            'role' => $user['role'] ?? 'admin',
                            'name' => 'Sean Andrie Cubio'
                        ]
                    ];
                }
            }
        } catch (Exception $e) {}
    }

    return [
        'success' => false,
        'message' => 'Invalid username or password. Please use username: andrie123'
    ];
}

/**
 * Helper function to retrieve profile information
 */
function getProfileData($pdo) {
    $fallback = [
        'full_name' => 'Sean Adrei Cubio',
        'title' => 'BSIT Student & Aspiring Web Developer',
        'tagline' => 'Information Technology student passionate about building practical PHP, MySQL, and IoT solutions.',
        'bio' => "Hello! I am Sean Adrei Cubio, an Information Technology (BSIT) undergraduate student at Datamex College of St. Adeline.\n\nI build web applications with a solid foundation in PHP backends, MySQL relational databases, responsive design, and Arduino microcontroller prototyping. I am eager to secure an internship, OJT, or entry-level role where I can apply my knowledge, gain hands-on industry experience, and contribute to team goals.",
        'email' => 'SeanCubio8@gmail.com',
        'phone' => '09687480382',
        'location' => 'Parañaque City, Metro Manila, Philippines',
        'school' => 'Datamex College of St. Adeline',
        'course' => 'Bachelor of Science in Information Technology (BSIT)',
        'batch' => '2023 - Present',
        'high_school' => 'Parañaque National High School - Main',
        'projects_completed' => 3,
        'tech_skills_count' => 6,
        'ojt_status' => 'OJT & Internship Ready',
        'github_url' => 'https://github.com',
        'linkedin_url' => 'https://linkedin.com',
        'facebook_url' => 'https://facebook.com',
        'resume_image' => 'assets/images/resume.png',
        'resume_file' => 'assets/images/resume.png',
        'profile_image' => 'assets/images/profile.png'
    ];

    if (!$pdo) return $fallback;

    try {
        // Sync database records to keep student information accurate
        try {
            $pdo->prepare("UPDATE `profile_info` SET `full_name` = :name, `title` = :title, `email` = :email, `phone` = :phone, `location` = :loc WHERE `id` = 1")
                ->execute([
                    ':name'  => $fallback['full_name'],
                    ':title' => $fallback['title'],
                    ':email' => $fallback['email'],
                    ':phone' => $fallback['phone'],
                    ':loc'   => $fallback['location']
                ]);
        } catch (Exception $sync_err) {}

        $stmt = $pdo->query("SELECT * FROM `profile_info` LIMIT 1");
        $data = $stmt->fetch();
        if ($data && is_array($data)) {
            $merged = array_merge($fallback, $data);
            $merged['email'] = $fallback['email'];
            $merged['phone'] = $fallback['phone'];
            $merged['location'] = $fallback['location'];
            $merged['full_name'] = $fallback['full_name'];
            $merged['title'] = $fallback['title'];
            $merged['school'] = $fallback['school'];
            $merged['course'] = $fallback['course'];
            $merged['batch'] = $fallback['batch'];
            return $merged;
        }
        return $fallback;
    } catch (Exception $e) {
        return $fallback;
    }
}

/**
 * Helper function to retrieve skills
 */
function getSkillsData($pdo) {
    $fallback = [
        ['name' => 'PHP', 'category' => 'Backend', 'proficiency' => 90, 'icon' => 'fab fa-php'],
        ['name' => 'MySQL & Database Design', 'category' => 'Database', 'proficiency' => 88, 'icon' => 'fas fa-database'],
        ['name' => 'HTML5 & Semantic Markup', 'category' => 'Frontend', 'proficiency' => 95, 'icon' => 'fab fa-html5'],
        ['name' => 'CSS3 & Modern Flex/Grid', 'category' => 'Frontend', 'proficiency' => 92, 'icon' => 'fab fa-css3-alt'],
        ['name' => 'JavaScript (ES6+)', 'category' => 'Frontend', 'proficiency' => 85, 'icon' => 'fab fa-js'],
        ['name' => 'Responsive Web Design', 'category' => 'Frontend', 'proficiency' => 94, 'icon' => 'fas fa-mobile-alt'],
        ['name' => 'Git & GitHub', 'category' => 'Tools', 'proficiency' => 85, 'icon' => 'fab fa-github'],
        ['name' => 'XAMPP & Apache', 'category' => 'Tools', 'proficiency' => 90, 'icon' => 'fas fa-server']
    ];

    if (!$pdo) return $fallback;

    try {
        $stmt = $pdo->query("SELECT * FROM `skills` ORDER BY `category`, `display_order` ASC");
        $skills = $stmt->fetchAll();
        return (!empty($skills) && is_array($skills)) ? $skills : $fallback;
    } catch (Exception $e) {
        return $fallback;
    }
}

/**
 * Helper function to retrieve projects (Exclusively Sean's 3 featured projects)
 */
function getProjectsData($pdo) {
    $fallback = [
        [
            'id' => 1,
            'title' => 'Tapseafo: Online Food Ordering',
            'description' => 'A full-featured online food ordering and delivery web application featuring interactive seafood & specialty food menus, dynamic shopping cart management, instant checkout, and an administrative order dispatch panel.',
            'category' => 'Web App',
            'tech_stack' => 'PHP, MySQL, JavaScript, CSS3, Bootstrap',
            'image_url' => 'assets/images/tapseafo.jpg',
            'features' => 'Menu Catalog, Smart Cart, Instant Checkout, Order Dispatch',
            'demo_url' => 'demos/tapseafo.php',
            'github_url' => '#',
            'is_featured' => 1
        ],
        [
            'id' => 2,
            'title' => 'Hotel Booking Website',
            'description' => 'A comprehensive online hospitality booking platform allowing guests to browse luxury suites, check real-time date availability, calculate pricing, and secure online room reservations with automated booking receipts.',
            'category' => 'PHP/MySQL',
            'tech_stack' => 'PHP, PDO, MySQL, Ajax, CSS3',
            'image_url' => 'assets/images/hotel_booking.jpg',
            'features' => 'Suite Showcase, Real-Time Availability, Instant Reservations, Booking Invoices',
            'demo_url' => 'demos/hotel.php',
            'github_url' => '#',
            'is_featured' => 1
        ],
        [
            'id' => 3,
            'title' => 'Arduino Water Dispenser',
            'description' => 'An automated touchless smart water dispensing system built with an Arduino microcontroller, ultrasonic distance sensors for cup detection, relay-operated solenoid valve, and LED status indicators.',
            'category' => 'IoT & Hardware',
            'tech_stack' => 'Arduino, C/C++, Ultrasonic Sensor, Solenoid Valve, Relay Module',
            'image_url' => 'assets/images/arduino_dispenser.jpg',
            'features' => 'Ultrasonic Cup Detection, Auto Solenoid Valve, Status LEDs, Touchless Dispense',
            'demo_url' => 'demos/arduino.php',
            'github_url' => '#',
            'is_featured' => 1
        ]
    ];

    if (!$pdo) return $fallback;

    try {
        // Sync project images and live demo URLs to database
        try {
            $pdo->exec("UPDATE `projects` SET `image_url` = 'assets/images/tapseafo.jpg', `demo_url` = 'demos/tapseafo.php' WHERE `id` = 1");
            $pdo->exec("UPDATE `projects` SET `image_url` = 'assets/images/hotel_booking.jpg', `demo_url` = 'demos/hotel.php' WHERE `id` = 2");
            $pdo->exec("UPDATE `projects` SET `image_url` = 'assets/images/arduino_dispenser.jpg', `demo_url` = 'demos/arduino.php' WHERE `id` = 3");
        } catch (Exception $sync_p_err) {}

        $stmt = $pdo->query("SELECT * FROM `projects` ORDER BY `id` ASC");
        $projects = $stmt->fetchAll();
        return (!empty($projects) && is_array($projects)) ? $projects : $fallback;
    } catch (Exception $e) {
        return $fallback;
    }
}

/**
 * Helper function to retrieve resume information
 */
function getResumeData() {
    return [
        'full_name'  => 'Sean Adrei Cubio',
        'title'      => 'BSIT Student & Web Developer',
        'email'      => 'SeanCubio8@gmail.com',
        'phone'      => '09687480382',
        'location'   => 'Parañaque, Metro Manila, Philippines',
        'objective'  => 'To obtain a position in a reputable company where I can apply my knowledge, develop new skills, and gain valuable work experience. I am willing to learn, take on new challenges, and work with a team to help achieve the company\'s goals while improving my professional growth.',
        'education'  => [
            [
                'level'       => 'COLLEGE',
                'period'      => '2023 - Present',
                'institution' => 'Datamex College of St. Adeline',
                'course'      => 'Bachelor of Science in Information Technology (BSIT)'
            ],
            [
                'level'       => 'SENIOR HIGH SCHOOL',
                'period'      => '2021 - 2023',
                'institution' => 'Parañaque National High School - Main',
                'course'      => 'Senior High School Graduate'
            ]
        ],
        'skills'     => [
            'Basic Computer Operation',
            'Excel',
            'File Management',
            'Data Entry',
            'PHP & MySQL Development',
            'Responsive Web Design'
        ],
        'references' => [
            [
                'name'  => 'Ralph Tuliao',
                'phone' => '09685487448'
            ]
        ],
        'image_url'    => 'assets/images/resume.png',
        'fallback_url' => 'get_image.php?img=resume'
    ];
}
?>
