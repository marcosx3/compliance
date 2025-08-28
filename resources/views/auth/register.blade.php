@extends('layout.auth')

@section('content')
<h1 class="text-2xl font-bold text-center" style="color:#5d596c;">Cadastro</h1>
<form id="registerForm" method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
    @csrf
    <div>
        <label for="nome" class="block text-sm font-medium " style="color:#5d596c;">Nome</label>
        <input type="text" name="name" id="name" required
            class="w-full mt-1 p-3 rounded-xl border border-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 text-black placeholder-black">
    </div>

    <div>
        <label for="email" class="block text-sm font-medium " style="color:#5d596c;">E-mail</label>
        <input type="email" name="email" id="email" required
            class="w-full mt-1 p-3 rounded-xl border border-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 text-black placeholder-black">
    </div>

    <div>
        <label for="password" class="block text-sm font-medium " style="color:#5d596c;">Senha</label>
        <input type="password" name="password" id="password" required
            class="w-full mt-1 p-3 rounded-xl border border-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 text-black placeholder-black">
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium " style="color:#5d596c;">Confirmar Senha</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required
            class="w-full mt-1 p-3 rounded-xl border border-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 text-black placeholder-black">
    </div>

    <button type="submit"
        class="w-full bg-gray-600 hover:bg-gray-500 transition-colors py-3 rounded-xl text-white font-semibold">
        Criar conta
    </button>
</form>


<p class="mt-6 text-center text-sm text-gray-400">
    Já tem conta?
    <a href="{{ route('login') }}" class=" hover:underline" style="color:#5d596c;">Entrar</a>
</p>



<!-- Overlay de carregamento -->
<div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="w-14 h-14 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
</div>
<script>
    $(function () {
        // Quando o form é submetido → mostra overlay
        $("#registerForm").on("submit", function () {
            $("#loadingOverlay").removeClass("hidden");

            // desabilita botão
            const $btn = $(this).find("button[type=submit]");
            $btn.prop("disabled", true).text("Processando...");
        });

        // Quando a página carrega de volta → esconde overlay (ex: erro de validação)
        $(window).on("load", function () {
            $("#loadingOverlay").addClass("hidden");
            $("#registerForm button[type=submit]").prop("disabled", false).text("Criar conta");
        });
    });
</script>
@endsection
