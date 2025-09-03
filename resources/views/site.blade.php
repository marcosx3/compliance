<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Denúncias - Compliance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- CSS principal --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- CSS/JS específico da página --}}
    @stack('styles')
    @stack('scripts')
</head>

<body class="min-h-screen">
    <!-- Navigation -->
    <nav class="gradient-bg text-white shadow-lg fixed w-full top-0 z-50">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-shield-alt text-2xl"></i>
                    <span class="text-xl font-bold">Fractal Compliance</span>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex space-x-6">
                    <a href="#home" class="hover:text-blue-100 transition">Início</a>
                    <a href="#denuncia" class="hover:text-blue-100 transition">Fazer Denúncia</a>
                    <a href="#acompanhar" class="hover:text-blue-100 transition">Acompanhar</a>

                </div>

                <div class="flex items-center space-x-4">
                    <button id="login-btn"
                        class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50 transition hidden sm:block">
                        <a href="{{'login'}}" class="block py-2 text-gray-800 hover:text-blue-600 font-semibold"><i
                                class="fas fa-sign-in-alt mr-2"></i>Entrar</a>
                    </button>
                    <button id="mobile-menu-btn" class="md:hidden">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="mobile-menu md:hidden fixed top-16 left-0 w-full bg-white shadow-xl">
            <div class="px-4 py-6 space-y-4">
                <a href="#home" class="block py-2 text-gray-800 hover:text-blue-600 font-semibold">Início</a>
                <a href="#denuncia" class="block py-2 text-gray-800 hover:text-blue-600 font-semibold">Fazer
                    Denúncia</a>
                <a href="#acompanhar" class="block py-2 text-gray-800 hover:text-blue-600 font-semibold">Acompanhar</a>
                <a href="#admin" class="block py-2 text-gray-800 hover:text-blue-600 font-semibold">Admin</a>
                <button id="mobile-login-btn" class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold">
                    <a href="{{'login'}}" class="block py-2 text-gray-800 hover:text-blue-600 font-semibold"><i
                            class="fas fa-sign-in-alt mr-2"></i>Entrar</a>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-16">
        <!-- Hero Section -->
        <section id="home" class="py-16 md:py-24">
            <div class="container mx-auto px-4">
                <div class="grid md:grid-cols-2 gap-8 items-center">
                    <div class="order-2 md:order-1">
                        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-800 mb-6">
                            Sistema Seguro de <span class="text-blue-600">Denúncias</span>
                        </h1>
                        <p class="text-lg text-gray-600 mb-8">
                            Canal confidencial para reportar irregularidades. Sua denúncia é importante para manter um
                            ambiente ético e transparente.
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <a href="#denuncia"
                                class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition text-sm sm:text-base">
                                <i class="fas fa-plus-circle mr-2"></i>Fazer Denúncia
                            </a>
                            <a href="#acompanhar"
                                class="border border-blue-600 text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition text-sm sm:text-base">
                                <i class="fas fa-search mr-2"></i>Acompanhar
                            </a>
                        </div>
                    </div>
                    <div class="order-1 md:order-2">
                        <div class="bg-white rounded-xl shadow-xl p-4 sm:p-6 card-hover">
                            <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/b6e1c102-97a9-4ae6-88c9-9ec1f20e9b57.png"
                                alt="Ilustração de um sistema de denúncias com shield de proteção e gráficos de análise de dados"
                                class="w-full h-auto rounded-lg" />
                            <div class="mt-4 text-center">
                                <h3 class="text-xl font-semibold text-gray-800">100% Confidencial</h3>
                                <p class="text-gray-600">Seus dados são protegidos com criptografia avançada</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="denuncia" class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4">Faça sua Denúncia</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        Preencha o formulário abaixo para reportar irregularidades. Você pode escolher se identificar ou
                        permanecer anônimo.
                    </p>
                </div>
                <div id="perguntas-container">
                    <form id="compliantForm" action="{{ route('complaints.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                            <div class="mb-6">
                                <label class="block text-gray-700 font-semibold mb-2">Deseja se identificar?</label>
                                <div class="flex flex-wrap gap-4">
                                    <label class="flex items-center">
                                        <input type="radio" name="identificar"  value="sim" class="mr-2">
                                        <span>Sim</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="identificar" value="nao" checked class="mr-2">
                                        <span>Não (Anônimo)</span>
                                    </label>
                                </div>
                            </div>

                            <div id="dados-identificacao" class="hidden mb-6">
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-gray-700 font-semibold mb-2">Nome</label>
                                        <input type="text" name="name" id="name" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-semibold mb-2">Email</label>
                                        <input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-semibold mb-2">Password</label>
                                        <input type="password" name="password" id="password" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2" for="title"> Título </label>
                                <input type="text" name="title" id="title" class="w-full px-4 py-2 border rounded-lg">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2" for="title"> Descrição </label>
                                <input type="text" name="description" id="description" class="w-full px-4 py-2 border rounded-lg">
                            </div>

                        @foreach($activeForm->questions as $question)
                            <div class="mb-4">
                                    <label class="block text-gray-700 font-semibold mb-2">
                                        {{ $question['text'] }}
                                    </label>
                                @switch($question['type'])
                                    @case('text')
                                    <input type="text" id="answers[{{ $question['id'] }}]"name="answers[{{ $question['id'] }}]" class="w-full px-4 py-2 border rounded-lg">
                                @break
                                @case('textarea')
                                    <textarea id="answers[{{ $question['id'] }}]" name="answers[{{ $question['id'] }}]" rows="4" class="w-full px-4 py-2 border rounded-lg"></textarea>
                                @break
                                @case('select')
                                    <select id="answers[{{ $question['id'] }}]" name="answers[{{ $question['id'] }}]" class="w-full px-4 py-2 border rounded-lg">
                                        <option value="">Selecione...</option>
                                        @foreach($question['options'] as $option)
                                            <option value="{{ is_array($option) ? $option['value'] : $option->value }}">
                                                {{ is_array($option) ? $option['value'] : $option->value }}
                                            </option>
                                        @endforeach
                                    </select>
                                @break
                                @case('radio')
                                    <div class="flex gap-4">
                                        @foreach($question['options'] as $option)
                                            <label class="flex items-center">
                                            <input type="radio" id="answers[{{ $question['id'] }}]" name="answers[{{ $question['id'] }}]" value="{{ is_array($option) ? $option['value'] : $option->value }}" class="mr-2">
                                            {{ is_array($option) ? $option['value'] : $option->value }}
                                            </label>
                                        @endforeach
                                    </div>
                                @break
                                @case('checkbox')
                                    <div class="flex flex-col gap-2">
                                        @foreach($question['options'] as $option)
                                            <label class="flex items-center">
                                            <input type="checkbox" id="answers[{{ $question['id'] }}][]" name="answers[{{ $question['id'] }}][]" value="{{ is_array($option) ? $option['value'] : $option->value }}" class="mr-2">
                                            {{ is_array($option) ? $option['value'] : $option->value }}
                                            </label>
                                        @endforeach
                                    </div>
                                @break
                                @case('file')
                                    <input type="file" id="answers[{{ $question['id'] }}]" 
                                        name="answers[{{ $question['id'] }}][]" 
                                        class="w-full px-4 py-2 border rounded-lg"
                                        multiple>
                                @break
                                @default
                                <input type="text" id="answers[{{ $question['id'] }}]" name="answers[{{ $question['id'] }}]" class="w-full px-4 py-2 border rounded-lg">
                        @endswitch
                        @endforeach
                    </div>
                        <button class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition"  type="submit" >Efetuar Denuncia</button>
                    </form>
            </div>
        </div>
            
        </section>


        <!-- Acompanhar Denúncia Section -->
        <section id="acompanhar" class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4">Acompanhe sua Denúncia</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        Digite o número do protocolo para verificar o status da sua denúncia.
                    </p>
                </div>

                <div class="max-w-2xl mx-auto">
                    <form action="{{ route('complaints.consulta') }}" method="get">
                        <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 md:p-8">
                            <div class="mb-6">
                                <label class="block text-gray-700 font-semibold mb-2">Número do Protocolo</label>
                                <input type="text" name="protocol" placeholder="Ex: DN20240001"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <button id="consultar-btn" type="submit"
                                class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                                <i class="fas fa-search mr-2"></i>Consultar Status
                            </button>
                        </div>
                    </form>
                    {{-- <div id="status-result" class="mt-8 hidden">
                        <div class="bg-white rounded-xl shadow-md p-4 sm:p-6">
                            <div class="flex items-center justify-between mb-4 flex-col sm:flex-row gap-2">
                                <h3 class="text-xl font-semibold">Status da Denúncia</h3>
                                <span
                                    class="status-pending px-3 py-1 rounded-full text-sm font-semibold">Pendente</span>
                            </div>
                            <div class="space-y-3">
                                <div class="flex justify-between flex-col sm:flex-row gap-2">
                                    <span class="text-gray-600">Protocolo:</span>
                                    <span class="font-semibold">DN20240001</span>
                                </div>
                                <div class="flex justify-between flex-col sm:flex-row gap-2">
                                    <span class="text-gray-600">Data:</span>
                                    <span class="font-semibold">15/01/2024</span>
                                </div>
                                <div class="flex justify-between flex-col sm:flex-row gap-2">
                                    <span class="text-gray-600">Categoria:</span>
                                    <span class="font-semibold">Conduta Inadequada</span>
                                </div>
                            </div>
                            <div class="mt-6 pt-4 border-t">
                                <h4 class="font-semibold mb-2">Últimas Atualizações:</h4>
                                <div class="space-y-2">
                                    <div class="flex items-start">
                                        <div class="w-3 h-3 bg-blue-500 rounded-full mt-1 mr-3"></div>
                                        <div>
                                            <p class="text-sm font-semibold">Denúncia recebida</p>
                                            <p class="text-xs text-gray-500">15/01/2024 14:30</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </section>

      
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Fractal Compliance</h3>
                    <p class="text-gray-400">
                        Sistema seguro e confidencial para reportar irregularidades e promover um ambiente ético.
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Contato</h4>
                    <p class="text-gray-400">
                        <i class="fas fa-envelope mr-2"></i>compliance@empresa.com
                    </p>
                    <p class="text-gray-400">
                        <i class="fas fa-phone mr-2"></i>(11) 9999-9999
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Links Rápidos</h4>
                    <ul class="space-y-2">
                        <li><a href="#home" class="text-gray-400 hover:text-white transition">Início</a></li>
                        <li><a href="#denuncia" class="text-gray-400 hover:text-white transition">Fazer Denúncia</a>
                        </li>
                        <li><a href="#acompanhar" class="text-gray-400 hover:text-white transition">Acompanhar</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>© 2025 Fractal Compliance. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>
    {{-- JS principal --}}
   @vite('resources/js/app.js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Overlay de carregamento -->
<div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="w-14 h-14 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
</div>
<script>
    $(function () {
        // Quando o form é submetido → mostra overlay
        $("#compliantForm").on("submit", function () {
            $("#loadingOverlay").removeClass("hidden");

            // desabilita botão
            const $btn = $(this).find("button[type=submit]");
            $btn.prop("disabled", true).text("Processando...");
        });

        // Quando a página carrega de volta → esconde overlay (ex: erro de validação)
        $(window).on("load", function () {
            $("#loadingOverlay").addClass("hidden");
            $("#registerForm button[type=submit]").prop("disabled", false).text("Criar conta");
        });
    });
</script>
</body>

</html>