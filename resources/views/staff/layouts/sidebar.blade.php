<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
  <div class="logo-area">
    <div class="logo-icon">🛕</div>
    <div class="logo-text">Staff<span>ERP</span></div>
  </div>

  <ul class="nav flex-column">
    <li class="nav-item">
      <a href="{{ route('staff.dashboard') }}" class="nav-link {{ request()->routeIs('staff.dashboard') && !request()->has('tab') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ route('staff.dashboard') }}?tab=attendance" class="nav-link {{ request()->get('tab') === 'attendance' ? 'active' : '' }}">
        <i class="bi bi-person-check"></i> Attendance
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ route('staff.dashboard') }}?tab=tasks" class="nav-link {{ request()->get('tab') === 'tasks' ? 'active' : '' }}">
        <i class="bi bi-list-task"></i> Tasks
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ route('staff.dashboard') }}?tab=inventory" class="nav-link {{ request()->get('tab') === 'inventory' ? 'active' : '' }}">
        <i class="bi bi-box-seam"></i> Inventory
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ route('staff.dashboard') }}?tab=events" class="nav-link {{ request()->get('tab') === 'events' ? 'active' : '' }}">
        <i class="bi bi-stars"></i> Events
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ route('staff.dashboard') }}?tab=wallet" class="nav-link {{ request()->get('tab') === 'wallet' ? 'active' : '' }}">
        <i class="bi bi-wallet2"></i> My Wallet
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ route('staff.dashboard') }}?tab=salary" class="nav-link {{ request()->get('tab') === 'salary' ? 'active' : '' }}">
        <i class="bi bi-cash-stack"></i> My Salary
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ route('staff.dashboard') }}?tab=profile" class="nav-link {{ request()->get('tab') === 'profile' ? 'active' : '' }}">
        <i class="bi bi-person-circle"></i> Profile
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link logout-link" id="sidebarLogoutBtn" style="cursor: pointer;">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </li>
  </ul>
</aside>
