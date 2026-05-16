<aside class="sidebar d-flex flex-column">
    <div class="sidebar-logo text-center py-3">
        <img src="/assets/images/logo/logosaloon.webp" alt="Salon Logo" class="logo-img" />
    </div>

    <div class="sidebar-buttons d-flex flex-wrap justify-content-center px-2 pb-3">
        <a href="{{ route('branch.dashboard')}}" class="sidebar-card text-center">
            <i class="fas fa-calendar-check"></i>

            <div class="label">Appointments</div>
        </a>
        <a href="dashboard.php" class="sidebar-card text-center">
            <i class="fas fa-chart-pie"></i>

            <div class="label">Dashboard</div>
        </a>
        <a href="{{ route('reports.index') }}" class="sidebar-card text-center">
            <i class="fas fa-file-alt"></i>

            <div class="label">Report</div>
        </a>
        <a href="settings.php" class="sidebar-card text-center">
            <i class="fas fa-cog"></i>

            <div class="label">Settings</div>
        </a>
      

    </div>
</aside>
