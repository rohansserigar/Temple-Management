<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devotee Registration</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

    <style>
        body {
            background: #f8f5ef;
        }

        .register-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,.1);
            margin-top: 80px;
        }

        .page-title {
            color: #b45309;
            font-weight: 700;
        }

        .btn-register {
            background: #d97706;
            border: none;
        }

        .btn-register:hover {
            background: #b45309;
        }

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
        #navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 999;
            transition: all .8s ease;
            backdrop-filter: blur(80px);
        }

        .alert-success {
            border-radius: 12px;
            padding: 10px 15px;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" 
            crossorigin="anonymous">
    </script>

    <nav id="navbar" class="navbar navbar-expand-lg py-3">
        <div class="container">
            <!-- ========== FIXED: Home link ========== -->
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

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card register-card">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h2 class="page-title">🛕 Devotee Registration</h2>
                            <p class="text-muted">
                                Create your account to book poojas and make donations.
                            </p>
                        </div>

                        <!-- Success Message -->
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- ========== FIXED: Form action ========== -->
                        <form method="POST" action="{{ route('register.post') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text"
                                       name="name"
                                       value="{{ old('name') }}"
                                       class="form-control @error('name') is-invalid @enderror"
                                       placeholder="Enter Full Name">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       class="form-control @error('email') is-invalid @enderror"
                                       placeholder="Enter Email">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mobile Number</label>
                                <input type="text"
                                       name="mobile"
                                       maxlength="10"
                                       value="{{ old('mobile') }}"
                                       class="form-control @error('mobile') is-invalid @enderror"
                                       placeholder="Enter Mobile Number">
                                @error('mobile')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" name="dob" value="{{ old('dob') }}" class="form-control @error('dob') is-invalid @enderror" required>
                                    @error('dob')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="3">{{ old('address') }}</textarea>
                                @error('address')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Gothra</label>
                                    <input type="text" name="gothra" value="{{ old('gothra') }}" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nakshatra</label>
                                    <input type="text" name="nakshatra" value="{{ old('nakshatra') }}" class="form-control">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password"
                                           id="password"
                                           name="password"
                                           class="form-control @error('password') is-invalid @enderror">
                                    <span class="input-group-text"
                                          onclick="togglePassword('password',this)"
                                          style="cursor:pointer">
                                        <i class="bi bi-eye"></i>
                                    </span>
                                </div>
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password"
                                           id="confirmPassword"
                                           name="password_confirmation"
                                           class="form-control">
                                    <span class="input-group-text"
                                          onclick="togglePassword('confirmPassword',this)"
                                          style="cursor:pointer">
                                        <i class="bi bi-eye"></i>
                                    </span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-register text-white w-100">
                                Register
                            </button>
                        </form>

                        <!-- ========== FIXED: Login link ========== -->
                        <div class="text-center mt-3">
                            Already have an account?
                            <a href="{{ route('login') }}">Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let lastScroll = 0;
        const navbar = document.getElementById("navbar");

        window.addEventListener("scroll", () => {
            let currentScroll = window.pageYOffset;

            if(currentScroll <= 100){
                navbar.style.top = "0";
                return;
            }

            if(currentScroll > lastScroll){
                navbar.style.top = "-100px";
            }else{
                navbar.style.top = "0";
            }

            lastScroll = currentScroll;
        });
    </script>

    <script>
        function togglePassword(id, element) {
            const input = document.getElementById(id);
            const icon = element.querySelector("i");

            if(input.type === "password") {
                input.type = "text";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            }
        }
    </script>
</body>
</html>