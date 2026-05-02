<?php
/**
 * Faculty Dashboard View
 * @var array $resources
 * @var array $stats
 * @var array $recentUploads
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard – LRIS | Olivarez College</title>
    <meta name="description" content="Faculty dashboard for the Olivarez College Learning Resource Information System.">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Faculty Styles -->
    <link rel="stylesheet" href="/library_system/assets/images/css/faculty/faculty_sidebar.css">
    <link rel="stylesheet" href="/library_system/assets/images/css/faculty/faculty_dashboard.css">
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<?php include __DIR__ . '/faculty_leftsidebar.php'; ?>

<div class="main-wrapper">

    <?php include __DIR__ . '/faculty_topbar.php'; ?>

    <main class="page-content">

        <!-- ── Welcome Banner ── -->
        <div class="welcome-banner-faculty mb-4">
            <div class="banner-content-faculty">
                <div class="banner-text-faculty">
                    <h1>Good Day, <?= htmlspecialchars(explode(' ', $_SESSION['fullname'] ?? 'Faculty')[0]) ?>! 👋</h1>
                    <p>Manage and share your learning materials with students at Olivarez College.</p>
                    <div class="banner-actions">
                        <a href="/library_system/index.php?action=faculty_upload" class="btn-faculty-gold">
                            <i class="bi bi-cloud-upload-fill"></i> Upload New Material
                        </a>
                        <a href="/library_system/index.php?action=faculty_my_uploads" class="btn-faculty-outline">
                            <i class="bi bi-journal-text"></i> View My Uploads
                        </a>
                    </div>
                </div>
                <img src="/library_system/assets/images/olivarez_logo.png" alt="OC" class="banner-logo-faculty d-none d-lg-block">
            </div>
        </div>

        <!-- ── Stat Cards ── -->
        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card-faculty">
                    <div class="scf-icon bg-success-subtle text-success">
                        <i class="bi bi-cloud-check-fill"></i>
                    </div>
                    <div class="scf-info">
                        <span class="scf-label">My Uploads</span>
                        <h3 class="scf-value"><?= $stats['my_uploads'] ?? 0 ?></h3>
                        <span class="scf-trend text-success"><i class="bi bi-file-earmark-plus"></i> Total materials</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card-faculty">
                    <div class="scf-icon bg-primary-subtle text-primary">
                        <i class="bi bi-journal-bookmark-fill"></i>
                    </div>
                    <div class="scf-info">
                        <span class="scf-label">Total Resources</span>
                        <h3 class="scf-value"><?= $stats['total_resources'] ?? 0 ?></h3>
                        <span class="scf-trend text-primary"><i class="bi bi-collection"></i> System-wide</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card-faculty">
                    <div class="scf-icon bg-warning-subtle text-warning">
                        <i class="bi bi-box-arrow-in-down"></i>
                    </div>
                    <div class="scf-info">
                        <span class="scf-label">Available</span>
                        <h3 class="scf-value"><?= $stats['available'] ?? 0 ?></h3>
                        <span class="scf-trend text-warning"><i class="bi bi-check-circle"></i> Ready to borrow</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="stat-card-faculty">
                    <div class="scf-icon bg-danger-subtle text-danger">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="scf-info">
                        <span class="scf-label">Active Students</span>
                        <h3 class="scf-value"><?= $stats['total_students'] ?? 0 ?></h3>
                        <span class="scf-trend text-danger"><i class="bi bi-person-check"></i> Registered</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Quick Actions ── -->
        <div class="section-title">Quick Actions</div>
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <a href="/library_system/index.php?action=faculty_upload" class="quick-action-faculty">
                    <div class="qaf-icon"><i class="bi bi-cloud-upload-fill"></i></div>
                    <div class="qaf-text">
                        <span class="qaf-title">Upload Material</span>
                        <span class="qaf-sub">Add a new resource</span>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="/library_system/index.php?action=faculty_my_uploads" class="quick-action-faculty">
                    <div class="qaf-icon"><i class="bi bi-journal-text"></i></div>
                    <div class="qaf-text">
                        <span class="qaf-title">My Uploads</span>
                        <span class="qaf-sub">Manage your files</span>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="/library_system/index.php?action=faculty_notifications" class="quick-action-faculty">
                    <div class="qaf-icon"><i class="bi bi-bell-fill"></i></div>
                    <div class="qaf-text">
                        <span class="qaf-title">Notifications</span>
                        <span class="qaf-sub">View your alerts</span>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="/library_system/index.php?action=faculty_profile" class="quick-action-faculty">
                    <div class="qaf-icon"><i class="bi bi-person-fill"></i></div>
                    <div class="qaf-text">
                        <span class="qaf-title">My Profile</span>
                        <span class="qaf-sub">Account settings</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- ── Recent Resources Table ── -->
        <div class="section-title">Recently Added Resources</div>
        <div class="content-card-faculty">
            <div class="cfc-header">
                <h3><i class="bi bi-journal-bookmark me-2" style="color: var(--oc-gold);"></i>Latest Materials in the System</h3>
                <a href="/library_system/index.php?action=faculty_upload" class="btn-view-all-faculty">Upload New</a>
            </div>
            <div class="table-responsive">
                <table class="table-faculty">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Date Added</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($resources)): ?>
                            <?php $count = 1; foreach (array_slice($resources, 0, 8) as $r): ?>
                            <tr>
                                <td style="color:#94a3b8; font-weight:700;"><?= $count++ ?></td>
                                <td>
                                    <div style="font-weight:700; color:#1e293b;"><?= htmlspecialchars($r['title']) ?></div>
                                    <?php if (!empty($r['subject'])): ?>
                                    <div style="font-size:0.75rem; color:#94a3b8;"><?= htmlspecialchars($r['subject']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($r['author']) ?></td>
                                <td><?= htmlspecialchars($r['category_name'] ?? $r['category'] ?? '—') ?></td>
                                <td><span style="font-size:0.8rem; font-weight:600;"><?= htmlspecialchars($r['resource_type'] ?? $r['type'] ?? 'Book') ?></span></td>
                                <td>
                                    <?php
                                        $status = $r['status'] ?? 'available';
                                        $badgeClass = match($status) {
                                            'available'   => 'badge-available',
                                            'borrowed'    => 'badge-borrowed',
                                            default       => 'badge-unavailable',
                                        };
                                    ?>
                                    <span class="badge-type <?= $badgeClass ?>"><?= ucfirst($status) ?></span>
                                </td>
                                <td style="color:#94a3b8;"><?= date('M d, Y', strtotime($r['created_at'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5" style="color:#94a3b8;">
                                    <i class="bi bi-journal-x" style="font-size:2.5rem; display:block; margin-bottom:10px; opacity:0.3;"></i>
                                    No resources available yet.
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
