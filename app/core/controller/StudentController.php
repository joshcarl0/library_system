<?php

require_once __DIR__ . '/../models/Users.php';
require_once __DIR__ . '/../models/Resources.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/ResourceLog.php';
require_once __DIR__ . '/../models/Notification.php';

class StudentController
{
    private Resources $resourceModel;
    private Category $categoryModel;
    private ResourceLog $resourceLogModel;
    private Notification $notificationModel;

    public function __construct()
    {
        $this->resourceModel = new Resources();
        $this->categoryModel = new Category();
        $this->resourceLogModel = new ResourceLog();
        $this->notificationModel = new Notification();
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
        
        $search   = $_GET['query'] ?? '';
        $category = $_GET['category'] ?? '';
        $type     = $_GET['type'] ?? '';
        
        $categories = $this->categoryModel->getAll();
        $resources  = $this->resourceModel->getAll($search, $category, $type, '');
        
        require_once __DIR__ . '/../../../views/student/search_resources.php';
    }

    // ════════════════════════════════════════════════════════
    //  BORROWED ITEMS
    // ════════════════════════════════════════════════════════

    public function borrowedItems(): void
    {
        Users::requireRole('student', '/library_system/index.php?action=login');
        $borrowed = $this->resourceLogModel->getStudentBorrowedItems($_SESSION['user_id']);
        require_once __DIR__ . '/../../../views/student/borrowed_items.php';
    }

    // ════════════════════════════════════════════════════════
    //  MY PROFILE
    // ════════════════════════════════════════════════════════

    public function myProfile(): void
    {
        Users::requireRole('student', '/library_system/index.php?action=login');
        $userModel = new Users();
        $user = $userModel->findById($_SESSION['user_id']);
        require_once __DIR__ . '/../../../views/student/my_profile.php';
    }

    public function notifications(): void
    {
        Users::requireRole('student', '/library_system/index.php?action=login');
        
        $filter = $_GET['filter'] ?? 'all';
        $notifications = $this->notificationModel->getByUser($_SESSION['user_id'], $filter);
        
        // Optional: Mark all as read when visiting the notifications page
        // $this->notificationModel->markAllAsRead($_SESSION['user_id']);
        
        require_once __DIR__ . '/../../../views/student/notifications.php';
    }

    /**
     * API Endpoint for real-time notification polling
     */
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

        $userId = $_SESSION['user_id'];
        $unreadCount = $this->notificationModel->getUnreadCount($userId);
        $recentNotifs = $this->notificationModel->getByUser($userId, 'all');
        $recentNotifs = array_slice($recentNotifs, 0, 5);

        header('Content-Type: application/json');
        echo json_encode([
            'unreadCount' => $unreadCount,
            'notifications' => $recentNotifs
        ]);
        exit;
    }
}
