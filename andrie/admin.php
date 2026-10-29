<?php
/**
 * Administrator Control Panel (admin.php)
 * Portfolio for Sean Andrie Cubio
 */

require_once __DIR__ . '/db.php';

// Authentication Check: only accessible to logged in admin (andrie123)
if (empty($_SESSION['logged_in']) || empty($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$notice = '';
$notice_type = 'success';

// Handle Message Management Actions
if (isset($_GET['mark_read']) && $pdo) {
    $msg_id = intval($_GET['mark_read']);
    try {
        $stmt = $pdo->prepare("UPDATE `messages` SET `is_read` = 1 WHERE `id` = :id");
        $stmt->execute([':id' => $msg_id]);
        $notice = "Message #{$msg_id} marked as read.";
    } catch (Exception $e) {
        $notice = "Error: " . $e->getMessage();
        $notice_type = 'danger';
    }
}

if (isset($_GET['delete_msg']) && $pdo) {
    $msg_id = intval($_GET['delete_msg']);
    try {
        $stmt = $pdo->prepare("DELETE FROM `messages` WHERE `id` = :id");
        $stmt->execute([':id' => $msg_id]);
        $notice = "Message #{$msg_id} has been permanently deleted.";
    } catch (Exception $e) {
        $notice = "Error: " . $e->getMessage();
        $notice_type = 'danger';
    }
}

// Fetch all messages from MySQL
$messages = [];
$total_messages = 0;
$unread_messages = 0;

if ($pdo && $db_connected) {
    try {
        $stmt = $pdo->query("SELECT * FROM `messages` ORDER BY `created_at` DESC");
        $messages = $stmt->fetchAll();
        $total_messages = count($messages);
        foreach ($messages as $m) {
            if (empty($m['is_read'])) $unread_messages++;
        }
    } catch (Exception $e) {}
}

$profile  = getProfileData($pdo);
$projects = getProjectsData($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Sean Andrie Cubio</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Fira+Code:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --accent: #0ea5e9;
            --dark-bg: #0b1120;
            --card-bg: #0f1c3f;
            --card-border: rgba(37, 99, 235, 0.2);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border-light: #1e293b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --radius: 14px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--dark-bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Admin Navbar */
        .admin-nav {
            background: #0b1329;
            border-bottom: 1px solid var(--border-light);
            padding: 14px 24px;
            position: sticky;
            top: 0;
            z-index: 50;
            backdrop-filter: blur(10px);
        }
        .nav-inner {
            max-width: 1300px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }
        .admin-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #fff;
        }
        .brand-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
        }
        .brand-text h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 800;
            line-height: 1.1;
        }
        .brand-text span {
            font-size: 11px;
            color: var(--accent);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .admin-user-bar {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .user-tag {
            background: rgba(16, 185, 129, 0.12);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #34d399;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .live-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 8px #10b981;
        }

        .btn-nav-action {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }
        .btn-view-site {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #e2e8f0;
        }
        .btn-view-site:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
        }
        .btn-logout {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #fca5a5;
        }
        .btn-logout:hover {
            background: #ef4444;
            color: #fff;
        }

        /* Main Container */
        .admin-container {
            max-width: 1300px;
            margin: 28px auto;
            padding: 0 20px;
            width: 100%;
            flex: 1;
        }

        /* Banner Welcome */
        .welcome-card {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.2), rgba(14, 165, 233, 0.1));
            border: 1px solid rgba(37, 99, 235, 0.3);
            border-radius: var(--radius);
            padding: 24px 28px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }
        .welcome-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.45rem;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 4px;
        }
        .welcome-desc {
            color: #cbd5e1;
            font-size: 0.92rem;
        }

        /* Metrics Row */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }
        .metric-card {
            background: #0f1c3f;
            border: 1px solid var(--border-light);
            border-radius: var(--radius);
            padding: 22px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.4);
            position: relative;
            overflow: hidden;
        }
        .metric-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--primary);
        }
        .metric-card.green::before { background: var(--success); }
        .metric-card.amber::before { background: var(--warning); }
        .metric-card.cyan::before { background: var(--accent); }

        .metric-info h4 {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            margin-bottom: 6px;
        }
        .metric-val {
            font-size: 1.85rem;
            font-weight: 800;
            font-family: 'Outfit', sans-serif;
            color: #fff;
        }
        .metric-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: var(--primary);
        }
        .metric-card.green .metric-icon-box { color: var(--success); }
        .metric-card.amber .metric-icon-box { color: var(--warning); }
        .metric-card.cyan .metric-icon-box { color: var(--accent); }

        /* Content Card */
        .content-card {
            background: #0f1c3f;
            border: 1px solid var(--border-light);
            border-radius: var(--radius);
            padding: 26px;
            margin-bottom: 28px;
            box-shadow: 0 15px 35px -5px rgba(0,0,0,0.4);
        }
        .content-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--border-light);
            flex-wrap: wrap;
            gap: 12px;
        }
        .content-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
        }
        .content-title i { color: var(--accent); }

        /* Messages Table */
        .table-responsive {
            overflow-x: auto;
        }
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
            text-align: left;
        }
        .admin-table th {
            background: #081026;
            color: #94a3b8;
            font-weight: 700;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-light);
            text-transform: uppercase;
            font-size: 11.5px;
            letter-spacing: 0.5px;
        }
        .admin-table td {
            padding: 14px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            color: #cbd5e1;
            vertical-align: middle;
        }
        .admin-table tr:hover td {
            background: rgba(255, 255, 255, 0.02);
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
        }
        .status-new {
            background: rgba(14, 165, 233, 0.15);
            color: #38bdf8;
            border: 1px solid rgba(14, 165, 233, 0.3);
        }
        .status-read {
            background: rgba(100, 116, 139, 0.15);
            color: #94a3b8;
        }

        .table-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-table-action {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border-light);
            background: rgba(255, 255, 255, 0.04);
            color: #cbd5e1;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-table-action:hover {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }
        .btn-table-action.del:hover {
            background: var(--danger);
            color: #fff;
            border-color: var(--danger);
        }

        /* Projects List */
        .project-row-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px;
            background: #081026;
            border: 1px solid var(--border-light);
            border-radius: 10px;
            margin-bottom: 10px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .proj-name {
            font-weight: 700;
            font-size: 14px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .proj-badge {
            font-size: 11px;
            color: var(--accent);
            background: rgba(14, 165, 233, 0.12);
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: 600;
        }

        .btn-demo-link {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: #ffffff;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: transform 0.2s;
        }
        .btn-demo-link:hover {
            transform: translateY(-2px);
        }

        .alert-notice {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.35);
            color: #34d399;
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 13.5px;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>
<body>

    <!-- Admin Navbar -->
    <nav class="admin-nav">
        <div class="nav-inner">
            <a href="admin.php" class="admin-brand">
                <div class="brand-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="brand-text">
                    <h2>Sean Andrie Cubio</h2>
                    <span>Portfolio Administration Panel</span>
                </div>
            </a>

            <div class="admin-user-bar">
                <span class="user-tag">
                    <span class="live-dot"></span>
                    <i class="fas fa-user-check"></i>
                    andrie123 (Administrator)
                </span>
                <a href="index.php" class="btn-nav-action btn-view-site" target="_blank">
                    <i class="fas fa-external-link-alt"></i> View Live Site
                </a>
                <a href="logout.php" class="btn-nav-action btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Admin Container -->
    <main class="admin-container">

        <?php if (!empty($notice)): ?>
            <div class="alert-notice">
                <i class="fas fa-check-circle"></i>
                <span><?php echo htmlspecialchars($notice); ?></span>
            </div>
        <?php endif; ?>

        <!-- Welcome Banner -->
        <div class="welcome-card">
            <div>
                <h1 class="welcome-title">Welcome back, andrie123!</h1>
                <p class="welcome-desc">
                    Candidate: <strong>Sean Adrei Cubio</strong> &bull; BSIT Student at Datamex College of St. Adeline &bull; Database: <code><?php echo htmlspecialchars($connected_db); ?></code>
                </p>
            </div>
            <div>
                <a href="index.php#contact" class="btn-nav-action btn-view-site">
                    <i class="fas fa-inbox"></i> Portfolio Contact Form
                </a>
            </div>
        </div>

        <!-- Metrics Overview -->
        <div class="metrics-grid">
            <div class="metric-card cyan">
                <div class="metric-info">
                    <h4>Total Inquiries</h4>
                    <div class="metric-val"><?php echo $total_messages; ?></div>
                </div>
                <div class="metric-icon-box">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
            </div>

            <div class="metric-card amber">
                <div class="metric-info">
                    <h4>Unread Messages</h4>
                    <div class="metric-val"><?php echo $unread_messages; ?></div>
                </div>
                <div class="metric-icon-box">
                    <i class="fas fa-bell"></i>
                </div>
            </div>

            <div class="metric-card green">
                <div class="metric-info">
                    <h4>Featured Projects</h4>
                    <div class="metric-val">3</div>
                </div>
                <div class="metric-icon-box">
                    <i class="fas fa-laptop-code"></i>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-info">
                    <h4>Database Status</h4>
                    <div class="metric-val" style="font-size: 1.15rem; color: #34d399;">
                        <?php echo $db_connected ? 'Connected' : 'Offline Mode'; ?>
                    </div>
                </div>
                <div class="metric-icon-box">
                    <i class="fas fa-database"></i>
                </div>
            </div>
        </div>

        <!-- Section 1: Contact Form Messages Inbox -->
        <div class="content-card">
            <div class="content-header">
                <h3 class="content-title">
                    <i class="fas fa-inbox"></i> Contact Form Messages &amp; Inquiries
                </h3>
                <span style="font-size: 12px; color: var(--text-muted);">
                    Recorded in MySQL <code>messages</code> table
                </span>
            </div>

            <?php if (empty($messages)): ?>
                <div style="text-align: center; padding: 40px 20px; color: var(--text-muted);">
                    <i class="fas fa-envelope-open" style="font-size: 2.5rem; color: #334155; margin-bottom: 12px;"></i>
                    <p>No messages received yet. Messages submitted through the portfolio contact form will appear here.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Date &amp; Time</th>
                                <th>Sender</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $msg): 
                                $is_unread = empty($msg['is_read']);
                            ?>
                                <tr>
                                    <td>
                                        <?php if ($is_unread): ?>
                                            <span class="status-badge status-new"><span class="live-dot" style="background:#0ea5e9;"></span> New</span>
                                        <?php else: ?>
                                            <span class="status-badge status-read"><i class="fas fa-check"></i> Read</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="font-family: 'Fira Code', monospace; font-size: 12px; color: #94a3b8;">
                                        <?php echo htmlspecialchars($msg['created_at']); ?>
                                    </td>
                                    <td><strong><?php echo htmlspecialchars($msg['name']); ?></strong></td>
                                    <td>
                                        <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>" style="color: #38bdf8; text-decoration: none;">
                                            <?php echo htmlspecialchars($msg['email']); ?>
                                        </a>
                                    </td>
                                    <td><?php echo htmlspecialchars($msg['subject']); ?></td>
                                    <td style="max-width: 280px; white-space: pre-wrap; font-size: 13px;">
                                        <?php echo htmlspecialchars($msg['message']); ?>
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>?subject=Re: <?php echo urlencode($msg['subject']); ?>" class="btn-table-action" title="Reply via Email">
                                                <i class="fas fa-reply"></i>
                                            </a>
                                            <?php if ($is_unread): ?>
                                                <a href="admin.php?mark_read=<?php echo $msg['id']; ?>" class="btn-table-action" title="Mark as Read">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="admin.php?delete_msg=<?php echo $msg['id']; ?>" class="btn-table-action del" onclick="return confirm('Permanently delete this message?');" title="Delete Message">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Section 2: Featured Projects & Live Demos -->
        <div class="content-card">
            <div class="content-header">
                <h3 class="content-title">
                    <i class="fas fa-laptop-code"></i> Live Project Prototypes
                </h3>
                <span style="font-size: 12px; color: var(--text-muted);">
                    Active Demos under <code>/demos/</code>
                </span>
            </div>

            <?php foreach ($projects as $p): ?>
                <div class="project-row-item">
                    <div class="proj-name">
                        <i class="fas fa-folder-open" style="color: var(--accent);"></i>
                        <span><?php echo htmlspecialchars($p['title']); ?></span>
                        <span class="proj-badge"><?php echo htmlspecialchars($p['category']); ?></span>
                    </div>
                    <div style="font-size: 12px; color: #94a3b8; font-family: 'Fira Code', monospace;">
                        <?php echo htmlspecialchars($p['demo_url']); ?>
                    </div>
                    <div>
                        <a href="<?php echo htmlspecialchars($p['demo_url']); ?>" class="btn-demo-link" target="_blank">
                            <i class="fas fa-play"></i> Open Live Demo
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer style="text-align: center; padding: 20px; font-size: 12px; color: #64748b; border-top: 1px solid var(--border-light); margin-top: auto;">
        Sean Andrie Cubio Portfolio &bull; BSIT Student Admin Portal &bull; Logged in as <strong>andrie123</strong>
    </footer>

</body>
</html>
