<!DOCTYPE html>
<html lang="en">
<head>
    <head>
        <meta charset="utf-8">
        <link href="/assets/images/logo.svg" rel="icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Midone admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
        <meta name="keywords" content="admin template, Midone admin template, dashboard template, flat admin template, responsive admin template, web app">
        <meta name="author" content="LEFT4CODE">
        <title>Simpedak</title>
        <!-- BEGIN: CSS Assets-->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('style')
        <script src="{{ asset("assets/sweetalert2/dist/sweetalert2.all.min.js") }}"></script>
        <link rel="stylesheet" href="{{ asset("assets/select2/dist/css/select2.min.css") }}">
        <link rel="stylesheet" href="{{ asset("assets/select2/dist/js/select2.min.js") }}">
        <!-- END: CSS Assets-->
    </head>
</head>
<body class="font-poppins">
    @include('components.home.navbar')
    @yield('content')

    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    @stack('script')
</body>
</html>