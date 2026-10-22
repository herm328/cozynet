<?php
session_start();
require_once 'config.php';

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$sql = "SELECT product_id, name, description, price, image
        FROM products
        ORDER BY product_id DESC";

$result = $conn->query($sql);
$products = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Our Menu | Ice Cream Delights</title>

    <link rel="stylesheet" href="css/shop_style.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
          rel="stylesheet">
</head>
<body>

<header class="site-header">
    <a class="brand" href="home.php">
        <i class="bx bxs-ice-cream"></i>
        Ice Cream <span>Delights</span>
    </a>

    <nav class="site-nav">
        <a href="home.php">Home</a>
        <a href="menu.php" class="active">Menu</a>
        <a href="wishlist.php">Wishlist</a>
        <a href="profile.php">Profile</a>
    </nav>
</header>

<main class="page">
    <section class="menu-heading">
        <span class="eyebrow">MADE WITH LOVE</span>
        <h1>Our <span>Sweet Menu</span></h1>
        <p>Discover your favorite flavors, one scoop at a time.</p>
    </section>

    <?php if (empty($products)): ?>
        <div class="empty-state">
            <div class="empty-icon">🍨</div>
            <h2>No ice cream available yet</h2>
            <p>Add products to your database using phpMyAdmin.</p>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <?php
                $image = trim((string)($product['image'] ?? ''));

                // A filename alone is assumed to be inside images/products/.
                if ($image !== ''
                    && !preg_match('~^(https?://|/|data:)~i', $image)) {
                    $image = 'images/products/' . ltrim($image, '/');
                }

                // Permit only web URLs or local paths; don't allow data URLs.
                $validImage = preg_match('~^(https?://|/)~i', $image);
                ?>

                <article class="product-card">
                    <a class="product-image"
                       href="view.php?id=<?= (int)$product['product_id'] ?>">

                        <?php if ($image !== '' && $validImage): ?>
                            <img
                                src="<?= e($image) ?>"
                                alt="<?= e($product['name']) ?>"
                                loading="lazy"
                                onerror="this.style.display='none';this.nextElementSibling.style.display='grid';"
                            >
                        <?php endif; ?>

                        <div class="image-fallback"
                             style="<?= ($image !== '' && $validImage) ? 'display:none;' : 'display:grid;' ?>">
                            🍨
                        </div>

                        <span class="image-label">Sweet &amp; Delicious</span>
                    </a>

                    <div class="product-info">
                        <h2><?= e($product['name']) ?></h2>

                        <p class="description">
                            <?= e($product['description'] ?? 'A delicious frozen treat made for you.') ?>
                        </p>

                        <div class="product-bottom">
                            <span class="price">
                                ETB <?= number_format((float)$product['price'], 2) ?>
                            </span>

                            <a class="btn"
                               href="view.php?id=<?= (int)$product['product_id'] ?>">
                                View Details <i class="bx bx-right-arrow-alt"></i>
                            </a>
                        </div>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <form action="wishlist.php" method="post" class="wishlist-form">
                                <input type="hidden" name="product_id"
                                       value="<?= (int)$product['product_id'] ?>">
                                <input type="hidden" name="action" value="add">

                                <button class="wishlist-btn" type="submit">
                                    <i class="bx bx-heart"></i> Add to Wishlist
                                </button>
                            </form>
                        <?php else: ?>
                            <p class="login-hint">
                                <a href="login.php">Log in</a> to save favorites.
                            </p>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<footer class="site-footer">
    <a class="brand" href="home.php">
        🍦 Ice Cream <span>Delights</span>
    </a>
    <p>Made with ♥ for ice cream lovers.</p>
    <p>&copy; <?= date('Y') ?> Ice Cream Delights</p>
</footer>

</body>
</html>