@extends('layout.auth')

@section('content')
    <div class="flex items-center justify-center p-3 ">
        <img src="{{ asset('logo-fractal-claro.png') }}" alt="logo fractal claro" class="h-10">
    </div>
<form action="{{ route('login.submit') }}" method="POST">    @csrf
    <div>
        <label for="email" class="block text-sm font-medium"  style="color:#5d596c;">E-mail</label>
        <input type="email" name="email" id="email" required
            class="w-full mt-1 p-3 rounded-xl bg-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 text-gray-900 placeholder-gray-700">
    </div>

    <div>
        <label for="password" class="block text-sm font-medium " style="color:#5d596c;">Senha</label>
        <input type="password" name="password" id="password" required
            class="w-full mt-1 p-3 rounded-xl bg-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 text-gray-900 placeholder-gray-700">
    </div>

    <button type="submit"
        class="w-full bg-gray-600 hover:bg-gray-500 transition-colors py-3 rounded-xl text-white font-semibold">
        Entrar
    </button>
</form>

<p class="mt-6 text-center text-sm text-gray-400">
    Não tem conta?
    <a href="{{ route('register') }}" class="hover:underline" style="color:#5d596c;">Cadastre-se</a>
</p>
@endsection
