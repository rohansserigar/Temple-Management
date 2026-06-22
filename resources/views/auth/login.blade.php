<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temple Management Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Playfair+Display:wght@400;600&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    
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

        .full-container {
            height: 100vh;
            display: flex;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('/images/mandir.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .left-side {
            height: 100vh;
            width: 50%;
            display: flex;
        }

        .right-side {
            height: 100vh;
            width: 50%;
            display: flex;
        }

        .login-card {
            background-color: antiquewhite;
            height: 550px;
            width: 500px;
            display: flex;
            margin: auto;
            align-items: center;
            border-radius: 20px;
            flex-direction: column;
            padding-top: 30px;
        }

        .temple-logo {
            width: 60px;
            height: 60px;
            margin: auto;
            border-radius: 50%;
            background: #ffd89a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
        }

        .login-title {
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
        }

        .role-badge {
            background: #ffdca5;
            border: 2px solid #ff0000;
            animation: borderColor 4s infinite;
            border-radius: 30px;
            padding: 10px 20px;
            font-weight: 600;
        }

        @keyframes borderColor {
            0%   { border-color: red; }
            25%  { border-color: blue; }
            50%  { border-color: green; }
            75%  { border-color: orange; }
            100% { border-color: red; }
        }

        .role-badge:hover {
            background: #ffd5d5;
        }

        .form-control {
            height: 50px;
            border-radius: 12px;
            width: 400px;
        }

        .btn-login {
            height: 50px;
            border: none;
            border-radius: 12px;
            background-color: #ff9900;
            color: rgb(255, 255, 255);
            font-weight: 600;
        }

        .btn-login:hover {
            background-color: rgb(255, 85, 0);
        }

        .alert-success {
            border-radius: 12px;
            padding: 10px 15px;
            font-size: 14px;
            width: 400px;
            text-align: center;
        }

        .alert-danger {
            border-radius: 12px;
            padding: 10px 15px;
            font-size: 14px;
            width: 400px;
            text-align: center;
        }

        .alert-danger ul {
            margin: 0;
            padding-left: 20px;
            text-align: left;
        }

        .alert-danger ul li {
            list-style: none;
        }

        .text-danger {
            font-size: 0.85rem;
        }
    </style>
</head>

<body>
    <nav id="navbar" class="navbar navbar-expand-lg py-3">
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
                    <li class="nav-item"><a class="nav-link active" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Pooja</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Donations</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Dashboard</a></li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-gold" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i> LOGIN
                        </a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-gold" href="{{ route('register') }}">
                            <i class="bi bi-person-plus me-1"></i> REGISTER
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="full-container">
        <div class="left-side"></div>
        <div class="right-side">
            <div class="login-card">
                <div class="temple-logo mt-3 mb-2">🛕</div>
                <div class="text-center">
                    <h3 class="login-title" id="loginTitle">
                        {{ old('role', 'Devotee') }} Login
                    </h3>
                    <div class="dropdown">
                        <button class="btn role-badge dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>
                            <span id="roleLabel">{{ old('role', 'Devotee') }}</span>
                        </button>
                        <ul class="dropdown-menu shadow border-0">
                            <li>
                                <a class="dropdown-item role-option" href="#" data-role="Devotee">🙏 Devotee</a>
                            </li>
                            <li>
                                <a class="dropdown-item role-option" href="#" data-role="Priest">🛕 Priest</a>
                            </li>
                            <li>
                                <a class="dropdown-item role-option" href="#" data-role="Trustee">👔 Trustee</a>
                            </li>
                            <li>
                                <a class="dropdown-item role-option" href="#" data-role="Staff">👨‍💼 Staff</a>
                            </li>
                            <li>
                                <a class="dropdown-item role-option" href="#" data-role="Accountant">💰 Accountant</a>
                            </li>
                            <li>
                                <a class="dropdown-item role-option" href="#" data-role="Admin">⚙️ Administrator</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- ========== DISPLAY ALL ERRORS ========== -->
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li><i class="bi bi-exclamation-circle me-1"></i> {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Success Message -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="mt-4">
                    @csrf
                    <input type="hidden" id="role" name="role" value="{{ old('role', 'Devotee') }}">

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" 
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="Enter Email ID" 
                               value="{{ old('email') }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Enter Password">
                            <button type="button" class="input-group-text" onclick="togglePassword()">
                                <i id="eyeIcon" class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- ========== DISPLAY ROLE ERROR ========== -->
                    @error('role')
                        <div class="mb-2">
                            <small class="text-danger">{{ $message }}</small>
                        </div>
                    @enderror

                    <div class="d-flex justify-content-between mb-3">
                        <a href="#">Forgot Password?</a>
                    </div>

                    <button class="btn btn-login w-100">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Login
                    </button>
                </form>

                <div class="text-center mt-4">
                    Don't have an account?
                    <a href="{{ route('register') }}">Register Here</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Role selection
        document.querySelectorAll('.role-option').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                let role = this.dataset.role;
                document.getElementById('role').value = role;
                document.getElementById('roleLabel').innerText = role;
                document.getElementById('loginTitle').innerText = role + ' Login';
            });
        });

        // Toggle password visibility
        function togglePassword() {
            let password = document.getElementById("password");
            let eyeIcon = document.getElementById("eyeIcon");

            if (password.type === "password") {
                password.type = "text";
                eyeIcon.classList.remove("bi-eye");
                eyeIcon.classList.add("bi-eye-slash");
            } else {
                password.type = "password";
                eyeIcon.classList.remove("bi-eye-slash");
                eyeIcon.classList.add("bi-eye");
            }
        }
    </script>
</body>
</html>