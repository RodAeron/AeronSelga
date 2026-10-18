CREATE DATABASE IF NOT EXISTS e_commerce_db;
USE e_commerce_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (fullname, email, password, role) VALUES 
('System Admin', 'admin@store.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO products (name, description, price, stock, image_url) VALUES 
('Tailwind CSS Masterclass Guide', 'Comprehensive ebook covering modern utility-first web design patterns.', 499.00, 50, 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=500'),
('Mechanical Gaming Keyboard', 'RGB backlit mechanical keyboard with tactile switches for high performance.', 2499.00, 15, 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=500'),
('Wireless Ergonomic Mouse', 'Precision optical sensor with adjustable DPI and comfortable contoured grip.', 1299.00, 30, 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=500');

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);