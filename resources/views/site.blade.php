<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Canal de Denúncias | Sistema de Compliance Seguro</title>
    <meta name="description" content="Sistema completo de canal de denúncias para compliance empresarial. Seguro, anônimo e em conformidade com LGPD." />
    <meta name="author" content="Canal de Denúncias" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="{{ asset('img/favicon-fractal.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- CSS principal --}}
    @vite(['resources/css/site.css', 'resources/js/app.js'])

    {{-- CSS/JS específico da página --}}
    @stack('styles')
    @stack('scripts')
</head>

<body class="min-h-screen bg-gray-50">
    <!-- Main Content -->

    <!-- Top central control with two buttons -->
    <header class="fixed left-0 right-0 top-0 z-50 bg-white/80 backdrop-blur-sm border-b border-gray-200">
        <div class="container mx-auto px-4 py-4 flex justify-center">
            <div class="inline-flex rounded-lg shadow-sm" role="tablist" aria-label="Controls">
                <button id="btnDenuncia" type="button"
                    class="px-6 py-2 rounded-l-lg font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 transition bg-blue-600 text-white"
                    aria-controls="denuncia" aria-selected="true">Fazer Denúncia</button>
                <button id="btnAcompanhar" type="button"
                    class="px-6 py-2 rounded-r-lg font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 transition bg-gray-200 text-gray-800"
                    aria-controls="acompanhar" aria-selected="false">Acompanhar</button>
            </div>
        </div>
    </header>

    <main class="pt-28">
        @if(session('success'))
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Denúncia registrada!',
                        html: 'Protocolo: <strong>{{ session('protocol') }}</strong>',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
        @endif

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
                            {{-- Pergunta sobre setor --}}
                            <div class="mb-6">
                                <label class="block text-gray-dark font-semibold mb-2">Denunciado é do setor de Compliance / Jurídico?</label>
                                <div class="flex items-center space-x-6">
                                    <label class="flex items-center space-x-2">
                                        <input type="radio" name="compliance_juridico" value="S" class="form-radio h-5 w-5 text-gray-custom" />
                                        <span>Sim</span>
                                    </label>
                                    <label class="flex items-center space-x-2">
                                        <input type="radio" name="compliance_juridico" value="N" class="form-radio h-5 w-5 text-gray-custom" />
                                        <span>Não</span>
                                    </label>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">Se "Sim", a denúncia será encaminhada diretamente para o moderador.</p>
                            </div>

                        <!-- @foreach($activeForm->questions ?? [] as $question)
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
                                <input type="file" 
                                    name="answers[{{ $question['id'] }}][]" 
                                    class="w-full px-4 py-2 border rounded-lg"
                                    multiple>

                                @break
                                @default
                                <input type="text" id="answers[{{ $question['id'] }}]" name="answers[{{ $question['id'] }}]" class="w-full px-4 py-2 border rounded-lg">
                        @endswitch
                        @endforeach -->
                                             @foreach($activeForm?->questions ?? [] as $question)

    <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2">
            {{ $question->text }}
            @if($question->required)
                <span class="text-red-500">*</span>
            @endif
        </label>

        @switch($question->type)

            @case('text')
                <input type="text"
                       name="answers[{{ $question->id }}]"
                       class="w-full px-4 py-2 border rounded-lg"
                       {{ $question->required ? 'required' : '' }}>
            @break

            @case('textarea')
                <textarea name="answers[{{ $question->id }}]"
                          rows="4"
                          class="w-full px-4 py-2 border rounded-lg"
                          {{ $question->required ? 'required' : '' }}></textarea>
            @break

            @case('select')
                <select name="answers[{{ $question->id }}]"
                        class="w-full px-4 py-2 border rounded-lg"
                        {{ $question->required ? 'required' : '' }}>
                    <option value="">Selecione...</option>
                    @foreach($question->options as $option)
                        <option value="{{ $option->value }}">
                            {{ $option->value }}
                        </option>
                    @endforeach
                </select>
            @break

            @case('radio')
                <div class="flex gap-4">
                    @foreach($question->options as $option)
                        <label class="flex items-center gap-2">
                            <input type="radio"
                                   name="answers[{{ $question->id }}]"
                                   value="{{ $option->value }}"
                                   {{ $question->required ? 'required' : '' }}>
                            {{ $option->value }}
                        </label>
                    @endforeach
                </div>
            @break

            @case('checkbox')
                <div class="flex flex-col gap-2">
                    @foreach($question->options as $option)
                        <label class="flex items-center gap-2">
                            <input type="checkbox"
                                   name="answers[{{ $question->id }}][]"
                                   value="{{ $option->value }}">
                            {{ $option->value }}
                        </label>
                    @endforeach
                </div>
            @break

            @case('file')
                <input type="file"
                       name="answers[{{ $question->id }}][]"
                       multiple
                       class="w-full px-4 py-2 border rounded-lg"
                       {{ $question->required ? 'required' : '' }}>
            @break

        @endswitch
    </div>
