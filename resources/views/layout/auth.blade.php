<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Auth - Compliance' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<link rel="shortcut icon" href="{{ asset('img/favicon-fractal.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center  text-gray-100  bg-cover bg-center bg-no-repeat bg-fixed" style="background-image: url('prospen-fundo-claro.jpg');">
    <div class="w-full max-w-md p-8 space-y-6 rounded-2xl shadow-xl" >
        @yield('content')
    </div>
    <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
