<?php
// Start session globally
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include required files
require_once __DIR__ . '/app/core/database.php';
require_once __DIR__ . '/app/core/models/Users.php';

// ── Controllers ────────────────────────────────────────────
require_once __DIR__ . '/app/core/controller/Authcontroller.php';
require_once __DIR__ . '/app/core/controller/AdminController.php';
require_once __DIR__ . '/app/core/controller/StudentController.php';
require_once __DIR__ . '/app/core/controller/FacultyController.php';

// Initialize variables for views
$error   = '';
$success = '';

// Simple routing based on the 'action' query parameter
$action = $_GET['action'] ?? 'home';

// Initialize Controllers
$authController    = new AuthController();
$adminController   = new AdminController();
$studentController = new StudentController();
$facultyController = new FacultyController();

// ── Route Handling ──────────────────────────────────────────

switch ($action) {

    // ════════════════════════════════════════════════════════
    //  PUBLIC ROUTES
    // ════════════════════════════════════════════════════════
    case 'home':
        require_once __DIR__ . '/views/home.php';
        break;

    // ════════════════════════════════════════════════════════
    //  AUTH ROUTES
    // ════════════════════════════════════════════════════════
    case 'login':
        $authController->login();
        break;

    case 'register':
        $authController->register();
        break;

    case 'logout':
        $authController->logout();
        break;

    case 'forgot_password':
        $authController->forgotPassword();
        break;

    case 'verify_otp':
        $authController->verifyOtp();
        break;

    case 'reset_password':
        $authController->resetPassword();
        break;

    // ════════════════════════════════════════════════════════
    //  ADMIN ROUTES  (role: admin)
    // ════════════════════════════════════════════════════════
    case 'admin_dashboard':
        $adminController->dashboard();
        break;

    case 'admin_manage_resources':
        $adminController->manageResources();
        break;

    case 'admin_upload_materials':
        $adminController->uploadMaterials();
        break;

    case 'admin_manage_categories':
        $adminController->manageCategories();
        break;

    case 'admin_manage_users':
        $adminController->manageUsers();
        break;

    case 'admin_faculty_accounts':
        $adminController->facultyAccounts();
        break;

    case 'admin_usage_reports':
        $adminController->usageReports();
        break;

    case 'admin_export_data':
        $adminController->exportData();
        break;

    // ════════════════════════════════════════════════════════
    //  STUDENT ROUTES  (role: student)
    // ════════════════════════════════════════════════════════
    case 'student_dashboard':
        $studentController->dashboard();
        break;

    case 'student_search':
        $studentController->searchResources();
        break;

    case 'student_borrowed':
        $studentController->borrowedItems();
        break;

    // ════════════════════════════════════════════════════════
    //  FACULTY ROUTES  (role: faculty)
    // ════════════════════════════════════════════════════════
    case 'faculty_dashboard':
        $facultyController->dashboard();
        break;

    case 'faculty_upload':
        $facultyController->uploadMaterials();
        break;

    // ════════════════════════════════════════════════════════
    //  DEFAULT (404)
    // ════════════════════════════════════════════════════════
    default:
        http_response_code(404);
        echo "<h2>404 Page Not Found</h2>";
        echo "<a href='/library_system/index.php?action=login'>Return to Login</a>";
        break;
}
