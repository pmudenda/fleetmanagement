<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>TMS :: Login </title>

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
            <img src="{{asset('assets/dist/img/icons/zesco_logo.png')}}" data-pagespeed-url-hash="2593638024"/>
        </figure>
    </div>
    <div class="container py-5 py-sm-7">
        <a class="d-flex justify-content-center mb-5 news365-logo" href="">
            <img class="z-index-2" src="{{asset('assets/dist/img/icons/zesco_logo.png')}}" alt="Image Description"
                 data-pagespeed-url-hash="799927880">
        </a>
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="">
                </div>
                <div class="form-card mb-5">
                    <div class="form-card_body">

                        <form method="POST" action="{{route('login')}}" id="loginForm" novalidate=""
                              accept-charset="utf-8">
                            @csrf

                            <div class="text-center">
                                <div class="mb-5">
                                    <h1 class="display-4 mt-0 font-weight-semi-bold">
                                        FLEET MASTER
                                    </h1>
                                    <small>A TRANSPORT MANAGEMENT SYSTEM</small>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="input-label font-weight-bold" for="username">Username</label>
                                <input type="text" name="email"
                                       value="{{ old('username') }}"
                                       autocomplete="off" id="username"
                                       placeholder="Username"
                                       required=""
                                       autofocus=""
                                       class="form-control @error('username') is-invalid @enderror"/>
                                @error('username')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="input-label font-weight-bold" for="inputPassword">Password</label>
                                <div class="position-relative">
                                    <input type="password"
                                           placeholder="Password"
                                           class="form-control password @error('password') is-invalid @enderror"
                                           name="password"
                                           id="password"
                                           required=""/>
                                    <i onclick="passShow()" class="fa fa-eye-slash"></i>
                                </div>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>


                            <div class="form-group row mb-0">
                                <div class="form-group col-md-4">
                                    <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-paper-plane"></i> Login
                                    </button>
                                </div>
                                <div class="col-md-8 pl-0">
                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password ?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>


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
</html>
