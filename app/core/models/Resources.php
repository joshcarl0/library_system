<?php

require_once __DIR__ . '/../database.php';

/**
 * Resources Model
 * Handles all learning resource CRUD operations using PDO.
 */
class Resources
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ════════════════════════════════════════════════════════
    //  FETCH
    // ════════════════════════════════════════════════════════

    /**
     * Get all resources, with optional search/filter.
     */
    public function getAll(string $search = '', string $category = '', string $type = '', string $status = ''): array
    {
        $sql    = "SELECT * FROM resources WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (title LIKE :search OR author LIKE :search2 OR subject LIKE :search3)";
            $params['search']  = "%{$search}%";
            $params['search2'] = "%{$search}%";
            $params['search3'] = "%{$search}%";
        }

        if (!empty($category)) {
            $sql .= " AND category = :category";
            $params['category'] = $category;
        }

        if (!empty($type)) {
            $sql .= " AND type = :type";
            $params['type'] = $type;
        }

        if (!empty($status)) {
            $sql .= " AND status = :status";
            $params['status'] = $status;
        }

        $sql .= " ORDER BY created_at DESC";

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Get a single resource by ID.
     */
    public function getById(int $id): array|false
    {
        return $this->db->fetchOne(
            "SELECT * FROM resources WHERE id = :id LIMIT 1",
            ['id' => $id]
        );
    }

    /**
     * Count total resources.
     */
    public function countAll(): int
    {
        $row = $this->db->fetchOne("SELECT COUNT(*) AS total FROM resources");
        return (int) ($row['total'] ?? 0);
    }

    /**
     * Count resources by status.
     */
    public function countByStatus(string $status): int
    {
        $row = $this->db->fetchOne(
            "SELECT COUNT(*) AS total FROM resources WHERE status = :status",
            ['status' => $status]
        );
        return (int) ($row['total'] ?? 0);
    }

    /**
     * Get all distinct categories.
     */
    public function getCategories(): array
    {
        return $this->db->fetchAll("SELECT DISTINCT category FROM resources WHERE category != '' ORDER BY category ASC");
    }

    // ════════════════════════════════════════════════════════
    //  CREATE
    // ════════════════════════════════════════════════════════

    public function create(array $data): array
    {
        $title       = trim($data['title'] ?? '');
        $author      = trim($data['author'] ?? '');
        $subject     = trim($data['subject'] ?? '');
        $category    = trim($data['category'] ?? '');
        $type        = trim($data['type'] ?? 'book');
        $description = trim($data['description'] ?? '');
        $status      = in_array($data['status'] ?? '', ['available', 'borrowed', 'unavailable'])
                       ? $data['status'] : 'available';
        $file_path   = trim($data['file_path'] ?? '');

        if (empty($title) || empty($author)) {
            return ['success' => false, 'message' => 'Title and Author are required.'];
        }

        try {
            $this->db->execute(
                "INSERT INTO resources (title, author, subject, category, type, description, status, file_path, created_at)
                 VALUES (:title, :author, :subject, :category, :type, :description, :status, :file_path, NOW())",
                compact('title', 'author', 'subject', 'category', 'type', 'description', 'status', 'file_path')
            );
            return ['success' => true, 'message' => 'Resource added successfully.'];
        } catch (\PDOException $e) {
            error_log('Resource create failed: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to add resource. Please try again.'];
        }
    }

    // ════════════════════════════════════════════════════════
    //  UPDATE
    // ════════════════════════════════════════════════════════

    public function update(int $id, array $data): array
    {
        $title       = trim($data['title'] ?? '');
        $author      = trim($data['author'] ?? '');
        $subject     = trim($data['subject'] ?? '');
        $category    = trim($data['category'] ?? '');
        $type        = trim($data['type'] ?? 'book');
        $description = trim($data['description'] ?? '');
        $status      = in_array($data['status'] ?? '', ['available', 'borrowed', 'unavailable'])
                       ? $data['status'] : 'available';
        $file_path   = trim($data['file_path'] ?? '');

        if (empty($title) || empty($author)) {
            return ['success' => false, 'message' => 'Title and Author are required.'];
        }

        try {
            $this->db->execute(
                "UPDATE resources
                 SET title = :title, author = :author, subject = :subject,
                     category = :category, type = :type, description = :description,
                     status = :status, file_path = :file_path
                 WHERE id = :id",
                compact('title', 'author', 'subject', 'category', 'type', 'description', 'status', 'file_path', 'id')
            );
            return ['success' => true, 'message' => 'Resource updated successfully.'];
        } catch (\PDOException $e) {
            error_log('Resource update failed: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to update resource.'];
        }
    }

    // ════════════════════════════════════════════════════════
    //  DELETE
    // ════════════════════════════════════════════════════════

    public function delete(int $id): array
    {
        try {
            $affected = $this->db->execute(
                "DELETE FROM resources WHERE id = :id",
                ['id' => $id]
            );
            if ($affected > 0) {
                return ['success' => true, 'message' => 'Resource deleted successfully.'];
            }
            return ['success' => false, 'message' => 'Resource not found.'];
        } catch (\PDOException $e) {
            error_log('Resource delete failed: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to delete resource.'];
        }
    }
}
