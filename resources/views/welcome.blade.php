@extends('layouts.app') {{-- Change this if your layout file is named differently --}}

@section('title', 'Welcome')

@section('content')
    <div class="welcome-content">
        <h1>Welcome to the ROTC Library Management System</h1>
        <p>Your gateway to knowledge and resources.</p>
    </div>

    <!-- Book Carousel Section -->
    <div class="container mt-5">
        <h2>Featured Books</h2>

        <!-- Book Carousel -->
        <div id="bookCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach ($books as $index => $book)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <div class="row d-flex align-items-center">
                            <!-- Book Image Section -->
                            <div class="col-md-6">
                                <img src="{{ asset('storage/covers/' . $book->cover_image) }}" class="d-block w-100" alt="{{ $book->title }}">
                            </div>
                            <!-- Book Description Section -->
                            <div class="col-md-6">
                                <div class="carousel-caption-container">
                                    <h4>{{ $book->title }}</h4>
                                    <p><strong>Author:</strong> {{ $book->author }}</p>
                                    <p><strong>Description:</strong> {{ Str::limit($book->description, 200, '...') }}</p>
                                </div>
                            </div>
                        </div>
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
@endsection

<style>
    .welcome-content h1, .welcome-content p {
        background-color: rgba(0, 0, 0, 0.6); /* Semi-transparent black background */
        color: white; /* White text for contrast */
        border-radius: 8px; /* Rounded corners for a smoother look */
        text-align: center;
        padding: 20px;
    }

    /* Book Carousel Styles */
    .carousel-item img {
        object-fit: cover;
        max-height: 400px;
        width: 100%;
        border-radius: 8px;
    }

    .carousel-item {
        padding: 20px;
        background-color: #f8f9fa; /* Light background color for the carousel */
        border-radius: 8px; /* Rounded corners */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Slight shadow for depth */
    }

    .carousel-caption-container {
        background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent dark background */
        color: white;
        padding: 20px;
        border-radius: 8px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .carousel-control-prev-icon, .carousel-control-next-icon {
        background-color: black;
    }

    .row.d-flex {
        height: 100%;
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
    }

    .col-md-6 {
        flex: 1; /* Ensures both columns take equal width */
    }

    /* Mobile adjustments */
    @media (max-width: 768px) {
        .carousel-item img {
            max-height: 300px;
        }

        .carousel-caption-container {
            font-size: 0.9rem; /* Slightly smaller text for smaller screens */
        }
    }
</style>
