<?php
require_once __DIR__ . '/app/core/Database.php';

$db = Database::getInstance();

try {
    // Add is_verified column if it doesn't exist
    $db->execute("ALTER TABLE users ADD COLUMN is_verified TINYINT(1) DEFAULT 0 AFTER role");
    echo "Column 'is_verified' added successfully.\n";
    
    // Also add otp_code and otp_expiry just in case (though they seem to be mentioned in code)
    // Looking at the code, they are used in generateAndSaveOtp but let's be sure
    $columns = $db->fetchAll("DESCRIBE users");
    $columnNames = array_column($columns, 'Field');
    
    if (!in_array('otp_code', $columnNames)) {
        $db->execute("ALTER TABLE users ADD COLUMN otp_code VARCHAR(10) DEFAULT NULL AFTER is_verified");
        echo "Column 'otp_code' added successfully.\n";
    }
    if (!in_array('otp_expiry', $columnNames)) {
        $db->execute("ALTER TABLE users ADD COLUMN otp_expiry DATETIME DEFAULT NULL AFTER otp_code");
        echo "Column 'otp_expiry' added successfully.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
