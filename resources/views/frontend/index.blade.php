<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    
    <title>TEMPLE MANAGEMENT SYSTEM</title>

    <style>
        .gold-accent {
            color: #b8863a;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8f3ec;
            color: #2d2a24;
        }

        nav {
            background: linear-gradient(to right, rgb(255, 222, 222), rgb(227, 227, 255));
        }

        .btn-gold {
            background: #b8863a;
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 0.7rem 2rem;
            border-radius: 60px;
            transition: 0.2s;
        }
        .btn-gold:hover {
            background: #a0732f;
            color: #fff;
            transform: scale(1.01);
            box-shadow: 0 8px 18px rgba(184, 134, 58, 0.25);
        }
        .btn-outline-gold {
            border: 1.5px solid #b8863a;
            color: #b8863a;
            background: transparent;
            font-weight: 600;
            padding: 0.7rem 2rem;
            border-radius: 60px;
            transition: 0.2s;
        }
        .btn-outline-gold:hover {
            background: #b8863a;
            color: #fff;
            box-shadow: 0 8px 18px rgba(184, 134, 58, 0.15);
        }
        .nav-link {
            color: #3f352b;
            font-weight: 500;
            margin: 0 0.5rem;
            border-radius: 40px;
            padding: 0.5rem 1.2rem;
        }
        .nav-link:hover {
            background: rgba(184, 134, 58, 0.08);
            color: #b8863a;
        }
        .nav-link.active {
            background: #b8863a;
            color: #fff !important;
        }
    </style>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" 
            crossorigin="anonymous">
    </script>

    <nav class="navbar navbar-expand-lg py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4 d-flex align-items-center" href="{{ route('home') }}">
                <span class="gold-accent">Temple</span><span class="text-dark">Manager</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" 
                    aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Pooja</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Donations</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Dashboard</a></li>
                    
                    <!-- ========== FIXED: LOGIN BUTTON ========== -->
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-gold" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i> LOGIN
                        </a>
                    </li>
                    
                    <!-- ========== FIXED: REGISTER BUTTON ========== -->
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-gold" href="{{ route('register') }}">
                            <i class="bi bi-person-plus me-1"></i> REGISTER
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section / Content -->
    <div class="container mt-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold">
                    Welcome to <span class="gold-accent">Temple Manager</span>
                </h1>
                <p class="lead text-muted">
                    Manage temple activities, book poojas, make donations, and connect with the divine.
                </p>
                <div class="mt-4">
                    <!-- ========== FIXED: GET STARTED BUTTON ========== -->
                    <a href="{{ route('register') }}" class="btn btn-gold btn-lg me-3">
                        <i class="bi bi-person-plus me-2"></i> Get Started
                    </a>
                    <!-- ========== FIXED: LOGIN BUTTON ========== -->
                    <a href="{{ route('login') }}" class="btn btn-outline-gold btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Login
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://images.unsplash.com/photo-1582510003544-4d00b7f74220?w=600&h=400&fit=crop" 
                     alt="Temple" class="img-fluid rounded-4 shadow-lg">
            </div>
        </div>
    </div>
</body>
</html>