<?php

require_once __DIR__ . '/../models/Users.php';
require_once __DIR__ . '/../models/Resources.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Subject.php';
require_once __DIR__ . '/../models/Notification.php';

class FacultyController
{
    private Resources $resourceModel;
    private Users $userModel;
    private Category $categoryModel;
    private Subject $subjectModel;
    private Notification $notificationModel;

    public function __construct()
    {
        $this->resourceModel = new Resources();
        $this->userModel = new Users();
        $this->categoryModel = new Category();
        $this->subjectModel = new Subject();
        $this->notificationModel = new Notification();
    }

    // ════════════════════════════════════════════════════════
    //  DASHBOARD
    // ════════════════════════════════════════════════════════

    public function dashboard(): void
    {
        Users::requireRole('faculty', '/library_system/index.php?action=login');

        $resources = $this->resourceModel->getAll('', '', '', '');

        $stats = [
            'total_resources' => $this->resourceModel->countAll(),
            'total_books'     => $this->resourceModel->countByType('book'),
            'available'       => $this->resourceModel->countByStatus('available'),
            'total_students'  => $this->userModel->countByRole('student'),
        ];

        require_once __DIR__ . '/../../../views/faculty/faculty_dashboard.php';
    }


    // ════════════════════════════════════════════════════════
    //  MY PROFILE
    // ════════════════════════════════════════════════════════

    public function profile(): void
    {
        Users::requireRole('faculty', '/library_system/index.php?action=login');
        
        $userId = $_SESSION['user_id'];
        $message = '';
        $msgType = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check if it's a password update or profile update
            if (isset($_POST['update_password'])) {
                $currentPass = $_POST['current_password'] ?? '';
                $newPass = $_POST['new_password'] ?? '';
                $confirmPass = $_POST['confirm_password'] ?? '';

                if ($newPass !== $confirmPass) {
                    $message = 'New passwords do not match.';
                    $msgType = 'error';
                } else {
                    $result = $this->userModel->changePassword($userId, $currentPass, $newPass);
                    $message = $result['message'];
                    $msgType = $result['success'] ? 'success' : 'error';
                }
            } elseif (isset($_POST['update_profile'])) {
                // Future expansion: Name/Email updates if allowed
                $message = 'Profile updated successfully.';
                $msgType = 'success';
            }
        }

        $user = $this->userModel->findById($userId);

        require_once __DIR__ . '/../../../views/faculty/my_profile.php';
    }

    // ════════════════════════════════════════════════════════
    //  NOTIFICATIONS
    // ════════════════════════════════════════════════════════

    public function notifications(): void
    {
        Users::requireRole('faculty', '/library_system/index.php?action=login');
        
        $filter = $_GET['filter'] ?? 'all';
        $notifications = $this->notificationModel->getByUser($_SESSION['user_id'], $filter);
        
        // Mark all as read when visiting the notifications page
        $this->notificationModel->markAllAsRead($_SESSION['user_id']);
        
        require_once __DIR__ . '/../../../views/faculty/notification.php';
    }

    // ════════════════════════════════════════════════════════
    //  NOTIFICATIONS (API endpoint for real-time polling)
    // ════════════════════════════════════════════════════════

    public function apiGetNotifications(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $userId      = $_SESSION['user_id'];
        $unreadCount = $this->notificationModel->getUnreadCount($userId);
        $recentNotifs = array_slice($this->notificationModel->getByUser($userId, 'all'), 0, 5);

        header('Content-Type: application/json');
        echo json_encode([
            'unreadCount'   => $unreadCount,
            'notifications' => $recentNotifs,
        ]);
        exit;
    }
}
