<?php
/**
 * Faculty — My Profile View
 * @var array $user
 * @var string $message
 * @var string $msgType
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile – LRIS | Olivarez College</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Core Styles -->
    <link rel="stylesheet" href="/library_system/assets/images/css/faculty/faculty_sidebar.css">
    <link rel="stylesheet" href="/library_system/assets/images/css/faculty/faculty_dashboard.css">
    <link rel="stylesheet" href="/library_system/assets/images/css/faculty/my_profile.css">
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<?php include __DIR__ . '/faculty_leftsidebar.php'; ?>

<div class="main-wrapper">
    <?php include __DIR__ . '/faculty_topbar.php'; ?>

    <main class="page-content">
        
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?= $msgType === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-<?= $msgType === 'success' ? 'check-circle-fill' : 'exclamation-circle-fill' ?> me-2"></i>
                <?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="section-title mb-4">Account Settings</div>

        <div class="row g-4">
            <!-- Profile Info Card -->
            <div class="col-lg-4">
                <div class="profile-card text-center">
                    <div class="profile-avatar-large">
                        <?= strtoupper(substr($user['fullname'] ?? 'F', 0, 1)) ?>
                    </div>
                    <h4 class="profile-name"><?= htmlspecialchars($user['fullname'] ?? '') ?></h4>
                    <p class="profile-role">Faculty Member</p>
                    
                    <div class="profile-details mt-4 text-start">
                        <div class="pd-item">
                            <span class="pd-label">Email Address</span>
                            <div class="pd-value"><i class="bi bi-envelope me-2 text-muted"></i> <?= htmlspecialchars($user['email'] ?? '') ?></div>
                        </div>
                        <div class="pd-item">
                            <span class="pd-label">Username / Employee ID</span>
                            <div class="pd-value"><i class="bi bi-person-badge me-2 text-muted"></i> <?= htmlspecialchars($user['username'] ?? '') ?></div>
                        </div>
                        <div class="pd-item">
                            <span class="pd-label">Account Status</span>
                            <div class="pd-value"><span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill"><i class="bi bi-check-circle-fill me-1"></i> Active</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security / Password Update -->
            <div class="col-lg-8">
                <div class="settings-card">
                    <div class="settings-header">
                        <h5><i class="bi bi-shield-lock-fill me-2 text-warning"></i> Security & Password</h5>
                        <p>Ensure your account is using a long, random password to stay secure.</p>
                    </div>
                    <div class="settings-body">
                        <form action="/library_system/index.php?action=faculty_profile" method="POST">
                            <input type="hidden" name="update_password" value="1">
                            
                            <div class="mb-4">
                                <label class="form-label fw-600 text-secondary small">Current Password</label>
                                <input type="password" name="current_password" class="form-control form-control-lg bg-light" required>
                            </div>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-600 text-secondary small">New Password</label>
                                    <input type="password" name="new_password" class="form-control form-control-lg bg-light" required minlength="8">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-600 text-secondary small">Confirm New Password</label>
                                    <input type="password" name="confirm_password" class="form-control form-control-lg bg-light" required minlength="8">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-dark btn-lg px-5 shadow-sm rounded-pill">
                                    Update Password
                                </button>
                            </div>
                        </form>
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
