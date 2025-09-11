@extends('layout.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Cabeçalho -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Detalhes da Denúncia</h1>
        <a href="{{ route('dashboard.dashboard') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-500">Voltar</a>
    </div>

    <!-- Card com informações da denúncia -->
    <div class="bg-white rounded shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Informações</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-gray-600 text-sm">Protocolo</p>
                <p class="font-mono font-semibold">{{ $complaint->protocol }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Data</p>
                <p>{{ $complaint->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Status atual</p>
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

        <div class="mt-4">
            <p class="text-gray-600 text-sm">Descrição</p>
            <p class="whitespace-pre-line">{{ $complaint->description }}</p>
        </div>
    </div>

    <!-- Respostas do Questionário -->
    <div class="bg-white rounded shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Respostas do Questionário</h2>

        @forelse($complaint->responses as $response)
            <div class="mb-4 border-b pb-2">
                <p class="text-gray-700 text-sm font-semibold">
                    {{ $response->question->text ?? 'Pergunta #' . $response->question_id }}
                </p>

                @if($response->response_option_id)
                    <p class="text-gray-800">{{ $response->response_option_id }}</p>
                @elseif(Str::startsWith($response->text_response, 'complaints_files/'))
                    <a href="{{ asset('storage/'.$response->text_response) }}"
                       target="_blank"
                       class="text-blue-600 hover:underline">
                        📎 Baixar Arquivo
                    </a>
                @elseif($response->text_response)
                    <p class="text-gray-800 whitespace-pre-line">{{ $response->text_response }}</p>
                @endif

                <p class="text-xs text-gray-500 mt-1">
                    Respondido em {{ $response->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
        @empty
            <p class="text-gray-500">Nenhuma resposta registrada.</p>
        @endforelse
    </div>

    <!-- Conversa (complaint_responses) -->
    <div class="bg-white rounded shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Conversa</h2>

        @forelse($complaint->complaintResponses as $message)
            <div class="mb-4 p-3 rounded-lg 
                        @if($message->user && $message->user->is_admin) bg-blue-50 ml-10 @else bg-gray-100 mr-10 @endif">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-semibold">
                        {{ $message->user->name ?? 'Denunciante' }}
                    </span>
                    <span class="text-xs text-gray-500">
                        {{ $message->created_at->format('d/m/Y H:i') }}
                    </span>
                </div>
                <p class="mt-1 text-gray-800 whitespace-pre-line">{{ $message->response }}</p>
            </div>
        @empty
            <p class="text-gray-500">Nenhuma mensagem ainda.</p>
        @endforelse
    </div>

    <!-- Formulário de nova mensagem (todos podem usar) -->
    <div class="bg-white rounded shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Enviar Mensagem</h2>

       <form action="{{ route('complaints.comment', $complaint->id) }}" method="POST">
            @csrf
            <textarea name="response" rows="3"
                      class="w-full border rounded px-3 py-2" required></textarea>

            <div class="flex justify-end mt-3">
                <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">
                    Enviar
                </button>
            </div>
        </form>
    </div>

    <!-- Formulário de status (somente Admin) -->
    @if(auth()->user()->isAdmin())
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Alterar Status (Admin)</h2>

            <form action="{{ route('complaints.update', $complaint->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status" class="mt-1 block w-full border rounded px-3 py-2">
                        <option value="ABERTA" {{ $complaint->status == 'ABERTA' ? 'selected' : '' }}>Aberta</option>
                        <option value="EM_ANALISE" {{ $complaint->status == 'EM_ANALISE' ? 'selected' : '' }}>Em Análise</option>
                        <option value="CONCLUIDA" {{ $complaint->status == 'CONCLUIDA' ? 'selected' : '' }}>Concluída</option>
                        <option value="ARQUIVADA" {{ $complaint->status == 'ARQUIVADA' ? 'selected' : '' }}>Arquivada</option>
                    </select>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection
