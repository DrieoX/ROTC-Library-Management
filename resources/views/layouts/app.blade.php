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
            background: url('images/background.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-x: hidden; /* Prevent horizontal scroll */
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: rgba(0, 95, 15, 0.8); /* Transparent green */
            color: white;
            flex-wrap: wrap; /* Allow wrapping for smaller screens */
        }

        .navbar .nav-links {
            display: flex;
            justify-content: center;
            flex-grow: 1;
            flex-wrap: wrap; /* Allow wrapping for smaller screens */
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            transition: background-color 0.3s;
            white-space: nowrap; /* Prevent link text from wrapping */
        }

        .navbar a:hover {
            background-color: #00440a;
            border-radius: 5px;
        }

        .footer {
            background-color: rgba(0, 51, 0, 0.8); /* Transparent dark green */
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
            width: 100%; /* Allow full width */
            max-width: 1200px; /* Add a max width for larger screens */
            margin: 0 auto; /* Center container */
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

        /* Custom modal styling */
        .custom-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 600px; /* Max width for modals */
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            z-index: 1000;
            overflow-y: auto;
            height: auto; /* Let height be automatic */
            max-height: 80vh; /* Limit max height */
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

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column; /* Stack navbar items vertically on small screens */
                align-items: flex-start; /* Align items to the start */
            }

            .navbar a {
                width: 100%; /* Make links full width */
                text-align: center; /* Center align text */
                padding: 10px 0; /* More vertical padding */
            }
        }

        @media (max-width: 480px) {
            .navbar {
                padding: 5px 10px; /* Reduce padding for small screens */
            }

            h1, h2 {
                font-size: 1.5rem; /* Smaller headings */
            }

            .button {
                width: 100%; /* Make buttons full width */
                padding: 8px; /* Reduce padding */
            }
        }
    </style>
</head>
<body>
    <header class="navbar">
        <div class="logo">
            <h2>ROTC Library</h2>
        </div>
        <nav class="nav-links">
            <a href="#" onclick="navigateToHome()">Home</a>
            <a href="#" onclick="toggleModal('aboutModal')">About</a>
            <a href="#" onclick="toggleModal('booksModal')">Books</a>
            <a href="#" onclick="toggleModal('updatesModal')">Updates</a>
            @auth
                <a href="#" onclick="toggleModal('achievementsModal')" class="button">Achievements</a>
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
                <a href="/login" class="button">Login</a>
                <a href="/register/student" class="button">Register</a>
            @endauth
        </div>
    </header>

    <main class="container">
        @yield('content')
    </main>

    <footer class="footer">
        <p>&copy; 2024 ROTC Library Management System. All rights reserved.</p>
    </footer>

    <!-- Custom Modals -->
    <div id="aboutModal" class="custom-modal">
        <div class="custom-modal-header">
            <h5>About</h5>
            <button class="close-btn" onclick="toggleModal('aboutModal')">&times;</button>
        </div>
        <div class="modal-body">
            @include('modals.about')
        </div>
    </div>

    <div id="booksModal" class="custom-modal">
        <div class="custom-modal-header">
            <h5>Books</h5>
            <button class="close-btn" onclick="toggleModal('booksModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="bookFilterForm">
                <input type="text" id="bookSearch" name="bookSearch" placeholder="Search by title..." />
                <select id="genreFilter" name="genre">
                    <option value="">All Genres</option>
                    <option value="fiction">Fiction</option>
                    <option value="non-fiction">Non-fiction</option>
                    <!-- More genres -->
                </select>
                <button type="submit" class="button">Search</button>
            </form>
            <div id="bookResults">
                <!-- Book results will be rendered here -->
            </div>
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

    <!-- Scripts -->
    <!-- Scripts -->
<script>
    // Toggle modal functionality
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal.classList.contains('active')) {
            // If modal is open, close it
            modal.classList.remove('active');
        } else {
            // Close any other open modals before opening the new one
            document.querySelectorAll('.custom-modal').forEach(m => m.classList.remove('active'));
            // Open the selected modal
            modal.classList.add('active');
        }
    }

    // Close modal when clicking outside of it
    window.onclick = function(event) {
        const modals = document.querySelectorAll('.custom-modal');
        modals.forEach(modal => {
            if (event.target === modal) {
                modal.classList.remove('active');
            }
        });
    };

    // Example homepage navigation function
    function navigateToHome() {
        window.location.href = '/';
    }
</script>
</body>
</html>
