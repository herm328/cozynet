
CREATE DATABASE IF NOT EXISTS ice_cream_delights;

USE ice_cream_delights;

-- Users table for registration and login
CREATE TABLE IF NOT EXISTS users (
    user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(254) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Ice cream products table
CREATE TABLE IF NOT EXISTS products (
    product_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample ice cream products
INSERT INTO products (name, description, price, image) VALUES
('Strawberry Bliss', 'Sweet, fruity, and wonderfully creamy.', 150.00, 'strawberry.jpg'),
('Chocolate Dream', 'Rich chocolate flavor for your sweetest cravings.', 180.00, 'chocolate.jpg'),
('Vanilla Cloud', 'Classic vanilla with a smooth, dreamy finish.', 130.00, 'vanilla.jpg');
