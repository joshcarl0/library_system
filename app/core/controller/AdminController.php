<?php

require_once __DIR__ . '/../models/Users.php';
require_once __DIR__ . '/../models/Resources.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/ResourceLog.php';
require_once __DIR__ . '/../models/Notification.php';

class AdminController
{
    private Users     $userModel;
    private Resources    $resourceModel;
    private Category     $categoryModel;
    private ResourceLog  $resourceLogModel;
    private Notification $notificationModel;

    public function __construct()
    {
        $this->userModel         = new Users();
        $this->resourceModel     = new Resources();
        $this->categoryModel     = new Category();
        $this->resourceLogModel  = new ResourceLog();
        $this->notificationModel = new Notification();
    }

    // ════════════════════════════════════════════════════════
    //  DASHBOARD
    // ════════════════════════════════════════════════════════

    public function dashboard(): void
    {
        Users::requireRole('admin', '/library_system/index.php?action=login');

        // Fetch Stats
        $stats = [
            'total_resources' => $this->resourceModel->countAll(),
            'total_users'     => $this->userModel->countAll(),
            'active_borrows'  => 0, // Placeholder for now, can be linked to ResourceLog
        ];

        // Fetch Recent Data
        $recentResources = $this->resourceModel->getAll('', '', '', ''); 
        $recentResources = array_slice($recentResources, 0, 5); // Latest 5

        $recentUsers = $this->userModel->getAllUsers();
        $recentUsers = array_slice($recentUsers, 0, 5); // Latest 5

        include __DIR__ . '/../../../views/admin/admin_dashboard.php';
    }

    public function myProfile(): void
    {
        Users::requireRole('admin', '/library_system/index.php?action=login');
        $user = $this->userModel->findById($_SESSION['user_id']);
        require_once __DIR__ . '/../../../views/admin/admin_profile.php';
    }

    // ════════════════════════════════════════════════════════
    //  MANAGE RESOURCES — List + CRUD handler
    // ════════════════════════════════════════════════════════

    public function manageResources(): void
    {
        Users::requireRole('admin', '/library_system/index.php?action=login');

        $message = '';
        $msgType = '';

        // ── Handle POST actions ──────────────────────────────
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postAction = $_POST['form_action'] ?? '';

            if ($postAction === 'add') {
                // Handle Cover Image Upload
                if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                    $uploadResult = $this->handleFileUpload($_FILES['cover_image'], 'covers');
                    if ($uploadResult['success']) {
                        $_POST['cover_image'] = $uploadResult['path'];
                    }
                }
                
                $result  = $this->resourceModel->create($_POST);
                $message = $result['message'];
                $msgType = $result['success'] ? 'success' : 'error';

            } elseif ($postAction === 'edit') {
                $id      = (int) ($_POST['resource_id'] ?? 0);
                
                // Handle Cover Image Upload
                if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                    $uploadResult = $this->handleFileUpload($_FILES['cover_image'], 'covers');
                    if ($uploadResult['success']) {
                        $_POST['cover_image'] = $uploadResult['path'];
                    }
                } else {
                    // Keep old image if not uploading new
                    $existing = $this->resourceModel->getById($id);
                    $_POST['cover_image'] = $existing['cover_image'] ?? '';
                }

                $result  = $this->resourceModel->update($id, $_POST);
                $message = $result['message'];
                $msgType = $result['success'] ? 'success' : 'error';

            } elseif ($postAction === 'delete') {
                $id      = (int) ($_POST['resource_id'] ?? 0);
                $result  = $this->resourceModel->delete($id);
                $message = $result['message'];
                $msgType = $result['success'] ? 'success' : 'error';
            }
        }

        // ── Fetch resources with optional search/filter ──────
        $search   = trim($_GET['search'] ?? '');
        $category = trim($_GET['category'] ?? '');
        $type     = trim($_GET['type'] ?? '');
        $status   = trim($_GET['status'] ?? '');


        $resources  = $this->resourceModel->getAll($search, $category, $type, $status) ?: [];
        $categories = $this->categoryModel->getAll() ?: [];

        require_once __DIR__ . '/../../../views/admin/manage_resources.php';
    }


    // ════════════════════════════════════════════════════════
    //  CATEGORIES
    // ════════════════════════════════════════════════════════

    public function manageCategories(): void
    {
        Users::requireRole('admin', '/library_system/index.php?action=login');

        $message = '';
        $msgType = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['form_action'] ?? '';
            $id     = (int) ($_POST['category_id'] ?? 0);

            if ($action === 'add') {
                $result = $this->categoryModel->create($_POST);
                $message = $result['message'];
                $msgType = $result['success'] ? 'success' : 'error';
            } elseif ($action === 'edit') {
                $result = $this->categoryModel->update($id, $_POST);
                $message = $result['message'];
                $msgType = $result['success'] ? 'success' : 'error';
            } elseif ($action === 'delete') {
                $result = $this->categoryModel->delete($id);
                $message = $result['message'];
                $msgType = $result['success'] ? 'success' : 'error';
            }
        }

        $categories = $this->categoryModel->getAll();
        require_once __DIR__ . '/../../../views/admin/categories.php';
    }

    // ════════════════════════════════════════════════════════
    //  MANAGE USERS
    // ════════════════════════════════════════════════════════

    public function manageUsers(): void
    {
        Users::requireRole('admin', '/library_system/index.php?action=login');

        $message = '';
        $msgType = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['form_action'] ?? '';
            $userId = (int) ($_POST['user_id'] ?? 0);

            if ($action === 'add') {
                $result = $this->userModel->register($_POST);
                $message = $result['message'];
                $msgType = $result['success'] ? 'success' : 'error';
            } elseif ($action === 'edit') {
                $result = $this->userModel->updateUser($userId, $_POST);
                $message = $result['message'];
                $msgType = $result['success'] ? 'success' : 'error';
            } elseif ($action === 'delete') {
                $result = $this->userModel->deleteUser($userId);
                $message = $result['message'];
                $msgType = $result['success'] ? 'success' : 'error';
            }
        }

        $search = $_GET['search'] ?? '';
        $role   = $_GET['role'] ?? '';
        $users  = $this->userModel->getAllUsers($search, $role);

        require_once __DIR__ . '/../../../views/admin/manage_users.php';
    }

    // ════════════════════════════════════════════════════════
    //  FACULTY ACCOUNTS
    // ════════════════════════════════════════════════════════

    public function facultyAccounts(): void
    {
        Users::requireRole('admin', '/library_system/index.php?action=login');

        $message = '';
        $msgType = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['form_action'] ?? '';
            $userId = (int) ($_POST['user_id'] ?? 0);

            if ($action === 'edit') {
                $result = $this->userModel->updateUser($userId, $_POST);
                $message = $result['message'];
                $msgType = $result['success'] ? 'success' : 'error';
            } elseif ($action === 'delete') {
                $result = $this->userModel->deleteUser($userId);
                $message = $result['message'];
                $msgType = $result['success'] ? 'success' : 'error';
            }
        }

        $search = $_GET['search'] ?? '';
        $users  = $this->userModel->getAllUsers($search, 'faculty');

        require_once __DIR__ . '/../../../views/admin/faculty_accounts.php';
    }


    // ════════════════════════════════════════════════════════
    //  BORROW REQUESTS & RETURNS
    // ════════════════════════════════════════════════════════

    public function manageRequests(): void
    {
        Users::requireRole('admin', '/library_system/index.php?action=login');
        
        $pendingRequests = $this->resourceLogModel->getAllLogs('Pending');
        $activeBorrows   = $this->resourceLogModel->getAllLogs('Borrowed');

        require_once __DIR__ . '/../../../views/admin/manage_requests.php';
    }

    public function approveRequest(): void
    {
        Users::requireRole('admin', '/library_system/index.php?action=login');
        
        $logId = (int) ($_REQUEST['id'] ?? 0);
        $log   = $this->resourceLogModel->findById($logId);

        if ($log && $log['action'] === 'Pending') {
            // Check if a custom due date was provided via POST, otherwise default to +7 days
            if (!empty($_POST['due_date'])) {
                $dueDate = date('Y-m-d H:i:s', strtotime($_POST['due_date']));
            } else {
                $dueDate = date('Y-m-d H:i:s', strtotime('+7 days'));
            }
            
            // 1. Update Log to 'Borrowed'
            $this->resourceLogModel->updateLogAction($logId, 'Borrowed', $dueDate);
            
            // Note: Resource stock is already decremented during the 'Pending' request state
            // so we do not need to update stock or status here.
            
            // 3. Notify User (if user exists)
            $userData = $this->userModel->findById($log['user_id']);
            if ($userData) {
                $this->notificationModel->create(
                    $log['user_id'],
                    'Borrow Request Approved',
                    "Your request has been approved. Please return it by " . date('M d, Y', strtotime($dueDate)) . ".",
                    'loan'
                );
            }
        }
        
        header('Location: /library_system/index.php?action=admin_manage_requests&success=Request Approved');
        exit;
    }

    public function rejectRequest(): void
    {
        Users::requireRole('admin', '/library_system/index.php?action=login');
        
        $logId = (int) ($_GET['id'] ?? 0);
        $log   = $this->resourceLogModel->findById($logId);

        if ($log && $log['action'] === 'Pending') {
            // 1. Update Log to 'Rejected'
            $this->resourceLogModel->updateLogAction($logId, 'Rejected');
            
            // 2. Increment Stock and Update Status to 'available'
            $resource = $this->resourceModel->getById($log['resource_id']);
            if ($resource) {
                $newStock = (int)$resource['stock'] + 1;
                $this->resourceModel->updateStockAndStatus($log['resource_id'], $newStock, 'available');
            }
            
            // 3. Notify User (if user exists)
            $userData = $this->userModel->findById($log['user_id']);
            if ($userData) {
                $this->notificationModel->create(
                    $log['user_id'],
                    'Borrow Request Rejected',
                    "Your request to borrow has been rejected by the admin.",
                    'system'
                );
            }
        }
        
        header('Location: /library_system/index.php?action=admin_manage_requests&error=Request Rejected');
        exit;
    }

    public function returnResource(): void
    {
        Users::requireRole('admin', '/library_system/index.php?action=login');
        
        $logId = (int) ($_GET['id'] ?? 0);
        $log   = $this->resourceLogModel->findById($logId);

        if ($log && $log['action'] === 'Borrowed') {
            // 1. Update Log to 'Returned'
            $this->resourceLogModel->updateLogAction($logId, 'Returned', null, date('Y-m-d H:i:s'));
            
            // 2. Increment Stock and Update Status to 'available'
            $resource = $this->resourceModel->getById($log['resource_id']);
            if ($resource) {
                $newStock = (int)$resource['stock'] + 1;
                $this->resourceModel->updateStockAndStatus($log['resource_id'], $newStock, 'available');
            }
            
            // 3. Notify User (if user exists)
            $userData = $this->userModel->findById($log['user_id']);
            if ($userData) {
                $this->notificationModel->create(
                    $log['user_id'],
                    'Resource Returned',
                    "Thank you for returning the resource on time.",
                    'system'
                );
            }
        }
        

        header('Location: /library_system/index.php?action=admin_manage_requests&success=Resource Returned');
        exit;
    }

    public function sendReminders(): void
    {
        Users::requireRole('admin', '/library_system/index.php?action=login');
        
        $db = Database::getInstance();
        require_once __DIR__ . '/../services/EmailService.php';
        $emailService = new EmailService();


        $today = date('Y-m-d');
        $threeDaysLater = date('Y-m-d', strtotime('+3 days'));
        
        $query = "
            SELECT rl.id, rl.due_date, r.title, u.fullname, u.email 
            FROM resource_logs rl
            JOIN resources r ON rl.resource_id = r.id
            JOIN users u ON rl.user_id = u.id
            WHERE rl.action = 'Borrowed' 
              AND rl.return_date IS NULL 
              AND DATE(rl.due_date) BETWEEN :today AND :threeDays
        ";
        $dueSoon = $db->fetchAll($query, [
            'today'     => $today,
            'threeDays' => $threeDaysLater
        ]);


        $sent = 0;
        foreach ($dueSoon as $item) {
            if (!empty($item['email'])) {
                if ($emailService->sendDueReminder($item['email'], $item['fullname'], $item['title'], $item['due_date'])) {
                    $sent++;
                }
            }
        }



        header('Location: /library_system/index.php?action=admin_manage_requests&success=Sent ' . $sent . ' reminders');
        exit;
    }

    public function sendIndividualReminder(): void
    {
        Users::requireRole('admin', '/library_system/index.php?action=login');
        
        $logId = (int) ($_GET['id'] ?? 0);
        $db = Database::getInstance();
        
        $query = "
            SELECT rl.id, rl.due_date, r.title, u.fullname, u.email 
            FROM resource_logs rl
            JOIN resources r ON rl.resource_id = r.id
            JOIN users u ON rl.user_id = u.id
            WHERE rl.id = :id
        ";
        $item = $db->fetchOne($query, ['id' => $logId]);

        if ($item && !empty($item['email'])) {
            require_once __DIR__ . '/../services/EmailService.php';
            $emailService = new EmailService();
            
            if ($emailService->sendDueReminder($item['email'], $item['fullname'], $item['title'], $item['due_date'])) {
                header('Location: /library_system/index.php?action=admin_manage_requests&success=Manual Reminder Sent');
                exit;
            }
        }

        header('Location: /library_system/index.php?action=admin_manage_requests&error=Failed to send reminder');
        exit;
    }

    private function handleFileUpload(array $file, string $subDir): array
    {
        $targetDir = __DIR__ . '/../../../assets/uploads/' . $subDir . '/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = time() . '_' . basename($file['name']);
        $targetPath = $targetDir . $fileName;
        $dbPath = 'assets/uploads/' . $subDir . '/' . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['success' => true, 'path' => $dbPath];
        }

        return ['success' => false, 'message' => 'Failed to move uploaded file.'];
    }
}
