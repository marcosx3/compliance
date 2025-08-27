@extends('layout.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Formulários</h1>
    <a href="{{ route('template.create') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">Novo Formulário</a>
</div>

<table class="w-full bg-white rounded shadow overflow-hidden">
    <thead class="bg-gray-800 text-gray-100">
        <tr>
            <th class="p-3 text-left">Nome</th>
            <th class="p-3 text-left">Perguntas</th>
            <th class="p-3">Ações</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-200">
        @foreach($templates as $template)
        <tr class="hover:bg-gray-50">
            <td class="p-3">{{ $template->name }}</td>
            <td class="p-3">{{ $template->questions->count() ?? 0}}</td>
            <td class="p-3 space-x-2">
                <a href="{{ route('template.edit', $template->id) }}"
                   class="text-blue-600 hover:underline">Editar</a>
                <form action="{{ route('template.destroy', $template->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-600 hover:underline" onclick="return confirm('Deseja excluir?')">Excluir</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
