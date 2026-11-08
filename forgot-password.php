<?php
$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_input = $_POST['email'];

    $host = 'db';
    $db   = 'contact_db';
    $user = 'root';
    $pass = 'secret_pass';
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email_input]);
        $user_record = $stmt->fetch();

        if ($user_record) {
            $token = bin2hex(random_bytes(16));

            $update_stmt = $pdo->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
            $update_stmt->execute([$token, $email_input]);

            $to = $email_input;
            $subject = "Password Reset Request - Distributed Demo App";

            $reset_link = "http://localhost:8080/reset-password.php?token=" . $token;
            $email_body = "Hello,\n\nYou requested a password reset. Click the link below to change it:\n\n" . $reset_link . "\n\nIf you did not request this, please ignore this message.";

            // Send test email through the Mailpit SMTP service in Docker Compose.
            // Mailpit captures the message; it does not deliver to a real mailbox.
            $headers = "From: system-noreply@kabarak.ac.ke\r\n" .
                       "Reply-To: support@kabarak.ac.ke\r\n" .
                       "X-Mailer: PHP/" . phpversion();

            if (mail($to, $subject, $email_body, $headers)) {
                $message = "A reset password message has been successfully sent to your email!";
            } else {
                $error = "Failed to dispatch email over the mail server network.";
            }

        } else {
            $error = "No user account found matching that email address.";
        }
    } catch (\PDOException $e) {
        $error = "Database link error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Distributed System Demo - Forgot Password</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 340px; }
        h2 { margin-top: 0; color: #333; text-align: center; }
        p { color: #666; font-size: 14px; text-align: center; line-height: 1.4; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #666; font-weight: bold; }
        input[type="email"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #0056b3; border: none; color: white; border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 10px; }
        button:hover { background-color: #004085; }
        .alert { padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 14px; text-align: center; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .links { margin-top: 20px; text-align: center; font-size: 14px; }
        .links a { color: #0056b3; text-decoration: none; }
    </style>
</head>
<body>

<div class="card">
    <h2>Task A: Reset Password</h2>
    <p>Enter your registration email address below to send a password reset request.</p>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="forgot-password.php" method="POST">
        <div class="form-group">
            <label>Your Account Email</label>
            <input type="email" name="email" placeholder="e.g., admin@kabarak.ac.ke" required>
        </div>
        <button type="submit">Send Reset Message</button>
    </form>

    <div class="links">
        <a href="index.php">← Back to Login Screen</a>
    </div>
</div>

</body>
</html>