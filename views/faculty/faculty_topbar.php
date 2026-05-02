<?php
/**
 * Faculty Topbar Component
 */
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
        <div class="page-context d-none d-sm-block">
            <span class="text-muted small">Faculty Access,</span>
            <div class="fw-700"><?= htmlspecialchars($_SESSION['fullname'] ?? 'Faculty Member') ?></div>
        </div>
    </div>

    <div class="topbar-actions">
        <!-- Notifications -->
        <div class="dropdown">
            <div class="action-icon" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications">
                <i class="bi bi-bell"></i>
                <span id="notif-dot" class="notification-dot" style="<?= ($unreadCount > 0) ? '' : 'display: none;' ?>"></span>
            </div>
            <div class="dropdown-menu dropdown-menu-end notification-dropdown">
                <div class="dropdown-header d-flex justify-content-between align-items-center">
                    <span>Notifications</span>
                    <span id="notif-count" class="badge bg-danger rounded-pill" style="font-size: 0.65rem; <?= ($unreadCount > 0) ? '' : 'display: none;' ?>"><?= $unreadCount ?> New</span>
                </div>
                <div id="notif-list" class="notification-list">
                    <?php if (!empty($recentNotifs)): ?>
                        <?php foreach ($recentNotifs as $rn): 
                            $rnIcon = 'bi-bell';
                            $rnColor = 'text-primary bg-primary-subtle';
                            if ($rn['type'] === 'system') { $rnIcon = 'bi-megaphone'; $rnColor = 'text-success bg-success-subtle'; }
                            elseif ($rn['type'] === 'loan') { $rnIcon = 'bi-journal-check'; $rnColor = 'text-warning bg-warning-subtle'; }
                        ?>
                            <a href="/library_system/index.php?action=faculty_notifications" class="notification-item <?= ($rn['is_read'] == 0) ? 'unread' : '' ?>">
                                <div class="ni-icon <?= $rnColor ?>">
                                    <i class="bi <?= $rnIcon ?>"></i>
                                </div>
                                <div class="ni-content">
                                    <div class="ni-text"><?= htmlspecialchars($rn['title']) ?></div>
                                    <div class="ni-time"><?= date('M d, g:i A', strtotime($rn['created_at'])) ?></div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="p-4 text-center text-muted small">
                            <i class="bi bi-bell-slash d-block fs-2 mb-2 opacity-25"></i>
                            No notifications yet
                        </div>
                    <?php endif; ?>
                </div>
                <div class="dropdown-footer">
                    <a href="/library_system/index.php?action=faculty_notifications">View All Notifications</a>
                </div>
            </div>
        </div>

        <!-- Settings Gear -->
        <a href="/library_system/index.php?action=faculty_profile" class="action-icon" title="Settings">
            <i class="bi bi-gear"></i>
        </a>

        <!-- Profile / Logout -->
        <div class="dropdown">
            <div class="user-profile-trigger ms-2" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-avatar-small bg-success"><?= strtoupper(substr($_SESSION['fullname'] ?? 'F', 0, 1)) ?></div>
                <i class="bi bi-chevron-down ms-2 small"></i>
            </div>
            <ul class="dropdown-menu dropdown-menu-end profile-dropdown">
                <li><a class="dropdown-item" href="/library_system/index.php?action=faculty_profile"><i class="bi bi-person me-2"></i> My Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="/library_system/index.php?action=logout"><i class="bi bi-box-arrow-left me-2"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</header>

<script src="/library_system/assets/js/notifications.js" data-api-url="/library_system/index.php?action=faculty_api_get_notifications"></script>
