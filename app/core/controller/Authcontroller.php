<?php

require_once __DIR__ . '/../models/Users.php';

class AuthController
{
    private Users $usersModel;

    public function __construct()
    {
        $this->usersModel = new Users();
    }

    /**
     * Handle the Login process and view
     */
    public function login()
    {
        $error = '';

        // If already logged in, redirect to dashboard
        if (Users::isLoggedIn()) {
            header("Location: /library_system/index.php?action=dashboard");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identifier = $_POST['identifier'] ?? '';
            $password   = $_POST['password'] ?? '';

            // Attempt login
            $result = $this->usersModel->login($identifier, $password);

            if ($result['success']) {
                // Set session data
                $this->usersModel->startSession($result['user']);
                
                // Redirect to dashboard
                header("Location: /library_system/index.php?action=dashboard");
                exit;
            } else {
                // Pass error to view
                $error = $result['message'];
            }
        }

        // Load the login view
        require __DIR__ . '/../../../views/login.php';
    }

    /**
     * Handle the Registration process and view
     */
    public function register()
    {
        $error = '';
        $success = '';

        // If already logged in, redirect to dashboard
        if (Users::isLoggedIn()) {
            header("Location: /library_system/index.php?action=dashboard");
            exit;
        }

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
                $result = $this->usersModel->register([
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
        require __DIR__ . '/../../../views/register.php';
    }

    /**
     * Handle User Logout
     */
    public function logout()
    {
        $this->usersModel->logout();
        header("Location: /library_system/index.php?action=login");
        exit;
    }

    // ════════════════════════════════════════════════════════
    //  FORGOT PASSWORD & OTP
    // ════════════════════════════════════════════════════════

    public function forgotPassword()
    {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $user = $this->usersModel->findByEmail($email);

            if ($user) {
                // Generate OTP and send email
                $otp = $this->usersModel->generateAndSaveOtp($user['id']);
                
                require_once __DIR__ . '/../mail.php';
                $refNo = strtoupper(substr(uniqid(), -6));
                
                if (sendOtpMail($email, $otp, $refNo)) {
                    // Store user ID in session temporarily for OTP verification
                    if (session_status() === PHP_SESSION_NONE) session_start();
                    $_SESSION['reset_user_id'] = $user['id'];
                    $_SESSION['reset_email'] = $email;
                    
                    header("Location: /library_system/index.php?action=verify_otp");
                    exit;
                } else {
                    $error = "Failed to send OTP email. Please try again later.";
                }
            } else {
                // Security best practice: Don't reveal if email exists or not
                $error = "If your email is registered, you will receive an OTP shortly.";
            }
        }

        require __DIR__ . '/../../../views/forgot_password.php';
    }

    public function verifyOtp()
    {
        $error = '';
        $success = '';

        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['reset_user_id'] ?? null;

        if (!$userId) {
            header("Location: /library_system/index.php?action=forgot_password");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $otp = $_POST['otp'] ?? '';
            $result = $this->usersModel->validateOtp($userId, $otp);

            if ($result['valid']) {
                // OTP is correct
                $_SESSION['otp_verified'] = true;
                header("Location: /library_system/index.php?action=reset_password");
                exit;
            } else {
                $error = $result['message'];
            }
        }

        require __DIR__ . '/../../../views/verify_otp.php';
    }

    public function resetPassword()
    {
        $error = '';

        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['reset_user_id'] ?? null;
        $otpVerified = $_SESSION['otp_verified'] ?? false;

        // Ensure user verified OTP before accessing reset page
        if (!$userId || !$otpVerified) {
            header("Location: /library_system/index.php?action=forgot_password");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (strlen($password) < 8) {
                $error = "Password must be at least 8 characters long.";
            } elseif ($password !== $confirmPassword) {
                $error = "Passwords do not match.";
            } else {
                if ($this->usersModel->updatePassword($userId, $password)) {
                    // Clear reset session data
                    unset($_SESSION['reset_user_id'], $_SESSION['reset_email'], $_SESSION['otp_verified']);
                    
                    // Redirect to login with success message
                    header("Location: /library_system/index.php?action=login&reset=success");
                    exit;
                } else {
                    $error = "Failed to update password. Please try again.";
                }
            }
        }

        require __DIR__ . '/../../../views/reset_password.php';
    }
}
