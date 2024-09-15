<!DOCTYPE html>
<html lang="en">
<head>

    <title>{{ config('app.name') }}</title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="title" content="{{ config('app.name') }}" />
    <meta name="description" content="{{ config('app.name') }}" />
    <meta name="keywords" content="{{ config('app.name') }}">
    
    @vite('resources/css/app.css')

</head>
<body>

    <div id="app"></div>

    <script src="{{ config('app.url') }}/assets/js/date-without-timezone.js"></script>
    <script src="{{ config('app.url') }}/assets/js/iframe-object-fit.js"></script>
    <script src="{{ config('app.url') }}/assets/js/static-toast.js"></script>

    @vite('resources/js/app.js')
    
</body>
</html>