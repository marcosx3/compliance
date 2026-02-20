@extends('layout.app')

@section('content')
<section id="admin" class="py-16 bg-white ">
    <div class="container mx-auto px-4">
        <!-- CARDS RESUMO -->
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

        <!-- LISTA DE DENÚNCIAS -->
        <div class="bg-gray-50 rounded-xl shadow-md p-4 sm:p-6">
            <h3 class="text-xl font-semibold mb-4">Denúncias Recebidas</h3>

            <!-- FILTROS -->
            <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-5 gap-3">

                <input type="text" name="protocolo"
                    placeholder="Protocolo"
                    value="{{ request('protocolo') }}"
                    class="border rounded px-3 py-2 w-full">

                <select name="status" class="border rounded px-3 py-2 w-full">
                    <option value="">Todos Status</option>
                    <option value="ABERTA" @selected(request('status')=='ABERTA')>Aberta</option>
                    <option value="EM_ANALISE" @selected(request('status')=='EM_ANALISE')>Em análise</option>
                    <option value="CONCLUIDA" @selected(request('status')=='CONCLUIDA')>Concluída</option>
                </select>

                <input type="date" name="data_inicio"
                    value="{{ request('data_inicio') }}"
                    class="border rounded px-3 py-2 w-full">

                <input type="date" name="data_fim"
                    value="{{ request('data_fim') }}"
                    class="border rounded px-3 py-2 w-full">

                <div class="flex gap-2">
                    <button class="bg-gray-600 hover:bg-gray-700 text-white rounded px-4 py-2 w-full">
                        Filtrar
                    </button>

                    <a href="{{ route(Route::currentRouteName()) }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 rounded px-4 py-2 w-full text-center">
                        Limpar
                    </a>
                </div>
            </form>

            <!-- TABELA DESKTOP -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full bg-white rounded-lg overflow-hidden">
                   <thead class="bg-gray-100">
                    <tr>
                        {{-- PROTOCOLO --}}
                        <th class="px-4 py-3 text-left">
                            <a href="{{ request()->fullUrlWithQuery([
                                'ordenar_por'=>'protocol',
                                'direcao'=> request('ordenar_por')=='protocol' && request('direcao')=='asc' ? 'desc' : 'asc'
                            ]) }}" class="flex items-center gap-1 hover:text-gray-600">

                                Protocolo

                                @if(request('ordenar_por')=='protocol')
                                    @if(request('direcao')=='asc')
                                        <i class="fas fa-arrow-up text-xs"></i>
                                    @else
                                        <i class="fas fa-arrow-down text-xs"></i>
                                    @endif
                                @else
                                    <i class="fas fa-sort text-xs text-gray-400"></i>
                                @endif
                            </a>
                        </th>

                        {{-- DATA --}}
                        <th class="px-4 py-3 text-left">
                            <a href="{{ request()->fullUrlWithQuery([
                                'ordenar_por'=>'created_at',
                                'direcao'=> request('ordenar_por')=='created_at' && request('direcao')=='asc' ? 'desc' : 'asc'
                            ]) }}" class="flex items-center gap-1 hover:text-gray-600">

                                Data

                                @if(request('ordenar_por')=='created_at')
                                    @if(request('direcao')=='asc')
                                        <i class="fas fa-arrow-up text-xs"></i>
                                    @else
                                        <i class="fas fa-arrow-down text-xs"></i>
                                    @endif
                                @else
                                    <i class="fas fa-sort text-xs text-gray-400"></i>
                                @endif
                            </a>
                        </th>

                        {{-- STATUS --}}
                        <th class="px-4 py-3 text-left">
                            <a href="{{ request()->fullUrlWithQuery([
                                'ordenar_por'=>'status',
                                'direcao'=> request('ordenar_por')=='status' && request('direcao')=='asc' ? 'desc' : 'asc'
                            ]) }}" class="flex items-center gap-1 hover:text-gray-600">

                                Status

                                @if(request('ordenar_por')=='status')
                                    @if(request('direcao')=='asc')
                                        <i class="fas fa-arrow-up text-xs"></i>
                                    @else
                                        <i class="fas fa-arrow-down text-xs"></i>
                                    @endif
                                @else
                                    <i class="fas fa-sort text-xs text-gray-400"></i>
                                @endif
                            </a>
                        </th>

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
                                        class="text-blue-600 hover:text-gray-800">
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

                <!-- PAGINAÇÃO -->
                <div class="mt-4">
                    {{ $denuncias->withQueryString()->links() }}
                </div>
            </div>
        </div>

        <!-- CARDS MOBILE -->
        <div class="space-y-4 md:hidden">
            @forelse($denuncias as $denuncia)
                <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-mono font-semibold">{{ $denuncia->protocol }}</span>
                        <span class="text-sm text-gray-500">
                            {{ $denuncia->created_at->format('d/m/Y') }}
                        </span>
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