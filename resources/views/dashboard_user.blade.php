@extends('layout.app')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Minhas Denúncias</h1>
    <!-- nova denuncia -->
    <a href="{{ route('complaints.create') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 mb-4 inline-block">
        <i class="fas fa-plus"></i> Nova Denúncia
    </a>
    @forelse($denuncias as $denuncia)
        <div class="bg-white rounded-lg shadow p-4 mb-4 border border-gray-200">
            <div class="flex justify-between items-center mb-2">
                <span class="font-mono font-semibold">{{ $denuncia->protocol }}</span>
                <span class="text-sm text-gray-500">{{ $denuncia->created_at->format('d/m/Y') }}</span>
            </div>
            <p class="mb-2"><strong>Status:</strong>
                <span class="px-2 py-1 rounded-full text-sm 
                    @if($denuncia->status == 'ABERTA') bg-yellow-100 text-yellow-800
                    @elseif($denuncia->status == 'EM_ANALISE') bg-blue-100 text-blue-800
                    @elseif($denuncia->status == 'CONCLUIDA') bg-green-100 text-green-800
                    @endif">
                    {{ $denuncia->status }}
                </span>
            </p>
            @if($denuncia->response)
                <p class="mb-2"><strong>Resposta:</strong> {{ $denuncia->response }}</p>
            @endif
            <a href="{{ route('complaints.show', $denuncia->id) }}"
               class="text-blue-600 hover:underline text-sm flex items-center gap-1">
                <i class="fas fa-eye"></i> Ver detalhes
            </a>
        </div>
    @empty
        <p class="text-center text-gray-500">Nenhuma denúncia encontrada.</p>
    @endforelse

    <div class="mt-4">
        {{ $denuncias->links() }}
    </div>
</div>
@endsection
