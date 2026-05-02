<?php
require_once __DIR__ . '/../app/core/database.php';

try {
    $db = Database::getInstance();
    
    // Create notifications table
    $sql = "CREATE TABLE IF NOT EXISTS notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        type VARCHAR(50) DEFAULT 'system',
        is_read TINYINT(1) DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB";
    
    $db->execute($sql);
    echo "Table 'notifications' created successfully.\n";

    // Insert initial notifications for all students
    $insertSql = "INSERT INTO notifications (user_id, title, message, type) 
                  SELECT id, 'Welcome to LRIS', 'Welcome to the new Learning Resource Information System!', 'system' 
                  FROM users 
                  WHERE role = 'student' 
                  AND id NOT IN (SELECT user_id FROM notifications WHERE title = 'Welcome to LRIS')";
    
    $db->execute($insertSql);
    echo "Initial notifications inserted.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
