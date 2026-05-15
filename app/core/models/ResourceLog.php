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
            "SELECT rl.*, r.title, r.author, r.type, r.cover_image, r.description, r.file_path, c.category_name 
             FROM resource_logs rl
             JOIN resources r ON rl.resource_id = r.id
             LEFT JOIN categories c ON r.category = c.id
             WHERE rl.user_id = :user_id 
               AND rl.action IN ('Borrowed', 'Pending')
               AND rl.return_date IS NULL
             ORDER BY rl.due_date ASC",
            ['user_id' => $userId]
        );
    }

    /**
     * Create a new log entry.
     */
    public function logAction(int $userId, int $resourceId, string $action, ?string $dueDate = null): bool
    {
        return $this->db->execute(
            "INSERT INTO resource_logs (user_id, resource_id, action, due_date, created_at)
             VALUES (:user_id, :resource_id, :action, :due_date, NOW())",
            [
                'user_id'     => $userId,
                'resource_id' => $resourceId,
                'action'      => $action,
                'due_date'    => $dueDate
            ]
        ) > 0;
    }

    /**
     * Get all logs with user and resource details.
     */
    public function getAllLogs(string $action = ''): array
    {
        $sql = "SELECT rl.*, r.title, r.type, u.fullname, u.student_id 
                FROM resource_logs rl
                LEFT JOIN resources r ON rl.resource_id = r.id
                LEFT JOIN users u ON rl.user_id = u.id";

        $params = [];
        if (!empty($action)) {
            $sql .= " WHERE rl.action = :action";
            $params['action'] = $action;
        }

        $sql .= " ORDER BY rl.created_at DESC";
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Update an existing log.
     */
    public function updateLogAction(int $logId, string $action, ?string $dueDate = null, ?string $returnDate = null): bool
    {
        return $this->db->execute(
            "UPDATE resource_logs 
             SET action = :action, due_date = :due_date, return_date = :return_date 
             WHERE id = :id",
            [
                'action'      => $action,
                'due_date'    => $dueDate,
                'return_date' => $returnDate,
                'id'          => $logId
            ]
        ) > 0;
    }

    public function findById(int $id): array|false
    {
        return $this->db->fetchOne("SELECT * FROM resource_logs WHERE id = :id", ['id' => $id]);
    }
}
