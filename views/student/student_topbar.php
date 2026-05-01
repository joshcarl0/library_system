<?php
/**
 * Student Topbar Component
 */
?>
<header class="topbar">
    <div class="d-flex align-items-center">
        <button class="mobile-toggle d-lg-none me-3" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
        <div class="page-context d-none d-sm-block">
            <span class="text-muted small">Welcome back,</span>
            <div class="fw-700"><?= htmlspecialchars($_SESSION['fullname'] ?? 'Student') ?></div>
        </div>
    </div>

    <div class="topbar-actions">
        <div class="action-icon" title="Notifications">
            <i class="bi bi-bell"></i>
            <span class="notification-dot"></span>
        </div>
        <div class="action-icon" title="Settings">
            <i class="bi bi-gear"></i>
        </div>
        <div class="user-profile-trigger ms-2" onclick="location.href='/library_system/index.php?action=logout'">
            <div class="user-avatar-small"><?= strtoupper(substr($_SESSION['fullname'] ?? 'S', 0, 1)) ?></div>
            <i class="bi bi-chevron-down ms-2 small"></i>
        </div>
    </div>
</header>
