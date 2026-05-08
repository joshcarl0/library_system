<?php
/**
 * @var array $pendingRequests
 * @var array $activeBorrows
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Requests – LRIS | Olivarez College</title>
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
    <style>
        .request-card {
            background: #fff;
            border-radius: 15px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
        }
        .status-badge-pending {
            background: #fef9c3;
            color: #854d0e;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-badge-borrowed {
            background: #dcfce7;
            color: #166534;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<?php include __DIR__ . '/adminleft_sidebar.php'; ?>

<div class="main-wrapper">

    <?php include __DIR__ . '/admin_topbar.php'; ?>

    <main class="page-content">

        <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($_GET['success']) ?>
        </div>
        <?php endif; ?>

        <div class="section-title mb-4">Manage Borrowing Requests</div>

        <!-- ── Pending Requests ── -->
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-700 mb-0"><i class="bi bi-clock-history me-2 text-warning"></i> Pending Approval</h5>
                <div style="width: 280px;">
                    <input type="text" id="searchPending" class="form-control" placeholder="🔍 Search user or resource..." style="border-radius:10px; font-size:0.85rem;">
                </div>
            </div>
            <div class="row g-4" id="pendingGrid">
                <?php if (empty($pendingRequests)): ?>
                    <div class="col-12 text-center py-5 text-muted bg-white rounded-4 border">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i> No pending requests.
                    </div>
                <?php else: ?>
                    <?php foreach ($pendingRequests as $req): ?>
                    <div class="col-md-6 col-xl-4">
                        <div class="request-card p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="status-badge-pending">Pending Approval</span>
                                </div>
                                <div class="text-muted small"><?= date('M d, H:i', strtotime($req['created_at'])) ?></div>
                            </div>
                            <h6 class="fw-700 mb-1"><?= htmlspecialchars($req['title']) ?></h6>
                            <p class="text-muted small mb-3"><i class="bi bi-person me-1"></i> Requested by: <strong><?= htmlspecialchars($req['fullname']) ?></strong> (<?= $req['student_id'] ?>)</p>
                            
                            <div class="d-flex gap-2">
                                <a href="/library_system/index.php?action=admin_approve_request&id=<?= $req['id'] ?>" class="btn btn-success w-100 rounded-pill fw-600">
                                    <i class="bi bi-check-lg me-1"></i> Approve
                                </a>
                                <button class="btn btn-outline-danger rounded-pill px-3"><i class="bi bi-x-lg"></i></button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- ── Active Borrows (Returning) ── -->
        <div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-700 mb-0"><i class="bi bi-journal-check me-2 text-success"></i> Active Borrows</h5>
                <div style="width: 280px;">
                    <input type="text" id="searchActive" class="form-control" placeholder="🔍 Search user or resource..." style="border-radius:10px; font-size:0.85rem;">
                </div>
            </div>
            <div class="content-card">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="activeBorrowsTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0">User</th>
                                <th class="border-0">Resource</th>
                                <th class="border-0">Borrowed Date</th>
                                <th class="border-0">Due Date</th>
                                <th class="border-0 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($activeBorrows)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">No active borrows.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($activeBorrows as $ab): ?>
                                <tr>
                                    <td>
                                        <div class="fw-600"><?= htmlspecialchars($ab['fullname']) ?></div>
                                        <div class="text-muted x-small"><?= $ab['student_id'] ?></div>
                                    </td>
                                    <td><?= htmlspecialchars($ab['title']) ?></td>
                                    <td class="small"><?= date('M d, Y', strtotime($ab['created_at'])) ?></td>
                                    <td>
                                        <span class="text-danger fw-600 small">
                                            <?= date('M d, Y', strtotime($ab['due_date'])) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="/library_system/index.php?action=admin_return_resource&id=<?= $ab['id'] ?>" class="btn btn-sm btn-primary rounded-pill px-3 fw-600">
                                            Mark as Returned
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
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

    // ── Live Search: Pending Requests (cards) ──
    const searchPending = document.getElementById('searchPending');
    if (searchPending) {
        searchPending.addEventListener('input', function () {
            const term = this.value.toLowerCase();
            document.querySelectorAll('#pendingGrid .col-md-6').forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(term) ? '' : 'none';
            });
        });
    }

    // ── Live Search: Active Borrows (table rows) ──
    const searchActive = document.getElementById('searchActive');
    if (searchActive) {
        searchActive.addEventListener('input', function () {
            const term = this.value.toLowerCase();
            document.querySelectorAll('#activeBorrowsTable tbody tr').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    }

    // Auto-dismiss success alert
    const alertEl = document.querySelector('.alert-success');
    if (alertEl) setTimeout(() => alertEl.style.display = 'none', 4000);
</script>
</body>
</html>
