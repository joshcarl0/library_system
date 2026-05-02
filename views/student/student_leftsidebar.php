<?php
/**
 * Student Sidebar Component
 * Olivarez College LRIS
 */
$current_action = $_GET['action'] ?? 'student_dashboard';
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="logo-circle">
            <img src="/library_system/assets/images/olivarez_logo.png" alt="OC Logo">
        </div>
        <div class="sidebar-title">
            <div class="main">LRIS Portal</div>
            <div class="sub">Olivarez College</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="menu-label">Main Menu</div>
        <a href="/library_system/index.php?action=student_dashboard" class="menu-item <?= ($current_action === 'student_dashboard') ? 'active' : '' ?>">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
        <a href="/library_system/index.php?action=student_search" class="menu-item <?= ($current_action === 'student_search') ? 'active' : '' ?>">
            <i class="bi bi-search"></i> Search Resources
        </a>
        <a href="/library_system/index.php?action=student_borrowed" class="menu-item <?= ($current_action === 'student_borrowed') ? 'active' : '' ?>">
            <i class="bi bi-book-half"></i> My Borrowed Items
        </a>

        <div class="menu-label">Account</div>
        <a href="/library_system/index.php?action=student_profile" class="menu-item <?= ($current_action === 'student_profile') ? 'active' : '' ?>">
            <i class="bi bi-person-fill"></i> My Profile
        </a>
        <a href="/library_system/index.php?action=logout" class="menu-item text-danger mt-4">
            <i class="bi bi-box-arrow-left"></i> Logout
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar-small"><?= strtoupper(substr($_SESSION['fullname'] ?? 'S', 0, 1)) ?></div>
            <div class="user-details">
                <span class="name"><?= htmlspecialchars($_SESSION['fullname'] ?? 'Student') ?></span>
                <span class="role">Student ID: <?= htmlspecialchars($_SESSION['student_id'] ?? 'N/A') ?></span>
            </div>
        </div>
    </div>
</aside>
