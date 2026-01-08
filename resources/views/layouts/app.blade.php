<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- PWA META TAGS -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#2196F3">
    
    <!-- For iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Foodhouse">
    
    <!-- For Windows -->
    <meta name="msapplication-TileColor" content="#2196F3">
    
    <!-- For Chrome/Android -->
    <meta name="mobile-web-app-capable" content="yes">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Styles -->
    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }
        
        /* Install Button Style */
        #installButton {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
            border-radius: 50px;
            padding: 12px 24px;
            font-weight: bold;
        }
        
        /* PWA Install Prompt */
        .pwa-prompt {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            padding: 15px;
            z-index: 9998;
            display: none;
            border: 2px solid #2196F3;
        }
        
        .pwa-prompt img {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            margin-right: 15px;
        }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <!-- PWA Install Prompt (will show automatically) -->
    <div class="pwa-prompt d-flex align-items-center" id="pwaPrompt">
        <div class="d-flex align-items-center">
            <div class="bg-primary rounded-circle p-3 me-3">
                <span style="font-size: 24px;">🍔</span>
            </div>
            <div>
                <h6 class="mb-1">Install Foodhouse App</h6>
                <small class="text-muted">Get app-like experience</small>
            </div>
        </div>
        <button class="btn btn-primary ms-auto" id="pwaInstallBtn">Install</button>
        <button class="btn btn-outline-secondary ms-2" onclick="hidePwaPrompt()">Later</button>
    </div>

    <div id="app">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <span class="text-primary">🍔</span> {{ config('app.name', 'Foodhouse') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                                </li>
                            @endif
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Install Button (Floating) -->
    <button id="installButton" class="btn btn-primary" style="display: none;">
        📱 Install App
    </button>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- PWA Script -->
    <script>
        let deferredPrompt;
        const installButton = document.getElementById('installButton');
        const pwaPrompt = document.getElementById('pwaPrompt');
        const pwaInstallBtn = document.getElementById('pwaInstallBtn');
        
        // Register Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('Service Worker registered:', registration);
                    })
                    .catch(function(error) {
                        console.log('Service Worker registration failed:', error);
                    });
            });
        }
        
        // Handle PWA Install Prompt
        window.addEventListener('beforeinstallprompt', (e) => {
            console.log('PWA install prompt available');
            
            // Prevent Chrome 67 and earlier from automatically showing the prompt
            e.preventDefault();
            
            // Stash the event so it can be triggered later
            deferredPrompt = e;
            
            // Show our custom prompt
            showPwaPrompt();
            
            // Also show floating button
            if(installButton) {
                installButton.style.display = 'block';
                installButton.addEventListener('click', () => {
                    showInstallPrompt();
                });
            }
        });
        
        // Show install prompt
        function showInstallPrompt() {
            if(deferredPrompt) {
                deferredPrompt.prompt();
                
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                        hidePwaPrompt();
                        if(installButton) installButton.style.display = 'none';
                    } else {
                        console.log('User dismissed the install prompt');
                    }
                    deferredPrompt = null;
                });
            } else {
                // Fallback: Show instructions
                alert('To install Foodhouse App:\n\n' +
                      '1. Click the menu (⋮) in Chrome\n' +
                      '2. Select "Install Foodhouse"\n' +
                      '3. Or "Add to Home Screen"\n\n' +
                      'Make sure you are using:\n' +
                      '- Chrome browser\n' +
                      '- HTTPS or localhost\n' +
                      '- Not in incognito mode');
            }
        }
        
        // Show custom PWA prompt
        function showPwaPrompt() {
            if(pwaPrompt) {
                // Show after 3 seconds
                setTimeout(() => {
                    pwaPrompt.style.display = 'flex';
                }, 3000);
            }
        }
        
        // Hide custom prompt
        function hidePwaPrompt() {
            if(pwaPrompt) {
                pwaPrompt.style.display = 'none';
            }
        }
        
        // Manual install button click
        if(pwaInstallBtn) {
            pwaInstallBtn.addEventListener('click', showInstallPrompt);
        }
        
        // Check if already installed
        window.addEventListener('appinstalled', (evt) => {
            console.log('Foodhouse app was installed');
            hidePwaPrompt();
            if(installButton) installButton.style.display = 'none';
        });
        
        // Check PWA criteria on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('PWA Check:');
            console.log('- HTTPS:', window.location.protocol === 'https:');
            console.log('- Localhost:', window.location.hostname === 'localhost' || 
                window.location.hostname === '127.0.0.1');
            console.log('- Service Worker:', 'serviceWorker' in navigator);
            
            // If criteria met but no prompt, show manual button after 5 seconds
            setTimeout(() => {
                if(!deferredPrompt && installButton) {
                    installButton.style.display = 'block';
                    installButton.textContent = '📱 GET FOODHOUSE APP';
                    installButton.onclick = showInstallPrompt;
                }
            }, 5000);
        });
    </script>
    
    <!-- Your Scripts -->
    @stack('scripts')
</body>
</html>