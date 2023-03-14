
<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>@yield('page-title') | Surigao del Sur DTS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('/assets/images/logo.png') }}">

    <!-- Bootstrap Css -->
    <link href="{{ asset('/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">




</head>

<body>
    <div class="account-pages my-2 pt-sm-2">
        <div class="container">
            <div class="row justify-content-center">
                @yield('content')
                <div class="mt-1 text-center">
                    @yield('footer')
                    <p>
                        ©
                        <script>document.write(new Date().getFullYear())</script> Surigao del Sur DTS
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <!-- JAVASCRIPT -->
    <!-- Latest compiled and minified JavaScript -->
    <script src="{{ asset('/assets/libs/jquery/jquery.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

    <script src="{{ asset('/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <!-- form wizard -->
    <script src="{{ asset('/assets/libs/jquery-steps/build/jquery.steps.min.js') }}"></script>
    <!-- form wizard init -->
    <script src="{{ asset('/assets/js/pages/form-wizard.init.js') }}"></script>
    @stack('page-scripts')
</body>

</html>
