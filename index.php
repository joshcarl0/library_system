<?php
// Start session globally
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include required files
require_once __DIR__ . '/app/core/database.php';
require_once __DIR__ . '/app/core/models/Users.php';

require_once __DIR__ . '/app/core/controller/Authcontroller.php';

// Initialize variables for views (still used in dashboard route for now)
$error = '';
$success = '';

// Simple routing based on the 'action' query parameter
$action = $_GET['action'] ?? 'login';

// Initialize the AuthController
$authController = new AuthController();

// ── Route Handling ──────────────────────────────────────────

switch ($action) {
    
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
