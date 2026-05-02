<?php
require_once __DIR__ . '/app/core/database.php';

try {
    $db = Database::getInstance();
    
    // Check if uploaded_by exists
    $res = $db->fetchAll("DESCRIBE resources");
    $hasColumn = false;
    foreach ($res as $row) {
        if ($row['Field'] === 'uploaded_by') {
            $hasColumn = true;
            break;
        }
    }

    if (!$hasColumn) {
        $db->execute("ALTER TABLE resources ADD COLUMN uploaded_by INT NULL AFTER status");
        $db->execute("ALTER TABLE resources ADD CONSTRAINT fk_resources_user FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL");
        echo "Successfully added 'uploaded_by' column to 'resources' table.";
    } else {
        echo "Column 'uploaded_by' already exists.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
