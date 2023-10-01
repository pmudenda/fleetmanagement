<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ZESCO FLEET MASTER:: Login </title>

    <link rel="shortcut icon" href="{{asset('assets/dist/img/icons/logo.png')}}"
          type="image/x-icon">

    <link href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/plugins/fontawesome/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/plugins/themify-icons/themify-icons.css')}}" rel="stylesheet">
    <link href="{{asset('assets/dist/css/login.css')}} " rel="stylesheet" type="text/css">
</head>
<body class="bg-white body-bg">
<main class="register-content">
    <div class="bg-img-hero position-fixed top-0 right-0 left-0">
        <figure class="position-absolute right-0 bottom-0 left-0 m-0">
            <img src="{{asset('assets/dist/img/icons/zesco_logo.png')}}"
                 alt="Company Logo"
                 data-pagespeed-url-hash="2593638024"/>
        </figure>
    </div>
    <div class="container py-5 py-sm-7">
        <a class="d-flex justify-content-center mb-5 news365-logo" href="">
            <img class="z-index-2" src="{{asset('assets/dist/img/icons/zesco_logo.png')}}" alt="Image Description"
                 data-pagespeed-url-hash="799927880">
        </a>

        @if(env('APP_ENV') == "local")
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="alert alert-danger text-center">
                        <h1>UAT</h1>
                    </div>
                </div>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="">
                </div>
                <div class="form-card mb-2">
                    <div class="form-card_body">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="{{asset('assets/plugins/jquery/jquery-3.6.3.js')}}"></script>

<script src="{{asset('assets/plugins/popper/popper.js')}}" type="module"></script>
<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/dist/js/classie.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/dist/js/login.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/dist/js/password.js')}}" type="text/javascript"></script>

</body>
<script>
    $(document).on('keyup', '[name="email"]', function (event) {
        this.value = this.value.toUpperCase();
    });
</script>
</html>
