@extends('layout.auth')

@section('content')
<h1 class="text-2xl font-bold text-center text-gray-100">Cadastro</h1>

<form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
    @csrf
    <div>
        <label for="nome" class="block text-sm font-medium text-gray-300">Nome</label>
        <input type="text" name="name" id="name" required
            class="w-full mt-1 p-3 rounded-xl bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 text-gray-100 placeholder-gray-400">
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-300">E-mail</label>
        <input type="email" name="email" id="email" required
            class="w-full mt-1 p-3 rounded-xl bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 text-gray-100 placeholder-gray-400">
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-300">Senha</label>
        <input type="password" name="password" id="password" required
            class="w-full mt-1 p-3 rounded-xl bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 text-gray-100 placeholder-gray-400">
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-300">Confirmar Senha</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required
            class="w-full mt-1 p-3 rounded-xl bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 text-gray-100 placeholder-gray-400">
    </div>

    <button type="submit"
        class="w-full bg-gray-600 hover:bg-gray-500 transition-colors py-3 rounded-xl text-white font-semibold">
        Criar conta
    </button>
</form>

<p class="mt-6 text-center text-sm text-gray-400">
    Já tem conta?
    <a href="{{ route('login') }}" class="text-gray-200 hover:underline">Entrar</a>
</p>
@endsection
