<?php
// Start session globally
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include required files
require_once __DIR__ . '/app/core/database.php';
require_once __DIR__ . '/app/core/models/Users.php';

// Initialize the Users model
$usersModel = new Users();

// Initialize variables for views
$error = '';
$success = '';

// Simple routing based on the 'action' query parameter
$action = $_GET['action'] ?? 'login';

// Check if user is already logged in (skip for logout action)
if ($action !== 'logout' && Users::isLoggedIn()) {
    // Redirect to dashboard if they try to access login/register while logged in
    if (in_array($action, ['login', 'register'])) {
        header("Location: /library_system/index.php?action=dashboard");
        exit;
    }
}

// ── Route Handling ──────────────────────────────────────────

switch ($action) {
    
    // ════════════════════════════════════════════════════════
    //  LOGIN ROUTE
    // ════════════════════════════════════════════════════════
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identifier = $_POST['identifier'] ?? '';
            $password   = $_POST['password'] ?? '';

            // Attempt login
            $result = $usersModel->login($identifier, $password);

            if ($result['success']) {
                // Set session data
                $usersModel->startSession($result['user']);
                
                // Redirect to dashboard
                header("Location: /library_system/index.php?action=dashboard");
                exit;
            } else {
                // Pass error to view
                $error = $result['message'];
            }
        }
        
        // Load the login view
        require __DIR__ . '/views/login.php';
        break;

    // ════════════════════════════════════════════════════════
    //  REGISTER ROUTE
    // ════════════════════════════════════════════════════════
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname         = $_POST['fullname'] ?? '';
            $studentId        = $_POST['student_id'] ?? '';
            $email            = $_POST['email'] ?? '';
            $password         = $_POST['password'] ?? '';
            $confirmPassword  = $_POST['confirm_password'] ?? '';
            $role             = $_POST['role'] ?? 'student';

            // Ensure role is valid
            if (!in_array($role, ['student', 'faculty'])) {
                $role = 'student';
            }

            // Server-side confirm password check
            if ($password !== $confirmPassword) {
                $error = "Passwords do not match.";
            } else {
                // Attempt registration
                $result = $usersModel->register([
                    'fullname'   => $fullname,
                    'student_id' => $studentId,
                    'email'      => $email,
                    'password'   => $password,
                    'role'       => $role
                ]);

                if ($result['success']) {
                    // Pass success message to view
                    $success = $result['message'];
                    
                    // Clear POST data so form fields are empty after success
                    $_POST = [];
                } else {
                    // Pass error to view
                    $error = $result['message'];
                }
            }
        }

        // Load the register view
        require __DIR__ . '/views/register.php';
        break;

    // ════════════════════════════════════════════════════════
    //  LOGOUT ROUTE
    // ════════════════════════════════════════════════════════
    case 'logout':
        $usersModel->logout();
        header("Location: /library_system/index.php?action=login");
        exit;

    // ════════════════════════════════════════════════════════
    //  DASHBOARD ROUTE (Protected)
    // ════════════════════════════════════════════════════════
    case 'dashboard':
        // Require login to access this page
        Users::requireLogin('/library_system/index.php?action=login');
        
        echo "<h1>Welcome, " . htmlspecialchars($_SESSION['fullname']) . "!</h1>";
        echo "<p>You are logged in.</p>";
        echo "<a href='/library_system/index.php?action=logout'>Log out</a>";
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
