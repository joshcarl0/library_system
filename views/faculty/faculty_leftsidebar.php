<?php
/**
 * Faculty Sidebar Component
 * Olivarez College LRIS
 */
$current_action = $_GET['action'] ?? 'faculty_dashboard';
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="logo-circle">
            <img src="/library_system/assets/images/olivarez_logo.png" alt="OC Logo">
        </div>
        <div class="sidebar-title">
            <div class="main">LRIS Portal</div>
            <div class="sub">Faculty Panel</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="menu-label">Main Menu</div>
        <a href="/library_system/index.php?action=faculty_dashboard" class="menu-item <?= ($current_action === 'faculty_dashboard') ? 'active' : '' ?>">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
        <a href="/library_system/index.php?action=faculty_upload" class="menu-item <?= ($current_action === 'faculty_upload') ? 'active' : '' ?>">
            <i class="bi bi-cloud-upload-fill"></i> Upload Materials
        </a>
        <a href="/library_system/index.php?action=faculty_my_uploads" class="menu-item <?= ($current_action === 'faculty_my_uploads') ? 'active' : '' ?>">
            <i class="bi bi-journal-text"></i> My Uploads
        </a>

        <div class="menu-label">Account</div>
        <a href="/library_system/index.php?action=faculty_profile" class="menu-item <?= ($current_action === 'faculty_profile') ? 'active' : '' ?>">
            <i class="bi bi-person-fill"></i> My Profile
        </a>
        <a href="/library_system/index.php?action=logout" class="menu-item text-danger mt-4">
            <i class="bi bi-box-arrow-left"></i> Logout
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar-small bg-success"><?= strtoupper(substr($_SESSION['fullname'] ?? 'F', 0, 1)) ?></div>
            <div class="user-details">
                <span class="name"><?= htmlspecialchars($_SESSION['fullname'] ?? 'Faculty') ?></span>
                <span class="role">Faculty Account</span>
            </div>
        </div>
    </div>
</aside>
