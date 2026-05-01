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
        <div class="p-4 mb-4 rounded-3" style="background: linear-gradient(135deg, var(--oc-green) 0%, #0d3612 100%); color:#fff; position:relative; overflow:hidden;">
            <div style="position:absolute; top:-20px; right:-20px; width:180px; height:180px; border-radius:50%; background:rgba(212,175,55,0.1);"></div>
            <div style="position:absolute; bottom:-40px; right:80px; width:120px; height:120px; border-radius:50%; background:rgba(212,175,55,0.07);"></div>
            <h2 style="font-weight:800; font-size:1.3rem;">
                Welcome back, <?php echo htmlspecialchars($_SESSION['fullname'] ?? 'Administrator', ENT_QUOTES, 'UTF-8'); ?>! 👋
            </h2>
            <p style="opacity:0.8; font-size:0.88rem; margin-top:6px;">
                Here's what's happening with the Learning Resource Info System today.
            </p>
            <span style="display:inline-block; margin-top:12px; background:var(--oc-gold); color:var(--oc-green); padding:6px 16px; border-radius:20px; font-size:0.80rem; font-weight:700;">
                "Educating the Mind, Body and Soul."
            </span>
        </div>

        <!-- ── Stat Cards ── -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon green"><i class="bi bi-journal-bookmark-fill"></i></div>
                    <div class="stat-info">
                        <div class="stat-value">0</div>
                        <div class="stat-label">Total Resources</div>
                        <div class="stat-change up"><i class="bi bi-arrow-up-short"></i> 0 this month</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon gold"><i class="bi bi-people-fill"></i></div>
                    <div class="stat-info">
                        <div class="stat-value">0</div>
                        <div class="stat-label">Registered Users</div>
                        <div class="stat-change up"><i class="bi bi-arrow-up-short"></i> 0 new today</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="bi bi-box-arrow-in-down"></i></div>
                    <div class="stat-info">
                        <div class="stat-value">0</div>
                        <div class="stat-label">Active Borrows</div>
                        <div class="stat-change up"><i class="bi bi-arrow-up-short"></i> 0 today</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="stat-card">
                    <div class="stat-icon orange"><i class="bi bi-file-earmark-pdf-fill"></i></div>
                    <div class="stat-info">
                        <div class="stat-value">0</div>
                        <div class="stat-label">Digital Files</div>
                        <div class="stat-change up"><i class="bi bi-arrow-up-short"></i> 0 uploaded</div>
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
                        <tr>
                            <td colspan="6" class="text-center py-5" style="color: var(--text-muted-oc);">
                                <i class="bi bi-journal-x" style="font-size:2rem; display:block; margin-bottom:8px; opacity:0.4;"></i>
                                No resources added yet.
                            </td>
                        </tr>
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
                        <tr>
                            <td colspan="5" class="text-center py-5" style="color: var(--text-muted-oc);">
                                <i class="bi bi-person-x" style="font-size:2rem; display:block; margin-bottom:8px; opacity:0.4;"></i>
                                No registered users yet.
                            </td>
                        </tr>
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
