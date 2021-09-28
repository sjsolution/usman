<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Font Awesome Script -->
    <!-- <script src="https://kit.fontawesome.com/7ae40850c4.js" crossorigin="anonymous"></script> -->
    <!-- Font Awesome Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome/css/all.css') }}" />
    <!-- Bootstrap 4.6x CSS -->
    <!-- <link rel="stylesheet" href="css/bootstrap-4.6x/bootstrap.min.css" /> -->
    <!-- Bootstrap 5.0 beta 2 CSS -->
    <!-- <link rel="stylesheet" href="assets/css/bootstrap-5.0/bootstrap.min.css" /> -->
    <!-- Bootstrap 5.0 beta 2 RTL CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-5.0/bootstrap.rtl.min.css') }}" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    <title>@yield('title')</title>

    <style>
        .parallax {
            /* The image used */
            background-image: url("../img/parralax.png");

            /* Set a specific height */
            min-height: 400px;

            /* Create the parallax scrolling effect */
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>