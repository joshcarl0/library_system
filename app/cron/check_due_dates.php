<?php
/**
 * Due Date Reminder Script
 * This script checks for resources that are due today and sends email reminders to users.
 * Recommended: Run once a day via Cron Job.
 */

// Include necessary files
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/services/EmailService.php';

// Initialize Database and EmailService
$db = Database::getInstance();
$emailService = new EmailService();

// Get items that are borrowed and due today (or overdue if we want)
// We'll target items where due_date is today and not yet returned.
$today = date('Y-m-d');

$query = "
    SELECT rl.id, rl.due_date, r.title, u.fullname, u.email 
    FROM resource_logs rl
    JOIN resources r ON rl.resource_id = r.id
    JOIN users u ON rl.user_id = u.id
    WHERE rl.action = 'Borrowed' 
      AND rl.return_date IS NULL 
      AND DATE(rl.due_date) = :today
";

$dueToday = $db->fetchAll($query, ['today' => $today]);

$sentCount = 0;
$failedCount = 0;

echo "--- Starting Due Date Reminder Process (" . date('Y-m-d H:i:s') . ") ---\n";
echo "Found " . count($dueToday) . " items due today.\n";

foreach ($dueToday as $item) {
    echo "Sending reminder to {$item['fullname']} ({$item['email']}) for '{$item['title']}'... ";
    
    if (empty($item['email'])) {
        echo "[FAILED] No email found for user.\n";
        $failedCount++;
        continue;
    }

    $success = $emailService->sendDueReminder(
        $item['email'], 
        $item['fullname'], 
        $item['title'], 
        $item['due_date']
    );

    if ($success) {
        echo "[SUCCESS]\n";
        $sentCount++;
    } else {
        echo "[FAILED] Check email service logs.\n";
        $failedCount++;
    }
}

echo "--- Process Completed ---\n";
echo "Total Sent: $sentCount\n";
echo "Total Failed: $failedCount\n";
echo "---------------------------------------------------------\n";
