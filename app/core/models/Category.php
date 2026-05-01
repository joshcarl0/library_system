<?php

require_once __DIR__ . '/../database.php';

class Category
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll(): array
    {
        return $this->db->fetchAll("SELECT * FROM categories ORDER BY category_name ASC");
    }

    public function countAll(): int
    {
        $row = $this->db->fetchOne("SELECT COUNT(*) AS total FROM categories");
        return (int) ($row['total'] ?? 0);
    }

    public function create(array $data): array
    {
        $name = trim($data['name'] ?? '');
        $type = $data['resource_type'] ?? 'Digital';

        if (empty($name)) return ['success' => false, 'message' => 'Category name is required.'];

        try {
            $this->db->execute(
                "INSERT INTO categories (category_name, resource_type) VALUES (:name, :type)",
                ['name' => $name, 'type' => $type]
            );
            return ['success' => true, 'message' => 'Category added successfully.'];
        } catch (\PDOException $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public function update(int $id, array $data): array
    {
        $name = trim($data['name'] ?? '');
        $type = $data['resource_type'] ?? 'Digital';

        if (empty($name)) return ['success' => false, 'message' => 'Category name is required.'];

        try {
            $this->db->execute(
                "UPDATE categories SET category_name = :name, resource_type = :type WHERE id = :id",
                ['name' => $name, 'type' => $type, 'id' => $id]
            );
            return ['success' => true, 'message' => 'Category updated successfully.'];
        } catch (\PDOException $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public function delete(int $id): array
    {
        try {
            $this->db->execute("DELETE FROM categories WHERE id = :id", ['id' => $id]);
            return ['success' => true, 'message' => 'Category deleted successfully.'];
        } catch (\PDOException $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
}
