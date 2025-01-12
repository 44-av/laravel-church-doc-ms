<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="w-auto">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('St. Michael the Archangel Parish Church') }}</title>
    <link rel="icon" href="{{ asset('assets/img/logo.png') }}" type="image/png">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-200 w-full">
        <div class="flex">
            <!-- Sidebar -->
            <div class="hidden lg:block">
                @include('layouts.sidebar')
            </div>
            <main class="w-full">
            <div class="sticky top-0 z-50 bg-white shadow-md">
                <x-navbar />
            </div>
                <div class="flex-grow p-4">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#birthdate_bride').on('change', function() {
                var birthdate = new Date($(this).val());
                var today = new Date();
                var age = today.getFullYear() - birthdate.getFullYear();
                var monthDifference = today.getMonth() - birthdate.getMonth();

                if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthdate
                .getDate())) {
                    age--;
                }

                $('#age_bride').val(age);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#toggle-drawer').click(function() {
                $('#my-drawer').prop('checked', function(i, val) {
                    return !val;
                });
            });

            $('.menu-link').click(function() {
                $('#my-drawer').prop('checked', false);
            });
        });

        if ($('#success').length > 0) {
            setTimeout(() => {
                $('#success').remove();
            }, 3000);
        }

        if ($('#error').length > 0) {
            setTimeout(() => {
                $('#error').remove();
            }, 3000);
        }
    </script>
</body>

</html>
