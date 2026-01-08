<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Foodhouse</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#e63946">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Foodhouse">
    <meta name="mobile-web-app-capable" content="yes">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        /* Animated Background - Moving Shapes */
        .background-animations {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: -1; overflow: hidden;
            background: linear-gradient(135deg, #ffecd2, #fcb69f);
        }
        .shape { position:absolute; border-radius:50%; opacity:0.6; pointer-events:none; }
        @keyframes move-diagonal { 0%{transform:translate(0,0) rotate(0deg);opacity:0.6;} 50%{opacity:0.3;} 100%{transform:translate(1200px,-1200px) rotate(360deg);opacity:0.6;} }
        @keyframes move-diagonal-reverse { 0%{transform:translate(0,0) rotate(0deg);opacity:0.5;} 50%{opacity:0.2;} 100%{transform:translate(-1200px,1200px) rotate(-360deg);opacity:0.5;} }

        body { background:#f8f9fa; font-family:'Segoe UI', system-ui, sans-serif; }
        .login-wrapper { min-height:100vh; display:flex; align-items:center; justify-content:center; padding:1rem; }
        .login-card { width:100%; max-width:450px; background:#fff; border-radius:12px; padding:2rem; box-shadow:0 10px 30px rgba(0,0,0,0.08); position:relative; }
        .pwa-badge { position:absolute; top:-10px; right:20px; background:#e63946; color:#fff; padding:4px 12px; border-radius:20px; font-size:0.75rem; font-weight:bold; display:flex; align-items:center; gap:5px; }
        .logo { width:70px; height:70px; margin:0 auto 1rem; background:#e63946; color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.8rem; }
        .brand-title { text-align:center; font-weight:700; margin-bottom:0.25rem; color:#333; }
        .brand-subtitle { text-align:center; font-size:0.9rem; color:#6c757d; margin-bottom:1.5rem; }
        .form-control { border-radius:8px; padding:0.65rem 0.75rem; border:1px solid #ddd; }
        .form-control:focus { border-color:#e63946; box-shadow:0 0 0 0.25rem rgba(230,57,70,0.25); }
        .btn-login { background:#e63946; color:#fff; border-radius:8px; padding:0.75rem; font-weight:600; border:none; transition:all 0.3s; }
        .btn-login:hover { background:#d62839; transform:translateY(-2px); box-shadow:0 5px 15px rgba(230,57,70,0.3); }
        .footer-links { text-align:center; margin-top:1.25rem; font-size:0.85rem; color:#6c757d; }
        .footer-links a { color:#e63946; text-decoration:none; font-weight:500; }
        .footer-links a:hover { text-decoration:underline; }
        .mobile-only { display:none; }
        @media (max-width:768px) { .mobile-only { display:block; } .login-card { padding:1.5rem; margin:1rem; } }
        #floatingInstallBtn { position:fixed; bottom:20px; right:20px; z-index:1000; background:#e63946; color:#fff; border:none; border-radius:50px; padding:12px 20px; font-weight:bold; box-shadow:0 4px 12px rgba(230,57,70,0.4); display:flex; align-items:center; gap:8px; display:none; }
        .alert-small { font-size:0.85rem; margin-bottom:0.75rem; }
    </style>
</head>
<body>

<!-- Animated Background -->
<div class="background-animations">
    <div class="shape" style="width:100px;height:100px;background:rgba(230,57,70,0.3);left:5%;top:10%;animation:move-diagonal 30s linear infinite;"></div>
    <div class="shape" style="width:150px;height:150px;background:rgba(0,123,255,0.2);left:70%;top:20%;animation:move-diagonal-reverse 35s linear infinite;"></div>
    <div class="shape" style="width:60px;height:60px;background:rgba(255,193,7,0.25);left:40%;top:60%;animation:move-diagonal 25s linear infinite;"></div>
    <div class="shape" style="width:80px;height:80px;background:rgba(40,167,69,0.3);left:80%;top:70%;animation:move-diagonal-reverse 28s linear infinite;"></div>
    <div class="shape" style="width:30px;height:30px;background:rgba(123,31,162,0.2);left:20%;top:50%;animation:move-diagonal 20s linear infinite;"></div>
    <div class="shape" style="width:40px;height:40px;background:rgba(255,87,34,0.3);left:60%;top:30%;animation:move-diagonal-reverse 22s linear infinite;"></div>
</div>

<div class="login-wrapper">
    <div class="login-card">

        <!-- PWA Badge -->
        <div class="pwa-badge">
            <i class="fas fa-bolt"></i> PWA READY
        </div>

        <!-- Logo -->
        <div class="logo">
            <i class="fas fa-utensils"></i>
        </div>

        <h4 class="brand-title">Foodhouse</h4>
        <p class="brand-subtitle">Smart Ordering & Inventory System</p>

        @if ($errors->any())
            <div class="alert alert-danger alert-small">
                @foreach ($errors->all() as $error)
                    <div><i class="fas fa-exclamation-circle"></i> {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label small fw-bold">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check small">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <!-- Functional Forgot Password Link -->
                @if (Route::has('password.request'))
                    <a class="small text-decoration-none" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn btn-login w-100">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>

        <div class="footer-links">
            <span>Don't have an account?</span>
            <a href="{{ route('register') }}">Register here</a>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
