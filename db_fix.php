<?php
require_once __DIR__ . '/app/core/Database.php';

$db = Database::getInstance();

echo "<h2>Database Health Check & Fix</h2>";

try {
    $columns = $db->fetchAll("DESCRIBE users");
    $columnNames = array_column($columns, 'Field');
    
    $toAdd = [
        'is_verified' => "ALTER TABLE users ADD COLUMN is_verified TINYINT(1) DEFAULT 0 AFTER role",
        'otp_code'    => "ALTER TABLE users ADD COLUMN otp_code VARCHAR(10) DEFAULT NULL AFTER is_verified",
        'otp_expiry'  => "ALTER TABLE users ADD COLUMN otp_expiry DATETIME DEFAULT NULL AFTER otp_code"
    ];

    foreach ($toAdd as $col => $sql) {
        if (!in_array($col, $columnNames)) {
            echo "Column '$col' is missing. Adding it... ";
            $db->execute($sql);
            echo "<span style='color:green;'>SUCCESS</span><br>";
        } else {
            echo "Column '$col' already exists. <span style='color:blue;'>OK</span><br>";
        }
    }

    echo "<br><strong style='color:green;'>Database is now up to date!</strong>";
    echo "<br><br><a href='index.php?action=register'>Go back to Registration</a>";

} catch (Exception $e) {
    echo "<br><strong style='color:red;'>Error:</strong> " . $e->getMessage();
}
