<?php

require_once __DIR__ . '/../database.php';

class Notification
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get notifications for a specific user with optional filtering.
     */
    public function getByUser(int $userId, string $filter = 'all'): array
    {
        $sql = "SELECT * FROM notifications WHERE user_id = :user_id";
        $params = ['user_id' => $userId];

        if ($filter === 'unread') {
            $sql .= " AND is_read = 0";
        } elseif ($filter === 'loans') {
            $sql .= " AND type = 'loan'";
        } elseif ($filter === 'system') {
            $sql .= " AND type = 'system'";
        }

        $sql .= " ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Get the count of unread notifications for a user.
     */
    public function getUnreadCount(int $userId): int
    {
        $row = $this->db->fetchOne(
            "SELECT COUNT(*) as total FROM notifications WHERE user_id = :user_id AND is_read = 0",
            ['user_id' => $userId]
        );
        return (int) ($row['total'] ?? 0);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(int $id): bool
    {
        return $this->db->execute(
            "UPDATE notifications SET is_read = 1 WHERE id = :id",
            ['id' => $id]
        ) > 0;
    }

    /**
     * Mark all notifications as read for a specific user.
     */
    public function markAllAsRead(int $userId): bool
    {
        return $this->db->execute(
            "UPDATE notifications SET is_read = 1 WHERE user_id = :user_id",
            ['user_id' => $userId]
        ) >= 0;
    }
}
