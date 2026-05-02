<?php
/**
 * @var array $notifications
 * @var string $filter
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications – LRIS | Olivarez College</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Student Specific CSS -->
    <link rel="stylesheet" href="/library_system/assets/images/css/student/student_sidebar.css">
    <link rel="stylesheet" href="/library_system/assets/images/css/student/student_topbar.css">
    <link rel="stylesheet" href="/library_system/assets/images/css/student/student_dashboard.css">
    <link rel="stylesheet" href="/library_system/assets/images/css/student/notifications.css">
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<?php include __DIR__ . '/student_leftsidebar.php'; ?>

<div class="main-wrapper">

    <?php include __DIR__ . '/student_topbar.php'; ?>

    <main class="page-content">

        <div class="section-title mb-2">Notifications</div>
        <p class="text-muted mb-4">Stay updated with library news, borrowed items, and system alerts.</p>

        <div class="notifications-container">
            <div class="notif-header">
                <h3>Inbox <?= (!empty($filter) && $filter !== 'all') ? '<span class="text-muted small fs-6">(' . ucfirst($filter) . ')</span>' : '' ?></h3>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle rounded-pill px-3" data-bs-toggle="dropdown">
                        Filter: <?= ucfirst($filter) ?>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/library_system/index.php?action=student_notifications&filter=all">All</a></li>
                        <li><a class="dropdown-item" href="/library_system/index.php?action=student_notifications&filter=unread">Unread</a></li>
                        <li><a class="dropdown-item" href="/library_system/index.php?action=student_notifications&filter=loans">Loans</a></li>
                        <li><a class="dropdown-item" href="/library_system/index.php?action=student_notifications&filter=system">System</a></li>
                    </ul>
                </div>
            </div>

            <div class="notif-list-full">
                <?php if (!empty($notifications)): ?>
                    <?php foreach ($notifications as $notif): 
                        // Determine icon and color based on type
                        $iconClass = 'bi-bell-fill';
                        $bgClass = 'bg-primary-subtle text-primary';
                        
                        if ($notif['type'] === 'system') {
                            $iconClass = 'bi-megaphone-fill';
                            $bgClass = 'bg-success-subtle text-success';
                        } elseif ($notif['type'] === 'loan') {
                            $iconClass = 'bi-journal-check';
                            $bgClass = 'bg-warning-subtle text-warning';
                        } elseif ($notif['type'] === 'security') {
                            $iconClass = 'bi-shield-lock-fill';
                            $bgClass = 'bg-danger-subtle text-danger';
                        }
                    ?>
                        <div class="notif-item-full <?= ($notif['is_read'] == 0) ? 'unread' : '' ?>">
                            <div class="notif-icon-box <?= $bgClass ?>">
                                <i class="bi <?= $iconClass ?>"></i>
                            </div>
                            <div class="notif-info">
                                <div class="notif-title-row">
                                    <span class="notif-title"><?= htmlspecialchars($notif['title']) ?></span>
                                    <span class="notif-time-full"><?= date('M d, g:i A', strtotime($notif['created_at'])) ?></span>
                                </div>
                                <p class="notif-desc"><?= htmlspecialchars($notif['message']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="notif-empty">
                        <i class="bi bi-bell-slash"></i>
                        <h4 class="fw-700">All caught up!</h4>
                        <p class="text-muted">You don't have any notifications matching this filter.</p>
                        <a href="/library_system/index.php?action=student_notifications" class="btn btn-sm btn-primary rounded-pill px-4 mt-2">View All</a>
                    </div>
                <?php endif; ?>
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
