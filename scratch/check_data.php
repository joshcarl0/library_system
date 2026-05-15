<?php
require_once __DIR__ . '/../app/core/database.php';

$db = Database::getInstance();

echo "--- CATEGORIES TABLE ---\n";
$categories = $db->fetchAll("SELECT * FROM categories");
foreach ($categories as $cat) {
    echo "ID: {$cat['id']} | Name: '{$cat['category_name']}'\n";
}

echo "\n--- RESOURCES TABLE (Categories Column) ---\n";
$resources = $db->fetchAll("SELECT id, title, category FROM resources");
foreach ($resources as $r) {
    echo "ID: {$r['id']} | Title: {$r['title']} | Category: '{$r['category']}'\n";
}
?>
