@extends('layout.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Denúncias</h1>
    {{-- <a href="{{ route('') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">Nova Denúncia</a> --}}
</div>

<table class="w-full bg-white rounded shadow overflow-hidden">
    <thead class="bg-gray-800 text-gray-100">
        <tr>
            <th class="p-3 text-left">Protocolo</th>
            <th class="p-3 text-left">Título</th>
            <th class="p-3 text-left">Usuário</th>
            <th class="p-3 text-left">Status</th>
            <th class="p-3 text-left">Data</th>
            <th class="p-3 text-center">Ações</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-200">
        @foreach($complaints as $complaint)
        <tr class="hover:bg-gray-50">
            <td class="p-3 font-mono max-w-[150px] truncate" title="{{ $complaint->protocol }}">
                {{ $complaint->protocol }}
            </td>
            <td class="p-3 max-w-[200px] truncate" title="{{ $complaint->title }}">
                {{ $complaint->title }}
            </td>            
            <td class="p-3">{{ $complaint->user->name ?? 'Anônimo' }}</td>
            <td class="p-3">
                <span class="px-2 py-1 rounded-full text-sm
                    @if($complaint->status == 'ABERTA') bg-yellow-100 text-yellow-800
                    @elseif($complaint->status == 'EM_ANALISE') bg-blue-100 text-blue-800
                    @elseif($complaint->status == 'CONCLUIDA') bg-green-100 text-green-800
                    @endif">
                    {{ $complaint->status }}
                </span>
            </td>
            <td class="p-3">{{ $complaint->created_at->format('d/m/Y H:i') }}</td>
            <td class="p-3 text-center space-x-2">
                <a href="{{ route('complaints.show', $complaint->id) }}" class="text-blue-600 hover:underline"><i class="fas fa-eye mr-1"></i>Ver</a>
                
                @can('update', $complaint)
                <a href="{{ route('complaints.edit', $complaint->id) }}" class="text-green-600 hover:underline">Editar</a>
                @endcan

                @can('delete', $complaint)
                <form action="{{ route('complaints.destroy', $complaint->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-600 hover:underline" onclick="return confirm('Deseja excluir?')">Excluir</button>
                </form>
                @endcan
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Paginação -->
<div class="mt-4">
    {{ $complaints->links() }}
</div>
@endsection
