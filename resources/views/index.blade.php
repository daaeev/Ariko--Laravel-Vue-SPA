<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Ariko</title>
    <meta name="description" content="Ariko - Minimal Portfolio Template for Creatives">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png">

    <!-- STYLESHEETS -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}" type="text/css" media="all">
    <link rel="stylesheet" href="{{asset('css/all.min.css')}}" type="text/css" media="all">
    <link rel="stylesheet" href="{{asset('css/slick.css')}}" type="text/css" media="all">
    <link rel="stylesheet" href="{{asset('css/ionicons.min.css')}}" type="text/css" media="all">
    <link rel="stylesheet" href="{{asset('css/style.css')}}" type="text/css" media="all">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
    <!-- custom cursor -->
    <div id="cursor"></div>

    <!-- Preloader -->
    <div id="preloader">
        <div class="loading-area">
            <h3>ariko.</h3>
            <span>loading...</span>
        </div>
        <div class="left-side"></div>
        <div class="right-side"></div>
    </div>

    <div id="app"></div>

    <!-- Go to top button -->
    <a href="javascript:" id="return-to-top"><i class="ion-md-arrow-up"></i></a>

    <!-- SCRIPTS -->
    <script src="{{asset('js/jquery-1.12.3.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/masonry.pkgd.min.js')}}"></script>
    <script src="{{asset('js/imagesloaded.pkgd.min.js')}}"></script>
    <script src="{{asset('js/jquery.stellar.js')}}"></script>
    <script src="{{asset('js/infinite-scroll.min.js')}}"></script>
    <script src="{{asset('js/slick.min.js')}}"></script>
    <script src="{{asset('js/custom.js')}}"></script>

    <script src="{{ mix('js/app.js') }}"></script>
</body>

</html>
