<?php
/**
 * @var array $stats
 * @var array $recentResources
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usage Reports – LRIS | Olivarez College</title>
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
    <link rel="stylesheet" href="/library_system/assets/images/css/usagereports.css">
</head>
<body>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<?php include __DIR__ . '/adminleft_sidebar.php'; ?>

<div class="main-wrapper">

    <?php include __DIR__ . '/admin_topbar.php'; ?>

    <!-- PAGE CONTENT -->
    <main class="page-content">

        <div class="section-title mb-4">System Usage Reports</div>

        <!-- STATS CARDS -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon icon-blue"><i class="bi bi-journal-bookmark-fill"></i></div>
                <div class="stat-info">
                    <h3><?= $stats['total_resources'] ?></h3>
                    <p>Total Resources</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-green"><i class="bi bi-check-circle-fill"></i></div>
                <div class="stat-info">
                    <h3><?= $stats['available_resources'] ?></h3>
                    <p>Available</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-gold"><i class="bi bi-clock-history"></i></div>
                <div class="stat-info">
                    <h3><?= $stats['borrowed_resources'] ?></h3>
                    <p>Borrowed</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-purple"><i class="bi bi-people-fill"></i></div>
                <div class="stat-info">
                    <h3><?= $stats['total_users'] ?></h3>
                    <p>Total Users</p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- User Distribution -->
            <div class="col-lg-5">
                <div class="report-section">
                    <div class="section-header">
                        <h2><i class="bi bi-pie-chart-fill me-2"></i>User Distribution</h2>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1 small fw-600">
                            <span>Students</span>
                            <span><?= $stats['total_students'] ?></span>
                        </div>
                        <div class="progress progress-custom">
                            <div class="progress-bar progress-bar-green" style="width: <?= ($stats['total_users'] > 0) ? ($stats['total_students'] / $stats['total_users'] * 100) : 0 ?>%"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1 small fw-600">
                            <span>Faculty</span>
                            <span><?= $stats['total_faculty'] ?></span>
                        </div>
                        <div class="progress progress-custom">
                            <div class="progress-bar progress-bar-gold" style="width: <?= ($stats['total_users'] > 0) ? ($stats['total_faculty'] / $stats['total_users'] * 100) : 0 ?>%"></div>
                        </div>
                    </div>
                    <div class="text-center mt-3 pt-3 border-top">
                        <div class="small text-muted">Active Categories: <strong><?= $stats['total_categories'] ?></strong></div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity / Resources -->
            <div class="col-lg-7">
                <div class="report-section">
                    <div class="section-header">
                        <h2><i class="bi bi-activity me-2"></i>Recent Resources Added</h2>
                        <a href="/library_system/index.php?action=admin_manage_resources" class="btn btn-sm btn-outline-primary rounded-pill px-3">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr class="text-muted small">
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Category</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $recent = array_slice($recentResources, 0, 5);
                                if (empty($recent)): ?>
                                <tr><td colspan="4" class="text-center py-4">No resources found.</td></tr>
                                <?php else: ?>
                                <?php foreach($recent as $r): ?>
                                <tr>
                                    <td class="fw-600 small"><?= htmlspecialchars($r['title']) ?></td>
                                    <td class="small"><?= htmlspecialchars($u['author'] ?? 'Admin') ?></td>
                                    <td><span class="badge bg-light text-dark fw-500"><?= htmlspecialchars($r['category_name'] ?? $r['category'] ?? 'N/A') ?></span></td>
                                    <td class="text-muted small"><?= date('M d', strtotime($r['created_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
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
