@extends('layout.auth')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Formulário de consulta -->
    <form action="{{ route('complaints.consulta') }}" method="get">
        <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 md:p-8 mb-6">
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Número do Protocolo</label>
                <input type="text" name="protocol" value="{{ old('protocol', $protocol) }}"
                    placeholder="Ex: FRABC123XYZ20240902"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <button type="submit"
                class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                <i class="fas fa-search mr-2"></i>Consultar Status
            </button>
        </div>
    </form>

    <!-- Resultado da consulta -->
    @if($protocol)
        @if($complaint)
            <div class="bg-white rounded shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Resultado da Consulta</h2>
                <p><strong>Protocolo:</strong> {{ $complaint->protocol }}</p>
                <p><strong>Status:</strong> {{ $complaint->status }}</p>
                <p><strong>Descrição:</strong> {{ $complaint->description }}</p>

                @if($complaint->admin_response)
                    <p class="mt-4"><strong>Resposta da Administração:</strong></p>
                    <p class="bg-gray-100 p-3 rounded">{{ $complaint->admin_response }}</p>
                @endif
            </div>
        @else
            <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded">
                Nenhuma denúncia encontrada com esse protocolo.
            </div>
        @endif
    @endif
</div>
@endsection

