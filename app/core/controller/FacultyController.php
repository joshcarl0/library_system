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
            'my_uploads'      => 0, // Faculty-specific upload count (requires uploaded_by field)
            'total_resources' => $this->resourceModel->countAll(),
            'available'       => $this->resourceModel->countByStatus('available'),
            'total_students'  => $this->userModel->countByRole('student'),
        ];

        require_once __DIR__ . '/../../../views/faculty/faculty_dashboard.php';
    }

    // ════════════════════════════════════════════════════════
    //  UPLOAD MATERIALS
    // ════════════════════════════════════════════════════════

    public function uploadMaterials(): void
    {
        Users::requireRole('faculty', '/library_system/index.php?action=login');

        $message = '';
        $msgType = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $uploadDir = __DIR__ . '/../../../assets/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName       = basename($_FILES['material_file']['name']);
            $targetFilePath = $uploadDir . $fileName;
            $fileType       = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
            $allowTypes     = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'zip', 'txt'];

            if (in_array($fileType, $allowTypes)) {
                if (move_uploaded_file($_FILES['material_file']['tmp_name'], $targetFilePath)) {
                    $data              = $_POST;
                    $data['file_path'] = '/library_system/assets/uploads/' . $fileName;
                    $data['uploaded_by'] = $_SESSION['user_id'];
                    $result  = $this->resourceModel->create($data);
                    $message = $result['success'] ? 'File uploaded and resource added successfully.' : $result['message'];
                    $msgType = $result['success'] ? 'success' : 'error';
                } else {
                    $message = 'Sorry, there was an error uploading your file.';
                    $msgType = 'error';
                }
            } else {
                $message = 'Sorry, only PDF, DOC, PPT, & ZIP files are allowed.';
                $msgType = 'error';
            }
        }

        $categories = $this->categoryModel->getAll();
        $subjects   = $this->subjectModel->getAll();

        require_once __DIR__ . '/../../../views/faculty/upload_materials.php';
    }

    // ════════════════════════════════════════════════════════
    //  MY UPLOADS
    // ════════════════════════════════════════════════════════

    public function myUploads(): void
    {
        Users::requireRole('faculty', '/library_system/index.php?action=login');

        $userId = $_SESSION['user_id'];
        $myUploads = $this->resourceModel->getByUploader($userId);

        require_once __DIR__ . '/../../../views/faculty/my_uploads.php';
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
