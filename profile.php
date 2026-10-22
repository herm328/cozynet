

<?php
session_start();
require_once 'config.php';

// Check whether the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Safely display database values
function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$user_id = (int) $_SESSION['user_id'];

// Get user profile
$stmt = $conn->prepare(
    "SELECT user_id, name, email, created_at
     FROM users
     WHERE user_id = ?"
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

// Get orders for the logged-in user
$orders = [];

if ($conn->query("SHOW TABLES LIKE 'orders'")->num_rows > 0) {
    $stmt = $conn->prepare(
        "SELECT o.order_id, o.quantity, o.total_price,
                o.status, o.order_date,
                p.name AS product_name
         FROM orders o
         LEFT JOIN products p ON o.product_id = p.product_id
         WHERE o.user_id = ?
         ORDER BY o.order_date DESC"
    );

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Profile | Ice Cream Delights</title>
    <link rel="stylesheet" href="css/shop_style.css">
    <style>
        .profile-card {
            background: #fff;
            padding: 28px;
            border-radius: 16px;
            box-shadow: 0 5px 22px rgba(0,0,0,.07);
            margin-top: 24px;
        }

        .profile-avatar {
            width: 75px;
            height: 75px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: #fff0f4;
            font-size: 35px;
            margin-bottom: 16px;
        }

        .profile-details {
            display: grid;
            gap: 12px;
            margin: 20px 0;
        }

        .profile-details p {
            margin: 0;
            overflow-wrap: anywhere;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 20px;
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 14px;
            border-bottom: 1px solid #eee;
            text-align: left;
            white-space: nowrap;
        }

        th {
            background: #fff0f4;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            background: #fff0d9;
            font-size: 12px;
        }

        .empty-orders {
            padding: 22px;
            margin-top: 18px;
            border-radius: 12px;
            background: #fff;
        }

        @media (max-width: 600px) {
            .site-header {
                flex-wrap: wrap;
                gap: 12px;
            }

            .site-nav {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
            }

            .profile-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<header class="site-header">
    <a class="brand" href="home.php">
        🍦 Ice Cream <span>Delights</span>
    </a>

    <nav class="site-nav">
        <a href="home.php">Home</a>
        <a href="menu.php">Menu</a>
        <a href="wishlist.php">Wishlist</a>
        <a href="profile.php">My Profile</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main class="page">

    <h1 class="page-title">
        My <span class="accent">Profile</span>
    </h1>

    <p class="muted">Manage your account and view your orders.</p>

    <section class="profile-card">
        <div class="profile-avatar">👤</div>

        <h2><?= e($user['name']) ?></h2>
        <p class="muted"><?= e($user['email']) ?></p>

        <div class="profile-details">
            <p>
                <strong>Customer ID:</strong>
                <?= (int)$user['user_id'] ?>
            </p>

            <p>
                <strong>Member since:</strong>
                <?= e($user['created_at']) ?>
            </p>
        </div>

        <div class="actions">
            <a class="btn" href="update.php">✏️ Edit Profile</a>
            <a class="btn btn-light" href="menu.php">🍨 Browse Menu</a>
        </div>
    </section>

    <section style="margin-top: 35px;">
        <h2>My Orders</h2>
        <p class="muted">View your order history and totals.</p>

        <?php if (!$orders): ?>
            <div class="empty-orders">
                <h3>🍦 No orders yet</h3>
                <p class="muted">
                    You haven't placed any orders yet.
                    Explore our menu and find your favorite flavor!
                </p>
                <a class="btn" href="menu.php">Explore Menu →</a>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Order Date</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= (int)$order['order_id'] ?></td>

                            <td>
                                <?= e(
                                    $order['product_name']
                                    ?? 'Product unavailable'
                                ) ?>
                            </td>

                            <td><?= (int)$order['quantity'] ?></td>

                            <td>
                                ETB
                                <?= number_format(
                                    (float)$order['total_price'],
                                    2
                                ) ?>
                            </td>

                            <td>
                                <span class="status">
                                    <?= e($order['status']) ?>
                                </span>
                            </td>

                            <td><?= e($order['order_date']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

</main>

<footer class="site-footer">
    <p>🍦 Ice Cream Delights</p>
    <p>Made with love for ice cream lovers.</p>
</footer>

</body>
</html>
