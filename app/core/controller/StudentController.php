<?php

require_once __DIR__ . '/../models/Users.php';
require_once __DIR__ . '/../models/Resources.php';
require_once __DIR__ . '/../models/Category.php';

class StudentController
{
    private Resources $resourceModel;
    private Category $categoryModel;

    public function __construct()
    {
        $this->resourceModel = new Resources();
        $this->categoryModel = new Category();
    }

    // ════════════════════════════════════════════════════════
    //  DASHBOARD
    // ════════════════════════════════════════════════════════

    public function dashboard(): void
    {
        Users::requireRole('student', '/library_system/index.php?action=login');
        
        $categories = $this->categoryModel->getAll();
        // Get some featured/recent resources for the dashboard
        $resources = $this->resourceModel->getAll('', '', '', ''); // Limit can be applied in view or model
        
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
