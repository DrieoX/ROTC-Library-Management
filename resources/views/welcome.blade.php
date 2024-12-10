@extends('layouts.app') {{-- Change this if your layout file is named differently --}}

@section('title', 'Welcome')

@section('content')
    <!-- Welcome Section -->
    <div class="welcome-header">
        <h1>Welcome to the ROTC Library Management System</h1>
    </div>
    <div class="welcome-subtext">
        <p>Your gateway to knowledge and resources.</p>
    </div>

    <!-- Book Carousel Section -->
    <div class="dashboard-container">
        <div class="form-section">
            <h4>Featured Books</h4>
            <div class="row">
                <!-- Book Carousel (Images Only) -->
                <div class="col-md-6">
                    <div id="bookCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="8000">
                        <div class="carousel-inner">
                            @foreach ($books as $index => $book)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" class="d-block w-100" alt="{{ $book->title }}" style="object-fit: contain; height: auto; max-height: 300px;">
                                </div>
                            @endforeach
                        </div>
                        <!-- Carousel Controls -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#bookCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#bookCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>

                <!-- Corresponding Book Details -->
                <div class="col-md-6">
                    @foreach ($books as $index => $book)
                        <div class="book-details {{ $index === 0 ? 'active' : 'd-none' }}" id="bookDetails-{{ $index }}">
                            <h4>{{ $book->title }}</h4>
                            <p><strong>Author:</strong> {{ $book->author }}</p>
                            <p><strong>Description:</strong> {{ Str::limit($book->description, 150, '...') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    /* Welcome Section Styling */
    .welcome-header {
        text-align: center;
        background-color: #800000;
        color: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 10px;
    }

    .welcome-subtext {
        text-align: center;
        background-color: #800000;
        color: white;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 10px;
    }

    /* Dashboard Container Styling */
    .dashboard-container {
        width: 100%;
        max-width: 1000px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin: 20px auto;
        height: 50%;
        max-height: 600px;
    }

    .dashboard-container h4 {
        text-align: center;
    }

    /* Carousel and Book Details Styling */
    .carousel-item img {
        object-fit: contain;
        height: auto;
        max-height: 600px;
        width: 100%;
        border-radius: 8px;
    }

    .book-details {
        background-color: rgba(0, 0, 0, 0.05); /* Light gray background */
        padding: 15px;
        border-radius: 8px;
        margin-top: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .book-details h4 {
        font-size: 16px;
        color: #333;
    }

    .book-details p {
        font-size: 14px;
        color: #555;
    }

    /* Mobile Adjustments */
    @media (max-width: 768px) {
        .carousel-item img {
            max-height: 200px;
        }

        .book-details {
            font-size: 0.9rem;
        }
    }

    /* Adjust Carousel Buttons */
    .carousel-control-prev, .carousel-control-next {
        width: 5%;
        z-index: 10;
        background-color: black; /* Set background to black */
    }

    .carousel-control-prev-icon, .carousel-control-next-icon {
        background-color: black; /* Set icon color to white to contrast with black background */
    }

</style>

<script>
    // Script to sync book details with the active carousel item
    document.addEventListener('DOMContentLoaded', function () {
        const carousel = document.querySelector('#bookCarousel');
        const bookDetails = document.querySelectorAll('.book-details');

        carousel.addEventListener('slide.bs.carousel', function (event) {
            // Hide all book details
            bookDetails.forEach(detail => detail.classList.add('d-none'));

            // Show the corresponding book detail
            const newIndex = event.to;
            document.querySelector(`#bookDetails-${newIndex}`).classList.remove('d-none');
        });
    });
</script>
