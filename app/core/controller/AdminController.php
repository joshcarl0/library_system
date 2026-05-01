<?php

require_once __DIR__ . '/../models/Users.php';
require_once __DIR__ . '/../models/Resources.php';
require_once __DIR__ . '/../models/Category.php';

class AdminController
{
    private Users     $userModel;
    private Resources $resourceModel;
    private Category  $categoryModel;

    public function __construct()
    {
        $this->userModel     = new Users();
        $this->resourceModel = new Resources();
        $this->categoryModel = new Category();
    }

    // ════════════════════════════════════════════════════════
    //  DASHBOARD
    // ════════════════════════════════════════════════════════

    public function dashboard(): void
    {
        Users::requireRole('admin', '/library_system/index.php?action=login');
        require_once __DIR__ . '/../../../views/admin/admin_dashboard.php';
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
                $result  = $this->resourceModel->create($_POST);
                $message = $result['message'];
                $msgType = $result['success'] ? 'success' : 'error';

            } elseif ($postAction === 'edit') {
                $id      = (int) ($_POST['resource_id'] ?? 0);
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
        $categories = $this->resourceModel->getCategories() ?: [];

        require_once __DIR__ . '/../../../views/admin/manage_resources.php';
    }

    // ════════════════════════════════════════════════════════
    //  UPLOAD MATERIALS
    // ════════════════════════════════════════════════════════

    public function uploadMaterials(): void
    {
        Users::requireRole('admin', '/library_system/index.php?action=login');

        $message = '';
        $msgType = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle File Upload
            $uploadDir = __DIR__ . '/../../../assets/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = basename($_FILES['material_file']['name']);
            $targetFilePath = $uploadDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            // Allow certain file formats
            $allowTypes = array('pdf', 'doc', 'docx', 'ppt', 'pptx', 'zip', 'txt');
            
            if (in_array(strtolower($fileType), $allowTypes)) {
                // Upload file to server
                if (move_uploaded_file($_FILES['material_file']['tmp_name'], $targetFilePath)) {
                    // Prepare data for database
                    $data = $_POST;
                    $data['file_path'] = '/library_system/assets/uploads/' . $fileName;
                    
                    $result = $this->resourceModel->create($data);
                    $message = $result['success'] ? "File uploaded and resource added successfully." : $result['message'];
                    $msgType = $result['success'] ? 'success' : 'error';
                } else {
                    $message = "Sorry, there was an error uploading your file.";
                    $msgType = "error";
                }
            } else {
                $message = "Sorry, only PDF, DOC, PPT, & ZIP files are allowed.";
                $msgType = "error";
            }
        }

        require_once __DIR__ . '/../../../views/admin/upload_materials.php';
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
    //  USAGE REPORTS
    // ════════════════════════════════════════════════════════

    public function usageReports(): void
    {
        Users::requireRole('admin', '/library_system/index.php?action=login');

        // Resources Stats
        $stats = [
            'total_resources'     => $this->resourceModel->countAll(),
            'available_resources' => $this->resourceModel->countByStatus('available'),
            'borrowed_resources'  => $this->resourceModel->countByStatus('borrowed'),
            'total_users'         => $this->userModel->countAll(),
            'total_students'      => $this->userModel->countByRole('student'),
            'total_faculty'       => $this->userModel->countByRole('faculty'),
            'total_categories'    => $this->categoryModel->countAll(),
        ];

        // Get some activity or details
        $recentResources = $this->resourceModel->getAll('', '', '', ''); // All, default limit? 
        // We can slice it in the view
        
        require_once __DIR__ . '/../../../views/admin/usage_reports.php';
    }

    // ════════════════════════════════════════════════════════
    //  EXPORT DATA
    // ════════════════════════════════════════════════════════

    public function exportData(): void
    {
        Users::requireRole('admin', '/library_system/index.php?action=login');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['export_type'] ?? '';

            if ($type === 'resources') {
                $data = $this->resourceModel->getAll();
                $filename = "lris_resources_" . date('Y-m-d') . ".csv";
                $header = ['ID', 'Title', 'Author', 'Category', 'Subject', 'Type', 'Status', 'Date Added'];
                $this->downloadCSV($filename, $header, $data, function($row) {
                    return [
                        $row['id'], $row['title'], $row['author'], 
                        $row['category_name'] ?? $row['category'], 
                        $row['subject'], $row['resource_type'], 
                        $row['status'], $row['created_at']
                    ];
                });
            } elseif ($type === 'users') {
                $data = $this->userModel->getAllUsers();
                $filename = "lris_users_" . date('Y-m-d') . ".csv";
                $header = ['ID', 'Student ID', 'Full Name', 'Email', 'Role', 'Date Registered'];
                $this->downloadCSV($filename, $header, $data, function($row) {
                    return [$row['id'], $row['student_id'], $row['fullname'], $row['email'], $row['role'], $row['created_at']];
                });
            } elseif ($type === 'categories') {
                $data = $this->categoryModel->getAll();
                $filename = "lris_categories_" . date('Y-m-d') . ".csv";
                $header = ['ID', 'Category Name', 'Resource Type'];
                $this->downloadCSV($filename, $header, $data, function($row) {
                    return [$row['id'], $row['category_name'], $row['resource_type']];
                });
            }
        }

        require_once __DIR__ . '/../../../views/admin/export_data.php';
    }

    /**
     * Helper to stream CSV to browser
     */
    private function downloadCSV(string $filename, array $header, array $data, callable $mapper): void
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        
        $output = fopen('php://output', 'w');
        
        // Add BOM for Excel compatibility (UTF-8)
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, $header);
        
        foreach ($data as $row) {
            fputcsv($output, $mapper($row));
        }
        
        fclose($output);
        exit;
    }
}
