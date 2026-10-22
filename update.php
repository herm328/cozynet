
<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit();
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$user_id = (int)$_SESSION['user_id'];
$warning = '';
$success = '';

$stmt = $conn->prepare(
    "SELECT name, email FROM users WHERE user_id = ?"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($name === '' || strlen($name) > 100) {
        $warning = 'Enter a name with no more than 100 characters.';
    } elseif (strlen($email) > 254 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $warning = 'Enter a valid email address.';
    } else {
        try {
            $stmt = $conn->prepare(
                "UPDATE users SET name = ?, email = ? WHERE user_id = ?"
            );
            $stmt->bind_param("ssi", $name, $email, $user_id);
            $stmt->execute();
            $stmt->close();

            $user['name'] = $name;
            $user['email'] = $email;
            $success = 'Your profile has been updated.';
        } catch (mysqli_sql_exception $e) {
            if ((int)$e->getCode() === 1062) {
                $warning = 'That email address is already being used.';
            } else {
                error_log($e->getMessage());
                $warning = 'Unable to update your profile. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Update Profile | Ice Cream Delights</title>
<link rel="stylesheet" href="css/shop_style.css">
</head>
<body>
<header class="site-header">
    <a class="brand" href="home.php">🍦 Ice Cream <span>Delights</span></a>
    <nav class="site-nav">
        <a href="home.php">Home</a>
        <a href="menu.php">Menu</a>
        <a href="profile.php">My Profile</a>
    </nav>
</header>

<main class="page">
    <div class="form-box">
        <h1 class="page-title">Update <span class="accent">Profile</span></h1>
        <p class="muted">Change your account information.</p>

        <?php if ($warning): ?>
            <div class="notice"><?= e($warning) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="notice success"><?= e($success) ?></div>
        <?php endif; ?>

        <form method="post" action="update.php">
            <div class="field">
                <label for="name">Full name</label>
                <input id="name" type="text" name="name" maxlength="100" required value="<?= e($user['name']) ?>">
            </div>

            <div class="field">
                <label for="email">Email address</label>
                <input id="email" type="email" name="email" maxlength="254" required value="<?= e($user['email']) ?>">
            </div>

            <button class="btn" type="submit">Save Changes</button>
            <a class="btn btn-light" href="profile.php">Cancel</a>
        </form>
    </div>
</main>

<footer class="site-footer">Ice Cream Delights</footer>
</body>
</html>
