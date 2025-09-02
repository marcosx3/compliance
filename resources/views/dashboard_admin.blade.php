@extends('layout.app')

@section('content')
    <!-- Admin Panel (Hidden by default) -->
    <section id="admin" class="py-16 bg-white ">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-8 text-center">Painel Administrativo</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-8">
                <div class="bg-blue-50 p-4 sm:p-6 rounded-xl card-hover">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg sm:text-xl font-semibold">Denúncias</h3>
                        <i class="fas fa-clipboard-list text-2xl sm:text-3xl text-blue-600"></i>
                    </div>
                    <p class="text-2xl font-bold text-blue-600">{{ $tot_denuncias }}</p>
                    <p class="text-sm text-gray-600">Total de denúncias</p>
                </div>

                <div class="bg-yellow-50 p-4 sm:p-6 rounded-xl card-hover">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg sm:text-xl font-semibold">Pendentes</h3>
                        <i class="fas fa-clock text-2xl sm:text-3xl text-yellow-600"></i>
                    </div>
                    <p class="text-2xl font-bold text-yellow-600">{{ $denuncias_pendentes }}</p>
                    <p class="text-sm text-gray-600">Aguardando análise</p>
                </div>

                <div class="bg-green-50 p-4 sm:p-6 rounded-xl card-hover">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg sm:text-xl font-semibold">Resolvidas</h3>
                        <i class="fas fa-check-circle text-2xl sm:text-3xl text-green-600"></i>
                    </div>
                    <p class="text-2xl font-bold text-green-600">{{ $denuncias_concluidas }}</p>
                    <p class="text-sm text-gray-600">Concluídas</p>
                </div>
            </div>

            <!-- Gestão de Perguntas -->
            <div class="bg-gray-50 rounded-xl shadow-md p-4 sm:p-6 mb-8">
                <h3 class="text-xl font-semibold mb-4">Gestão de Perguntas do Formulário</h3>

                <div class="mb-6">
                    <button
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700 transition text-sm sm:text-base">
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

                <!-- Versão em tabela (somente em md+) -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full bg-white rounded-lg overflow-hidden">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left">Protocolo</th>
                                <th class="px-4 py-3 text-left">Data</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-left">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($denuncias as $denuncia)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 font-mono">{{ $denuncia->protocol }}</td>
                                    <td class="px-4 py-3">{{ $denuncia->created_at->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 rounded-full text-sm 
                                        @if($denuncia->status == 'ABERTA') bg-yellow-100 text-yellow-800
                                        @elseif($denuncia->status == 'EM_ANALISE') bg-blue-100 text-blue-800
                                        @elseif($denuncia->status == 'CONCLUIDA') bg-green-100 text-green-800
                                        @endif">
                                            {{ $denuncia->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('complaints.show', $denuncia->id) }}"
                                            class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-center text-gray-500">
                                        Nenhuma denúncia encontrada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <!-- Paginação -->
                    <div class="mt-4">
                        {{ $denuncias->links() }}
                    </div>
                </div>

            </div>

            <!-- Versão em cards (somente em mobile) -->
            <div class="space-y-4 md:hidden">
                @forelse($denuncias as $denuncia)
                    <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-mono font-semibold">{{ $denuncia->protocol }}</span>
                            <span class="text-sm text-gray-500">{{ $denuncia->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="px-2 py-1 rounded-full text-sm 
                                                @if($denuncia->status == 'ABERTA') bg-yellow-100 text-yellow-800
                                                @elseif($denuncia->status == 'EM_ANALISE') bg-blue-100 text-blue-800
                                                @elseif($denuncia->status == 'CONCLUIDA') bg-green-100 text-green-800
                                                @endif">
                                {{ $denuncia->status }}
                            </span>
                            <a href="{{ route('complaints.show', $denuncia->id) }}"
                                class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500">Nenhuma denúncia encontrada.</p>
                @endforelse
            </div>


        </div>
    </section>
@endsection