<?php
/**
 * Admin Profile View
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile – LRIS Admin | Olivarez College</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Admin Styles -->
    <link rel="stylesheet" href="/library_system/assets/images/css/admin_dashboard.css">
    <link rel="stylesheet" href="/library_system/assets/images/css/admin_leftsidebar.css">
    <link rel="stylesheet" href="/library_system/assets/images/css/admin_topbar.css">
    <!-- Reusing Student Profile CSS for consistency -->
    <link rel="stylesheet" href="/library_system/assets/images/css/student/my_profile.css">
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<?php include __DIR__ . '/adminleft_sidebar.php'; ?>

<div class="main-wrapper">

    <?php include __DIR__ . '/admin_topbar.php'; ?>

    <main class="page-content">
        <div class="section-title mb-4">My Profile Settings</div>

        <div class="profile-container">
            <!-- Profile Header -->
            <div class="profile-header-card mb-4">
                <div class="ph-avatar">
                    <?= strtoupper(substr($user['fullname'] ?? 'A', 0, 1)) ?>
                </div>
                <div class="ph-info">
                    <h2 class="ph-name"><?= htmlspecialchars($user['fullname'] ?? 'Administrator') ?></h2>
                    <span class="badge bg-success-subtle text-success rounded-pill px-3">System Administrator</span>
                </div>
            </div>

            <div class="row g-4">
                <!-- Account Info -->
                <div class="col-lg-7">
                    <div class="profile-card">
                        <div class="card-header-oc">
                            <i class="bi bi-person-vcard text-primary me-2"></i>
                            Account Information
                        </div>
                        <div class="card-body-oc">
                            <div class="info-grid">
                                <div class="info-item">
                                    <label>Full Name</label>
                                    <p><?= htmlspecialchars($user['fullname'] ?? 'N/A') ?></p>
                                </div>
                                <div class="info-item">
                                    <label>ID Number</label>
                                    <p><?= htmlspecialchars($user['student_id'] ?? 'N/A') ?></p>
                                </div>
                                <div class="info-item">
                                    <label>Email Address</label>
                                    <p><?= htmlspecialchars($user['email'] ?? 'N/A') ?></p>
                                </div>
                                <div class="info-item">
                                    <label>Account Role</label>
                                    <p class="text-capitalize"><?= htmlspecialchars($user['role'] ?? 'admin') ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security / Password -->
                <div class="col-lg-5">
                    <div class="profile-card">
                        <div class="card-header-oc">
                            <i class="bi bi-shield-lock text-danger me-2"></i>
                            Security Settings
                        </div>
                        <div class="card-body-oc">
                            <form action="/library_system/index.php?action=update_password" method="POST">
                                <div class="mb-3">
                                    <label class="form-label small fw-700">Current Password</label>
                                    <input type="password" name="current_password" class="form-control-oc" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-700">New Password</label>
                                    <input type="password" name="new_password" class="form-control-oc" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-700">Confirm New Password</label>
                                    <input type="password" name="confirm_password" class="form-control-oc" required>
                                </div>
                                <button type="submit" class="btn-update-profile w-100">Update Security</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('show');
    }
</script>
</body>
</html>
