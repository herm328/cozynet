
<?php
session_start();
require_once 'config.php';

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// Get product ID from the URL
$product_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$product_id || $product_id < 1) {
    header('Location: menu.php');
    exit();
}

// Fetch the selected product
$stmt = $conn->prepare(
    "SELECT product_id, name, description, price, image
     FROM products
     WHERE product_id = ?"
);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Redirect if product does not exist
if (!$product) {
    http_response_code(404);
    $not_found = true;
} else {
    $not_found = false;
}

// Prepare the product image path
$image = '';

if (!$not_found) {
    $image = trim((string)($product['image'] ?? ''));

    if (
        $image !== '' &&
        !preg_match('~^(https?://|/)~i', $image)
    ) {
        $image = 'images/products/' . ltrim($image, '/');
    }

    // Only permit HTTP(S) URLs or local absolute paths.
    if (
        $image !== '' &&
        !preg_match('~^(https?://|/)~i', $image)
    ) {
        $image = '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        <?= $not_found ? 'Product Not Found' : e($product['name']) ?>
        | Ice Cream Delights
    </title>

    <link rel="stylesheet" href="css/shop_style.css">
    <link
        href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
        rel="stylesheet"
    >

    <style>
        * {
            box-sizing: border-box;
        }

        .details-container {
            max-width: 1050px;
            margin: 45px auto;
            padding: 0 22px;
        }

        .breadcrumb {
            margin-bottom: 25px;
            color: #777;
            font-size: 14px;
        }

        .breadcrumb a {
            color: #b43c7b;
            text-decoration: none;
        }

        .product-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
            padding: 28px;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.07);
        }

        .detail-image {
            min-height: 350px;
            display: grid;
            place-items: center;
            overflow: hidden;
            border-radius: 15px;
            background: linear-gradient(135deg, #fff0f6, #ffe1ec);
        }

        .detail-image img {
            display: block;
            width: 100%;
            height: 350px;
            object-fit: cover;
            transition: transform .4s ease;
        }

        .detail-image:hover img {
            transform: scale(1.04);
        }

        .image-placeholder {
            font-size: 100px;
        }

        .detail-label {
            display: inline-block;
            padding: 8px 13px;
            border-radius: 25px;
            background: #fff0f6;
            color: #b43c7b;
            font-size: 12px;
            font-weight: 700;
        }

        .detail-info h1 {
            margin: 18px 0 12px;
            font-size: clamp(28px, 4vw, 40px);
            color: #382533;
        }

        .detail-description {
            color: #777;
            font-size: 15px;
            line-height: 1.9;
        }

        .detail-price {
            margin: 25px 0;
            color: #b43c7b;
            font-size: 30px;
            font-weight: 700;
        }

        .detail-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 20px;
        }

        .detail-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 13px 20px;
            border: none;
            border-radius: 8px;
            background: #b43c7b;
            color: white;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            transition: background .25s, transform .25s;
        }

        .detail-button:hover {
            background: #8e2c60;
            transform: translateY(-2px);
        }

        .detail-button.secondary {
            border: 1px solid #e6d7e0;
            background: white;
            color: #6a4058;
        }

        .detail-button.secondary:hover {
            background: #fff0f6;
        }

        .wishlist-form {
            margin: 0;
        }

        .not-found {
            padding: 60px 20px;
            text-align: center;
            background: white;
            border-radius: 16px;
        }

        .not-found .emoji {
            font-size: 65px;
        }

        @media (max-width: 700px) {
            .product-details {
                grid-template-columns: 1fr;
                gap: 25px;
                padding: 18px;
            }

            .detail-image {
                min-height: 250px;
            }

            .detail-image img {
                height: 280px;
            }

            .detail-price {
                font-size: 26px;
            }
        }
    </style>
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

<main class="details-container">

    <div class="breadcrumb">
        <a href="home.php">Home</a> /
        <a href="menu.php">Our Menu</a> /
        Product Details
    </div>

    <?php if ($not_found): ?>

        <section class="not-found">
            <div class="emoji">🍨</div>
            <h1>Product Not Found</h1>
            <p>Sorry, this ice cream product may have been removed.</p>
            <a class="detail-button" href="menu.php">
                <i class="bx bx-arrow-back"></i>
                Back to Menu
            </a>
        </section>

    <?php else: ?>

        <section class="product-details">

            <div class="detail-image">
                <?php if ($image !== ''): ?>
                    <img
                        src="<?= e($image) ?>"
                        alt="<?= e($product['name']) ?>"
                        onerror="this.style.display='none';this.nextElementSibling.style.display='block';"
                    >
                <?php endif; ?>

                <div
                    class="image-placeholder"
                    style="<?= $image !== '' ? 'display:none;' : '' ?>"
                >🍨</div>
            </div>

            <div class="detail-info">
                <span class="detail-label">
                    <i class="bx bxs-heart"></i>
                    MADE WITH LOVE
                </span>

                <h1><?= e($product['name']) ?></h1>

                <p class="detail-description">
                    <?= nl2br(e(
                        $product['description']
                        ?? 'A delicious frozen treat made especially for you.'
                    )) ?>
                </p>

                <div class="detail-price">
                    ETB <?= number_format((float)$product['price'], 2) ?>
                </div>

                <p class="detail-description">
                    Enjoy a delightful ice cream experience with
                    your favorite flavor from Ice Cream Delights.
                </p>

                <div class="detail-actions">
                    <a class="detail-button" href="menu.php">
                        <i class="bx bx-shopping-bag"></i>
                        Back to Menu
                    </a>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <form
                            action="wishlist.php"
                            method="post"
                            class="wishlist-form"
                        >
                            <input
                                type="hidden"
                                name="product_id"
                                value="<?= (int)$product['product_id'] ?>"
                            >

                            <input
                                type="hidden"
                                name="action"
                                value="add"
                            >

                            <button type="submit" class="detail-button secondary">
                                <i class="bx bx-heart"></i>
                                Add to Wishlist
                            </button>
                        </form>
                    <?php else: ?>
                        <a
                            class="detail-button secondary"
                            href="login.php"
                        >
                            <i class="bx bx-user"></i>
                            Log in to save
                        </a>
                    <?php endif; ?>
                </div>
            </div>

        </section>

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

