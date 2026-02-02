@extends('layout.dashboard')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Formulário de consulta (apenas para denunciante anônimo) -->
    @if(!auth()->check())
    <div class="lg:col-span-3">
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border border-gray-200">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-search text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Consultar Denúncia</h2>
                    <p class="text-gray-600">Digite o número do protocolo para acompanhar o andamento</p>
                </div>
            </div>
            
            <form action="{{ route('complaints.consulta') }}" method="get" class="space-y-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Número do Protocolo</label>
                    <input type="text" name="protocol" value="{{ old('protocol', $protocol ?? '') }}"
                           placeholder="Ex: FRABC123XYZ20240902"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800 placeholder-gray-400">
                    @if(session('error'))
                    <p class="text-red-600 text-sm mt-2 flex items-center gap-1">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </p>
                    @endif
                </div>
                <button type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-200 flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                    <i class="fas fa-search"></i>Consultar Status da Denúncia
                </button>
            </form>
        </div>
    </div>
    @endif

    <!-- Se houver denúncia, mostrar informações em grid -->
    @if(isset($complaint))
    <!-- Card de Informações Principais -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-lg p-6 h-full border border-gray-200">
            <h2 class="text-xl font-semibold mb-6 text-gray-800 pb-4 border-b">Informações da Denúncia</h2>
            
            <div class="space-y-6">
                <div class="p-4 bg-gray-50 rounded-xl">
                    <p class="text-gray-600 text-sm mb-1">Protocolo</p>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-hashtag text-blue-600"></i>
                        <p class="font-mono font-bold text-lg text-gray-800 break-all">
                            {{ $complaint->protocol }}
                        </p>
                    </div>
                </div>
                
                <div class="p-4 bg-gray-50 rounded-xl">
                    <p class="text-gray-600 text-sm mb-2">Status</p>
                    <div class="flex items-center gap-2">
                        @if($complaint->status == 'ABERTA')
                            <div class="w-3 h-3 bg-yellow-500 rounded-full animate-pulse"></div>
                            <span class="px-3 py-1.5 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                                ⏳ ABERTA
                            </span>
                        @elseif($complaint->status == 'EM_ANALISE')
                            <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
                            <span class="px-3 py-1.5 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                🔍 EM ANÁLISE
                            </span>
                        @elseif($complaint->status == 'CONCLUIDA')
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="px-3 py-1.5 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                ✅ CONCLUÍDA
                            </span>
                        @elseif($complaint->status == 'ARQUIVADA')
                            <div class="w-3 h-3 bg-gray-500 rounded-full"></div>
                            <span class="px-3 py-1.5 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">
                                📁 ARQUIVADA
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="p-4 bg-gray-50 rounded-xl">
                    <p class="text-gray-600 text-sm mb-1">Data de Criação</p>
                    <div class="flex items-center gap-2">
                        <i class="far fa-calendar text-blue-600"></i>
                        <p class="font-medium text-gray-800">
                            {{ $complaint->created_at->format('d/m/Y') }}
                        </p>
                        <span class="text-gray-400">•</span>
                        <p class="text-gray-600">
                            {{ $complaint->created_at->format('H:i') }}
                        </p>
                    </div>
                </div>
                
                @if($complaint->responses && $complaint->responses->count())
                <div class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                    <p class="text-gray-600 text-sm mb-1">Questionário Respondido</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-clipboard-check text-blue-600"></i>
                            <p class="font-medium text-gray-800">
                                {{ $complaint->responses->count() }} resposta(s)
                            </p>
                        </div>
                        <span class="text-green-600 text-sm font-medium">
                            <i class="fas fa-check-circle"></i> Completo
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Card de Descrição e Detalhes -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-lg p-6 h-full border border-gray-200">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-file-alt text-purple-600 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Descrição da Denúncia</h2>
                    <p class="text-gray-600 text-sm">Detalhes fornecidos pelo denunciante</p>
                </div>
            </div>
            
            <div class="mb-6">
                <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                    <p class="whitespace-pre-line text-gray-800 leading-relaxed text-base">
                        {{ $complaint->description }}
                    </p>
                </div>
            </div>

            <!-- Respostas do questionário -->
            @if($complaint->responses && $complaint->responses->count())
            <div class="mt-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i class="fas fa-list-check text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700">Respostas do Questionário</h3>
                </div>
                <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                    @foreach($complaint->responses as $index => $response)
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 hover:bg-gray-100 transition-all duration-200">
                        <div class="flex items-start gap-3">
                            <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-800 rounded-full text-xs flex items-center justify-center font-bold">
                                {{ $index + 1 }}
                            </span>
                            <div class="flex-grow">
                                <p class="text-sm font-semibold text-gray-700 mb-2">
                                    {{ $response->question->text ?? 'Pergunta #' . $response->question_id }}
                                </p>
                                @if($response->response_option_id)
                                    <div class="p-3 bg-white rounded-lg border border-gray-300">
                                        <p class="text-gray-800">{{ $response->response_option_id }}</p>
                                    </div>
                                @elseif(Str::startsWith($response->text_response, 'complaints_files/'))
                                    <div class="p-3 bg-white rounded-lg border border-gray-300">
                                        <a href="{{ asset('storage/'.$response->text_response) }}" target="_blank" 
                                           class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 hover:underline font-medium">
                                            <i class="fas fa-paperclip"></i>
                                            <span>📎 Baixar Arquivo Anexado</span>
                                        </a>
                                        <p class="text-gray-500 text-sm mt-1">Clique para visualizar o arquivo</p>
                                    </div>
                                @elseif($response->text_response)
                                    <div class="p-3 bg-white rounded-lg border border-gray-300">
                                        <p class="text-gray-800 whitespace-pre-line">{{ $response->text_response }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Card de Conversa/Mensagens -->
    @if($complaint->complaintResponses && $complaint->complaintResponses->count())
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-lg p-6 h-full border border-gray-200">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-orange-100 rounded-lg">
                    <i class="fas fa-comments text-orange-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-700">Histórico de Mensagens</h3>
                    <p class="text-gray-600 text-sm">Comunicação entre denunciante e administradores</p>
                </div>
            </div>
            <div class="space-y-4 max-h-96 overflow-y-auto p-4 bg-gray-50 rounded-xl">
                @foreach($complaint->complaintResponses as $message)
                <div class="flex flex-col @if($message->user) items-end @else items-start @endif">
                    <div class="max-w-[85%]">
                        <div class="p-4 rounded-2xl 
                            @if($message->user) 
                                bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-tr-none
                            @else 
                                bg-gradient-to-r from-gray-100 to-gray-200 border border-gray-300 rounded-tl-none
                            @endif shadow-sm">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-gray-700">
                                        {{ $message->user->name ?? 'Denunciante' }}
                                    </span>
                                    @if($message->user)
                                    <span class="text-xs bg-blue-600 text-white px-2 py-1 rounded-full">👤 Administrador</span>
                                    @else
                                    <span class="text-xs bg-gray-600 text-white px-2 py-1 rounded-full">👤 Denunciante</span>
                                    @endif
                                </div>
                                <span class="text-xs text-gray-500 bg-white px-2 py-1 rounded-full">
                                    <i class="far fa-clock mr-1"></i>
                                    {{ $message->created_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                            <p class="text-gray-800 whitespace-pre-line leading-relaxed">
                                {{ $message->response }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Card de Nova Mensagem -->
    <div class="lg:col-span-{{ $complaint->complaintResponses && $complaint->complaintResponses->count() ? '1' : '3' }}">
        <div class="bg-white rounded-2xl shadow-lg p-6 h-full border border-gray-200">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-paper-plane text-green-600 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Enviar Nova Mensagem</h2>
                    <p class="text-gray-600 text-sm">Escreva uma mensagem para acompanhar esta denúncia</p>
                </div>
            </div>
            <form action="{{ route('complaints.comment', $complaint->id) }}" method="POST" class="h-full flex flex-col">
                @csrf
                <div class="flex-grow mb-6">
                    <label class="block text-gray-700 font-medium mb-2">Digite sua mensagem</label>
                    <textarea name="response" rows="5"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-gray-800 focus:ring-2 focus:ring-green-500 focus:border-transparent placeholder-gray-400"
                            placeholder="Escreva sua mensagem aqui... Sua mensagem será enviada aos responsáveis pela denúncia."
                            required></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                            class="bg-gradient-to-r from-green-600 to-green-700 text-white px-8 py-3 rounded-xl font-semibold hover:from-green-700 hover:to-green-800 transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg">
                        <i class="fas fa-paper-plane"></i>Enviar Mensagem
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
@endsection