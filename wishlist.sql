
CREATE TABLE IF NOT EXISTS `wishlist` (
    `wishlist_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `added_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY `unique_user_product` (`user_id`, `product_id`),

    INDEX `idx_wishlist_user` (`user_id`),
    INDEX `idx_wishlist_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
