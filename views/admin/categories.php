<?php
/**
 * @var string $message
 * @var string $msgType
 * @var array $categories
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories – LRIS | Olivarez College</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Shared Admin CSS -->
    <link rel="stylesheet" href="/library_system/assets/images/css/admin_dashboard.css">
    <link rel="stylesheet" href="/library_system/assets/images/css/admin_leftsidebar.css">
    <link rel="stylesheet" href="/library_system/assets/images/css/admin_topbar.css">
    <!-- Page-specific CSS -->
    <link rel="stylesheet" href="/library_system/assets/images/css/manage_resources.css">
    <link rel="stylesheet" href="/library_system/assets/images/css/categories.css">
</head>
<body>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<?php include __DIR__ . '/adminleft_sidebar.php'; ?>

<div class="main-wrapper">

    <?php include __DIR__ . '/admin_topbar.php'; ?>

    <!-- PAGE CONTENT -->
    <main class="page-content">

        <!-- Alert Message -->
        <?php if (!empty($message)): ?>
        <div class="alert-oc <?= $msgType ?>">
            <i class="bi bi-<?= $msgType === 'success' ? 'check-circle-fill' : 'exclamation-circle-fill' ?>"></i>
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php endif; ?>

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="section-title mb-0">Resource Categories</div>
        </div>

        <!-- Add Category Form -->
        <div class="add-category-card">
            <h3 style="font-size: 1rem; font-weight: 700; margin-bottom: 15px; color: var(--oc-green);">
                <i class="bi bi-plus-circle-fill me-2"></i>Add New Category
            </h3>
            <form action="/library_system/index.php?action=admin_manage_categories" method="POST" class="row g-3">
                <input type="hidden" name="form_action" value="add">
                <div class="col-md-6">
                    <input type="text" name="name" class="modal-input" placeholder="Enter category name..." required>
                </div>
                <div class="col-md-3">
                    <select name="resource_type" class="modal-input" style="appearance: auto;">
                        <option value="Digital">Digital</option>
                        <option value="Physical">Physical</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn-oc-primary w-100 h-100">Add Category</button>
                </div>
            </form>
        </div>

        <!-- Categories Grid -->
        <div class="category-grid">
            <?php if (empty($categories)): ?>
            <div class="col-12 text-center p-5 bg-white rounded-4 border">
                <i class="bi bi-tags" style="font-size: 3rem; color: #e2e8f0;"></i>
                <p class="mt-2 text-muted">No categories created yet.</p>
            </div>
            <?php else: ?>
            <?php foreach ($categories as $cat): ?>
            <div class="category-card">
                <div class="category-info">
                    <div class="category-icon">
                        <i class="bi bi-tag-fill"></i>
                    </div>
                    <div>
                        <div class="category-name"><?= htmlspecialchars($cat['category_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                        <small class="text-muted"><?= htmlspecialchars($cat['resource_type'] ?? '', ENT_QUOTES, 'UTF-8') ?></small>
                    </div>
                </div>
                <div class="category-actions">
                    <button class="action-btn edit" onclick="openEditModal(<?= $cat['id'] ?>, '<?= addslashes($cat['category_name'] ?? '') ?>', '<?= $cat['resource_type'] ?>')">
                        <i class="bi bi-pencil-fill"></i>
                    </button>
                    <button class="action-btn delete" onclick="openDeleteModal(<?= $cat['id'] ?>, '<?= addslashes($cat['category_name'] ?? '') ?>')">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </main>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-oc">
                <h5 class="modal-title fw-700">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/library_system/index.php?action=admin_manage_categories" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" name="form_action" value="edit">
                    <input type="hidden" name="category_id" id="edit_cat_id">
                    <div class="mb-3">
                        <label class="modal-label">Category Name</label>
                        <input type="text" name="name" id="edit_cat_name" class="modal-input" required>
                    </div>
                    <div>
                        <label class="modal-label">Resource Type</label>
                        <select name="resource_type" id="edit_cat_type" class="modal-input" style="appearance: auto;">
                            <option value="Digital">Digital</option>
                            <option value="Physical">Physical</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer modal-footer-oc">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-oc-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom:1px solid #f1f5f9; padding:18px 22px;">
                <h5 class="modal-title fw-700" style="color:#dc2626;"><i class="bi bi-exclamation-triangle-fill me-2"></i>Delete Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <p>Are you sure you want to delete category:</p>
                <h4 id="delete_cat_display_name" style="font-weight: 800; color: #1a202c; margin: 15px 0;"></h4>
                <p class="text-danger small"><i class="bi bi-info-circle me-1"></i>This might affect resources under this category.</p>
            </div>
            <form action="/library_system/index.php?action=admin_manage_categories" method="POST">
                <input type="hidden" name="form_action" value="delete">
                <input type="hidden" name="category_id" id="delete_cat_id">
                <div class="modal-footer modal-footer-oc">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" style="border-radius: 9px; font-weight: 600;">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('show');
    }

    function openEditModal(id, name, type) {
        document.getElementById('edit_cat_id').value = id;
        document.getElementById('edit_cat_name').value = name;
        document.getElementById('edit_cat_type').value = type;
        new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
    }

    function openDeleteModal(id, name) {
        document.getElementById('delete_cat_id').value = id;
        document.getElementById('delete_cat_display_name').textContent = name;
        new bootstrap.Modal(document.getElementById('deleteCategoryModal')).show();
    }

    // Auto-dismiss alert
    const alert = document.querySelector('.alert-oc');
    if (alert) setTimeout(() => alert.style.display = 'none', 4000);
</script>
</body>
</html>
