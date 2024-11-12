<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MediVerse - Hospital Management System</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/logo/favicon.png" type="image/x-icon">

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/animate-3.7.0.css">
    <link rel="stylesheet" href="assets/css/font-awesome-4.7.0.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap-4.1.3.min.css">
    <link rel="stylesheet" href="assets/css/owl-carousel.min.css">
    <link rel="stylesheet" href="assets/css/jquery.datetimepicker.min.css">
    <link rel="stylesheet" href="assets/css/linearicons.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        /* Video Background Styling */
        .video-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            z-index: -1;
            /* Sends the video to the background */
        }

        .video-background video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Ensures the video covers the viewport */
        }
    </style>
</head>

<body>
    <!-- Preloader Starts -->
    <div class="preloader">
        <div class="spinner"></div>
    </div>
    <!-- Preloader End -->

    <!-- Header Area Starts -->
    <header class="header-area">
        <div id="header" id="home">
            <div class="container">
                <div class="row align-items-center justify-content-between d-flex">
                    <div id="logo">
                        <a href="index.php"></a>
                    </div>
                    <nav id="nav-menu-container">
                        <ul class="nav-menu">
                            <li class="menu-active"><a href="index.php" class="txt-white">Home</a></li>
                            <li><a href="backend/doc/index.php" class="txt-white">Doctor's Login</a></li>
                            <li><a href="backend/admin/index.php" class="txt-white">Administrator Login</a></li>
                        </ul>
                    </nav><!-- #nav-menu-container -->
                </div>
            </div>
        </div>
    </header>
    <!-- Header Area End -->

    <!-- Banner Area Starts -->
    <section class="">
        <div class="video-container">
            <video autoplay loop muted playsinline style="width: 100%;">
                <source src="./assets/images/vid.mp4" type="video/mp4">
                <!-- Alternative formats for compatibility -->
                Your browser does not support the video tag.
            </video>
        </div>
        <!-- <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h4>Caring for better life</h4>
                    <h1>Leading the way in medical excellence</h1>
                    <p>HMS is awarded as one of the Top Hospital Management System, which can integrate all the HIS systems, processes and machines into an intelligent information system to derive operational efficiency and assist hospitals in decision making process through MIS and Analytics.</p>
                </div>
            </div>
        </div> -->
    </section>




    <!-- Javascript -->
    <script src="assets/js/vendor/jquery-2.2.4.min.js"></script>
    <script src="assets/js/vendor/bootstrap-4.1.3.min.js"></script>
    <script src="assets/js/vendor/wow.min.js"></script>
    <script src="assets/js/vendor/owl-carousel.min.js"></script>
    <script src="assets/js/vendor/jquery.datetimepicker.full.min.js"></script>
    <script src="assets/js/vendor/jquery.nice-select.min.js"></script>
    <script src="assets/js/vendor/superfish.min.js"></script>
    <script src="assets/js/main.js"></script>

</body>

</html>