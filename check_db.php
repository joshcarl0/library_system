<?php
require_once __DIR__ . '/app/core/database.php';
try {
    $db = Database::getInstance();
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    print_r($tables);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
