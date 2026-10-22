
<?php
session_start();
require_once 'config.php';

$warning_msg = [];
$success_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['pass'] ?? '';
    $cpass = $_POST['cpass'] ?? '';

    if ($name === '' || strlen($name) > 100) {
        $warning_msg[] = 'Please enter your name (up to 100 characters).';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 254) {
        $warning_msg[] = 'Please enter a valid email address.';
    }

    if (strlen($pass) < 8) {
        $warning_msg[] = 'Password must contain at least 8 characters.';
    }

    if ($pass !== $cpass) {
        $warning_msg[] = 'Passwords do not match.';
    }

    if (empty($warning_msg)) {
        try {
            // Check if email already exists
            $check_user = $conn->prepare(
                "SELECT user_id FROM users WHERE email = ? LIMIT 1"
            );
            $check_user->execute([$email]);

            if ($check_user->fetch()) {
                $warning_msg[] = 'This email is already registered.';
            } else {
                // Hash password securely
                $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

                $insert_user = $conn->prepare(
                    "INSERT INTO users (name, email, password)
                     VALUES (?, ?, ?)"
                );

                $insert_user->execute([$name, $email, $hashed_pass]);

                $success_msg = 'Registration successful! You can now log in.';
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $warning_msg[] = 'Unable to register right now. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register | Ice Cream Delights</title>

    <link rel="stylesheet" href="css/user_style.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
          rel="stylesheet">
</head>

<body>

<div class="login-page">

    <div class="login-banner">
        <div class="ice-icon">
            <i class="bx bxs-ice-cream"></i>
        </div>

        <p class="small-title">JOIN OUR SWEET FAMILY</p>

        <h1>Create an account full of sweetness.</h1>

        <p class="banner-text">
            Register today to discover delicious flavors and enjoy
            everything Ice Cream Delights has to offer.
        </p>

        <div class="banner-decoration">🍦 🍓 🍨</div>
    </div>

    <div class="form-container">

        <form action="" method="POST" class="login">

            <div class="form-heading">
                <span class="form-label">GET STARTED</span>
                <h2>Create Account</h2>
                <p>Fill in your details to join us.</p>
            </div>

            <?php foreach ($warning_msg as $message): ?>
                <div class="error-message">
                    <i class="bx bx-error-circle"></i>
                    <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endforeach; ?>

            <?php if ($success_msg !== ''): ?>
                <div class="success-message">
                    <i class="bx bx-check-circle"></i>
                    <?= htmlspecialchars($success_msg, ENT_QUOTES, 'UTF-8') ?>
                    <a href="login.php">Log in now</a>
                </div>
            <?php endif; ?>

            <div class="input-field">
                <label for="name">Your Name <span>*</span></label>
                <div class="input-box">
                    <i class="bx bx-user"></i>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Enter your full name"
                        maxlength="100"
                        autocomplete="name"
                        required
                        value="<?= htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div>
            </div>

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
                <label for="pass">Password <span>*</span></label>
                <div class="input-box">
                    <i class="bx bx-lock-alt"></i>
                    <input
                        type="password"
                        id="pass"
                        name="pass"
                        placeholder="At least 8 characters"
                        minlength="8"
                        maxlength="72"
                        autocomplete="new-password"
                        required
                    >
                </div>
            </div>

            <div class="input-field">
                <label for="cpass">Confirm Password <span>*</span></label>
                <div class="input-box">
                    <i class="bx bx-lock"></i>
                    <input
                        type="password"
                        id="cpass"
                        name="cpass"
                        placeholder="Re-enter your password"
                        minlength="8"
                        maxlength="72"
                        autocomplete="new-password"
                        required
                    >
                </div>
            </div>

            <button type="submit" name="submit" class="btn">
                Register Now
                <i class="bx bx-right-arrow-alt"></i>
            </button>

            <p class="register-link">
                Already have an account?
                <a href="login.php">Login now</a>
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
