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

        /* Custom modal styling */
        .custom-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            width: 1800px;
            max-width: 2000px;
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            z-index: 1000;
            overflow-y: auto;
            height: 80vh;
            max-height: 90vh;
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
                <a href="{{ route('login') }}" class="button">Login to View Achievements</a>
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
    <script>
        // Toggle modal functionality
        function toggleModal(modalId) {
            // Close all modals
            const modals = document.querySelectorAll('.custom-modal');
            modals.forEach(modal => {
                modal.classList.remove('active');
            });
            
            // Open the clicked modal
            const modal = document.getElementById(modalId);
            modal.classList.toggle('active');
        }

        // Retain book modal state and search functionality
        document.getElementById('bookFilterForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting and reloading the page

            const searchQuery = document.getElementById('bookSearch').value;
            const genre = document.getElementById('genreFilter').value;

            // Make an AJAX request to search for books (use your Laravel route here)
            fetch('/search-books', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token
                },
                body: JSON.stringify({
                    search: searchQuery,
                    genre: genre
                })
            })
            .then(response => response.json())
            .then(data => {
                // Render the search results in the #bookResults div
                const bookResultsDiv = document.getElementById('bookResults');
                bookResultsDiv.innerHTML = ''; // Clear previous results

                if (data.length > 0) {
                    data.forEach(book => {
                        const bookItem = document.createElement('div');
                        bookItem.textContent = `${book.title} by ${book.author}`;
                        bookResultsDiv.appendChild(bookItem);
                    });
                } else {
                    bookResultsDiv.textContent = 'No books found.';
                }
            })
            .catch(error => console.error('Error:', error));
        });

        // Handle "Home" navigation without page refresh
        function navigateToHome() {
            // Close all modals
            const modals = document.querySelectorAll('.custom-modal');
            modals.forEach(modal => {
                modal.classList.remove('active');
            });

            // You can add any other logic to refresh content without reloading the page here
            // e.g., if you need to fetch home page content dynamically, use AJAX here
            console.log('Navigated to Home.');
        }
    </script>
</body>
</html>
