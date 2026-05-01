<?php

require_once __DIR__ . '/../models/Users.php';

class FacultyController
{
    public function __construct() {}

    // ════════════════════════════════════════════════════════
    //  DASHBOARD
    // ════════════════════════════════════════════════════════

    public function dashboard(): void
    {
        Users::requireRole('faculty', '/library_system/index.php?action=login');
        require_once __DIR__ . '/../../../views/faculty/faculty_dashboard.php';
    }

    // ════════════════════════════════════════════════════════
    //  UPLOAD MATERIALS
    // ════════════════════════════════════════════════════════

    public function uploadMaterials(): void
    {
        Users::requireRole('faculty', '/library_system/index.php?action=login');
        require_once __DIR__ . '/../../../views/faculty/upload_materials.php';
    }
}
