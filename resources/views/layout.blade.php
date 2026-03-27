{{--
|--------------------------------------------------------------------------
| Laravel Gallery - Frontend Layout
|--------------------------------------------------------------------------
|
| Base layout used for public gallery pages.
|
--}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - {{ __("gallery::gallery.frontend.title") }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @yield('content')

    @stack('scripts')
</body>
</html>