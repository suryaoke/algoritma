<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login - SMK MUTU Pekanbaru</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('backend/dist/images/smk1.png') }}" type="image/png">

    <!-- Viewport Meta Tag -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Description and Keywords -->
    <meta name="description" content="PTSP MAN 1 Kota Padang - Menuju Sistem Digital 2023">
    <meta name="keywords" content="admin template, PTSP MAN 1 Kota Padang, login, digital system">

    <!-- Author -->
    <meta name="author" content="LEFT4CODE">

    <!-- CSS Assets -->
    <link rel="stylesheet" href="{{ asset('backend/dist/css/app.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
</head>

<body class="login">
    <div class="container sm:px-10">
        <div class="block xl:grid grid-cols-2 gap-4">
            <!-- Login Info -->
            <!-- BEGIN: Login Info -->
            <div class="hidden xl:flex flex-col min-h-screen">
                <a href="" class="-intro-x flex items-center pt-5">
                    <img alt="Midone - HTML Admin Template" class="w-12"
                        src="{{ asset('backend/dist/images/smk1.png') }}">
                    <span class="text-white text-lg ml-3"> SMK Muhammadiyah 1 Pekanbaru</span>
                </a>
                <div class="my-auto ">
                    <img width="320px" alt="Midone - HTML Admin Template" class="-intro-x -mt-16"
                        src="{{ asset('backend/dist/images/mapel.png') }}">
                    <div class="-intro-x text-white font-medium text-4xl leading-tight mt-4">
                        Sistem Penjadwalan
                        <br>
                        SMK MUTU Pekanbaru
                    </div>
                    <div class="-intro-x mt-5 text-lg  text-white text-opacity-70 dark:text-slate-400">
                        <p>SMK yaitu SMK Bisa, SMK Hebat, Siap Kerja, Santun, Mandiri dan Kreatif.</p>
                    </div>
                </div>
            </div>
            <!-- END: Login Info -->
            <!-- End Login Info -->

            <!-- Login Form -->
            <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                <div
                    class="my-auto mx-auto xl:ml-20 bg-white dark:bg-darkmode-600 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                    <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                        Sign In
                    </h2>
                    <div class="intro-x mt-2 text-slate-400 xl:hidden text-center">
                        Manage all your services in one place
                    </div>
                    <form method="POST" action="{{ route('admin.login.submit') }}">
                        @csrf
                        <div class="intro-x mt-8">
                            <input type="text" id="email" name="email"
                                class="intro-x login__input form-control py-3 px-4 block" placeholder="Email">
                            <input id="password" name="password" type="password"
                                class="intro-x login__input form-control py-3 px-4 block mt-4" placeholder="Password">
                        </div>
                        <div class="intro-x flex text-slate-600 dark:text-slate-500 text-xs sm:text-sm mt-4">
                            <div class="flex items-center mr-auto">
                                <input id="remember-me" type="checkbox" class="form-check-input border mr-2">
                                <label class="cursor-pointer select-none" for="remember-me">Remember me</label>
                            </div>
                            <a href="{{ route('password.request') }}">Forgot Password?</a>
                        </div>
                        <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                            <button class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top"
                                type="submit">Login</button>
                        </div>
                    </form>
                    <div class="intro-x mt-10 xl:mt-24 text-slate-600 dark:text-slate-500 text-center xl:text-left">
                    </div>
                </div>
            </div>
            <!-- End Login Form -->
        </div>
    </div>

    <!-- JS Assets -->
    <script src="{{ asset('backend/dist/js/app.js') }}"></script>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('backend/assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/node-waves/waves.min.js') }}"></script>

    <script src="{{ asset('backend/assets/js/app.js') }}"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        @if (Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}"
            switch (type) {
                case 'info':
                    toastr.info(" {{ Session::get('message') }} ");
                    break;

                case 'success':
                    toastr.success(" {{ Session::get('message') }} ");
                    break;

                case 'warning':
                    toastr.warning(" {{ Session::get('message') }} ");
                    break;

                case 'error':
                    toastr.error(" {{ Session::get('message') }} ");
                    break;
            }
        @endif
    </script>
</body>

</html>
