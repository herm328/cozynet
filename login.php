
<?php
session_start();
require_once 'config.php';

$warning_msg = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['pass'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $warning_msg[] = 'Please enter a valid email address.';
    } else {
        try {
            $stmt = $conn->prepare(
                "SELECT user_id, password FROM users WHERE email = ? LIMIT 1"
            );

            $stmt->bind_param("s", $email);
            $stmt->execute();

            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();

            if ($user && password_verify($pass, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['user_id'];

                header("Location: home.php");
                exit();
            } else {
                $warning_msg[] = "Incorrect email or password.";
            }

        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage());
            $warning_msg[] = "Database error. Check your database and users table.";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Ice Cream Delights</title>

    <link rel="stylesheet" href="css/user_style.css">

    <!-- Boxicons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
          rel="stylesheet">
</head>

<body>

    <div class="login-page">

        <div class="login-banner">
            <div class="ice-icon">
                <i class="bx bxs-ice-cream"></i>
            </div>

            <p class="small-title">WELCOME BACK</p>

            <h1>A little sweetness goes a long way.</h1>

            <p class="banner-text">
                Log in to Ice Cream Delights and enjoy your favorite
                frozen treats, special offers, and more.
            </p>

            <div class="banner-decoration">🍦 🍓 🍨</div>
        </div>

        <div class="form-container">

            <form action="" method="POST" class="login">

                <div class="form-heading">
                    <span class="form-label">YOUR ACCOUNT</span>
                    <h2>Login Now</h2>
                    <p>Enter your details to continue.</p>
                </div>

                <?php foreach ($warning_msg as $message): ?>
                    <div class="error-message">
                        <i class="bx bx-error-circle"></i>
                        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endforeach; ?>

                <div class="input-field">
                    <label for="email">Your Email <span>*</span></label>

                    <div class="input-box">
                        <i class="bx bx-envelope"></i>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Enter your email"
                            maxlength="254"
                            autocomplete="email"
                            required
                            value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        >
                    </div>
                </div>

                <div class="input-field">
                    <label for="pass">Your Password <span>*</span></label>

                    <div class="input-box">
                        <i class="bx bx-lock-alt"></i>
                        <input
                            type="password"
                            id="pass"
                            name="pass"
                            placeholder="Enter your password"
                            maxlength="72"
                            autocomplete="current-password"
                            required
                        >
                    </div>
                </div>

                <button type="submit" name="submit" class="btn">
                    Login Now
                    <i class="bx bx-right-arrow-alt"></i>
                </button>

                <p class="register-link">
                    Don't have an account?
                    <a href="register.php">Register now</a>
                </p>

                <p class="back-link">
                    <a href="home.php">
                        <i class="bx bx-home-alt"></i> Back to home
                    </a>
                </p>

            </form>

        </div>
    </div>

</body>
</html>
