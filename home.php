
<?php
session_start();
require_once 'config.php';

$is_logged_in = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ice Cream Delights | Home</title>

    <link rel="stylesheet" href="css/home_style.css">

    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
          rel="stylesheet">
</head>

<body>

<!-- Navigation -->
<header class="header">
    <a href="home.php" class="logo">
        <i class="bx bxs-ice-cream"></i>
        Ice Cream <span>Delights</span>
    </a>

    <nav class="navbar">
        <a href="home.php">Home</a>
        <a href="menu.php">Our Menu</a>
        <a href="home.php#about">About Us</a>
        <a href="home.php#contact">Contact</a>

        <?php if ($is_logged_in): ?>
            <a href="profile.php">My Orders</a>
            <a href="wishlist.php">Wishlist</a>
        <?php endif; ?>
    </nav>

    <div class="header-right">
        <?php if ($is_logged_in): ?>
            <a href="profile.php" class="welcome-user">
                <i class="bx bx-user-circle"></i> My Profile
            </a>

            <a href="update.php" class="login-btn">Edit Profile</a>
        <?php else: ?>
            <a href="login.php" class="login-btn">Login</a>
            <a href="register.php" class="login-btn">Register</a>
        <?php endif; ?>

        <a href="wishlist.php" class="cart-icon"
           aria-label="My wishlist">
            <i class="bx bx-heart"></i>
        </a>
    </div>
</header>

<!-- Hero Section -->
<section class="hero" id="home">
    <div class="hero-content">
        <span class="eyebrow">
            <i class="bx bxs-heart"></i> MADE WITH LOVE
        </span>

        <h1>Life is better with a scoop of <span>happiness!</span></h1>

        <p>
            Discover your favorite flavors, creamy textures, and
            delicious frozen treats. Every scoop is a little moment
            of happiness!
        </p>

        <div class="hero-buttons">
            <a href="menu.php" class="primary-btn">
                Explore Menu <i class="bx bx-right-arrow-alt"></i>
            </a>

            <a href="#about" class="secondary-btn">
                Discover More
            </a>
        </div>

        <div class="hero-note">
            <i class="bx bxs-star"></i>
            Fresh flavors. Sweet memories. Every day.
        </div>
    </div>

    <div class="hero-image">
        <img
            src="https://images.unsplash.com/photo-1560008581-09826d1de69e?auto=format&fit=crop&w=1000&q=85"
            alt="Delicious ice cream cones"
        >

        <div class="floating-card">
            <span class="floating-icon">🍓</span>
            <div>
                <strong>Sweet moments</strong>
                <p>Made just for you</p>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="features">
    <div class="feature">
        <i class="bx bxs-heart"></i>
        <div>
            <h3>Made with Love</h3>
            <p>Sweetness in every scoop</p>
        </div>
    </div>

    <div class="feature">
        <i class="bx bxs-leaf"></i>
        <div>
            <h3>Quality Ingredients</h3>
            <p>Flavor you'll remember</p>
        </div>
    </div>

    <div class="feature">
        <i class="bx bxs-happy-heart-eyes"></i>
        <div>
            <h3>Pure Happiness</h3>
            <p>A treat for every mood</p>
        </div>
    </div>
</section>

