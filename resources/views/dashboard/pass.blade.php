<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ZESCO FLEET MASTER:: Gate Pass </title>

    <link rel="shortcut icon" href="{{asset('assets/dist/img/icons/logo.png')}}"
          type="image/x-icon">

    <link href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/plugins/fontawesome/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/plugins/themify-icons/themify-icons.css')}}" rel="stylesheet">
    {{--
        <link href="{{asset('assets/dist/css/login.css')}} " rel="stylesheet" type="text/css">
    --}}
</head>
<body class="bg-white body-bg">
<main class="register-content">
    <object id="gatePass" style="width: 100%; height: 800px;" data="{{asset('forms/VEHICLE-GATE-PASS-.svg')}}"></object>
</main>

<script src="{{asset('assets/plugins/jquery/jquery-3.6.3.js')}}"></script>

<script src="{{asset('assets/plugins/popper/popper.js')}}" type="module"></script>
<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}" type="text/javascript"></script>

</body>
<script>
    window.addEventListener("load", function () {
        let svgObject = document.getElementById('gatePass').contentDocument;
        let svg = svgObject.getElementById('svg2');
        //svg.querySelector('#registrationNumber').textContent = 'ADD 5952';
        svg.querySelector('#department').textContent = 'INNOVATION AND SYSTEMS DEVELOPMENT';
        console.log(svg);
    });
</script>
</html>
