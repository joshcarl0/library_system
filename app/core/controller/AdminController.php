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
            $name   = $_POST['category_name'] ?? '';
            $id     = (int) ($_POST['category_id'] ?? 0);

            if ($action === 'add') {
                $result = $this->categoryModel->create($name);
                $message = $result['message'];
                $msgType = $result['success'] ? 'success' : 'error';
            } elseif ($action === 'edit') {
                $result = $this->categoryModel->update($id, $name);
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
}