<!-- Menu Section -->
<section class="menu-section" id="menu">
    <div class="section-heading">
        <span class="eyebrow">OUR SWEET COLLECTION</span>
        <h2>Explore Our <span>Favorites</span></h2>
        <p>Find your new favorite scoop among our delicious treats.</p>
    </div>

    <div class="menu-grid">

        <!-- Strawberry -->
        <article class="ice-card">
            <div class="product-image">
                <img
                    src="https://images.unsplash.com/photo-1563805042-7684c019e1cb?auto=format&fit=crop&w=700&q=85"
                    alt="Strawberry ice cream"
                    loading="lazy"
                >
                <span class="product-tag">Fan Favorite</span>
            </div>

            <div class="product-info">
                <div class="product-title">
                    <h3>Strawberry Bliss</h3>
                    <span class="rating">
                        <i class="bx bxs-star"></i> 4.9
                    </span>
                </div>

                <p>Sweet, fruity, and wonderfully creamy.</p>

                <div class="product-bottom">
                    <span class="price">ETB 150</span>
                    <a href="view.php?id=1" class="order-btn"
                       aria-label="View Strawberry Bliss">
                        <i class="bx bx-plus"></i>
                    </a>
                </div>
            </div>
        </article>

        <!-- Chocolate -->
        <article class="ice-card">
            <div class="product-image">
                <img
                    src="https://images.unsplash.com/photo-1560081223-3e5dd3e6b2e7?auto=format&fit=crop&w=700&q=85"
                    alt="Chocolate ice cream dessert"
                    loading="lazy"
                >
                <span class="product-tag chocolate-tag">Chocolate Love</span>
            </div>

            <div class="product-info">
                <div class="product-title">
                    <h3>Chocolate Dream</h3>
                    <span class="rating">
                        <i class="bx bxs-star"></i> 4.8
                    </span>
                </div>

                <p>Rich chocolate flavor for your sweetest cravings.</p>

                <div class="product-bottom">
                    <span class="price">ETB 180</span>
                    <a href="view.php?id=2" class="order-btn"
                       aria-label="View Chocolate Dream">
                        <i class="bx bx-plus"></i>
                    </a>
                </div>
            </div>
        </article>

        <!-- Vanilla -->
        <article class="ice-card">
            <div class="product-image">
                <img
                    src="https://images.unsplash.com/photo-1497034825429-c343d7c6a68f?auto=format&fit=crop&w=700&q=85"
                    alt="Ice cream with colorful toppings"
                    loading="lazy"
                >
                <span class="product-tag vanilla-tag">Classic</span>
            </div>

            <div class="product-info">
                <div class="product-title">
                    <h3>Vanilla Cloud</h3>
                    <span class="rating">
                        <i class="bx bxs-star"></i> 4.9
                    </span>
                </div>

                <p>Classic vanilla with a smooth, dreamy finish.</p>

                <div class="product-bottom">
                    <span class="price">ETB 130</span>
                    <a href="view.php?id=3" class="order-btn"
                       aria-label="View Vanilla Cloud">
                        <i class="bx bx-plus"></i>
                    </a>
                </div>
            </div>
        </article>

    </div>

    <p class="menu-note">
        Sample products and prices — update them to match your real menu.
    </p>

    <div style="text-align:center; margin-top:30px;">
        <a href="menu.php" class="primary-btn">
            View Full Menu <i class="bx bx-right-arrow-alt"></i>
        </a>
    </div>
</section>

<!-- About Section -->
<section class="about-section" id="about">
    <div class="about-image">
        <img
            src="https://images.unsplash.com/photo-1501446529957-6226bd447c46?auto=format&fit=crop&w=900&q=85"
            alt="Colorful ice cream treats"
            loading="lazy"
        >
    </div>

    <div class="about-content">
        <span class="eyebrow">A LITTLE ABOUT US</span>
        <h2>Sharing smiles, <span>one scoop at a time.</span></h2>

        <p>
            At Ice Cream Delights, we believe the sweetest things in
            life are the little moments we share. Our goal is to make
            every visit a delightful experience filled with flavor,
            fun, and happiness.
        </p>

        <a href="#contact" class="primary-btn">
            Get in Touch <i class="bx bx-right-arrow-alt"></i>
        </a>
    </div>
</section>

<!-- Contact Section -->
<section class="contact-section" id="contact">
    <div class="contact-content">
        <span class="eyebrow">YOUR NEXT FAVORITE TREAT</span>
        <h2>Ready for something <span>sweet?</span></h2>

        <p>
            Have a question about our flavors or want to place an order?
            We'd love to hear from you.
        </p>

        <a href="menu.php" class="primary-btn">
            Order Your Favorite <i class="bx bx-shopping-bag"></i>
        </a>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <a href="home.php" class="logo">
        <i class="bx bxs-ice-cream"></i>
        Ice Cream <span>Delights</span>
    </a>

    <div class="social-links">
        <a href="menu.php" aria-label="Menu">
            <i class="bx bx-food-menu"></i>
        </a>

        <a href="wishlist.php" aria-label="Wishlist">
            <i class="bx bx-heart"></i>
        </a>

        <a href="profile.php" aria-label="My Profile">
            <i class="bx bx-user"></i>
        </a>
    </div>

    <p>
        Made with <i class="bx bxs-heart heart"></i>
        for ice cream lovers.
    </p>

    <p class="copyright">
        &copy; <?= date('Y') ?> Ice Cream Delights.
        All rights reserved.
    </p>
</footer>

</body>
</html>