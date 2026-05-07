<?php
/**
 * @var array $stats
 * @var array $recentResources
 * @var array $recentUsers
 */
// Initialize variables if not set to prevent warnings
$stats = $stats ?? ['total_resources' => 0, 'total_users' => 0, 'active_borrows' => 0, 'digital_files' => 0];
$recentResources = $recentResources ?? [];
$recentUsers = $recentUsers ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard – LRIS | Olivarez College</title>
    <meta name="description" content="Admin dashboard for the Olivarez College Learning Resource Information System.">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Admin Dashboard Styles -->
    <link rel="stylesheet" href="/library_system/assets/images/css/admin_dashboard.css">
    <link rel="stylesheet" href="/library_system/assets/images/css/admin_leftsidebar.css">
    <link rel="stylesheet" href="/library_system/assets/images/css/admin_topbar.css">
</head>
<body>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<?php include __DIR__ . '/adminleft_sidebar.php'; ?>

<!-- ══════════ MAIN WRAPPER ══════════ -->
<div class="main-wrapper">

    <?php include __DIR__ . '/admin_topbar.php'; ?>

    <!-- PAGE CONTENT -->
    <main class="page-content">

        <!-- Welcome Banner -->
        <div class="welcome-banner-admin mb-4">
            <div class="banner-content">
                <div class="banner-text">
                    <h2>Welcome back, <?= htmlspecialchars($_SESSION['fullname'] ?? 'Administrator') ?>! 👋</h2>
                    <p>Here's what's happening with the Learning Resource Info System today.</p>
                    <div class="banner-badge">"Educating the Mind, Body and Soul."</div>
                </div>
                <div class="banner-img">
                    <img src="/library_system/assets/images/olivarez_logo.png" alt="OC Logo" class="banner-logo-floating">
                </div>
            </div>
        </div>

        <!-- ── Stat Cards ── -->
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-xl-3">
                <div class="stat-card-new">
                    <div class="scn-icon bg-success-subtle text-success">
                        <i class="bi bi-journal-bookmark-fill"></i>
                    </div>
                    <div class="scn-info">
                        <span class="scn-label">Total Resources</span>
                        <h3 class="scn-value"><?= $stats['total_resources'] ?></h3>
                        <span class="scn-trend text-success"><i class="bi bi-journal-check"></i> Overall resources</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="stat-card-new">
                    <div class="scn-icon bg-warning-subtle text-warning">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="scn-info">
                        <span class="scn-label">Registered Users</span>
                        <h3 class="scn-value"><?= $stats['total_users'] ?></h3>
                        <span class="scn-trend text-warning"><i class="bi bi-person-check"></i> Total accounts</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="stat-card-new">
                    <div class="scn-icon bg-primary-subtle text-primary">
                        <i class="bi bi-box-arrow-in-down"></i>
                    </div>
                    <div class="scn-info">
                        <span class="scn-label">Active Borrows</span>
                        <h3 class="scn-value"><?= $stats['active_borrows'] ?></h3>
                        <span class="scn-trend text-primary"><i class="bi bi-clock-history"></i> Current logs</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="stat-card-new">
                    <div class="scn-icon bg-danger-subtle text-danger">
                        <i class="bi bi-file-earmark-pdf-fill"></i>
                    </div>
                    <div class="scn-info">
                        <span class="scn-label">Digital Files</span>
                        <h3 class="scn-value"><?= $stats['digital_files'] ?></h3>
                        <span class="scn-trend text-danger"><i class="bi bi-file-earmark-pdf"></i> E-Books</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Quick Actions ── -->
        <div class="section-title">Quick Actions</div>
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <a href="/library_system/index.php?action=admin_manage_resources" class="quick-action">
                    <i class="bi bi-plus-circle-fill"></i>
                    <div class="quick-action-text">
                        <span>Add Resource</span>
                        <small>Upload a new material</small>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="#" class="quick-action">
                    <i class="bi bi-person-plus-fill"></i>
                    <div class="quick-action-text">
                        <span>Add User</span>
                        <small>Create a new account</small>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="#" class="quick-action">
                    <i class="bi bi-file-earmark-bar-graph-fill"></i>
                    <div class="quick-action-text">
                        <span>View Reports</span>
                        <small>Usage statistics</small>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="#" class="quick-action">
                    <i class="bi bi-download"></i>
                    <div class="quick-action-text">
                        <span>Export Data</span>
                        <small>Download reports</small>
                    </div>
                </a>
            </div>
        </div>

        <!-- ── Recent Resources Table ── -->
        <div class="section-title">Recent Resources</div>
        <div class="content-card mb-4">
            <div class="content-card-header">
                <h3><i class="bi bi-journal-bookmark me-2" style="color:var(--oc-gold)"></i> Latest Added Resources</h3>
                <a href="/library_system/index.php?action=admin_manage_resources" class="btn-view-all">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table-clean">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Date Added</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recentResources)): ?>
                            <?php foreach ($recentResources as $res): ?>
                                <tr>
                                    <td><div class="fw-600"><?= htmlspecialchars($res['title']) ?></div></td>
                                    <td><?= htmlspecialchars($res['author']) ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($res['category']) ?></span></td>
                                    <td><?= htmlspecialchars($res['type']) ?></td>
                                    <td><span class="status-pill <?= $res['status'] ?>"><?= ucfirst($res['status']) ?></span></td>
                                    <td><?= date('M d, Y', strtotime($res['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5" style="color: var(--text-muted-oc);">
                                    <i class="bi bi-journal-x" style="font-size:2rem; display:block; margin-bottom:8px; opacity:0.4;"></i>
                                    No resources added yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ── Recent Users Table ── -->
        <div class="section-title">Recent Registrations</div>
        <div class="content-card">
            <div class="content-card-header">
                <h3><i class="bi bi-people me-2" style="color:var(--oc-gold)"></i> Newly Registered Users</h3>
                <a href="#" class="btn-view-all">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table-clean">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>ID Number</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recentUsers)): ?>
                            <?php foreach ($recentUsers as $ru): ?>
                                <tr>
                                    <td><div class="fw-600"><?= htmlspecialchars($ru['fullname']) ?></div></td>
                                    <td><?= htmlspecialchars($ru['student_id'] ?: 'N/A') ?></td>
                                    <td><?= htmlspecialchars($ru['email']) ?></td>
                                    <td><span class="badge <?= ($ru['role'] === 'admin') ? 'bg-danger' : (($ru['role'] === 'faculty') ? 'bg-success' : 'bg-primary') ?>"><?= ucfirst($ru['role']) ?></span></td>
                                    <td><?= date('M d, Y', strtotime($ru['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5" style="color: var(--text-muted-oc);">
                                    <i class="bi bi-person-x" style="font-size:2rem; display:block; margin-bottom:8px; opacity:0.4;"></i>
                                    No registered users yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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
