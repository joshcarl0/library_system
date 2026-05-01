<?php
require_once __DIR__ . '/../app/core/database.php';
try {
    $db = Database::getInstance();
    $res = $db->fetchAll("SELECT * FROM resources");
    echo "Total Resources: " . count($res) . "\n";
    print_r($res);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
