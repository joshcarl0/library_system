<?php
require_once __DIR__ . '/../app/core/database.php';
try {
    $db = Database::getInstance();
    $res = $db->fetchAll("DESCRIBE resource_logs");
    echo "Columns in resource_logs:\n";
    foreach ($res as $row) {
        echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
