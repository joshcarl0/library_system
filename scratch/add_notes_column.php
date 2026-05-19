<?php
/**
 * Migration: Add 'notes' column to resource_logs table.
 * Run once via browser: http://localhost/library_system/scratch/add_notes_column.php
 */

require_once __DIR__ . '/../app/core/database.php';

$db  = Database::getInstance();
$pdo = $db->getConnection();

try {
    // Check if column already exists
    $check = $pdo->query("SHOW COLUMNS FROM resource_logs LIKE 'notes'");
    if ($check->rowCount() > 0) {
        echo "<p style='color:orange;'>⚠️ Column <strong>notes</strong> already exists in <strong>resource_logs</strong>. No changes made.</p>";
    } else {
        $pdo->exec("ALTER TABLE resource_logs ADD COLUMN notes TEXT NULL AFTER due_date");
        echo "<p style='color:green;'>✅ Column <strong>notes</strong> added successfully to <strong>resource_logs</strong>.</p>";
    }
} catch (Exception $e) {
    echo "<p style='color:red;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
