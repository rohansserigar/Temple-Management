<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Devotee Portal')</title>
  
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- Google Font (Inter) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      background: transparent !important;
      color: #1e1e2a;
    }

    /* Dynamic background layers */
    .dashboard-bg-layer {
      position: fixed;
      inset: 0;
      z-index: -2;
      background: linear-gradient(135deg, #fdfbf7 0%, #f7f1e6 50%, #faf5eb 100%);
    }

    .dashboard-bg-pattern {
      position: fixed;
      inset: 0;
      z-index: -1;
      opacity: 0.03;
      background-image: 
        radial-gradient(circle, #b8863a 1px, transparent 1px),
        radial-gradient(circle, #b8863a 1px, transparent 1px);
      background-size: 40px 40px;
      background-position: 0 0, 20px 20px;
      animation: shiftPattern 40s linear infinite;
    }

    @keyframes shiftPattern {
      from { background-position: 0 0, 20px 20px; }
      to { background-position: 40px 40px, 60px 60px; }
    }

    .dashboard-ambient-glow {
      position: fixed;
      border-radius: 50%;
      filter: blur(120px);
      pointer-events: none;
      z-index: -1;
      opacity: 0.22;
      animation: floatGlow 25s ease-in-out infinite alternate;
    }

    .glow-1 {
      width: 450px;
      height: 450px;
      background: radial-gradient(circle, rgba(255, 111, 0, 0.4) 0%, transparent 70%);
      top: -10%;
      right: 5%;
    }

    .glow-2 {
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, rgba(184, 134, 58, 0.35) 0%, transparent 70%);
      bottom: -15%;
      left: -5%;
      animation-delay: -5s;
    }

    @keyframes floatGlow {
      0% {
        transform: translate(0, 0) scale(1);
      }
      50% {
        transform: translate(40px, -30px) scale(1.1);
      }
      100% {
        transform: translate(-30px, 40px) scale(0.95);
      }
    }

    /* ---------- SIDEBAR ---------- */
    .sidebar {
      width: 260px;
      height: 100vh;
      position: fixed;
      left: 0;
      top: 0;
      background: #ffffff;
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
      background: linear-gradient(135deg, #b8863a, #d4a05a);
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
      background: linear-gradient(135deg, #b8863a, #d4a05a);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
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
      background: linear-gradient(135deg, #b8863a, #d4a05a);
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
      margin-left: 260px;
      min-height: 100vh;
      transition: margin 0.25s;
    }

    /* top bar */
    .topbar {
      background: rgba(255, 255, 255, 0.8);
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

    .membership-badge {
      padding: 6px 18px;
      border-radius: 40px;
      font-size: 0.8rem;
      font-weight: 700;
      letter-spacing: 0.5px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      text-transform: uppercase;
    }
    .membership-badge.gold {
      background: linear-gradient(135deg, #ffd700, #f5a623);
      color: #7c5c00;
      box-shadow: 0 2px 12px rgba(255, 215, 0, 0.3);
    }
    .membership-badge.silver {
      background: linear-gradient(135deg, #e8e8e8, #c0c0c0);
      color: #4a4a4a;
      box-shadow: 0 2px 12px rgba(192, 192, 192, 0.3);
    }
    .membership-badge.bronze {
      background: linear-gradient(135deg, #cd7f32, #a05a2c);
      color: #ffffff;
      box-shadow: 0 2px 12px rgba(205, 127, 50, 0.3);
    }
    .membership-badge.platinum {
      background: linear-gradient(135deg, #e5e4e2, #b8b8b8);
      color: #2d2d2d;
      box-shadow: 0 2px 12px rgba(181, 181, 181, 0.3);
      border: 1px solid #d4d4d4;
    }

    .membership-sidebar-card {
      margin: 16px 16px 0;
      padding: 16px;
      background: linear-gradient(135deg, #faf6f0, #f3ebe0);
      border-radius: 16px;
      border: 1px solid rgba(184, 134, 58, 0.1);
      text-align: center;
    }
    .membership-sidebar-card .membership-tier {
      font-size: 0.7rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: #7b6b5a;
      font-weight: 600;
    }
    .membership-sidebar-card .tier-name {
      font-size: 1.2rem;
      font-weight: 700;
      margin: 4px 0;
    }
    .membership-sidebar-card .tier-name.gold-text { color: #b8863a; }
    .membership-sidebar-card .tier-name.silver-text { color: #8a8a8a; }
    .membership-sidebar-card .tier-name.bronze-text { color: #cd7f32; }
    .membership-sidebar-card .tier-name.platinum-text { color: #6b6b6b; }
    .membership-sidebar-card .membership-benefits {
      font-size: 0.75rem;
      color: #7b6b5a;
      margin-top: 6px;
    }

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
    }

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
  </style>
  
  @yield('page-css')
</head>
<body>
  <!-- Animated background layers -->
  <div class="dashboard-bg-layer"></div>
  <div class="dashboard-bg-pattern"></div>
  <div class="dashboard-ambient-glow glow-1"></div>
  <div class="dashboard-ambient-glow glow-2"></div>

  @include('layouts.partials.notifications')

  {{-- Devotee Sidebar --}}
  @include('devotee.layouts.sidebar')

  <div class="main-content">
    {{-- Devotee Topbar --}}
    @include('devotee.layouts.topbar')

    <div class="container-fluid px-4 py-4">
      @yield('content')
    </div>
  </div>

  <!-- LOGOUT CONFIRMATION MODAL -->
  <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" style="border-radius:24px; border:none; box-shadow:0 24px 48px rgba(0,0,0,0.08);">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold"><i class="bi bi-box-arrow-right me-2 text-danger"></i>Confirm Logout</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body pt-3">
          <p class="mb-0" style="font-weight: 450; color: #2d1f0e;">Are you sure you want to logout?</p>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal" style="background:#f0ece6; border:none; color:#1e1e2a;">Cancel</button>
          <button type="button" class="btn btn-danger rounded-pill px-4" id="confirmLogoutBtn" style="background:#b34a4a; border:none;">Logout</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <script>
    $(document).ready(function() {
      // Mobile sidebar toggle
      $('#menuToggle').on('click', function(e) {
        e.stopPropagation();
        $('#sidebar').toggleClass('show');
      });

      $(document).on('click', function(e) {
        if ($(window).width() <= 992) {
          if (!$('#sidebar').is(e.target) && $('#sidebar').has(e.target).length === 0 && !$('#menuToggle').is(e.target) && $('#menuToggle').has(e.target).length === 0) {
            $('#sidebar').removeClass('show');
          }
        }
      });

      // Logout modal behavior
      const logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));
      
      $('#sidebarLogoutBtn, #topbarLogoutBtn').on('click', function(e) {
        e.preventDefault();
        logoutModal.show();
      });

      $('#confirmLogoutBtn').on('click', function() {
        window.location.href = '{{ route('logout') }}';
      });
    });
  </script>

  @yield('page-js')
</body>
</html>
