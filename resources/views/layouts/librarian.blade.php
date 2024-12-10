<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - ROTC Library Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: rgba(0, 0, 0, 1);
            color: white;
            position: relative;
        }

        .logo {
            position: absolute;
            top: -30px;
            left: 20px;
            z-index: 2;
        }

        .logo img {
            height: 120px;
            width: auto;
        }

        .nav-links {
            display: flex;
            justify-content: center;
            flex-grow: 1;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            transition: background-color 0.3s;
            white-space: nowrap;
        }

        .nav-links a:hover {
            background-color: #00440a;
            border-radius: 5px;
        }

        .auth-buttons a.button:hover, 
        .auth-buttons button.button:hover {
            background-color: #007b00;
            color: white !important;
            text-decoration: none;
        }

        .footer {
            background-color: rgba(0, 0, 0, 1);
            color: white;
            text-align: center;
            padding: 10px 0;
            margin-top: auto;
        }

        .container {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .button {
            background-color: #800000;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: green;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-links a {
                width: 100%;
                text-align: center;
                padding: 10px 0;
            }
        }

        @media (max-width: 480px) {
            .navbar {
                padding: 5px 10px;
            }

            h1, h2 {
                font-size: 1.5rem;
            }

            .button {
                width: 100%;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <header class="navbar">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="ROTC Library Logo">
        </div>
        <nav class="nav-links">
            <a href="{{ route('librarian.welcome') }}">Home</a>
        </nav>
        <div class="auth-buttons">
            @auth
                <a href="{{ route('librarian.dashboard') }}" class="button">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="button">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="button">Login</a>
                <a href="{{ route('register-student') }}" class="button">Register</a>
            @endauth
        </div>
    </header>

    <main class="container">
        @yield('content')
    </main>

    <footer class="footer">
        <p>&copy; 2024 ROTC Library Management System. All rights reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
