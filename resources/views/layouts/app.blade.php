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

    {{-- Datatable link --}}
    <link rel="stylesheet" href="{{ asset('assets/datatables/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/datatables/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/datatables/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
        integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/timepicker.js/latest/timepicker.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/timepicker.js/latest/timepicker.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
</head>

<body>
    <div id="app">
        @include('layouts.sidebar')
        <div id="main">
            {{-- navbar --}}
            @include('layouts.navbar')

            {{-- content --}}
            @yield('content')

            {{-- footer --}}
            @include('layouts.footer')
        </div>
    </div>
    @if (Session::has('message'))
    <script type="text/javascript">
        $(function() {
                $.notify(`<strong>Success</strong> Clear Cache successfully</> !`, {
                    allow_dismiss: false,
                    type: 'success'
                });
            });
    </script>
    @endif

    <!-- Import jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- Import Feather Icons, Perfect Scrollbar, dan Library Lain -->
    <script src="{{ asset('assets/js/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <!-- Import Chart.js dan ApexCharts -->
    <script src="{{ asset('assets/vendors/chartjs/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/dashboard.js') }}"></script>

    <!-- Import DataTables Core -->
    <script src="{{ asset('assets/datatables/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Import DataTables Buttons -->
    <script src="{{ asset('assets/datatables/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/jszip/jszip.js') }}"></script>
    <script src="{{ asset('assets/datatables/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/datatables/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    <!-- Import Notifikasi dan CKEditor -->
    <script src="{{ asset('assets/js/bootstrap-notify.min.js') }}"></script>
    <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script src="/vendor/unisharp/laravel-ckeditor/adapters/jquery.js"></script>

    <!-- Script Lainnya -->
    <script src="{{ asset('assets/js/vendors.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>

</html>