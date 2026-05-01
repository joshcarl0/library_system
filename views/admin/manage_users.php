<?php
/**
 * @var string $message
 * @var string $msgType
 * @var array $users
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users – LRIS | Olivarez College</title>
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
    <link rel="stylesheet" href="/library_system/assets/images/css/manage_users.css">
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
            <div class="section-title mb-0">System Users</div>
        </div>

        <!-- Filters & Search -->
        <div class="content-card mb-4 p-3">
            <form action="/library_system/index.php" method="GET" class="row g-2">
                <input type="hidden" name="action" value="admin_manage_users">
                
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search by name, ID, or email..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </div>
                </div>

                <div class="col-md-3">
                    <select name="role" class="form-select">
                        <option value="">All Roles</option>
                        <option value="student" <?= ($_GET['role'] ?? '') === 'student' ? 'selected' : '' ?>>Students</option>
                        <option value="faculty" <?= ($_GET['role'] ?? '') === 'faculty' ? 'selected' : '' ?>>Faculty</option>
                        <option value="admin" <?= ($_GET['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admins</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-oc-primary w-100">Filter</button>
                </div>
                
                <div class="col-md-2">
                    <a href="/library_system/index.php?action=admin_manage_users" class="btn btn-outline-secondary w-100">Clear</a>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="content-card p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">User Details</th>
                            <th class="py-3">ID Number</th>
                            <th class="py-3">Role</th>
                            <th class="py-3">Registered Date</th>
                            <th class="py-3 text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">No users found matching your criteria.</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="user-name-cell">
                                    <div class="user-avatar">
                                        <?= strtoupper(substr($u['fullname'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <span class="user-fullname"><?= htmlspecialchars($u['fullname'], ENT_QUOTES, 'UTF-8') ?></span>
                                        <span class="user-email"><?= htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8') ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="id-badge"><?= htmlspecialchars($u['student_id'], ENT_QUOTES, 'UTF-8') ?></span>
                            </td>
                            <td>
                                <span class="user-badge badge-<?= strtolower($u['role']) ?>">
                                    <?= htmlspecialchars($u['role'], ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </td>
                            <td class="text-muted small">
                                <?= date('M d, Y', strtotime($u['created_at'])) ?>
                            </td>
                            <td class="text-end pe-4">
                                <button class="action-btn edit" onclick="openEditUserModal(<?= htmlspecialchars(json_encode($u)) ?>)">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                <button class="action-btn delete" onclick="openDeleteUserModal(<?= $u['id'] ?>, '<?= addslashes($u['fullname']) ?>')">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header modal-header-oc">
                <h5 class="modal-title fw-700">Update User Account</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/library_system/index.php?action=admin_manage_users" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" name="form_action" value="edit">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    
                    <div class="mb-3">
                        <label class="modal-label">Full Name</label>
                        <input type="text" name="fullname" id="edit_fullname" class="modal-input" required>
                    </div>

                    <div class="mb-3">
                        <label class="modal-label">Email Address</label>
                        <input type="email" name="email" id="edit_email" class="modal-input" required>
                    </div>

                    <div class="mb-3">
                        <label class="modal-label">User Role</label>
                        <select name="role" id="edit_role" class="modal-input" style="appearance: auto;">
                            <option value="student">Student</option>
                            <option value="faculty">Faculty</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer modal-footer-oc bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-oc-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="mb-3 text-danger">
                    <i class="bi bi-person-x-fill" style="font-size: 3.5rem;"></i>
                </div>
                <h5 class="fw-800 mb-2">Delete User?</h5>
                <p class="text-muted">Are you sure you want to delete <strong id="delete_user_display_name"></strong>? This action cannot be undone.</p>
            </div>
            <form action="/library_system/index.php?action=admin_manage_users" method="POST">
                <input type="hidden" name="form_action" value="delete">
                <input type="hidden" name="user_id" id="delete_user_id">
                <div class="modal-footer border-0 pt-0 pb-4 d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger px-4" style="border-radius: 9px; font-weight: 600;">Yes, Delete</button>
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

    function openEditUserModal(user) {
        document.getElementById('edit_user_id').value = user.id;
        document.getElementById('edit_fullname').value = user.fullname;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_role').value = user.role;
        new bootstrap.Modal(document.getElementById('editUserModal')).show();
    }

    function openDeleteUserModal(id, name) {
        document.getElementById('delete_user_id').value = id;
        document.getElementById('delete_user_display_name').textContent = name;
        new bootstrap.Modal(document.getElementById('deleteUserModal')).show();
    }

    // Auto-dismiss alert
    const alert = document.querySelector('.alert-oc');
    if (alert) setTimeout(() => alert.style.display = 'none', 4000);
</script>
</body>
</html>
