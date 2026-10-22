
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
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
    $action = $_POST['action'] ?? '';

    if (!$product_id || $product_id < 1) {
        $message = 'Invalid product.';
    } elseif ($action === 'add') {
        $stmt = $conn->prepare(
            "INSERT IGNORE INTO wishlist (user_id, product_id) VALUES (?, ?)"
        );
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $stmt->close();

        header('Location: wishlist.php?message=added');
        exit();
    } elseif ($action === 'remove') {
        $stmt = $conn->prepare(
            "DELETE FROM wishlist WHERE user_id = ? AND product_id = ?"
        );
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $stmt->close();

        header('Location: wishlist.php?message=removed');
        exit();
    }
}

if (($_GET['message'] ?? '') === 'added') {
    $message = 'Favorite saved to your wishlist.';
} elseif (($_GET['message'] ?? '') === 'removed') {
    $message = 'Favorite removed from your wishlist.';
}

$stmt = $conn->prepare(
    "SELECT p.product_id, p.name, p.description, p.price, p.image
     FROM wishlist w
     JOIN products p ON p.product_id = w.product_id
     WHERE w.user_id = ?
     ORDER BY w.created_at DESC"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>My Wishlist | Ice Cream Delights</title>
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
    <h1 class="page-title">My <span class="accent">Wishlist</span> ♡</h1>
    <p class="muted">All your favorite flavors in one place.</p>

    <?php if ($message): ?>
        <div class="notice success"><?= e($message) ?></div>
    <?php endif; ?>

    <?php if (!$products): ?>
        <div class="notice">Your wishlist is empty. <a href="menu.php"><strong>Explore our menu!</strong></a></div>
    <?php else: ?>
    <div class="grid">
        <?php foreach ($products as $product): ?>
        <article class="product-card">
            <?php if (!empty($product['image'])): ?>
                <img src="<?= e($product['image']) ?>" alt="<?= e($product['name']) ?>" loading="lazy">
            <?php else: ?>
                <div style="height:150px;background:#fff0f4;display:grid;place-items:center;font-size:55px">🍨</div>
            <?php endif; ?>

            <div class="product-info">
                <h3><?= e($product['name']) ?></h3>
                <p class="muted"><?= e($product['description'] ?? '') ?></p>
                <p class="price">ETB <?= number_format((float)$product['price'], 2) ?></p>

                <div class="actions">
                    <a class="btn" href="view.php?id=<?= (int)$product['product_id'] ?>">View</a>
                    <form method="post" action="wishlist.php">
                        <input type="hidden" name="product_id" value="<?= (int)$product['product_id'] ?>">
                        <input type="hidden" name="action" value="remove">
                        <button class="btn btn-light" type="submit">Remove</button>
                    </form>
                </div>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</main>

<footer class="site-footer">Ice Cream Delights</footer>
</body>
</html>
