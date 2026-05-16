<aside class="sidebar d-flex flex-column">
   <div class="sidebar-logo text-center">
    @php
        $user = auth()->user();
        $branch = \App\Models\AddBranches::where('branch_id', $user->branch_id)->first();
        $logoPath = $branch && $branch->logo ? asset('uploads/logos/' . $branch->logo) : asset('default-logo.png');
    @endphp

    <img src="{{ $logoPath }}" alt="Salon Logo" class="logo-img" />
</div>


    <div class="sidebar-buttons d-flex flex-wrap justify-content-center px-2 pb-3">
        <a href="{{ route('branch.dashboard') }}" class="sidebar-card text-center">
            <i class="fas fa-calendar-check"></i>

            <div class="label">Appointments</div>
        </a>
        <a href="{{ route('attendance') }}" class="sidebar-card text-center">
            <i class="fas fa-chart-pie"></i>

            <div class="label">Staff Attendence</div>
        </a>
        <a href="{{ route('reports.index') }}" class="sidebar-card text-center">
            <i class="fas fa-file-alt"></i>

            <div class="label">Report</div>
        </a>
        <a href="{{ route('settings') }}" class="sidebar-card text-center">
            <i class="fas fa-cog"></i>
            <div class="label">Settings</div>
        </a>



    </div>

</aside>
