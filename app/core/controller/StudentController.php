<?php

require_once __DIR__ . '/../models/Users.php';

class StudentController
{
    public function __construct() {}

    // ════════════════════════════════════════════════════════
    //  DASHBOARD
    // ════════════════════════════════════════════════════════

    public function dashboard(): void
    {
        Users::requireRole('student', '/library_system/index.php?action=login');
        require_once __DIR__ . '/../../../views/student/student_dashboard.php';
    }

    // ════════════════════════════════════════════════════════
    //  SEARCH RESOURCES
    // ════════════════════════════════════════════════════════

    public function searchResources(): void
    {
        Users::requireRole('student', '/library_system/index.php?action=login');
        require_once __DIR__ . '/../../../views/student/search_resources.php';
    }

    // ════════════════════════════════════════════════════════
    //  BORROWED ITEMS
    // ════════════════════════════════════════════════════════

    public function borrowedItems(): void
    {
        Users::requireRole('student', '/library_system/index.php?action=login');
        require_once __DIR__ . '/../../../views/student/borrowed_items.php';
    }
}
