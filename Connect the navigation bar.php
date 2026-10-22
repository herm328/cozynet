
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
