{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html> --}}

<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!--favicon-->
		<link rel="icon" href="{{asset('storage/' . $setting->logo)}}" type="image/png" />
		<!-- loader-->
		<link href="assets/css/pace.min.css" rel="stylesheet" />
		<script src="assets/js/pace.min.js"></script>
		<!-- Bootstrap CSS -->
		<link href="assets/css/bootstrap.min.css" rel="stylesheet">
		<link href="assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
		<link href="assets/css/app.css" rel="stylesheet">
		<link href="assets/css/icons.css" rel="stylesheet">
		<title>{{$setting->name}} - Login</title>
	</head>

    <body class="bg-theme bg-theme6">
        <!--wrapper-->
        <div class="wrapper">

            <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">

                <div class="container-fluid">
                    @yield('content')
                    <!--end row-->
                </div>
            </div>
        </div>
        <!--end wrapper-->
        <!--start switcher-->
        {{-- <div class="switcher-wrapper">
            <div class="switcher-btn"> <i class='bx bx-cog bx-spin'></i>
            </div>
            <div class="switcher-body">
                <div class="d-flex align-items-center">
                    <h5 class="mb-0 text-uppercase">Theme Customizer</h5>
                    <button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
                </div>
                <hr/>
                <p class="mb-0">Gaussian Texture</p>
                <hr>
                <ul class="switcher">
                    <li id="theme1"></li>
                    <li id="theme2"></li>
                    <li id="theme3"></li>
                    <li id="theme4"></li>
                    <li id="theme5"></li>
                    <li id="theme6"></li>
                </ul>
                <hr>
                <p class="mb-0">Gradient Background</p>
                <hr>
                <ul class="switcher">
                    <li id="theme7"></li>
                    <li id="theme8"></li>
                    <li id="theme9"></li>
                    <li id="theme10"></li>
                    <li id="theme11"></li>
                    <li id="theme12"></li>
                    <li id="theme13"></li>
                    <li id="theme14"></li>
                    <li id="theme15"></li>
                </ul>
            </div>
        </div> --}}
        <!--end switcher-->


        <!--plugins-->
        <script src="assets/js/jquery.min.js"></script>
        <!--Password show & hide js -->
        <script>
            $(document).ready(function () {
                $("#show_hide_password a").on('click', function (event) {
                    event.preventDefault();
                    if ($('#show_hide_password input').attr("type") == "text") {
                        $('#show_hide_password input').attr('type', 'password');
                        $('#show_hide_password i').addClass("bx-hide");
                        $('#show_hide_password i').removeClass("bx-show");
                    } else if ($('#show_hide_password input').attr("type") == "password") {
                        $('#show_hide_password input').attr('type', 'text');
                        $('#show_hide_password i').removeClass("bx-hide");
                        $('#show_hide_password i').addClass("bx-show");
                    }
                });
            });
        </script>

        <script>
        $(".switcher-btn").on("click", function() {
        $(".switcher-wrapper").toggleClass("switcher-toggled")
        }), $(".close-switcher").on("click", function() {
            $(".switcher-wrapper").removeClass("switcher-toggled")
        }),


        $('#theme1').click(theme1);
        $('#theme2').click(theme2);
        $('#theme3').click(theme3);
        $('#theme4').click(theme4);
        $('#theme5').click(theme5);
        $('#theme6').click(theme6);
        $('#theme7').click(theme7);
        $('#theme8').click(theme8);
        $('#theme9').click(theme9);
        $('#theme10').click(theme10);
        $('#theme11').click(theme11);
        $('#theme12').click(theme12);
        $('#theme13').click(theme13);
        $('#theme14').click(theme14);
        $('#theme15').click(theme15);


        function theme1() {
        $('body').attr('class', 'bg-theme bg-theme1');
        }

        function theme2() {
        $('body').attr('class', 'bg-theme bg-theme2');
        }

        function theme3() {
        $('body').attr('class', 'bg-theme bg-theme3');
        }

        function theme4() {
        $('body').attr('class', 'bg-theme bg-theme4');
        }

        function theme5() {
        $('body').attr('class', 'bg-theme bg-theme5');
        }

        function theme6() {
        $('body').attr('class', 'bg-theme bg-theme6');
        }

        function theme7() {
        $('body').attr('class', 'bg-theme bg-theme7');
        }

        function theme8() {
        $('body').attr('class', 'bg-theme bg-theme8');
        }

        function theme9() {
        $('body').attr('class', 'bg-theme bg-theme9');
        }

        function theme10() {
        $('body').attr('class', 'bg-theme bg-theme10');
        }

        function theme11() {
        $('body').attr('class', 'bg-theme bg-theme11');
        }

        function theme12() {
        $('body').attr('class', 'bg-theme bg-theme12');
        }

        function theme13() {
        $('body').attr('class', 'bg-theme bg-theme13');
        }

        function theme14() {
        $('body').attr('class', 'bg-theme bg-theme14');
        }

        function theme15() {
        $('body').attr('class', 'bg-theme bg-theme15');
        }
        </script>
    </body>

</html>