@endforeach

                    </div>
                        <button class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition"  type="submit" >Efetuar Denuncia</button>
                    </form>
            </div>
        </div>

        </section>


        <!-- Acompanhar Denúncia Section (oculta por padrão) -->
        <section id="acompanhar" class="py-16 bg-gray-50 hidden">
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
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Compliance</h3>
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
                        <li><a href="#denuncia" class="text-gray-400 hover:text-white transition">Fazer Denúncia</a>
                        </li>
                        <li><a href="#acompanhar" class="text-gray-400 hover:text-white transition">Acompanhar</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>© 2025 Compliance. Todos os direitos reservados.</p>
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
    // botões de topo que alternam entre sections mantendo o comportamento original do formulário
    const btnDenuncia = document.getElementById('btnDenuncia');
    const btnAcompanhar = document.getElementById('btnAcompanhar');
    const secDenuncia = document.getElementById('denuncia');
    const secAcompanhar = document.getElementById('acompanhar');

    function activateDenuncia() {
        secDenuncia.classList.remove('hidden');
        secAcompanhar.classList.add('hidden');

        btnDenuncia.classList.remove('bg-gray-200','text-gray-800');
        btnDenuncia.classList.add('bg-blue-600','text-white');

        btnAcompanhar.classList.remove('bg-blue-600','text-white');
        btnAcompanhar.classList.add('bg-gray-200','text-gray-800');

        btnDenuncia.setAttribute('aria-selected','true');
        btnAcompanhar.setAttribute('aria-selected','false');

        // scroll to section for focus (optional)
        secDenuncia.scrollIntoView({behavior: 'smooth', block: 'start'});
    }

    function activateAcompanhar() {
        secDenuncia.classList.add('hidden');
        secAcompanhar.classList.remove('hidden');

        btnAcompanhar.classList.remove('bg-gray-200','text-gray-800');
        btnAcompanhar.classList.add('bg-blue-600','text-white');

        btnDenuncia.classList.remove('bg-blue-600','text-white');
        btnDenuncia.classList.add('bg-gray-200','text-gray-800');

        btnDenuncia.setAttribute('aria-selected','false');
        btnAcompanhar.setAttribute('aria-selected','true');

        secAcompanhar.scrollIntoView({behavior: 'smooth', block: 'start'});
    }

    // ativar denúncia por padrão
    document.addEventListener('DOMContentLoaded', function () {
        activateDenuncia();

        btnDenuncia.addEventListener('click', activateDenuncia);
        btnAcompanhar.addEventListener('click', activateAcompanhar);

        // mantém funcionalidade do formulário dinâmico (mostrar dados de identificação)
        const radiosIdent = document.querySelectorAll('input[name="identificar"]');
        const dadosIdent = document.getElementById('dados-identificacao');
        radiosIdent.forEach(r => r.addEventListener('change', function (){
            if (this.value === 'sim') {
                dadosIdent.classList.remove('hidden');
            } else {
                dadosIdent.classList.add('hidden');
            }
        }));
    });
</script>
</body>
</html>