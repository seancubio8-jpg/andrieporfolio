<?php
/**
 * Quick Database Installer / Reset Utility
 * Portfolio for Sean Andrie Cubio
 */

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'andrie_db';
$message = '';
$status = 'info';

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['run'])) {
    try {
        // Connect to MySQL server
        $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        // Read SQL script
        $sql_file = __DIR__ . '/database.sql';
        if (!file_exists($sql_file)) {
            throw new Exception("database.sql file not found in current directory!");
        }

        $sql_content = file_get_contents($sql_file);

        // Safely execute statement by statement
        $queries = explode(';', $sql_content);
        foreach ($queries as $query) {
            $query = trim($query);
            if (!empty($query)) {
                try {
                    $pdo->exec($query);
                } catch (PDOException $qe) {
                    // Ignore innocuous notices
                }
            }
        }

        $status = 'success';
        $message = "Database '{$dbname}' and all tables (profile_info, projects, messages, users) have been created and populated successfully! Admin login: <strong>andrie123</strong> / <strong>andrie12345</strong>";
    } catch (Exception $e) {
        $status = 'danger';
        $message = "Database Setup Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup - Sean Andrie Cubio Portfolio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-page: #f0f7ff;
            --bg-card: #ffffff;
            --primary-blue: #2563eb;
            --primary-hover: #1d4ed8;
            --accent-blue: #0ea5e9;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --border-light: #e2e8f0;
            --border-blue: rgba(37, 99, 235, 0.2);
            --success: #10b981;
            --danger: #ef4444;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #eff6ff 0%, #e0f2fe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-dark);
            padding: 20px;
        }
        .setup-card {
            background: #ffffff;
            border: 1px solid var(--border-blue);
            box-shadow: 0 20px 40px rgba(37, 99, 235, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
            border-radius: 20px;
            max-width: 540px;
            width: 100%;
            padding: 40px;
            text-align: center;
        }
        .icon-box {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, var(--primary-blue), var(--accent-blue));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 34px;
            color: #ffffff;
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.35);
        }
        h1 {
            font-size: 26px;
            margin-bottom: 10px;
            color: var(--text-dark);
        }
        p {
            color: var(--text-muted);
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 14px;
            text-align: left;
            line-height: 1.5;
        }
        .alert-success {
            background: #ecfdf5;
            border: 1px solid var(--success);
            color: #065f46;
        }
        .alert-danger {
            background: #fef2f2;
            border: 1px solid var(--danger);
            color: #991b1b;
        }
        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.25s ease;
            border: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-blue), var(--accent-blue));
            color: #fff;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.45);
        }
        .btn-outline {
            background: #ffffff;
            color: var(--primary-blue);
            border: 1.5px solid var(--primary-blue);
        }
        .btn-outline:hover {
            background: #eff6ff;
        }
        .db-info {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border-light);
            font-size: 13px;
            color: var(--text-muted);
        }
        .db-info code {
            color: var(--primary-blue);
            background: #eff6ff;
            padding: 3px 8px;
            border-radius: 6px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="setup-card">
    <div class="icon-box">
        <i class="fas fa-database"></i>
    </div>
    <h1>Database Setup Utility</h1>
    <p>Initialize or reset the <strong>portfolio_db</strong> MySQL database for Sean Andrie Cubio's portfolio website.</p>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $status; ?>">
            <i class="fas <?php echo $status === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?>"></i>
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="btn-group">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-magic"></i> <?php echo $status === 'success' ? 'Re-run Setup' : 'Initialize Database Now'; ?>
        </button>
        <a href="index.php" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Go to Portfolio Website
        </a>
    </form>

    <div class="db-info">
        <p>Target Database: <code>andrie_db</code> (or <code>portfolio_db</code>) | Host: <code>localhost</code></p>
        <p>Loaded from: <code>database.sql</code></p>
    </div>
</div>

</body>
</html>
