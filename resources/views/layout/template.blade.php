<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Denúncias - Compliance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="{{ asset('img/favicon-fractal.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
     {{-- CSS principal --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- CSS/JS específico da página --}}
    @stack('styles')
    @stack('scripts')
</head>
<body>
    
    <header>
        <h1>Compliance</h1>
    </header>
    
    <main>
        @yield('content')
    </main>
</body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</html>