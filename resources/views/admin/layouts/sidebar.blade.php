<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
  <div class="logo-area">
    <div class="logo-icon">🛕</div>
    <div class="logo-text">Temple<span>ERP</span></div>
  </div>

  <ul class="nav flex-column">
    <li class="nav-item">
      <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ route('admin.devotees.index') }}" class="nav-link {{ request()->routeIs('admin.devotees.*') ? 'active' : '' }}">
        <i class="bi bi-people-fill"></i> Devotees
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ route('admin.priests.index') }}" class="nav-link {{ request()->routeIs('admin.priests.*') ? 'active' : '' }}">
        <i class="bi bi-person-badge"></i> Priests
      </a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link"><i class="bi bi-person-workspace"></i> Trustees</a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link"><i class="bi bi-person-lines-fill"></i> Staff</a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link"><i class="bi bi-cash-stack"></i> Accountants</a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link"><i class="bi bi-calendar-event"></i> Poojas</a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link"><i class="bi bi-wallet2"></i> Donations</a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link"><i class="bi bi-stars"></i> Events</a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link"><i class="bi bi-box-seam"></i> Inventory</a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link"><i class="bi bi-bar-chart-fill"></i> Reports</a>
    </li>
    <li class="nav-item">
      <a class="nav-link logout-link" id="sidebarLogoutBtn">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </li>
  </ul>
</aside>