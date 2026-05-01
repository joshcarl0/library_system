<!-- TOP BAR -->
<header class="topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-sm d-lg-none" onclick="toggleSidebar()" style="border:none; color: var(--oc-green); font-size:1.3rem; background:transparent;">
            <i class="bi bi-list"></i>
        </button>
        <div class="topbar-left">
            <h1>Dashboard</h1>
            <p><?php echo date('l, F j, Y'); ?></p>
        </div>
    </div>
    <div class="topbar-right">
        <div class="text-end me-1">
            <div class="topbar-name"><?php echo htmlspecialchars($_SESSION['fullname'] ?? 'Administrator', ENT_QUOTES, 'UTF-8'); ?></div>
            <div class="topbar-role">System Administrator</div>
        </div>
        <div class="topbar-avatar">
            <?php
                $name = $_SESSION['fullname'] ?? 'A';
                echo strtoupper(substr($name, 0, 1));
            ?>
        </div>
    </div>
</header>
