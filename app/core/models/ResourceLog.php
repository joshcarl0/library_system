<?php

class ResourceLog
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all logs for a specific student, joined with resource info.
     */
    public function getStudentBorrowedItems(int $userId): array
    {
        return $this->db->fetchAll(
            "SELECT rl.*, r.title, r.author, r.type, c.category_name 
             FROM resource_logs rl
             JOIN resources r ON rl.resource_id = r.id
             LEFT JOIN categories c ON r.category = c.id
             WHERE rl.user_id = :user_id 
               AND rl.action = 'Borrowed'
               AND rl.return_date IS NULL
             ORDER BY rl.due_date ASC",
            ['user_id' => $userId]
        );
    }
}
