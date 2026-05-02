<?php
require_once __DIR__ . '/../app/core/database.php';
try {
    $db = Database::getInstance();
    $res = $db->fetchAll("SHOW TABLES");
    echo "Tables in lris_db:\n";
    foreach ($res as $row) {
        echo "- " . current($row) . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
