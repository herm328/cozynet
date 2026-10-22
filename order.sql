
CREATE TABLE IF NOT EXISTS `orders` (
    `order_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `product_name` VARCHAR(150) NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `quantity` INT NOT NULL DEFAULT 1,
    `total_price` DECIMAL(10,2) NOT NULL,
    `customer_name` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `address` TEXT NOT NULL,
    `payment_method` VARCHAR(50) NOT NULL DEFAULT 'Cash on Delivery',
    `order_status` ENUM(
        'Pending',
        'Confirmed',
        'Preparing',
        'Delivered',
        'Cancelled'
    ) NOT NULL DEFAULT 'Pending',
    `order_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
