@extends('front.layouts.app')

@section('content')


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PeacePay - Landing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        /* Navbar */
        .navbar {
            padding: 1rem 2rem;
            background: #ffffff; /* White navbar */
            box-shadow: 0px 2px 10px rgba(0,0,0,0.05);
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: #0d6efd !important; /* Blue brand text */
        }
        .btn-login {
            font-weight: 500;
        }
        /* Hero Section */
        .hero {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background: linear-gradient(to right, #0d6efd, #6610f2);
            color: white;
        }
        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
        }
        /* Sections */
        .section {
            padding: 80px 0;
        }
        .section h2 {
            font-weight: 700;
            margin-bottom: 20px;
        }
        /* Footer */
        footer {
            background: #0d6efd;
            color: white;
            padding: 40px 0;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">  <img style="width:155px" src="{{ config('custom.public_path') . '/adminAssets/assets/images/logo-full.png' }}" alt=""
                    class="logo logo-lg" />
              </a>
            <div class="ms-auto">
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login</a>
            </div>
        </div>
    </nav>

    <!-- Hero Carousel -->
    <div id="heroCarousel" class="carousel slide hero" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div>
                    <h1>Welcome to PeacePay</h1>
                    <p class="lead">Seamless & Secure Payments for Everyone</p>
                </div>
            </div>
            <div class="carousel-item">
                <div>
                    <h1>Fast & Reliable</h1>
                    <p class="lead">Experience next-gen payment solutions</p>
                </div>
            </div>
            <div class="carousel-item">
                <div>
                    <h1>Trusted by Thousands</h1>
                    <p class="lead">Join a growing network of happy customers</p>
                </div>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <section class="section text-center">
        <div class="container">
            <h2 class="mb-4">Why Choose PeacePay?</h2>
            <p class="lead">We bring you a safe, fast, and reliable way to manage your payments online. Whether youâ€™re a business or an individual, PeacePay has the tools you need.</p>
        </div>
    </section>

    <!-- Features Section -->
    <section class="section bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <h4>ðŸ”’ Secure</h4>
                    <p>Your transactions are fully encrypted and safe.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h4>âš¡ Fast</h4>
                    <p>Payments are processed instantly without delays.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h4>ðŸŒ Global</h4>
                    <p>Send and receive money across the world with ease.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="section text-center">
        <div class="container">
            <h2>Start Using PeacePay Today</h2>
            <p class="lead">Sign up now and take control of your payments with our secure platform.</p>
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg mt-3">Get Started</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <p>&copy; {{ date('Y') }} PeacePay. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>




@endsection

