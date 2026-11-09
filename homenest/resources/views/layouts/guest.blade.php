
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'HomeNest') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
          rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">

    <div class="min-h-screen flex flex-col justify-center items-center
                bg-gray-100 px-4 py-8">

        <a href="{{ url('/') }}" class="mb-6">
            <div class="text-3xl font-bold text-teal-700">
                HomeNest
            </div>
        </a>

        <div class="w-full sm:max-w-md px-6 py-6 bg-white shadow-md
                    overflow-hidden sm:rounded-xl">

            {{ $slot }}

        </div>

        <a href="{{ url('/') }}"
           class="mt-5 text-sm text-gray-600 hover:text-teal-700">
            <i class="bi bi-arrow-left"></i>
            Quay về trang chủ
        </a>

    </div>

</body>
</html>
