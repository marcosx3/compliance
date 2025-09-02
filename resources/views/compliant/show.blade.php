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

    <!-- Respostas e Arquivos -->
    <div class="bg-white rounded shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Respostas / Anexos</h2>

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
            <p class="text-gray-500">Nenhuma resposta ou arquivo enviado ainda.</p>
        @endforelse
    </div>

    <!-- Formulário de resposta -->
    <div class="bg-white rounded shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Responder Denúncia</h2>

        <form action="{{ route('complaints.update', $complaint->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Select Status -->
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Alterar Status</label>
                <select id="status" name="status" class="mt-1 block w-full border rounded px-3 py-2">
                    <option value="ABERTA" {{ $complaint->status == 'ABERTA' ? 'selected' : '' }}>Aberta</option>
                    <option value="EM_ANALISE" {{ $complaint->status == 'EM_ANALISE' ? 'selected' : '' }}>Em Análise</option>
                    <option value="CONCLUIDA" {{ $complaint->status == 'CONCLUIDA' ? 'selected' : '' }}>Concluída</option>
                </select>
            </div>

            <!-- Campo de resposta -->
            <div class="mb-4">
                <label for="response" class="block text-sm font-medium text-gray-700">Resposta</label>
                <textarea id="response" name="response" rows="4"
                          class="mt-1 block w-full border rounded px-3 py-2">{{ old('response', $complaint->response) }}</textarea>
            </div>

            <!-- Botão -->
            <div class="flex justify-end">
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
