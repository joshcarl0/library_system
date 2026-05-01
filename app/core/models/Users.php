<?php

require_once __DIR__ . '/../database.php';

/**
 * Users Model
 *
 * Handles all user-related database operations securely using PDO.
 * - Login with bcrypt password_verify (no plain-text passwords)
 * - OTP generation, saving, and validation
 * - Brute-force protection via login attempt tracking
 * - Session management helpers
 */
class Users
{
    // ── Brute-force lockout settings ────────────────────────
    private const MAX_ATTEMPTS       = 5;    // Max failed logins before lockout
    private const LOCKOUT_MINUTES    = 15;   // Lockout duration in minutes
    private const IP_MAX_ATTEMPTS    = 10;   // Max attempts from a single IP (across all accounts)
    private const IP_LOCKOUT_MINUTES = 30;   // IP lockout duration in minutes

    // ── OTP settings ────────────────────────────────────────
    private const OTP_EXPIRY_MINUTES = 10;

    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ════════════════════════════════════════════════════════
    //  LOGIN
    // ════════════════════════════════════════════════════════

    /**
     * Attempt to log a user in.
     *
     * Returns an array on success:
     *   ['success' => true, 'user' => [...user row...]]
     *
     * Returns an array on failure:
     *   ['success' => false, 'message' => 'Human-readable error']
     *
     * @param  string $identifier  email OR student_id
     * @param  string $plainPassword
     * @return array
     */
    public function login(string $identifier, string $plainPassword): array
    {
        // ── 1. Sanitize ──────────────────────────────────────
        $identifier    = trim($identifier);
        $plainPassword = trim($plainPassword);
        $ip            = $this->getClientIp();

        if (empty($identifier) || empty($plainPassword)) {
            return ['success' => false, 'message' => 'All fields are required.'];
        }

        // ── 2. IP-based lockout check (before DB user lookup) ─
        //    Blocks attackers cycling through many accounts from one IP.
        $ipLockout = $this->checkIpLockout($ip);
        if ($ipLockout['locked']) {
            return [
                'success' => false,
                'message' => "Too many failed attempts from your location. Try again in {$ipLockout['minutes_left']} minute(s)."
            ];
        }

        // ── 3. Fetch user by email or student_id ─────────────
        $user = $this->findByEmailOrStudentId($identifier);

        if (!$user) {
            // Still record the IP attempt even for unknown accounts
            $this->recordFailedAttempt(null, $ip);
            return ['success' => false, 'message' => 'Invalid credentials.'];
        }

        // ── 4. Account-level lockout check ───────────────────
        $lockout = $this->checkLockout($user['id']);
        if ($lockout['locked']) {
            return [
                'success' => false,
                'message' => "Account locked. Try again in {$lockout['minutes_left']} minute(s)."
            ];
        }

        // ── 5. Verify password (bcrypt) ──────────────────────
        if (!password_verify($plainPassword, $user['password'])) {
            $this->recordFailedAttempt($user['id'], $ip);
            $remaining = self::MAX_ATTEMPTS - $this->getRecentAttemptCount($user['id']);
            $remaining = max(0, $remaining);

            return [
                'success' => false,
                'message' => "Invalid credentials. {$remaining} attempt(s) remaining."
            ];
        }

        // ── 6. Clear failed attempts on success ──────────────
        $this->clearFailedAttempts($user['id'], $ip);

        // ── 7. Rehash if needed (future PHP upgrades) ────────
        if (password_needs_rehash($user['password'], PASSWORD_BCRYPT)) {
            $newHash = password_hash($plainPassword, PASSWORD_BCRYPT);
            $this->db->execute(
                "UPDATE users SET password = :password WHERE id = :id",
                ['password' => $newHash, 'id' => $user['id']]
            );
        }

        return ['success' => true, 'user' => $this->sanitizeUser($user)];
    }

    // ════════════════════════════════════════════════════════
    //  REGISTER
    // ════════════════════════════════════════════════════════

