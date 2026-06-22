<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>🛕 Temple Admin · Redesign</title>
  <!-- Bootstrap 5 + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <!-- Google Font (Inter) -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      background: #f6f1eb;
      color: #1e1e2a;
    }

    /* ---------- SIDEBAR (refined) ---------- */
    .sidebar {
      width: 270px;
      height: 100vh;
      position: fixed;
      left: 0;
      top: 0;
      background: #ffffff;
      backdrop-filter: blur(2px);
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.04);
      overflow-y: auto;
      z-index: 1050;
      padding: 0 0 24px 0;
      border-right: 1px solid rgba(184, 134, 58, 0.08);
      transition: transform 0.25s ease;
    }

    .logo-area {
      padding: 24px 20px 20px 24px;
      display: flex;
      align-items: center;
      gap: 10px;
      border-bottom: 1px solid rgba(0,0,0,0.03);
    }
    .logo-icon {
      background: #b8863a;
      width: 40px;
      height: 40px;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 22px;
      box-shadow: 0 6px 12px rgba(184, 134, 58, 0.2);
    }
    .logo-text {
      font-weight: 700;
      font-size: 22px;
      letter-spacing: -0.3px;
      color: #2d1f0e;
    }
    .logo-text span {
      color: #b8863a;
    }

    .sidebar .nav {
      padding: 16px 12px 0 12px;
    }
    .sidebar .nav-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 11px 16px;
      border-radius: 14px;
      color: #3e3e4a;
      font-weight: 500;
      font-size: 15px;
      transition: all 0.2s;
      margin-bottom: 2px;
      position: relative;
    }
    .sidebar .nav-link i {
      font-size: 1.25rem;
      width: 24px;
      text-align: center;
      color: #7b6b5a;
      transition: color 0.2s;
    }
    .sidebar .nav-link:hover {
      background: #f3ebe0;
      color: #b8863a;
    }
    .sidebar .nav-link:hover i {
      color: #b8863a;
    }
    .sidebar .nav-link.active {
      background: #b8863a;
      color: white;
      box-shadow: 0 6px 16px rgba(184, 134, 58, 0.25);
    }
    .sidebar .nav-link.active i {
      color: white;
    }
    .sidebar .nav-link.logout-link {
      margin-top: 20px;
      border-top: 1px solid #eeece7;
      border-radius: 0;
      padding-top: 20px;
      color: #b34a4a;
    }
    .sidebar .nav-link.logout-link i {
      color: #b34a4a;
    }
    .sidebar .nav-link.logout-link:hover {
      background: transparent;
      color: #b34a4a;
    }

    /* ---------- MAIN CONTENT ---------- */
    .main-content {
      margin-left: 270px;
      min-height: 100vh;
      transition: margin 0.25s;
    }

    /* top bar */
    .topbar {
      background: rgba(255, 255, 255, 0.7);
      backdrop-filter: blur(8px);
      padding: 16px 32px;
      border-bottom: 1px solid rgba(0,0,0,0.02);
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 12px;
      position: sticky;
      top: 0;
      z-index: 1020;
    }
    .topbar h4 {
      font-weight: 600;
      font-size: 1.5rem;
      letter-spacing: -0.3px;
      color: #2d1f0e;
      margin: 0;
    }
    .topbar h4 i {
      color: #b8863a;
      margin-right: 8px;
    }
    .topbar-actions {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .btn-notif {
      background: white;
      border: none;
      width: 44px;
      height: 44px;
      border-radius: 40px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.02);
      position: relative;
      transition: 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .btn-notif:hover {
      background: #f3ebe0;
    }
    .badge-dot {
      position: absolute;
      top: 4px;
      right: 4px;
      background: #d13a3a;
      color: white;
      font-size: 11px;
      font-weight: 600;
      width: 22px;
      height: 22px;
      border-radius: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 2px solid white;
    }
    .profile-toggle {
      background: white;
      border: none;
      padding: 6px 16px 6px 12px;
      border-radius: 40px;
      font-weight: 500;
      box-shadow: 0 2px 8px rgba(0,0,0,0.02);
      display: flex;
      align-items: center;
      gap: 10px;
      transition: 0.2s;
    }
    .profile-toggle:hover {
      background: #f3ebe0;
    }
    .profile-toggle i {
      font-size: 1.4rem;
      color: #b8863a;
    }

    /* cards */
    .stat-card {
      background: white;
      border-radius: 24px;
      padding: 22px 24px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.02);
      border: 1px solid rgba(184, 134, 58, 0.06);
      transition: transform 0.15s, box-shadow 0.2s;
      height: 100%;
    }
    .stat-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 16px 32px rgba(184, 134, 58, 0.08);
    }
    .stat-card .stat-label {
      font-size: 0.85rem;
      text-transform: uppercase;
      letter-spacing: 0.3px;
      color: #7b6b5a;
      font-weight: 600;
    }
    .stat-card .stat-number {
      font-size: 2.2rem;
      font-weight: 700;
      color: #1e1e2a;
      letter-spacing: -0.5px;
      margin: 4px 0 0 0;
    }
    .stat-icon {
      width: 52px;
      height: 52px;
      border-radius: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      color: white;
    }
    .stat-icon.gold {
      background: #b8863a;
    }
    .stat-icon.blue {
      background: #2a6fdb;
    }
    .stat-icon.green {
      background: #1f9d6a;
    }
    .stat-icon.rose {
      background: #c94b6e;
    }
     .stat-icon.red {
      background: #ff0d0d;
    }
  .stat-icon.yellow {
      background: #ffbb00;
    }


    /* quick actions */
    .quick-card {
      background: white;
      border-radius: 24px;
      padding: 24px 28px;
      border: 1px solid rgba(184, 134, 58, 0.06);
      box-shadow: 0 8px 24px rgba(0,0,0,0.02);
    }
    .quick-card h5 {
      font-weight: 600;
      color: #2d1f0e;
      margin-bottom: 18px;
    }
    .quick-btn {
      border-radius: 60px;
      padding: 12px 0;
      font-weight: 600;
      font-size: 0.95rem;
      border: none;
      transition: all 0.2s;
      background: #f4efe9;
      color: #2d1f0e;
    }
    .quick-btn:hover {
      background: #b8863a;
      color: white;
      transform: scale(0.98);
    }
    .quick-btn i {
      margin-right: 8px;
    }
    .quick-btn.primary {
      background: #b8863a;
      color: white;
    }
    .quick-btn.primary:hover {
      background: #a07431;
    }

    /* table cards */
    .table-wrap {
      background: white;
      border-radius: 24px;
      border: 1px solid rgba(184, 134, 58, 0.06);
      box-shadow: 0 8px 24px rgba(0,0,0,0.02);
      overflow: hidden;
      height: 100%;
    }
    .table-wrap .card-header {
      background: transparent;
      border-bottom: 1px solid #f0ece6;
      padding: 18px 24px;
      font-weight: 600;
      font-size: 1.05rem;
      color: #2d1f0e;
    }
    .table-wrap .table {
      margin: 0;
    }
    .table-wrap .table th {
      font-weight: 600;
      color: #5a4e3e;
      border-bottom: 1px solid #f0ece6;
      padding: 14px 24px;
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 0.4px;
    }
    .table-wrap .table td {
      padding: 14px 24px;
      border-bottom: 1px solid #f5f0ea;
      color: #1e1e2a;
      font-weight: 500;
    }
    .table-wrap .table tr:last-child td {
      border-bottom: none;
    }
    .badge-amount {
      background: #f3ebe0;
      color: #b8863a;
      padding: 4px 12px;
      border-radius: 40px;
      font-weight: 600;
      font-size: 0.8rem;
    }
    .text-muted-light {
      color: #a0907e;
    }

    /* responsive */
    @media (max-width: 992px) {
      .sidebar {
        transform: translateX(-100%);
        width: 280px;
      }
      .sidebar.show {
        transform: translateX(0);
      }
      .main-content {
        margin-left: 0;
      }
      .topbar {
        padding: 14px 20px;
      }
      .stat-card .stat-number {
        font-size: 1.8rem;
      }
    }
    /* toggle button (mobile) */
    .menu-toggle {
      background: white;
      border: none;
      width: 44px;
      height: 44px;
      border-radius: 40px;
      display: none;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    @media (max-width: 992px) {
      .menu-toggle {
        display: flex;
      }
    }

    /* scrollbar */
    .sidebar::-webkit-scrollbar {
      width: 4px;
    }
    .sidebar::-webkit-scrollbar-track {
      background: transparent;
    }
    .sidebar::-webkit-scrollbar-thumb {
      background: #d6cbbc;
      border-radius: 12px;
    }
  </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
  <div class="logo-area">
    <div class="logo-icon">🛕 </div>
    <div class="logo-text">Temple<span>ERP</span></div>
  </div>

  <ul class="nav flex-column">
    <li class="nav-item"><a href="#" class="nav-link active"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
    <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-people-fill"></i> Devotees</a></li>
    <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-person-badge"></i> Priests</a></li>
    <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-person-workspace"></i> Trustees</a></li>
    <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-person-lines-fill"></i> Staff</a></li>
    <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-cash-stack"></i> Accountants</a></li>
    <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-calendar-event"></i> Poojas</a></li>
    <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-wallet2"></i> Donations</a></li>
    <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-stars"></i> Events</a></li>
    <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-box-seam"></i> Inventory</a></li>
    <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-bar-chart-fill"></i> Reports</a></li>
    <!-- <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-gear-fill"></i> Settings</a></li> -->
  </ul>
</aside>

<!-- MAIN -->
<div class="main-content">

  <!-- TOPBAR -->
  <header class="topbar">
    <div class="d-flex align-items-center gap-3">
      <button class="menu-toggle" id="menuToggle" aria-label="Toggle sidebar">
        <i class="bi bi-list"></i>
      </button>
      <h4><i class="bi bi-grid-1x2-fill"></i> Dashboard</h4>
    </div>
    <div class="topbar-actions">
      <button class="btn-notif">
        <i class="bi bi-bell-fill fs-5" style="color:#5a4e3e;"></i>
        <span class="badge-dot">3</span>
      </button>
      <div class="dropdown">
        <button class="profile-toggle dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-person-circle"></i>
          <span>Admin</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="border-radius: 20px; padding: 8px;">
          <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>My Profile</a></li>
          <li><a class="dropdown-item" href="#"><i class="bi bi-key me-2"></i>Change Password</a></li>
          <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
        </ul>
      </div>
    </div>
  </header>

  <!-- DASHBOARD CONTENT -->
  <div class="container-fluid px-4 py-4">

    <!-- STATS ROW -->
    <div class="row g-4 mb-4">
      <div class="col-md-3 col-sm-6">
        <div class="stat-card d-flex align-items-center justify-content-between">
          <div>
            <div class="stat-label">Devotees</div>
            <div class="stat-number">2,450</div>
          </div>
          <div class="stat-icon gold"><i class="bi bi-people-fill"></i></div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="stat-card d-flex align-items-center justify-content-between">
          <div>
            <div class="stat-label">Today's Poojas</div>
            <div class="stat-number">35</div>
          </div>
          <div class="stat-icon blue"><i class="bi bi-calendar-event"></i></div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="stat-card d-flex align-items-center justify-content-between">
          <div>
            <div class="stat-label">Donations</div>
            <div class="stat-number">₹1.25L</div>
          </div>
          <div class="stat-icon green"><i class="bi bi-wallet2"></i></div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="stat-card d-flex align-items-center justify-content-between">
          <div>
            <div class="stat-label">Events</div>
            <div class="stat-number">8</div>
          </div>
          <div class="stat-icon rose"><i class="bi bi-stars"></i></div>
        </div>
      </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="quick-card mb-4">
      <h5><i class="bi bi-lightning-charge-fill me-2" style="color:#b8863a;"></i>Quick Actions</h5>
      <div class="row g-3">
        <div class="col-md-3 col-sm-6">
          <button class="quick-btn primary w-100"><i class="bi bi-person-plus"></i> Manage Devotee</button>
        </div>
        <div class="col-md-3 col-sm-6">
          <button class="quick-btn w-100"><i class="bi bi-person-plus"></i> Manage Priest</button>
        </div>
        <div class="col-md-3 col-sm-6">
          <button class="quick-btn w-100"><i class="bi bi-coin"></i> Manage Donation</button>
        </div>
        <div class="col-md-3 col-sm-6">
          <button class="quick-btn w-100"><i class="bi bi-calendar-plus"></i> Manage Event</button>
        </div>
      </div>
    </div>
<!-- STATS ROW -->
    <div class="row g-4 mb-4">
      <div class="col-md-3 col-sm-6">
        <div class="stat-card d-flex align-items-center justify-content-between">
          <div>
            <div class="stat-label">Online Priest</div>
            <div class="stat-number">15</div>
          </div>
          <div class="stat-icon green"><i class="bi bi-people-fill"></i></div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="stat-card d-flex align-items-center justify-content-between">
          <div>
            <div class="stat-label">Busy Priest</div>
            <div class="stat-number">3</div>
          </div>
          <div class="stat-icon rose"><i class="bi bi-calendar-event"></i></div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">   
        <div class="stat-card d-flex align-items-center justify-content-between">
          <div>
            <div class="stat-label">Offline Priest</div>
            <div class="stat-number">3</div>
          </div>
          <div class="stat-icon red"><i class="bi bi-calendar-event"></i></div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="stat-card d-flex align-items-center justify-content-between">
          <div>
            <div class="stat-label">Priest in leave</div>
            <div class="stat-number">2</div>
          </div>
          <div class="stat-icon blue"><i class="bi bi-wallet2"></i></div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="stat-card d-flex align-items-center justify-content-between">
          <div>
            <div class="stat-label">Total Priest</div>
            <div class="stat-number">20</div>
          </div>
          <div class="stat-icon yellow"><i class="bi bi-stars"></i></div>
        </div>
      </div>
    </div>
    <!-- TABLES ROW -->
    <div class="row g-4">
      <div class="col-lg-6">
        <div class="table-wrap">
          <div class="card-header"><i class="bi bi-clock-history me-2" style="color:#b8863a;"></i>Recent Devotees</div>
          <table class="table align-middle">
            <thead><tr><th>Name</th><th>Mobile</th><th></th></tr></thead>
            <tbody>
              <tr><td>Ravi Kumar</td><td>9876543210</td><td><span class="badge-amount">new</span></td></tr>
              <tr><td>Priya Sharma</td><td>9823456789</td><td></td></tr>
              <tr><td>Anand Iyer</td><td>9765432109</td><td></td></tr>
              <tr><td>Meera Reddy</td><td>9988776655</td><td></td></tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="table-wrap">
          <div class="card-header"><i class="bi bi-gift me-2" style="color:#b8863a;"></i>Recent Donations</div>
          <table class="table align-middle">
            <thead><tr><th>Donor</th><th>Amount</th><th></th></tr></thead>
            <tbody>
              <tr><td>Suresh</td><td>₹5,000</td><td><span class="badge-amount">today</span></td></tr>
              <tr><td>Lakshmi</td><td>₹11,000</td><td></td></tr>
              <tr><td>Ganesh</td><td>₹2,500</td><td></td></tr>
              <tr><td>Kavya</td><td>₹7,200</td><td></td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- extra spacer -->
    <div class="mt-4 text-muted-light small d-flex justify-content-end">
      <i class="bi bi-droplet me-1"></i> updated just now
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- mobile toggle -->
<script>
  (function() {
    const toggleBtn = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    if (toggleBtn && sidebar) {
      toggleBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        sidebar.classList.toggle('show');
      });
      // close on click outside (optional)
      document.addEventListener('click', function(e) {
        if (window.innerWidth <= 992) {
          if (!sidebar.contains(e.target) && e.target !== toggleBtn && !toggleBtn.contains(e.target)) {
            sidebar.classList.remove('show');
          }
        }
      });
    }
  })();
</script>

</body>
</html>