<?php
require_once __DIR__ . '/../app/core/database.php';

try {
    $db = Database::getInstance();
    $sql = "CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $db->execute($sql);
    echo "Categories table created or already exists.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
