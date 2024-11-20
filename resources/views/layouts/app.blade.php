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
            background: url('{{ asset('images/background.jpg') }}') no-repeat center center fixed;
            background-size: cover;
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
            background-color: rgba(0, 0, 0, 0.8);
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
            background-color: green;
            color: white !important;
            text-decoration: none;
        }

        .footer {
            background-color: rgba(0, 0, 0, 0.8);
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

        /* Custom modal styling */
        .custom-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 600px;
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            z-index: 1000;
            overflow-y: auto;
            display: none;
        }

        .custom-modal.active {
            display: block;
        }

        .custom-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .custom-modal .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #888;
            cursor: pointer;
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

        /* Book Listing Styles */
        .book-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .book-item {
            background-color: rgba(255, 255, 255, 0.8); /* Transparent white background */
            color: black; /* Black font color */
            padding: 20px;
            border-radius: 8px; /* Rounded corners */
            width: 250px; /* Adjust width as necessary */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Optional: add shadow for better contrast */
        }

        .book-item h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .book-item p {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            text-decoration: none;
            color: #007bff;
        }

        .pagination a:hover {
            text-decoration: underline;
        }

        .pagination .active {
            font-weight: bold;
        }

    </style>
</head>
<body>
    <header class="navbar">
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="ROTC Library Logo">
        </div>
        <nav class="nav-links">
            <a href="{{ url('/') }}">Home</a>
            <a href="{{ url('#') }}" onclick="toggleModal('aboutModal')">About</a>
            <a href="{{ route('books.list') }}">Books</a>
            <a href="{{ url('#') }}" onclick="toggleModal('updatesModal')">Updates</a>
            @auth
                <a href="{{ url('#') }}" onclick="toggleModal('achievementsModal')" class="button">Achievements</a>
            @else
                <a href="{{ route('login') }}" class="button">Login</a>
            @endauth
        </nav>
        <div class="auth-buttons">
            @auth
                <a href="{{ route('dashboard') }}" class="button">Dashboard</a>
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

    <!-- Modals -->
    <div id="aboutModal" class="custom-modal">
        <div class="custom-modal-header">
            <h5>About</h5>
            <button class="close-btn" onclick="toggleModal('aboutModal')">&times;</button>
        </div>
        <div class="modal-body">
            @include('modals.about')
        </div>
    </div>

    <div id="updatesModal" class="custom-modal">
        <div class="custom-modal-header">
            <h5>Updates</h5>
            <button class="close-btn" onclick="toggleModal('updatesModal')">&times;</button>
        </div>
        <div class="modal-body">
            @include('modals.updates')
        </div>
    </div>

    <div id="achievementsModal" class="custom-modal">
        <div class="custom-modal-header">
            <h5>Achievements</h5>
            <button class="close-btn" onclick="toggleModal('achievementsModal')">&times;</button>
        </div>
        <div class="modal-body">
            @include('modals.achievements')
        </div>
    </div>

    <script>
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('active');
        }

        window.onclick = function(event) {
            document.querySelectorAll('.custom-modal').forEach(modal => {
                if (event.target === modal) {
                    modal.classList.remove('active');
                }
            });
        };

        function navigateToHome() {
            window.location.href = '/';
        }
    </script>
</body>
</html>
