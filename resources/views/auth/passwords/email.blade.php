<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password | Foodhouse</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        /* Animated Background - Moving Shapes */
        .background-animations { position:fixed; top:0; left:0; width:100%; height:100%; z-index:-1; overflow:hidden; background: linear-gradient(135deg,#ffecd2,#fcb69f); }
        .shape { position:absolute; border-radius:50%; opacity:0.6; pointer-events:none; }
        @keyframes move-diagonal { 0%{transform:translate(0,0) rotate(0deg);opacity:0.6;} 50%{opacity:0.3;} 100%{transform:translate(1200px,-1200px) rotate(360deg);opacity:0.6;} }
        @keyframes move-diagonal-reverse { 0%{transform:translate(0,0) rotate(0deg);opacity:0.5;} 50%{opacity:0.2;} 100%{transform:translate(-1200px,1200px) rotate(-360deg);opacity:0.5;} }

        body { background:#f8f9fa; font-family:'Segoe UI',system-ui,sans-serif; }
        .reset-wrapper { min-height:100vh; display:flex; align-items:center; justify-content:center; padding:1rem; }
        .reset-card { width:100%; max-width:450px; background:#fff; border-radius:12px; padding:2rem; box-shadow:0 10px 30px rgba(0,0,0,0.08); position:relative; }
        .logo { width:70px;height:70px;margin:0 auto 1rem;background:#e63946;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.8rem; }
        .brand-title { text-align:center; font-weight:700; margin-bottom:0.25rem; color:#333; }
        .brand-subtitle { text-align:center; font-size:0.9rem; color:#6c757d; margin-bottom:1.5rem; }
        .form-control { border-radius:8px; padding:0.65rem 0.75rem; border:1px solid #ddd; }
        .form-control:focus { border-color:#e63946; box-shadow:0 0 0 0.25rem rgba(230,57,70,0.25); }
        .btn-reset { background:#e63946; color:#fff; border-radius:8px; padding:0.75rem; font-weight:600; border:none; transition:all 0.3s; width:100%; }
        .btn-reset:hover { background:#d62839; transform:translateY(-2px); box-shadow:0 5px 15px rgba(230,57,70,0.3); }
        .footer-links { text-align:center; margin-top:1rem; font-size:0.85rem; color:#6c757d; }
        .footer-links a { color:#e63946; text-decoration:none; font-weight:500; }
        .footer-links a:hover { text-decoration:underline; }
        .alert-small { font-size:0.85rem; margin-bottom:0.75rem; }
    </style>
</head>
<body>

<!-- Animated Background -->
<div class="background-animations">
    <div class="shape" style="width:100px;height:100px;background:rgba(230,57,70,0.3);left:10%;top:15%;animation:move-diagonal 30s linear infinite;"></div>
    <div class="shape" style="width:120px;height:120px;background:rgba(0,123,255,0.2);left:70%;top:25%;animation:move-diagonal-reverse 35s linear infinite;"></div>
    <div class="shape" style="width:60px;height:60px;background:rgba(255,193,7,0.25);left:40%;top:60%;animation:move-diagonal 25s linear infinite;"></div>
</div>

<div class="reset-wrapper">
    <div class="reset-card">

        <!-- Logo -->
        <div class="logo">
            <i class="fas fa-utensils"></i>
        </div>

        <h4 class="brand-title">Foodhouse</h4>
        <p class="brand-subtitle">Reset Your Password</p>

        @if (session('status'))
            <div class="alert alert-success alert-small">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-small">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <!-- Password Reset Form -->
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label small fw-bold">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>

            <button type="submit" class="btn btn-reset">
                <i class="fas fa-envelope"></i> Send Password Reset Link
            </button>
        </form>

        <div class="footer-links">
            <span>Back to login?</span>
            <a href="{{ route('login') }}">Sign in</a>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
