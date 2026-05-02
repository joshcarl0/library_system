<?php
require_once __DIR__ . '/../../app/core/models/Notification.php';
$notifModel = new Notification();
$unreadCount = $notifModel->getUnreadCount($_SESSION['user_id']);
$recentNotifs = $notifModel->getByUser($_SESSION['user_id'], 'all');
$recentNotifs = array_slice($recentNotifs, 0, 5);
?>
<header class="topbar">
    <div class="d-flex align-items-center">
        <button class="mobile-toggle d-lg-none me-3" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
        <div class="topbar-left-new">
            <span class="text-muted-oc small d-block"><?= date('l, F j, Y') ?></span>
            <h1 class="h5 fw-800 mb-0" style="color: var(--oc-green);">Dashboard</h1>
        </div>
    </div>

    <div class="topbar-right-new">
        <div class="topbar-actions me-3 d-none d-md-flex">
            <!-- Notifications Dropdown -->
            <div class="dropdown">
                <div class="action-icon-admin" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications">
                    <i class="bi bi-bell"></i>
                    <?php if ($unreadCount > 0): ?>
                        <span class="notification-dot"></span>
                    <?php endif; ?>
                </div>
                <div class="dropdown-menu dropdown-menu-end notification-dropdown">
                    <div class="dropdown-header d-flex justify-content-between align-items-center">
                        <span>Admin Notifications</span>
                        <?php if ($unreadCount > 0): ?>
                            <span class="badge bg-danger rounded-pill" style="font-size: 0.65rem;"><?= $unreadCount ?> New</span>
                        <?php endif; ?>
                    </div>
                    <div class="notification-list">
                        <?php if (!empty($recentNotifs)): ?>
                            <?php foreach ($recentNotifs as $rn): ?>
                                <div class="notification-item <?= ($rn['is_read'] == 0) ? 'unread' : '' ?>">
                                    <div class="ni-icon bg-success-subtle text-success">
                                        <i class="bi bi-info-circle"></i>
                                    </div>
                                    <div class="ni-content">
                                        <div class="ni-text"><?= htmlspecialchars($rn['title']) ?></div>
                                        <div class="ni-time"><?= date('M d, g:i A', strtotime($rn['created_at'])) ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="p-4 text-center text-muted small">No alerts today</div>
                        <?php endif; ?>
                    </div>
                    <div class="dropdown-footer">
                        <a href="#">Clear All</a>
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <a href="/library_system/index.php?action=admin_profile" class="action-icon-admin" title="Settings" style="text-decoration: none;">
                <i class="bi bi-gear"></i>
            </a>
        </div>
        
        <div class="user-profile-admin">
            <div class="user-info-admin d-none d-sm-block text-end me-2">
                <div class="ua-name"><?= htmlspecialchars($_SESSION['fullname'] ?? 'Admin') ?></div>
                <div class="ua-role">System Administrator</div>
            </div>
            <div class="ua-avatar">
                <?= strtoupper(substr($_SESSION['fullname'] ?? 'A', 0, 1)) ?>
            </div>
        </div>
    </div>
</header>
