<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - ROTC Library Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #005f0f;
            color: white;
        }

        .navbar .nav-links {
            display: flex;
            justify-content: center;
            flex-grow: 1;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            transition: background-color 0.3s;
        }

        .navbar a:hover {
            background-color: #00440a;
            border-radius: 5px;
        }

        .footer {
            background-color: #003300;
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
        }

        h1, h2 {
            color: #B8860B;
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
            background-color: #660000;
        }
    </style>
</head>
<body>
    <header class="navbar">
        <div class="logo">
            <h2>ROTC Library</h2>
        </div>
        <div class="auth-buttons">
            @auth
                <a href="{{ route('dashboard') }}" class="button">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="button">Logout</button>
                </form>
            @else
                <a href="/login" class="button">Login</a>
                <a href="/register/librarian" class="button">Register</a>
            @endauth
        </div>
    </header>

    <main class="container">
        @yield('content')
    </main>

    <footer class="footer">
        <p>&copy; 2024 ROTC Library Management System. All rights reserved.</p>
    </footer>
</body>
</html>
