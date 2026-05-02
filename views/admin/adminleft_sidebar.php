<!-- ══════════ SIDEBAR ══════════ -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="logo-circle">
            <img src="/library_system/assets/images/olivarez_logo.png" alt="OC Logo">
        </div>
        <div class="sidebar-brand-text">
            <h2>Olivarez College</h2>
            <span>LRIS Admin Panel</span>
        </div>
    </div>

    <?php $current_action = $_GET['action'] ?? 'admin_dashboard'; ?>
    <nav class="sidebar-menu">
        <div class="menu-label">Main</div>
        <a href="/library_system/index.php?action=admin_dashboard" class="menu-item <?= ($current_action === 'admin_dashboard') ? 'active' : '' ?>" id="nav-dashboard">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="/library_system/index.php?action=admin_profile" class="menu-item <?= ($current_action === 'admin_profile') ? 'active' : '' ?>" id="nav-profile">
            <i class="bi bi-person-badge"></i> My Profile
        </a>

        <div class="menu-label">Resources</div>
        <a href="/library_system/index.php?action=admin_manage_resources" class="menu-item <?= ($current_action === 'admin_manage_resources') ? 'active' : '' ?>" id="nav-resources">
            <i class="bi bi-journal-bookmark-fill"></i> Manage Resources
        </a>
        <a href="/library_system/index.php?action=admin_upload_materials" class="menu-item <?= ($current_action === 'admin_upload_materials') ? 'active' : '' ?>" id="nav-upload">
            <i class="bi bi-cloud-arrow-up-fill"></i> Upload Materials
        </a>
        <a href="/library_system/index.php?action=admin_manage_categories" class="menu-item <?= ($current_action === 'admin_manage_categories') ? 'active' : '' ?>" id="nav-categories">
            <i class="bi bi-tags-fill"></i> Categories
        </a>

        <div class="menu-label">Users</div>
        <a href="/library_system/index.php?action=admin_manage_users" class="menu-item <?= ($current_action === 'admin_manage_users') ? 'active' : '' ?>" id="nav-users">
            <i class="bi bi-people-fill"></i> Manage Users
        </a>
        <a href="/library_system/index.php?action=admin_faculty_accounts" class="menu-item <?= ($current_action === 'admin_faculty_accounts') ? 'active' : '' ?>" id="nav-faculty">
            <i class="bi bi-mortarboard-fill"></i> Faculty Accounts
        </a>

        <div class="menu-label">Reports</div>
        <a href="/library_system/index.php?action=admin_usage_reports" class="menu-item <?= ($current_action === 'admin_usage_reports') ? 'active' : '' ?>" id="nav-reports">
            <i class="bi bi-bar-chart-line-fill"></i> Usage Reports
        </a>
        <a href="/library_system/index.php?action=admin_export_data" class="menu-item <?= ($current_action === 'admin_export_data') ? 'active' : '' ?>" id="nav-export">
            <i class="bi bi-download"></i> Export Data
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="/library_system/index.php?action=logout" class="logout-btn">
            <i class="bi bi-box-arrow-left"></i> Log Out
        </a>
    </div>
</aside>
