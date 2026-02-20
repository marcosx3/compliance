<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ $title ?? 'Painel - Compliance' }}</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<link rel="shortcut icon" href="{{ asset('img/favicon-fractal.ico') }}">
	@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex bg-gray-100 min-h-screen text-gray-900" style="background-image: url('prospen-fundo-claro.jpg');">

	<!-- Sidebar -->
	<aside id="sidebar"
		class="fixed inset-y-0 left-0 w-64 bg-gray-900 text-gray-100 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-40">
		<div class="flex items-center justify-between h-16 border-b border-gray-700 px-4">
			<h1 class="text-xl font-bold">Compliance</h1>
			<!-- Botão fechar (só mobile) -->
			<button id="closeBtn" class="md:hidden text-gray-400 hover:text-gray-200">
				✖
			</button>
		</div>
		<nav class="mt-6 space-y-2">
			<a href="{{route('dashboard.dashboard')}}" class="block px-6 py-3 hover:bg-gray-800">📊 Dashboard</a>
			@if(auth()->user()->isAdmin())
				<a href="{{route('complaints.index')}}" class="block px-6 py-3 hover:bg-gray-800">📁 Denúncias</a>
				<a href="" class="block px-6 py-3 hover:bg-gray-800">⚙️ Configurações</a>
				<a href="{{route('template.index')}}" class="block px-6 py-3 hover:bg-gray-800">⚙️ Formulários</a>
			@endif
		</nav>
	</aside>

	<!-- Conteúdo -->
	<div class="flex-1 flex flex-col md:ml-64">
		<!-- Topbar -->
		<header class="flex items-center justify-between bg-white h-16 shadow px-4 md:px-6">
			<button id="menuBtn" class="md:hidden text-gray-700 focus:outline-none">
				☰
			</button>
			<h2 class="text-lg font-semibold">{{ $title ?? 'Painel' }}</h2>
			<div class="flex items-center space-x-4">
				<div class="relative">
					<button id="userMenuBtn" class="flex items-center space-x-2 focus:outline-none hover:text-gray-700">
						<span class="font-medium">
							{{ auth()->user()->name ?? 'Usuário' }}
						</span>
						<i class="fas fa-chevron-down text-sm"></i>
					</button>

					<!-- Dropdown -->
					<div id="userDropdown"
						class="hidden absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 z-50">

						<a href="{{ route('profile.index') }}" class="block px-4 py-3 hover:bg-gray-100 text-sm">
							👤 Meu Perfil
						</a>

						<hr class="border-gray-200">

						<a href="{{ route('profile.delete') }}" class="block px-4 py-3 text-red-600 hover:bg-red-50 text-sm">
							🗑️ Excluir Conta
						</a>

						<form action="{{ route('logout') }}" method="POST">
							@csrf
							<button type="submit" class="w-full text-left px-4 py-3 hover:bg-gray-100 text-sm">
								🚪 Sair
							</button>
						</form>
					</div>
				</div>

			</div>
		</header>

		<!-- Main -->
		<main class="flex-1 p-6">
			@yield('content')
		</main>
	</div>
	@include('components.flash')
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<!-- Script -->
	<script>
		document.addEventListener("DOMContentLoaded", () => {
			const menuBtn = document.getElementById("menuBtn");
			const closeBtn = document.getElementById("closeBtn");
			const sidebar = document.getElementById("sidebar");

			function toggleMenu() {
				sidebar.classList.toggle("-translate-x-full");
			}

			if (menuBtn) menuBtn.addEventListener("click", toggleMenu);
			if (closeBtn) closeBtn.addEventListener("click", toggleMenu);
		});
		const userMenuBtn = document.getElementById("userMenuBtn");
		const userDropdown = document.getElementById("userDropdown");

		if (userMenuBtn) {
			userMenuBtn.addEventListener("click", () => {
				userDropdown.classList.toggle("hidden");
			});

			document.addEventListener("click", (e) => {
				if (!userMenuBtn.contains(e.target) &&
					!userDropdown.contains(e.target)) {
					userDropdown.classList.add("hidden");
				}
			});
		}

	</script>
</body>

</html>