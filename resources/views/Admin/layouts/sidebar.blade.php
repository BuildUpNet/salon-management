<!-- <div class="sidebar">
    <h2>My Salon Hair & Beauty</h2>

    <nav class="nav">
        <a href="dashboard.php" class="nav-link">
            <i class="fas fa-chart-pie"></i>
            <span>Dashboard</span>
        </a>

        <a class="nav-link" href="manage.php">
            <i class="fa-solid fa-briefcase"></i>

            <span>Manage </span>
        </a>


        <a href="#" class="nav-link">
            <i class="fas fa-file-alt"></i>
            <span>Report</span>
        </a>
        <a href="#" class="nav-link">
            <i class="fas fa-cog"></i>
            <span>Settings</span>
        </a>
    </nav>
</div>
 -->

<!-- Sidebar -->
<aside class="sidebar d-flex flex-column">
    <div class="sidebar-logo text-center py-3">
        <img src="../assets/images/logo/logosaloon.webp" alt="Salon Logo" class="logo-img" />
    </div>

    <div class="sidebar-buttons d-flex flex-wrap justify-content-center px-2 pb-3">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-card text-center">
            <i class="bi bi-calendar-check-fill"></i>
            <div class="label">Dashboard</div>
        </a>
        <a href="{{ route('admin.manage') }}" class="sidebar-card text-center">
            <i class="bi bi-pie-chart-fill"></i>
            <div class="label">Manage</div>
        </a>
        <a href="{{ route('adminreports.index') }}" class="sidebar-card text-center">
            <i class="bi bi-file-earmark-fill"></i>
            <div class="label">Report</div>
        </a>
        <a href="#" class="sidebar-card text-center">
            <i class="bi bi-gear-fill"></i>
            <div class="label">Settings</div>
        </a>
    </div>
</aside>