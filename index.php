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

    case 'verify_registration':
        $authController->verifyRegistration();
        break;

    case 'update_password':
    case 'change_password':
        $authController->changePassword();
        break;

    // ════════════════════════════════════════════════════════
    //  ADMIN ROUTES  (role: admin)
    // ════════════════════════════════════════════════════════
    case 'admin_dashboard':
        $adminController->dashboard();
        break;
    case 'admin_profile':
        $adminController->myProfile();
        break;

    case 'admin_manage_resources':
        $adminController->manageResources();
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


    case 'admin_manage_requests':
        $adminController->manageRequests();
        break;

    case 'admin_approve_request':
        $adminController->approveRequest();
        break;

    case 'admin_reject_request':
        $adminController->rejectRequest();
        break;

    case 'admin_return_resource':
        $adminController->returnResource();
        break;


    case 'admin_send_reminders':
        $adminController->sendReminders();
        break;

    case 'admin_send_individual_reminder':
        $adminController->sendIndividualReminder();
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

    case 'student_profile':
        $studentController->myProfile();
        break;

    case 'student_notifications':
        $studentController->notifications();
        break;

    case 'api_get_notifications':
        $studentController->apiGetNotifications();
        break;

    case 'student_borrow':
        $studentController->borrow();
        break;

    // ════════════════════════════════════════════════════════
    //  FACULTY ROUTES  (role: faculty)
    // ════════════════════════════════════════════════════════
    case 'faculty_dashboard':
        $facultyController->dashboard();
        break;


    case 'faculty_notifications':
        $facultyController->notifications();
        break;

    case 'faculty_profile':
        $facultyController->profile();
        break;

    case 'faculty_api_get_notifications':
        $facultyController->apiGetNotifications();
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
