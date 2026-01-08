<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | Foodhouse</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        /* Animated Background - Moving Shapes */
        .background-animations {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
            background: linear-gradient(135deg, #ffecd2, #fcb69f);
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.6;
            pointer-events: none;
        }

        @keyframes move-diagonal {
            0% { transform: translate(0,0) rotate(0deg); opacity:0.6; }
            50% { opacity:0.3; }
            100% { transform: translate(1200px, -1200px) rotate(360deg); opacity:0.6; }
        }

        @keyframes move-diagonal-reverse {
            0% { transform: translate(0,0) rotate(0deg); opacity:0.5; }
            50% { opacity:0.2; }
            100% { transform: translate(-1200px, 1200px) rotate(-360deg); opacity:0.5; }
        }

        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        .register-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
        }

        .logo {
            width: 70px;
            height: 70px;
            margin: 0 auto 1rem;
            background: #e63946;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }

        .brand-title {
            text-align: center;
            font-weight: 700;
        }

        .brand-subtitle {
            text-align: center;
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 1.5rem;
        }

        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.65rem 0.75rem;
        }

        .btn-register {
            background: #e63946;
            color: #fff;
            border-radius: 8px;
            padding: 0.65rem;
            font-weight: 600;
        }

        .btn-register:hover {
            background: #d62839;
        }

        .footer-links {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.85rem;
        }

        .footer-links a {
            color: #e63946;
            text-decoration: none;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        .role-note {
            font-size: 0.8rem;
            color: #6c757d;
        }
    </style>
</head>
<body>

<!-- Animated Background -->
<div class="background-animations">
    <!-- Large shapes -->
    <div class="shape" style="width:100px; height:100px; background: rgba(230,57,70,0.3); left:5%; top:10%; animation: move-diagonal 30s linear infinite;"></div>
    <div class="shape" style="width:150px; height:150px; background: rgba(0,123,255,0.2); left:70%; top:20%; animation: move-diagonal-reverse 35s linear infinite;"></div>
    
    <!-- Medium shapes -->
    <div class="shape" style="width:60px; height:60px; background: rgba(255,193,7,0.25); left:40%; top:60%; animation: move-diagonal 25s linear infinite;"></div>
    <div class="shape" style="width:80px; height:80px; background: rgba(40,167,69,0.3); left:80%; top:70%; animation: move-diagonal-reverse 28s linear infinite;"></div>
    
    <!-- Small shapes -->
    <div class="shape" style="width:30px; height:30px; background: rgba(123,31,162,0.2); left:20%; top:50%; animation: move-diagonal 20s linear infinite;"></div>
    <div class="shape" style="width:40px; height:40px; background: rgba(255,87,34,0.3); left:60%; top:30%; animation: move-diagonal-reverse 22s linear infinite;"></div>
</div>

<div class="register-wrapper">
    <div class="register-card">

        <!-- Logo -->
        <div class="logo">
            <i class="fas fa-utensils"></i>
        </div>

        <h4 class="brand-title">Foodhouse</h4>
        <p class="brand-subtitle">Create a new account</p>

        @if ($errors->any())
            <div class="alert alert-danger small">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label small">Full Name</label>
                <input type="text" name="full_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label small">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label small">Phone Number</label>
                <input type="text" name="phone_number" class="form-control" required>
            </div>

            <!-- Role Selection -->
            <div class="mb-3">
                <label class="form-label small">Register As</label>
                <select name="role" id="role" class="form-select" required>
                    <option value="customer" selected>Customer</option>
                    <option value="cashier">Cashier</option>
                    <option value="admin">Admin</option>
                </select>
                <div class="role-note mt-1">
                    Admin & Cashier require a special access key.
                </div>
            </div>

            <!-- Secret Key -->
            <div class="mb-3 d-none" id="secretKeyField">
                <label class="form-label small">Access Key</label>
                <input type="password" name="access_key" class="form-control"
                       placeholder="Enter special key">
            </div>

            <div class="mb-3">
                <label class="form-label small">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label small">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-register w-100">
                Create Account
            </button>
        </form>

        <div class="footer-links">
            Already have an account?
            <a href="{{ route('login') }}">Sign in</a>
        </div>

    </div>
</div>

<script>
    const roleSelect = document.getElementById('role');
    const secretKeyField = document.getElementById('secretKeyField');

    roleSelect.addEventListener('change', function () {
        if (this.value === 'admin' || this.value === 'cashier') {
            secretKeyField.classList.remove('d-none');
        } else {
            secretKeyField.classList.add('d-none');
        }
    });
</script>

</body>
</html>
