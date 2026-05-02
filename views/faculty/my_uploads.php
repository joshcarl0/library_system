<?php
/**
 * Faculty — My Uploads View
 * @var array $myUploads
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Uploads – LRIS | Olivarez College</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Core Styles -->
    <link rel="stylesheet" href="/library_system/assets/images/css/faculty/faculty_sidebar.css">
    <link rel="stylesheet" href="/library_system/assets/images/css/faculty/faculty_dashboard.css">
    <link rel="stylesheet" href="/library_system/assets/images/css/faculty/my_uploads.css">
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<?php include __DIR__ . '/faculty_leftsidebar.php'; ?>

<div class="main-wrapper">
    <?php include __DIR__ . '/faculty_topbar.php'; ?>

    <main class="page-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="section-title mb-1">My Uploads</h2>
                <p class="text-muted small">Manage the learning materials you have shared with students.</p>
            </div>
            <a href="/library_system/index.php?action=faculty_upload" class="btn-upload-new">
                <i class="bi bi-cloud-arrow-up me-2"></i> Upload New
            </a>
        </div>

        <!-- Uploads List Card -->
        <div class="uploads-card">
            <?php if (empty($myUploads)): ?>
                <div class="empty-state">
                    <i class="bi bi-folder2-open empty-icon"></i>
                    <h4>No Uploads Yet</h4>
                    <p>You haven't uploaded any learning materials.</p>
                    <a href="/library_system/index.php?action=faculty_upload" class="btn btn-success mt-3 px-4 rounded-pill">Upload First Material</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="uploads-table">
                        <thead>
                            <tr>
                                <th>Resource Name</th>
                                <th>Subject</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Date Uploaded</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($myUploads as $res): 
                                $icon = match($res['type']) {
                                    'digital' => 'file-earmark-pdf-fill text-danger',
                                    'module' => 'book-half text-success',
                                    'thesis' => 'mortarboard-fill text-warning',
                                    'journal' => 'journal-text text-primary',
                                    default => 'journal-bookmark-fill text-secondary'
                                };
                            ?>
                            <tr>
                                <td>
                                    <div class="res-title-cell">
                                        <i class="bi bi-<?= $icon ?> fs-4 me-3"></i>
                                        <div>
                                            <div class="res-title"><?= htmlspecialchars($res['title']) ?></div>
                                            <div class="res-author"><?= htmlspecialchars($res['author']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($res['subject'] ?: 'N/A') ?></span></td>
                                <td class="text-capitalize"><?= htmlspecialchars($res['type']) ?></td>
                                <td>
                                    <span class="status-badge status-<?= $res['status'] ?>">
                                        <?= ucfirst($res['status']) ?>
                                    </span>
                                </td>
                                <td class="text-muted small"><?= date('M d, Y h:i A', strtotime($res['created_at'])) ?></td>
                                <td class="text-end">
                                    <?php if ($res['file_path']): ?>
                                        <a href="<?= htmlspecialchars($res['file_path']) ?>" target="_blank" class="action-btn view-btn" title="View File">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    <?php else: ?>
                                        <button class="action-btn view-btn text-muted" disabled title="No digital file"><i class="bi bi-eye-slash"></i></button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
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
