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
                    <button id="login-btn" class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50 transition hidden sm:block">
                        <a href="{{'login'}}" class="block py-2 text-gray-800 hover:text-blue-600 font-semibold"><i class="fas fa-sign-in-alt mr-2"></i>Entrar</a> 
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
                <a href="#denuncia" class="block py-2 text-gray-800 hover:text-blue-600 font-semibold">Fazer Denúncia</a>
                <a href="#acompanhar" class="block py-2 text-gray-800 hover:text-blue-600 font-semibold">Acompanhar</a>
                <a href="#admin" class="block py-2 text-gray-800 hover:text-blue-600 font-semibold">Admin</a>
                <button id="mobile-login-btn" class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold">
                   <a href="{{'login'}}" class="block py-2 text-gray-800 hover:text-blue-600 font-semibold"><i class="fas fa-sign-in-alt mr-2"></i>Entrar</a> 
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
                            Canal confidencial para reportar irregularidades. Sua denúncia é importante para manter um ambiente ético e transparente.
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <a href="#denuncia" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition text-sm sm:text-base">
                                <i class="fas fa-plus-circle mr-2"></i>Fazer Denúncia
                            </a>
                            <a href="#acompanhar" class="border border-blue-600 text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition text-sm sm:text-base">
                                <i class="fas fa-search mr-2"></i>Acompanhar
                            </a>
                        </div>
                    </div>
                    <div class="order-1 md:order-2">
                        <div class="bg-white rounded-xl shadow-xl p-4 sm:p-6 card-hover">
                            <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/b6e1c102-97a9-4ae6-88c9-9ec1f20e9b57.png" alt="Ilustração de um sistema de denúncias com shield de proteção e gráficos de análise de dados" class="w-full h-auto rounded-lg" />
                            <div class="mt-4 text-center">
                                <h3 class="text-xl font-semibold text-gray-800">100% Confidencial</h3>
                                <p class="text-gray-600">Seus dados são protegidos com criptografia avançada</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Denúncia Form Section -->
        <section id="denuncia" class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4">Faça sua Denúncia</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        Preencha o formulário abaixo para reportar irregularidades. Você pode escolher se identicar ou permanecer anônimo.
                    </p>
                </div>

                <div class="max-w-4xl mx-auto bg-gray-50 rounded-xl shadow-md p-4 sm:p-6 md:p-8">
                    {{-- <form id="denuncia-form" method="POST" enctype="multipart/form-data" action="{{ route('denuncias.store') }}"> --}}
                    <form id="denuncia-form" method="POST" enctype="multipart/form-data" >
                        @csrf
                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">Deseja se identificar?</label>
                            <div class="flex flex-wrap gap-4">
                                <label class="flex items-center">
                                    <input type="radio" name="identificar" value="sim" class="mr-2">
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
                                    <input type="text" name="name" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-semibold mb-2">Email</label>
                                    <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>

                        <div id="perguntas-container">
                            <!-- Perguntas serão carregadas dinamicamente -->
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">Descrição da Denúncia</label>
                            <textarea rows="5" name="description" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Descreva com detalhes a situação que deseja denunciar..."></textarea>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">Anexos (opcional)</label>
                            <input type="file" name="files" class="w-full px-4 py-2 border rounded-lg" multiple>
                            <p class="text-sm text-gray-500 mt-1">Formatos aceitos: PDF, JPG, PNG (máx. 10MB)</p>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                            <i class="fas fa-paper-plane mr-2"></i>Enviar Denúncia
                        </button>
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
                    <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 md:p-8">
                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">Número do Protocolo</label>
                            <input type="text" placeholder="Ex: DN20240001" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <button id="consultar-btn" class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                            <i class="fas fa-search mr-2"></i>Consultar Status
                        </button>
                    </div>

                    <div id="status-result" class="mt-8 hidden">
                        <div class="bg-white rounded-xl shadow-md p-4 sm:p-6">
                            <div class="flex items-center justify-between mb-4 flex-col sm:flex-row gap-2">
                                <h3 class="text-xl font-semibold">Status da Denúncia</h3>
                                <span class="status-pending px-3 py-1 rounded-full text-sm font-semibold">Pendente</span>
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
                    </div>
                </div>
            </div>
        </section>

        <!-- Admin Panel (Hidden by default) -->
        <section id="admin" class="py-16 bg-white hidden">
            <div class="container mx-auto px-4">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-8 text-center">Painel Administrativo</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-8">
                    <div class="bg-blue-50 p-4 sm:p-6 rounded-xl card-hover">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg sm:text-xl font-semibold">Denúncias</h3>
                            <i class="fas fa-clipboard-list text-2xl sm:text-3xl text-blue-600"></i>
                        </div>
                        <p class="text-2xl font-bold text-blue-600">24</p>
                        <p class="text-sm text-gray-600">Total de denúncias</p>
                    </div>
                    
                    <div class="bg-yellow-50 p-4 sm:p-6 rounded-xl card-hover">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg sm:text-xl font-semibold">Pendentes</h3>
                            <i class="fas fa-clock text-2xl sm:text-3xl text-yellow-600"></i>
                        </div>
                        <p class="text-2xl font-bold text-yellow-600">8</p>
                        <p class="text-sm text-gray-600">Aguardando análise</p>
                    </div>
                    
                    <div class="bg-green-50 p-4 sm:p-6 rounded-xl card-hover">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg sm:text-xl font-semibold">Resolvidas</h3>
                            <i class="fas fa-check-circle text-2xl sm:text-3xl text-green-600"></i>
                        </div>
                        <p class="text-2xl font-bold text-green-600">12</p>
                        <p class="text-sm text-gray-600">Concluídas</p>
                    </div>
                </div>

                <!-- Gestão de Perguntas -->
                <div class="bg-gray-50 rounded-xl shadow-md p-4 sm:p-6 mb-8">
                    <h3 class="text-xl font-semibold mb-4">Gestão de Perguntas do Formulário</h3>
                    
                    <div class="mb-6">
                        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700 transition text-sm sm:text-base">
                            <i class="fas fa-plus mr-2"></i>Nova Pergunta
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full bg-white rounded-lg overflow-hidden responsive-table">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left">Pergunta</th>
                                    <th class="px-4 py-3 text-left">Tipo</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                    <th class="px-4 py-3 text-left">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b">
                                    <td class="px-4 py-3" data-label="Pergunta">Setor/Área relacionada</td>
                                    <td class="px-4 py-3" data-label="Tipo">Dropdown</td>
                                    <td class="px-4 py-3" data-label="Status">
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm">Ativa</span>
                                    </td>
                                    <td class="px-4 py-3" data-label="Ações">
                                        <button class="text-blue-600 hover:text-blue-800 mr-2">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="border-b">
                                    <td class="px-4 py-3" data-label="Pergunta">Tipo de irregularidade</td>
                                    <td class="px-4 py-3" data-label="Tipo">Checkbox</td>
                                    <td class="px-4 py-3" data-label="Status">
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm">Ativa</span>
                                    </td>
                                    <td class="px-4 py-3" data-label="Ações">
                                        <button class="text-blue-600 hover:text-blue-800 mr-2">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Lista de Denúncias -->
                <div class="bg-gray-50 rounded-xl shadow-md p-4 sm:p-6">
                    <h3 class="text-xl font-semibold mb-4">Denúncias Recebidas</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full bg-white rounded-lg overflow-hidden responsive-table">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left">Protocolo</th>
                                    <th class="px-4 py-3 text-left">Data</th>
                                    <th class="px-4 py-3 text-left">Categoria</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                    <th class="px-4 py-3 text-left">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 font-mono" data-label="Protocolo">DN20240001</td>
                                    <td class="px-4 py-3" data-label="Data">15/01/2024</td>
                                    <td class="px-4 py-3" data-label="Categoria">Conduta</td>
                                    <td class="px-4 py-3" data-label="Status">
                                        <span class="status-pending px-2 py-1 rounded-full text-sm">Pendente</span>
                                    </td>
                                    <td class="px-4 py-3" data-label="Ações">
                                        <button class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-eye"></i> Ver
                                        </button>
                                    </td>
                                </tr>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 font-mono" data-label="Protocolo">DN20240002</td>
                                    <td class="px-4 py-3" data-label="Data">14/01/2024</td>
                                    <td class="px-4 py-3" data-label="Categoria">Financeiro</td>
                                    <td class="px-4 py-3" data-label="Status">
                                        <span class="status-in-progress px-2 py-1 rounded-full text-sm">Em Análise</span>
                                    </td>
                                    <td class="px-4 py-3" data-label="Ações">
                                        <button class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-eye"></i> Ver
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
                        <li><a href="#denuncia" class="text-gray-400 hover:text-white transition">Fazer Denúncia</a></li>
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
    @vite(['resources/js/app.js', 'resources/js/app.js'])
    <!-- Script JavaScript -->
    <script>
       
    </script>
</body>
</html>

