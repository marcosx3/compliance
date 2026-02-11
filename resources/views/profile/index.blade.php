@extends('layout.app')

@section('content')

<div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-8">

    <h2 class="text-2xl font-bold mb-6">Meu Perfil</h2>

    {{-- MENSAGENS --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    {{-- FORM ATUALIZAÇÃO --}}
    <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Nome --}}
        <div>
            <label class="block text-sm font-medium mb-1">Nome</label>
            <input type="text"
                   name="name"
                   value="{{ old('name', $user->name) }}"
                   class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200"
                   required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="block text-sm font-medium mb-1">E-mail</label>
            <input type="email"
                   name="email"
                   value="{{ old('email', $user->email) }}"
                   class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200"
                   required>
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Alterar Senha --}}
        <div class="border-t pt-6">
            <h3 class="text-lg font-semibold mb-3">Alterar Senha</h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Nova Senha</label>
                    <input type="password"
                           name="password"
                           class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Confirmar Nova Senha</label>
                    <input type="password"
                           name="password_confirmation"
                           class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200">
                </div>
            </div>
        </div>

        <div class="pt-4">
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                Salvar Alterações
            </button>
        </div>
    </form>

    {{-- EXCLUSÃO DE CONTA --}}
    <div class="border-t mt-10 pt-6">
        <h3 class="text-lg font-semibold text-red-600 mb-3">
            Zona de Perigo
        </h3>

        <p class="text-sm text-gray-600 mb-4">
            Ao excluir sua conta, todos os seus dados pessoais serão removidos do sistema conforme a LGPD.
            Esta ação não poderá ser desfeita.
        </p>

        <form action="{{ route('profile.delete') }}" method="POST"
              onsubmit="return confirm('Tem certeza que deseja excluir sua conta? Esta ação é irreversível.')">
            @csrf
            @method('DELETE')

            <div class="mb-3">
                <input type="password"
                       name="password"
                       placeholder="Confirme sua senha para excluir"
                       class="w-full border rounded-lg p-2 focus:ring focus:ring-red-200"
                       required>
            </div>

            <button type="submit"
                    class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition">
                Excluir Conta
            </button>
        </form>
    </div>

</div>

@endsection