    /**
     * Attempt to register a new user.
     *
     * Returns an array on success:
     *   ['success' => true, 'message' => 'Registration successful']
     *
     * Returns an array on failure:
     *   ['success' => false, 'message' => 'Human-readable error']
     */
    public function register(array $data): array
    {
        $fullname   = trim($data['fullname'] ?? '');
        $studentId  = trim($data['student_id'] ?? '');
        $email      = trim($data['email'] ?? '');
        $password   = $data['password'] ?? '';
        
        if (empty($fullname) || empty($studentId) || empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'All fields are required.'];
        }

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email address.'];
        }

        // Check if email already exists
        if ($this->findByEmail($email)) {
            return ['success' => false, 'message' => 'Email address is already in use.'];
        }

        // Check if student ID already exists
        if ($this->findByStudentId($studentId)) {
            return ['success' => false, 'message' => 'ID Number is already registered.'];
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        // Use provided role or default to 'student'
        $role = in_array($data['role'] ?? '', ['student', 'faculty']) ? $data['role'] : 'student';

        try {
            $this->db->execute(
                "INSERT INTO users (student_id, fullname, email, password, role, created_at)
                 VALUES (:student_id, :fullname, :email, :password, :role, NOW())",
                [
                    'student_id' => $studentId,
                    'fullname'   => $fullname,
                    'email'      => $email,
                    'password'   => $hashedPassword,
                    'role'       => $role
                ]
            );
            return ['success' => true, 'message' => 'Registration successful! You can now log in.'];
        } catch (\PDOException $e) {
            error_log('Registration failed: ' . $e->getMessage());
            return ['success' => false, 'message' => 'An error occurred during registration. Please try again later.'];
        }
    }

    // ════════════════════════════════════════════════════════
    //  SESSION HELPERS
    // ════════════════════════════════════════════════════════

    /**
     * Start a user session after a successful login.
     * Call this after login() returns success.
     */
    /**
     * Strip sensitive fields from a user row before returning it to callers.
     * Ensures password hashes and OTP codes never leak into views or session dumps.
     */
    private function sanitizeUser(array $user): array
    {
        unset(
            $user['password'],
            $user['otp_code'],
            $user['otp_expiry']
        );
        return $user;
    }

    public function startSession(array $user): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Regenerate session ID to prevent session fixation
        session_regenerate_id(true);

        $_SESSION['user_id']    = $user['id'];
        $_SESSION['student_id'] = $user['student_id'];
        $_SESSION['fullname']   = $user['fullname'];
        $_SESSION['email']      = $user['email'];
        $_SESSION['role']       = $user['role'];
        $_SESSION['logged_in']  = true;
    }

    /**
     * Destroy the current session (logout).
     */
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        session_destroy();

        // Remove session cookie
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
    }

    /**
     * Check if a user is currently logged in.
     */
    public static function isLoggedIn(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return !empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    /**
     * Redirect to login page if user is not logged in.
     */
    public static function requireLogin(string $loginUrl = '/login.php'): void
    {
        if (!self::isLoggedIn()) {
            header('Location: ' . $loginUrl);
            exit;
        }
    }

    /**
     * Require a specific role; redirect if not authorized.
     */
    public static function requireRole(string $role, string $redirectUrl = '/login.php'): void
    {
        self::requireLogin($redirectUrl);
        if (($_SESSION['role'] ?? '') !== $role) {
            header('Location: ' . $redirectUrl);
            exit;
        }
    }

    // ════════════════════════════════════════════════════════
    //  OTP
    // ════════════════════════════════════════════════════════

    /**
     * Generate a 6-digit OTP, save it to the DB, and return it.
     */
    public function generateAndSaveOtp(int $userId): string
    {
        $otp    = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiry = date('Y-m-d H:i:s', strtotime('+' . self::OTP_EXPIRY_MINUTES . ' minutes'));

        $this->db->execute(
            "UPDATE users SET otp_code = :otp, otp_expiry = :expiry WHERE id = :id",
            ['otp' => $otp, 'expiry' => $expiry, 'id' => $userId]
        );

        return $otp;
    }

    /**
     * Validate a submitted OTP for a given user.
     *
     * @return array ['valid' => bool, 'message' => string]
     */
    public function validateOtp(int $userId, string $submittedOtp): array
    {
        $user = $this->findById($userId);

        if (!$user) {
            return ['valid' => false, 'message' => 'User not found.'];
        }

        if (empty($user['otp_code'])) {
            return ['valid' => false, 'message' => 'No OTP was generated.'];
        }

        if (strtotime($user['otp_expiry']) < time()) {
            return ['valid' => false, 'message' => 'OTP has expired. Please request a new one.'];
        }

        if (!hash_equals($user['otp_code'], $submittedOtp)) {
            return ['valid' => false, 'message' => 'Invalid OTP.'];
        }

        // Clear OTP after successful use
        $this->db->execute(
            "UPDATE users SET otp_code = NULL, otp_expiry = NULL WHERE id = :id",
            ['id' => $userId]
        );

        return ['valid' => true, 'message' => 'OTP verified successfully.'];
    }

    // ════════════════════════════════════════════════════════
    //  PASSWORD MANAGEMENT
    // ════════════════════════════════════════════════════════

    /**
     * Update a user's password.
     */
    public function updatePassword(int $userId, string $newPassword): bool
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        
        try {
            $this->db->execute(
                "UPDATE users SET password = :password WHERE id = :id",
                ['password' => $hashedPassword, 'id' => $userId]
            );
            return true;
        } catch (\PDOException $e) {
            error_log('Failed to update password: ' . $e->getMessage());
            return false;
        }
    }

    // ════════════════════════════════════════════════════════
    //  FINDERS
    // ════════════════════════════════════════════════════════

    /**
     * Find a user by their primary key ID.
     */
    public function findById(int $id): array|false
    {
        return $this->db->fetchOne(
            "SELECT * FROM users WHERE id = :id LIMIT 1",
            ['id' => $id]
        );
    }

    /**
     * Find a user by email address.
     */
    public function findByEmail(string $email): array|false
    {
        return $this->db->fetchOne(
            "SELECT * FROM users WHERE email = :email LIMIT 1",
            ['email' => $email]
        );
    }

    /**
     * Find a user by student ID.
     */
    public function findByStudentId(string $studentId): array|false
    {
        return $this->db->fetchOne(
            "SELECT * FROM users WHERE student_id = :student_id LIMIT 1",
            ['student_id' => $studentId]
        );
    }

    /**
     * Find a user by email OR student_id (used for flexible login).
     */
    public function findByEmailOrStudentId(string $identifier): array|false
    {
        return $this->db->fetchOne(
            "SELECT * FROM users
             WHERE email = :email OR student_id = :student_id
             LIMIT 1",
            ['email' => $identifier, 'student_id' => $identifier]
        );
    }

    /**
     * Count total users.
     */
    public function countAll(): int
    {
        $row = $this->db->fetchOne("SELECT COUNT(*) AS total FROM users");
        return (int) ($row['total'] ?? 0);
    }

    /**
     * Count users by role.
     */
    public function countByRole(string $role): int
    {
        $row = $this->db->fetchOne(
            "SELECT COUNT(*) AS total FROM users WHERE role = :role",
            ['role' => $role]
        );
        return (int) ($row['total'] ?? 0);
    }

    /**
     * Get all users, with optional search and role filter.
     */
    public function getAllUsers(string $search = '', string $role = ''): array
    {
        $sql    = "SELECT id, student_id, fullname, email, role, created_at FROM users WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (fullname LIKE :search OR student_id LIKE :search2 OR email LIKE :search3)";
            $params['search']  = "%{$search}%";
            $params['search2'] = "%{$search}%";
            $params['search3'] = "%{$search}%";
        }

        if (!empty($role)) {
            $sql .= " AND role = :role";
            $params['role'] = $role;
        }

        $sql .= " ORDER BY created_at DESC";

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Update user details (admin use).
     */
    public function updateUser(int $id, array $data): array
    {
        $fullname  = trim($data['fullname'] ?? '');
        $email     = trim($data['email'] ?? '');
        $role      = in_array($data['role'] ?? '', ['student', 'faculty', 'admin']) ? $data['role'] : 'student';

        if (empty($fullname) || empty($email)) {
            return ['success' => false, 'message' => 'Full name and email are required.'];
        }

        try {
            $this->db->execute(
                "UPDATE users SET fullname = :fullname, email = :email, role = :role WHERE id = :id",
                ['fullname' => $fullname, 'email' => $email, 'role' => $role, 'id' => $id]
            );
            return ['success' => true, 'message' => 'User updated successfully.'];
        } catch (\PDOException $e) {
            error_log('User update failed: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to update user.'];
        }
    }

    /**
     * Delete a user by ID.
     */
    public function deleteUser(int $id): array
    {
        try {
            $affected = $this->db->execute(
                "DELETE FROM users WHERE id = :id",
                ['id' => $id]
            );
            if ($affected > 0) {
                return ['success' => true, 'message' => 'User deleted successfully.'];
            }
            return ['success' => false, 'message' => 'User not found.'];
        } catch (\PDOException $e) {
            error_log('User delete failed: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to delete user.'];
        }
    }

    // ════════════════════════════════════════════════════════
    //  BRUTE-FORCE PROTECTION — USER + IP BASED
    // ════════════════════════════════════════════════════════

    /**
     * Get the real client IP address.
     * Checks proxy headers first, falls back to REMOTE_ADDR.
     */
    private function getClientIp(): string
    {
        $headers = [
            'HTTP_CF_CONNECTING_IP',   // Cloudflare
            'HTTP_X_FORWARDED_FOR',    // Load balancers / proxies
            'HTTP_X_REAL_IP',          // Nginx proxy
            'REMOTE_ADDR',             // Direct connection
        ];

        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                // X-Forwarded-For can be a comma-separated list — take the first
                $ip = trim(explode(',', $_SERVER[$header])[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        return '0.0.0.0'; // Fallback
    }

    /**
     * Record a failed login attempt — stores both user_id (nullable) and IP.
     */
    private function recordFailedAttempt(?int $userId, string $ip): void
    {
        $this->db->execute(
            "INSERT INTO login_attempts (user_id, ip_address, attempted_at)
             VALUES (:user_id, :ip, NOW())",
            ['user_id' => $userId, 'ip' => $ip]
        );
    }

    // ── Account-level lockout ────────────────────────────────

    /**
     * Count recent failed attempts for a specific user account.
     */
    private function getRecentAttemptCount(int $userId): int
    {
        $row = $this->db->fetchOne(
            "SELECT COUNT(*) AS cnt FROM login_attempts
             WHERE user_id = :user_id
               AND attempted_at >= DATE_SUB(NOW(), INTERVAL :mins MINUTE)",
            ['user_id' => $userId, 'mins' => self::LOCKOUT_MINUTES]
        );
        return (int) ($row['cnt'] ?? 0);
    }

    /**
     * Check if a specific user account is locked out.
     *
     * @return array ['locked' => bool, 'minutes_left' => int]
     */
    private function checkLockout(int $userId): array
    {
        $count = $this->getRecentAttemptCount($userId);

        if ($count < self::MAX_ATTEMPTS) {
            return ['locked' => false, 'minutes_left' => 0];
        }

        $oldest = $this->db->fetchOne(
            "SELECT attempted_at FROM login_attempts
             WHERE user_id = :user_id
               AND attempted_at >= DATE_SUB(NOW(), INTERVAL :mins MINUTE)
             ORDER BY attempted_at ASC LIMIT 1",
            ['user_id' => $userId, 'mins' => self::LOCKOUT_MINUTES]
        );

        $unlockAt    = strtotime($oldest['attempted_at']) + (self::LOCKOUT_MINUTES * 60);
        $minutesLeft = (int) ceil(($unlockAt - time()) / 60);

        return ['locked' => true, 'minutes_left' => max(1, $minutesLeft)];
    }

    // ── IP-level lockout ─────────────────────────────────────

    /**
     * Count recent failed attempts from a specific IP address.
     * Catches attackers who try many different accounts from one IP.
     */
    private function getRecentIpAttemptCount(string $ip): int
    {
        $row = $this->db->fetchOne(
            "SELECT COUNT(*) AS cnt FROM login_attempts
             WHERE ip_address = :ip
               AND attempted_at >= DATE_SUB(NOW(), INTERVAL :mins MINUTE)",
            ['ip' => $ip, 'mins' => self::IP_LOCKOUT_MINUTES]
        );
        return (int) ($row['cnt'] ?? 0);
    }

    /**
     * Check if an IP address is locked out.
     *
     * @return array ['locked' => bool, 'minutes_left' => int]
     */
    private function checkIpLockout(string $ip): array
    {
        $count = $this->getRecentIpAttemptCount($ip);

        if ($count < self::IP_MAX_ATTEMPTS) {
            return ['locked' => false, 'minutes_left' => 0];
        }

        $oldest = $this->db->fetchOne(
            "SELECT attempted_at FROM login_attempts
             WHERE ip_address = :ip
               AND attempted_at >= DATE_SUB(NOW(), INTERVAL :mins MINUTE)
             ORDER BY attempted_at ASC LIMIT 1",
            ['ip' => $ip, 'mins' => self::IP_LOCKOUT_MINUTES]
        );

        $unlockAt    = strtotime($oldest['attempted_at']) + (self::IP_LOCKOUT_MINUTES * 60);
        $minutesLeft = (int) ceil(($unlockAt - time()) / 60);

        return ['locked' => true, 'minutes_left' => max(1, $minutesLeft)];
    }

    // ── Cleanup ──────────────────────────────────────────────

    /**
     * Clear failed attempts for both user account and IP on successful login.
     */
    private function clearFailedAttempts(int $userId, string $ip): void
    {
        $this->db->execute(
            "DELETE FROM login_attempts
             WHERE user_id = :user_id OR ip_address = :ip",
            ['user_id' => $userId, 'ip' => $ip]
        );
    }
}
