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
        return $this->db->fetchAll("SELECT * FROM categories ORDER BY category ASC");
    }

    public function create(string $name): array
    {
        $name = trim($name);
        if (empty($name)) return ['success' => false, 'message' => 'Category name is required.'];

        try {
            $this->db->execute("INSERT INTO categories (category) VALUES (:category)", ['category' => $name]);
            return ['success' => true, 'message' => 'Category added successfully.'];
        } catch (\PDOException $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public function update(int $id, string $name): array
    {
        $name = trim($name);
        if (empty($name)) return ['success' => false, 'message' => 'Category name is required.'];

        try {
            $this->db->execute("UPDATE categories SET category = :category WHERE id = :id", ['category' => $name, 'id' => $id]);
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
