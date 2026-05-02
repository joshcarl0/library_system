<?php

require_once __DIR__ . '/../database.php';

class Subject
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll(): array
    {
        return $this->db->fetchAll("SELECT * FROM subjects ORDER BY name ASC");
    }
}
