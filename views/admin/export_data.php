<?php
/**
 * Export Data View
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Data – LRIS | Olivarez College</title>
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
    <link rel="stylesheet" href="/library_system/assets/images/css/export_data.css">
</head>
<body>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<?php include __DIR__ . '/adminleft_sidebar.php'; ?>

<div class="main-wrapper">

    <?php include __DIR__ . '/admin_topbar.php'; ?>

    <!-- PAGE CONTENT -->
    <main class="page-content">

        <div class="section-title mb-2">Export System Reports</div>
        <p class="text-muted mb-4">Generate and download comprehensive data reports in CSV format for offline use or analysis.</p>

        <div class="export-info-box">
            <i class="bi bi-info-circle-fill fs-5"></i>
            <div>All reports are generated in <strong>UTF-8 CSV format</strong>, which is compatible with Microsoft Excel, Google Sheets, and other data tools.</div>
        </div>

        <div class="export-grid">
            
            <!-- Resources Export -->
            <div class="export-card">
                <div class="export-icon">
                    <i class="bi bi-journal-text"></i>
                </div>
                <h3>Library Resources</h3>
                <p>Full list of books, journals, theses, and other digital/physical materials currently in the system.</p>
                <form action="/library_system/index.php?action=admin_export_data" method="POST">
                    <input type="hidden" name="export_type" value="resources">
                    <button type="submit" class="btn-export">
                        <i class="bi bi-file-earmark-spreadsheet me-2"></i> Download CSV
                    </button>
                </form>
            </div>

            <!-- Users Export -->
            <div class="export-card">
                <div class="export-icon">
                    <i class="bi bi-people"></i>
                </div>
                <h3>System Users</h3>
                <p>Complete directory of students, faculty members, and administrators registered in the LRIS portal.</p>
                <form action="/library_system/index.php?action=admin_export_data" method="POST">
                    <input type="hidden" name="export_type" value="users">
                    <button type="submit" class="btn-export">
                        <i class="bi bi-file-earmark-spreadsheet me-2"></i> Download CSV
                    </button>
                </form>
            </div>

            <!-- Categories Export -->
            <div class="export-card">
                <div class="export-icon">
                    <i class="bi bi-tags"></i>
                </div>
                <h3>Resource Categories</h3>
                <p>List of all academic departments and resource classifications used to organize the library library.</p>
                <form action="/library_system/index.php?action=admin_export_data" method="POST">
                    <input type="hidden" name="export_type" value="categories">
                    <button type="submit" class="btn-export">
                        <i class="bi bi-file-earmark-spreadsheet me-2"></i> Download CSV
                    </button>
                </form>
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
