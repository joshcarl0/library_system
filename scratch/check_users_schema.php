<?php
require_once __DIR__ . '/../app/core/database.php';
try {
    $db = Database::getInstance();
    $res = $db->fetchAll("DESCRIBE users");
    echo "<pre>"; print_r($res); echo "</pre>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
