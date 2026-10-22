
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

$product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT)
    ?: filter_input(INPUT_GET, 'product_id', FILTER_VALIDATE_INT);

$quantity = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT) ?: 0;

    if (!$product_id || $product_id < 1 || $quantity < 1 || $quantity > 20) {
        $warning = 'Choose a valid product and quantity between 1 and 20.';
    } else {
        $stmt = $conn->prepare(
            "SELECT product_id, name, price FROM products WHERE product_id = ?"
        );
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$product) {
            $warning = 'That product could not be found.';
        } else {
            $total = round((float)$product['price'] * $quantity, 2);

            $stmt = $conn->prepare(
                "INSERT INTO orders (user_id, product_id, quantity, total_price)
                 VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param("iiid", $user_id, $product_id, $quantity, $total);
            $stmt->execute();
            $stmt->close();

            $success = 'Your order request has been placed successfully.';
        }
    }
}

$product = null;

if ($product_id && $product_id > 0) {
    $stmt = $conn->prepare(
        "SELECT product_id, name, description, price
         FROM products WHERE product_id = ?"
    );
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if (!$product && !$warning && !$success) {
    $warning = 'Please choose an ice cream from the menu first.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Order | Ice Cream Delights</title>
<link rel="stylesheet" href="css/shop_style.css">
</head>
<body>
<header class="site-header">
    <a class="brand" href="home.php">🍦 Ice Cream <span>Delights</span></a>
    <nav class="site-nav">
        <a href="home.php">Home</a>
        <a href="menu.php">Menu</a>
        <a href="profile.php">Profile</a>
        <a href="wishlist.php">Wishlist</a>
    </nav>
</header>

<main class="page">
    <div class="form-box">
        <h1 class="page-title">Place Your <span class="accent">Order</span></h1>
        <p class="muted">Choose your favorite treat and quantity.</p>

        <?php if ($warning): ?>
            <div class="notice"><?= e($warning) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="notice success"><?= e($success) ?></div>
            <a class="btn" href="profile.php">View My Orders</a>
            <a class="btn btn-light" href="menu.php">Continue Shopping</a>
        <?php elseif ($product): ?>
            <h3 style="margin-top:22px"><?= e($product['name']) ?></h3>
            <p class="muted"><?= e($product['description'] ?? '') ?></p>
            <p class="price">ETB <?= number_format((float)$product['price'], 2) ?> each</p>

            <form action="order.php" method="post">
                <input type="hidden" name="product_id" value="<?= (int)$product['product_id'] ?>">

                <div class="field">
                    <label for="quantity">Quantity (1–20)</label>
                    <input type="number" id="quantity" name="quantity" min="1" max="20" value="<?= (int)$quantity ?>" required>
                </div>

                <button class="btn" type="submit">Confirm Order</button>
            </form>
        <?php endif; ?>
    </div>
</main>

<footer class="site-footer">Thank you for choosing Ice Cream Delights!</footer>
</body>
</html>
