<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBT-LPIA</title>

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendors/chartjs/Chart.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.svg" type="image/x-icon') }}">

    <link rel="stylesheet" href="{{ asset('assets/fontawesome-free/css/all.min.css') }}">

    <script src="https://cdn.jsdelivr.net/timepicker.js/latest/timepicker.min.js"></script>
    <link href="https://cdn.jsdelivr.net/timepicker.js/latest/timepicker.min.css" rel="stylesheet" />


    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>

    @yield('style')
</head>

<body>

    <nav style="background-color: rgb(28, 161, 223)" class="navbar navbar-header navbar-expand">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <h3 class="text-white">E-Learning</h3>
            <ul class="navbar-nav d-flex align-items-center navbar-light ms-auto">
                <li class="dropdown">
                    <a href="#" data-bs-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                        <div class="avatar me-1">
                            <!-- <img src="assets/images/avatar/avatar-s-1.png" alt="" srcset=""> -->
                        </div>
                        <div class="d-none d-md-block d-lg-inline-block text-white">Hi, {{ auth()->user()->name }}</div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="{{route('profil')}}"><i data-feather="user"></i> Account</a>
                        {{-- <a class="dropdown-item" href="#"><i data-feather="settings"></i> Settings</a> --}}
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                      document.getElementById('logout-form').submit();"><i data-feather="log-out"></i>
                            Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="main-content container-fluid">
        @yield('content')
    </div>

    <script src="{{ asset('assets/js/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <script src="{{ asset('assets/vendors/chartjs/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/dashboard.js') }}"></script>

    {{-- <script src="{{asset('assets/vendors/simple-datatables/simple-datatables.js')}}"></script> --}}
    <script src="{{ asset('assets/js/vendors.js') }}"></script>

    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-notify.min.js') }}"></script>
    <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script src="/vendor/unisharp/laravel-ckeditor/adapters/jquery.js"></script>

</body>

</html>