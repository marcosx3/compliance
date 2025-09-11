@extends('layout.auth')

@section('content')
<div class="max-w-3xl mx-auto py-6">

    <!-- Formulário de consulta (apenas para denunciante anônimo) -->
    @if(!auth()->check())
        <form action="{{ route('complaints.consulta') }}" method="get" class="mb-6">
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Consultar Denúncia</h2>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Número do Protocolo</label>
                    <input type="text" name="protocol" value="{{ old('protocol', $protocol ?? '') }}"
                        placeholder="Ex: FRABC123XYZ20240902"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition flex items-center justify-center">
                    <i class="fas fa-search mr-2"></i>Consultar Status
                </button>
            </div>
        </form>
    @endif

    <!-- Resultado da denúncia -->
    @if(isset($complaint))
        <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Detalhes da Denúncia</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-gray-600 text-sm">Protocolo</p>
                    <p class="font-mono font-semibold text-gray-800">{{ $complaint->protocol }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Status</p>
                    <span class="px-2 py-1 rounded-full text-sm 
                        @if($complaint->status == 'ABERTA') bg-yellow-100 text-yellow-800
                        @elseif($complaint->status == 'EM_ANALISE') bg-blue-100 text-blue-800
                        @elseif($complaint->status == 'CONCLUIDA') bg-green-100 text-green-800
                        @elseif($complaint->status == 'ARQUIVADA') bg-gray-200 text-gray-700
                        @endif">
                        {{ $complaint->status }}
                    </span>
                </div>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Descrição</p>
                <p class="whitespace-pre-line text-gray-800 bg-gray-50 p-3 rounded">{{ $complaint->description }}</p>
            </div>

            <!-- Respostas do questionário -->
            @if($complaint->responses && $complaint->responses->count())
                <div class="mt-4">
                    <h3 class="text-lg font-semibold mb-2 text-gray-700">Respostas do Questionário</h3>
                    <div class="space-y-3">
                        @foreach($complaint->responses as $response)
                            <div class="p-3 rounded-lg bg-gray-100">
                                <p class="text-sm font-semibold text-gray-700">
                                    {{ $response->question->text ?? 'Pergunta #' . $response->question_id }}
                                </p>
                                @if($response->response_option_id)
                                    <p class="text-gray-800">{{ $response->response_option_id }}</p>
                                @elseif(Str::startsWith($response->text_response, 'complaints_files/'))
                                    <a href="{{ asset('storage/'.$response->text_response) }}" target="_blank" class="text-blue-600 hover:underline">
                                        📎 Baixar Arquivo
                                    </a>
                                @elseif($response->text_response)
                                    <p class="text-gray-800 whitespace-pre-line">{{ $response->text_response }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Conversa / comentários (admin e denunciante) -->
            @if($complaint->complaintResponses && $complaint->complaintResponses->count())
                <div class="mt-4">
                    <h3 class="text-lg font-semibold mb-2 text-gray-700">Respostas / Conversa</h3>
                    <div class="space-y-3">
                        @foreach($complaint->complaintResponses as $message)
                            <div class="p-3 rounded-lg 
                                        @if($message->user) bg-blue-50 ml-10 @else bg-gray-100 mr-10 @endif">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-semibold text-gray-700">
                                        {{ $message->user->name ?? 'Denunciante' }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $message->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                                <p class="text-gray-800 whitespace-pre-line">{{ $message->response }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Formulário de nova mensagem -->
            <div class="bg-white rounded shadow p-6 mb-6">
                <h2 class="text-xl font-semibold mb-2 text-gray-800 mb-4">Enviar Mensagem</h2>
                <form action="{{ route('complaints.comment', $complaint->id) }}" method="POST">
                    @csrf
                    <textarea name="response" rows="3"
                              class="w-full border rounded px-3 text-gray-800  py-2 mb-2" required></textarea>
                    <div class="flex justify-end">
                        <button type="submit"
                                  class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">
                            Enviar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection
