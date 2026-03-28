<?php
require_once 'db.php';
$msg = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    $email = trim($_POST['email']);
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $check = $pdo->prepare("SELECT COUNT(*) FROM password_resets WHERE email = ? AND status = 'Pending'");
        $check->execute([$email]);
        if ($check->fetchColumn() == 0) {
            $pdo->prepare("INSERT INTO password_resets (email) VALUES (?)")->execute([$email]);
        }
        $msg = "A password reset request has been sent to the administrator. Please wait for them to manually reset your password.";
    } else {
        $error = "If that email is registered, a request has been sent."; // Standard security practice
        $msg = "A request will be processed if the email exists.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Sheger Kurt</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #ff9d2d; --primary-dark: #e68a1a; }
        * { box-sizing: border-box; font-family: 'Inter', sans-serif; margin: 0; padding: 0; }
        body { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f8faf8; }
        .login-wrapper { position: relative; z-index: 10; width: 100%; max-width: 440px; padding: 20px; }
        .login-card { background: #fff; padding: 50px 40px; border-radius: 28px; box-shadow: 0 30px 80px rgba(0,0,0,0.08); text-align: center; }
        .brand-icon { width: 64px; height: 64px; background: var(--primary); border-radius: 18px; display: inline-flex; align-items: center; justify-content: center; font-size: 28px; color: #fff; margin-bottom: 20px; }
        .input-wrap { position: relative; margin: 25px 0; }
        .input-wrap input { width: 100%; padding: 14px 16px 14px 48px; border: 1.5px solid #e2e8f0; border-radius: 14px; outline: none; }
        .input-wrap i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
        .btn { width: 100%; padding: 15px; background: var(--primary); color: #fff; border: none; border-radius: 14px; font-weight: 700; cursor: pointer; }
        .msg { background: #f0fdf4; color: #16a34a; padding: 15px; border-radius: 12px; font-size: 14px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="brand-icon"><i class="fa-solid fa-key"></i></div>
            <h2 style="margin-bottom: 10px;">Forgot Password?</h2>
            <p style="color: #64748b; font-size: 14px; line-height: 1.6;">Don't worry! Enter your email and we'll notify the admin to reset your password.</p>
            
            <?php if ($msg): ?><div class="msg"><?= $msg ?></div><?php endif; ?>

            <form method="POST">
                <div class="input-wrap">
                    <input type="email" name="email" required placeholder="Enter your email">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <button type="submit" class="btn">Send Reset Request</button>
            </form>
            <p style="margin-top: 25px;"><a href="login.php" style="color: var(--primary); text-decoration: none; font-weight: 600;">Back to Login</a></p>
        </div>
    </div>
</body>
</html>
