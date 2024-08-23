<?php
require_once 'config.php';

global $link;
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

// Check connection
if ($link === false) {
    error_log("ERROR: Could not connect. " . mysqli_connect_error());
    die("ERROR: Could not connect to the database.");
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if (mysqli_query($link, $sql)) {
    error_log("Database created successfully or already exists.");
} else {
    error_log("ERROR: Could not execute $sql. " . mysqli_error($link));
    die("ERROR: Could not create the database.");
}

// Select the database
mysqli_select_db($link, DB_NAME);

// SQL to create tables
$sql = "
CREATE TABLE IF NOT EXISTS Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role VARCHAR(20) DEFAULT 'user'
);

CREATE TABLE IF NOT EXISTS Addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    shipping_name VARCHAR(255) NOT NULL,  
    address_line1 VARCHAR(255) NOT NULL,
    address_line2 VARCHAR(255),
    city_id INT NOT NULL,
    state_id INT NOT NULL,
    country_id INT NOT NULL,
    postal_code VARCHAR(20) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users(id)
);

CREATE TABLE IF NOT EXISTS Categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS Products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    category_id INT NOT NULL,
    image_url VARCHAR(255),
    FOREIGN KEY (category_id) REFERENCES Categories(id)
);

CREATE TABLE IF NOT EXISTS RegionTable (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('City', 'State', 'Country') NOT NULL,
    parent_id INT DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS Cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    status ENUM('active', 'ordered') DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES Users(id),
    FOREIGN KEY (product_id) REFERENCES Products(id)
);

CREATE TABLE IF NOT EXISTS Orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10, 2) NOT NULL,
    shipping_name VARCHAR(255) NOT NULL,
    shipping_address VARCHAR(255) NOT NULL,
    shipping_city VARCHAR(255) NOT NULL,
    shipping_state VARCHAR(255) NOT NULL,
    shipping_zip VARCHAR(20) NOT NULL,
    shipping_phone VARCHAR(20) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users(id)
);


CREATE TABLE IF NOT EXISTS OrderItems (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES Orders(id),
    FOREIGN KEY (product_id) REFERENCES Products(id)
);";

if (mysqli_multi_query($link, $sql)) {
    do {
        if ($result = mysqli_store_result($link)) {
            while ($row = mysqli_fetch_row($result)) {
            }
            mysqli_free_result($result);
        }
    } while (mysqli_next_result($link));
    error_log("Tables created successfully.");
} else {
    error_log("ERROR: Could not execute $sql. " . mysqli_error($link));
    die("ERROR: Could not create tables.");
}

// Insert categories if they don't already exist
$checkCategories = "SELECT COUNT(*) AS count FROM Categories";
$result = mysqli_query($link, $checkCategories);
$row = mysqli_fetch_assoc($result);

if ($row['count'] == 0) {
    $categories = "
    INSERT INTO Categories (name) VALUES
    ('Smartphones'),
    ('Televisions'),
    ('Smartwatches'),
    ('Laptops'),
    ('Headphones'),
    ('Smart Speakers'),
    ('Cameras'),
    ('Gaming Consoles'),
    ('Fitness Trackers');
    ";

    if (mysqli_query($link, $categories)) {
        error_log("Categories inserted successfully.");
    } else {
        error_log("ERROR: Could not execute $categories. " . mysqli_error($link));
    }
}

// Check if sample products already exist
$checkProducts = "SELECT COUNT(*) AS count FROM Products";
$result = mysqli_query($link, $checkProducts);
$row = mysqli_fetch_assoc($result);

if ($row['count'] == 0) {
    // Insert sample products if they do not exist
    $sampleProducts = "
    INSERT INTO Products (name, description, price, category_id, image_url) VALUES
    ('Samsung Galaxy S21', 'The Samsung Galaxy S21 offers high-end performance with an advanced camera system and sleek design.', 799.99, 1, 'images/s21.webp'),
    ('Sony Bravia 55 Inch 4K', 'Sony Bravia delivers stunning 4K resolution with vivid colors and deep contrast for an immersive viewing experience.', 1299.99, 2, 'images/bravia.webp'),
    ('Apple Watch Series 6', 'Apple Watch Series 6 with advanced health tracking and a brighter always-on display.', 399.99, 3, 'images/apple_watch.webp'),
    ('Dell XPS 13', 'Dell XPS 13 is a high-performance laptop with a stunning 13-inch display and powerful processors.', 999.99, 4, 'images/xps13.webp'),
    ('Bose QuietComfort 35 II', 'Bose QuietComfort 35 II offers top-of-the-line noise cancellation and superior comfort.', 299.99, 5, 'images/bose_qc35.webp'),
    ('Amazon Echo Dot', 'Amazon Echo Dot is a smart speaker with Alexa, perfect for hands-free control of your smart home.', 49.99, 6, 'images/echo_dot.jpeg'),
    ('GoPro HERO9', 'GoPro HERO9 captures stunning 5K video and 20MP photos, perfect for adventurers and vloggers.', 449.99, 7, 'images/gopro_hero9.webp'),
    ('Nintendo Switch', 'Nintendo Switch is a versatile gaming console that can be used as a handheld or connected to a TV.', 299.99, 8, 'images/nintendo_switch.webp'),
    ('Fitbit Charge 4', 'Fitbit Charge 4 tracks your fitness activities, heart rate, and sleep patterns.', 149.99, 9, 'images/fitbit_charge4.webp'),
    ('Sony WH-1000XM4', 'Sony WH-1000XM4 offers industry-leading noise cancellation and exceptional sound quality.', 349.99, 5, 'images/sony_wh1000xm4.webp');";

    if (mysqli_query($link, $sampleProducts)) {
        error_log("Sample products inserted successfully.");
    } else {
        error_log("ERROR: Could not execute $sampleProducts. " . mysqli_error($link));
    }
}
?>