<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Consulta - Compliance' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    	<link rel="shortcut icon" href="{{ asset('img/favicon-fractal.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Estilos para a scrollbar personalizada */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 text-gray-800">
    <!-- Container principal -->
    <div class="min-h-screen">
        <!-- Header -->
        @if(!auth()->check())
        <div class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <h1 class="text-xl font-bold text-gray-800">
                                <div class="flex">
                                    <a href="/">
                                        <img src="{{ asset('ico-32-fractal.png') }}" alt="logo fractal claro" >
                                    </a>
                                </div>
                            </h1>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500">
                        Consulta de Denúncia
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Conteúdo principal -->
        <main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="mt-12 border-t border-gray-200 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="text-center text-gray-500 text-sm">
                    <p>© {{ date('Y') }} Sistema de Compliance. Todos os direitos reservados.</p>
                    <p class="mt-1">Para dúvidas, entre em contato: compliance@empresa.com</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Script para auto-focus no campo de protocolo
        $(document).ready(function() {
            @if(!isset($complaint))
            $('input[name="protocol"]').focus();
            @endif
        });
    </script>
</body>
</html>