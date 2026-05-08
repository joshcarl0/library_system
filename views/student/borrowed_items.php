<?php
/**
 * @var array $borrowed
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Borrowed Items – LRIS | Olivarez College</title>
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
    <link rel="stylesheet" href="/library_system/assets/images/css/student/borrowed_items.css">
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<?php include __DIR__ . '/student_leftsidebar.php'; ?>

<div class="main-wrapper">

    <?php include __DIR__ . '/student_topbar.php'; ?>

    <main class="page-content">

        <div class="section-title mb-2">My Borrowed Items</div>
        <p class="text-muted mb-4">View your active loans and tracking history.</p>

        <div class="borrow-table-container">
            <?php if (empty($borrowed)): ?>
            <div class="text-center py-5">
                <i class="bi bi-journal-check fs-1 text-muted d-block mb-3"></i>
                <h4 class="fw-700">No active loans found</h4>
                <p class="text-muted">You haven't borrowed any materials yet.</p>
                <a href="/library_system/index.php?action=student_search" class="btn btn-primary rounded-pill px-4 mt-2">Browse Resources</a>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Book / Material</th>
                            <th>Borrow Date</th>
                            <th>Estimated Due Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($borrowed as $item): 
                            $borrow_date = new DateTime($item['created_at']);
                            
                            // Use DB due_date if available, otherwise fallback to +7 days
                            if (!empty($item['due_date'])) {
                                $due_date = new DateTime($item['due_date']);
                            } else {
                                $due_date = clone $borrow_date;
                                $due_date->modify('+7 days');
                            }
                            
                            $is_overdue = (new DateTime() > $due_date);
                        ?>
                        <tr>
                            <td>
                                <div class="book-info">
                                    <div class="book-icon">
                                        <i class="bi bi-book"></i>
                                    </div>
                                    <div class="book-details">
                                        <span class="book-title"><?= htmlspecialchars($item['title']) ?></span>
                                        <span class="book-cat"><?= htmlspecialchars($item['category_name'] ?? 'General') ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="date-box">
                                    <span class="date-label">Borrowed on</span>
                                    <span class="date-value"><?= $borrow_date->format('M d, Y') ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="date-box">
                                    <span class="date-label">Return by</span>
                                    <span class="date-value <?= $is_overdue ? 'text-danger' : '' ?>">
                                        <?= $due_date->format('M d, Y') ?>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <?php if ($item['action'] === 'Pending'): ?>
                                    <span class="status-badge bg-warning-subtle text-warning">
                                        Pending Approval
                                    </span>
                                <?php else: ?>
                                    <span class="status-badge <?= $is_overdue ? 'status-overdue' : 'status-borrowed' ?>">
                                        <?= $is_overdue ? 'Overdue' : 'Active Loan' ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-light rounded-pill px-3 fw-600"
                                        onclick="viewDetails(<?= htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8') ?>)">
                                    Details
                                </button>
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

<!-- Resource Details Modal -->
<div class="modal fade" id="resourceDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <div class="text-center mb-4">
                    <div id="modalCoverContainer" class="mb-3 mx-auto shadow-sm rounded-3" style="width: 140px; height: 190px; background: #f8fafc; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        <i class="bi bi-journal-text fs-1 text-muted" id="modalDefaultIcon"></i>
                        <img src="" id="modalCoverImg" class="w-100 h-100 object-fit-cover d-none">
                    </div>
                    <h5 class="fw-800 mb-1" id="modalTitle"></h5>
                    <p class="text-muted mb-2" id="modalAuthor"></p>
                    <div id="modalStatusBadge"></div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-700">Description</h6>
                    <p class="text-muted small mb-0" id="modalDescription"></p>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('show');
    }

    const resourceModal = new bootstrap.Modal(document.getElementById('resourceDetailsModal'));

    function viewDetails(item) {
        document.getElementById('modalTitle').textContent = item.title;
        document.getElementById('modalAuthor').textContent = 'by ' + item.author;
        document.getElementById('modalDescription').textContent = item.description || 'No description provided for this material.';
        
        // Handle Cover Image
        const coverImg = document.getElementById('modalCoverImg');
        const defaultIcon = document.getElementById('modalDefaultIcon');
        if (item.cover_image) {
            coverImg.src = item.cover_image;
            coverImg.classList.remove('d-none');
            defaultIcon.classList.add('d-none');
        } else {
            coverImg.classList.add('d-none');
            defaultIcon.classList.remove('d-none');
        }

        const statusBadge = document.getElementById('modalStatusBadge');
        
        // Status Badge
        if (item.action === 'Pending') {
            statusBadge.innerHTML = '<span class="badge bg-warning-subtle text-warning rounded-pill px-3">Pending Approval</span>';
        } else {
            statusBadge.innerHTML = '<span class="badge bg-success-subtle text-success rounded-pill px-3">Borrowed</span>';
        }
        
        resourceModal.show();
    }
</script>
</body>
</html>
