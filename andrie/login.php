<?php
/**
 * Administrator Login Page (login.php)
 * Portfolio for Sean Andrie Cubio
 */

require_once __DIR__ . '/db.php';

// If already logged in, redirect to admin dashboard
if (!empty($_SESSION['logged_in']) && !empty($_SESSION['user'])) {
    header("Location: admin.php");
    exit;
}

$error_msg = '';
$success_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($username) || empty($password)) {
        $error_msg = 'Please enter both username and password.';
    } else {
        $auth_result = authenticateUser($username, $password, $pdo);
        if ($auth_result['success']) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user'] = $auth_result['user'];
            $_SESSION['username'] = $auth_result['user']['username'];
            
            // Redirect to admin panel
            header("Location: admin.php?welcome=1");
            exit;
        } else {
            $error_msg = $auth_result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Sign In | Sean Andrie Cubio Portfolio</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Fira+Code:wght@500&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --accent: #0ea5e9;
            --dark-bg: #0b1329;
            --card-bg: #0f1c3f;
            --card-border: rgba(37, 99, 235, 0.28);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --input-bg: #081026;
            --input-border: #1e293b;
            --radius: 18px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--dark-bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient Glow Backgrounds */
        body::before {
            content: '';
            position: fixed;
            top: -20%;
            left: -10%;
            width: 55vw;
            height: 55vw;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.15) 0%, transparent 65%);
            filter: blur(80px);
            pointer-events: none;
        }
        body::after {
            content: '';
            position: fixed;
            bottom: -20%;
            right: -10%;
            width: 55vw;
            height: 55vw;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.12) 0%, transparent 65%);
            filter: blur(80px);
            pointer-events: none;
        }

        .login-card {
            background: rgba(15, 28, 63, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--card-border);
            border-radius: var(--radius);
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(255, 255, 255, 0.05);
            width: 100%;
            max-width: 440px;
            padding: 40px 34px;
            position: relative;
            z-index: 10;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #2563eb, #06b6d4, #3b82f6);
            border-radius: var(--radius) var(--radius) 0 0;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 28px;
        }
        .icon-badge {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin: 0 auto 16px;
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);
        }
        .brand-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 4px;
        }
        .brand-header p {
            color: var(--text-muted);
            font-size: 0.88rem;
        }

        /* Demo Account Notification Banner */
        .demo-cred-hint {
            background: rgba(14, 165, 233, 0.1);
            border: 1px dashed rgba(14, 165, 233, 0.4);
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 22px;
            font-size: 12px;
            color: #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }
        .demo-cred-hint code {
            font-family: 'Fira Code', monospace;
            background: rgba(0, 0, 0, 0.35);
            color: #38bdf8;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
        }
        .btn-autofill {
            background: rgba(14, 165, 233, 0.2);
            border: 1px solid rgba(14, 165, 233, 0.4);
            color: #38bdf8;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .btn-autofill:hover {
            background: var(--accent);
            color: #ffffff;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #fca5a5;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #cbd5e1;
            margin-bottom: 8px;
        }
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-icon {
            position: absolute;
            left: 14px;
            color: #64748b;
            font-size: 14px;
            pointer-events: none;
            transition: color 0.2s;
        }
        .form-control {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            color: #ffffff;
            padding: 12px 42px 12px 40px;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
            background: #0b142e;
        }
        .form-control:focus + .input-icon {
            color: var(--accent);
        }

        .toggle-password-btn {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 4px 6px;
            font-size: 14px;
            transition: color 0.2s;
        }
        .toggle-password-btn:hover { color: #e2e8f0; }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: #ffffff;
            border: none;
            padding: 13px;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            font-family: 'Outfit', sans-serif;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 10px;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.55);
        }

        .footer-links {
            margin-top: 24px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            font-size: 13px;
        }
        .footer-links a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .footer-links a:hover {
            color: #38bdf8;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand-header">
            <div class="icon-badge">
                <i class="fas fa-user-shield"></i>
            </div>
            <h1>Administrator Login</h1>
            <p>Sean Andrie Cubio &bull; Portfolio Control Panel</p>
        </div>

        <!-- Helpful Hint / 1-Click Autofill -->
        <div class="demo-cred-hint">
            <div>
                <i class="fas fa-key" style="color: #38bdf8; margin-right: 4px;"></i>
                User: <code>andrie123</code> &bull; Pass: <code>andrie12345</code>
            </div>
            <button type="button" class="btn-autofill" onclick="autofillCreds();">
                Autofill
            </button>
        </div>

        <?php if (!empty($error_msg)): ?>
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo htmlspecialchars($error_msg); ?></span>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" autocomplete="off">
            <div class="form-group">
                <label for="usernameInput" class="form-label">Username</label>
                <div class="input-wrapper">
                    <input type="text" id="usernameInput" name="username" class="form-control" placeholder="Enter username (andrie123)" required autofocus value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                    <i class="fas fa-user input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="passwordInput" class="form-label">Password</label>
                <div class="input-wrapper">
                    <input type="password" id="passwordInput" name="password" class="form-control" placeholder="Enter password (andrie12345)" required>
                    <i class="fas fa-lock input-icon"></i>
                    <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility();" title="Show / Hide Password">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-sign-in-alt"></i> Sign In to Dashboard
            </button>
        </form>

        <div class="footer-links">
            <a href="index.php"><i class="fas fa-arrow-left"></i> Return to Portfolio</a>
            <span style="color: #334155;">&bull;</span>
            <a href="index.php#projects"><i class="fas fa-code"></i> Live Demos</a>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            var pwd = document.getElementById('passwordInput');
            var icon = document.getElementById('eyeIcon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                pwd.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        function autofillCreds() {
            document.getElementById('usernameInput').value = 'andrie123';
            document.getElementById('passwordInput').value = 'andrie12345';
        }
    </script>
</body>
</html>
