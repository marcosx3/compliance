	<!DOCTYPE html>
	<html lang="pt-BR">

	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Canal de Denúncias | Sistema de Compliance Seguro</title>
		<meta name="description" content="Sistema completo de canal de denúncias para compliance empresarial. Seguro, anônimo e em conformidade com LGPD." />
		<meta name="author" content="Canal de Denúncias" />
		<script src="https://cdn.tailwindcss.com"></script>
		<link rel="shortcut icon" href="{{ asset('img/favicon-fractal.ico') }}">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
		{{-- CSS principal --}}
		@vite(['resources/css/site.css', 'resources/js/app.js'])

		{{-- CSS/JS específico da página --}}
		@stack('styles')
		@stack('scripts')
	</head>

	<body class="min-h-screen bg-gray-50" >
		<!-- Main Content -->

		<!-- Top central control with two buttons -->
	<header class="fixed left-0 right-0 top-0 z-50 bg-white/80 backdrop-blur-sm border-b border-gray-200">
	<div class="container mx-auto px-4 py-4 flex justify-center">
		<div class="inline-flex items-center gap-2 rounded-xl bg-gray-100 p-1"
			 role="tablist"
			 aria-label="Controls">

			<button id="btnDenuncia" type="button"
				class="tab-btn px-6 py-2 rounded-lg font-semibold text-sm transition
					   bg-[#4b5563] text-white"
				aria-controls="denuncia"
				aria-selected="true">
				Fazer Denúncia
			</button>

			<button id="btnAcompanhar" type="button"
				class="tab-btn px-6 py-2 rounded-lg font-semibold text-sm transition
					   text-[#4b5563]"
				aria-controls="acompanhar"
				aria-selected="false">
				Acompanhar
			</button>

			<button id="btnLogin" type="button"
				class="tab-btn px-6 py-2 rounded-lg font-semibold text-sm transition
					   text-[#4b5563]"
				aria-controls="login"
				aria-selected="false">
				Login
			</button>

		</div>
	</div>
</header>



		<main class="pt-28" >
			@if(session('success'))
				<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
				<script>
					document.addEventListener('DOMContentLoaded', function () {
						Swal.fire({
							icon: 'success',
							title: 'Denúncia registrada!',
							html: 'Protocolo: <strong>{{ session('protocol') }}</strong>',
							confirmButtonText: 'OK'
						});
					});
				</script>
			@endif

			<section id="denuncia" class="py-16" style="background-image: url('prospen-fundo-claro.jpg');">
				<div class="container mx-auto px-4">
					<div class="text-center mb-12">
						<h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4">Faça sua Denúncia</h2>
						<p class="text-gray-600 max-w-2xl mx-auto">
							Preencha o formulário abaixo para reportar irregularidades. Você pode escolher se identificar ou
							permanecer anônimo.
						</p>
					</div>
					<div id="perguntas-container">
						<form id="compliantForm" action="{{ route('complaints.store') }}" method="post" enctype="multipart/form-data">
						@csrf
								<div class="mb-6">
									<label class="block text-gray-700 font-semibold mb-2">Deseja se identificar?</label>
									<div class="flex flex-wrap gap-4">
										<label class="flex items-center">
											<input type="radio" name="identificar"  value="sim" class="mr-2">
											<span>Sim</span>
										</label>
										<label class="flex items-center">
											<input type="radio" name="identificar" value="nao" checked class="mr-2">
											<span>Não (Anônimo)</span>
										</label>
									</div>
								</div>

								<div id="dados-identificacao" class="hidden mb-6">
									<div class="grid md:grid-cols-2 gap-4">
										<div>
											<label class="block text-gray-700 font-semibold mb-2">Nome</label>
											<input type="text" name="name" id="name" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
										</div>
										<div>
											<label class="block text-gray-700 font-semibold mb-2">Email</label>
											<input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
										</div>
										<div>
											<label class="block text-gray-700 font-semibold mb-2">Password</label>
											<input type="password" name="password" id="password" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
										</div>
									</div>
								</div>
								<div class="mb-4">
									<label class="block text-gray-700 font-semibold mb-2" for="title"> Título </label>
									<input type="text" name="title" id="title" class="w-full px-4 py-2 border rounded-lg">
								</div>
								<div class="mb-4">
									<label class="block text-gray-700 font-semibold mb-2" for="title"> Descrição </label>
									<input type="text" name="description" id="description" class="w-full px-4 py-2 border rounded-lg">
								</div>
								{{-- Pergunta sobre setor --}}
								<div class="mb-6">
									<label class="block text-gray-dark font-semibold mb-2">Denunciado é do setor de Compliance / Jurídico?</label>
									<div class="flex items-center space-x-6">
										<label class="flex items-center space-x-2">
											<input type="radio" name="compliance_juridico" value="S" class="form-radio h-5 w-5 text-gray-custom" />
											<span>Sim</span>
										</label>
										<label class="flex items-center space-x-2">
											<input type="radio" name="compliance_juridico" value="N" class="form-radio h-5 w-5 text-gray-custom" />
											<span>Não</span>
										</label>
									</div>
									<p class="text-sm text-gray-500 mt-1">Se "Sim", a denúncia será encaminhada diretamente para o moderador.</p>
								</div>


		@foreach($activeForm?->questions ?? [] as $question)

		<div class="mb-4">
			<label class="block text-gray-700 font-semibold mb-2">
				{{ $question->text }}
				@if($question->required)
					<span class="text-red-500">*</span>
				@endif
			</label>

			@switch($question->type)

				@case('text')
					<input type="text"
						name="answers[{{ $question->id }}]"
						class="w-full px-4 py-2 border rounded-lg"
						{{ $question->required ? 'required' : '' }}>
				@break

				@case('textarea')
					<textarea name="answers[{{ $question->id }}]"
							rows="4"
							class="w-full px-4 py-2 border rounded-lg"
							{{ $question->required ? 'required' : '' }}></textarea>
				@break

				@case('select')
					<select name="answers[{{ $question->id }}]"
							class="w-full px-4 py-2 border rounded-lg"
							{{ $question->required ? 'required' : '' }}>
						<option value="">Selecione...</option>
						@foreach($question->options as $option)
							<option value="{{ $option->value }}">
								{{ $option->value }}
							</option>
						@endforeach
					</select>
				@break

				@case('radio')
					<div class="flex gap-4">
						@foreach($question->options as $option)
							<label class="flex items-center gap-2">
								<input type="radio"
									name="answers[{{ $question->id }}]"
									value="{{ $option->value }}"
									{{ $question->required ? 'required' : '' }}>
								{{ $option->value }}
							</label>
						@endforeach
					</div>
				@break

				@case('checkbox')
					<div class="flex flex-col gap-2">
						@foreach($question->options as $option)
							<label class="flex items-center gap-2">
								<input type="checkbox"
									name="answers[{{ $question->id }}][]"
									value="{{ $option->value }}">
								{{ $option->value }}
							</label>
						@endforeach
					</div>
				@break

				@case('file')
					<input type="file"
						name="answers[{{ $question->id }}][]"
						multiple
						class="w-full px-4 py-2 border rounded-lg"
						{{ $question->required ? 'required' : '' }}>
				@break

			@endswitch
		</div>
	@endforeach

						</div>
							<button class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition"  type="submit" >Efetuar Denuncia</button>
						</form>
				</div>
			</div>

			</section>


			<!-- Acompanhar Denúncia Section (oculta por padrão) -->
			<section id="acompanhar" class="py-16 hidden" style="background-image: url('prospen-fundo-claro.jpg');">
				<div class="container mx-auto px-4">
					<div class="text-center mb-12">
						<h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4">Acompanhe sua Denúncia</h2>
						<p class="text-gray-600 max-w-2xl mx-auto">
							Digite o número do protocolo para verificar o status da sua denúncia.
						</p>
					</div>

					<div class="max-w-2xl mx-auto">
						<form action="{{ route('complaints.consulta') }}" method="get">
							<div class="bg-white rounded-xl shadow-md p-4 sm:p-6 md:p-8">
								<div class="mb-6">
									<label class="block text-gray-700 font-semibold mb-2">Número do Protocolo</label>
									<input type="text" name="protocol" placeholder="Ex: DN20240001"
										class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
								</div>
								<button id="consultar-btn" type="submit"
									class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
									<i class="fas fa-search mr-2"></i>Consultar Status
								</button>
							</div>
						</form>
					</div>
				</div>
			</section>


			<section id="login" class="py-16  hidden" style="background-image: url('prospen-fundo-claro.jpg');">
	<div class="container mx-auto px-4">
		<div class="text-center mb-12">
			<h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4">
				Acesso ao Sistema
			</h2>
			<p class="text-gray-600 max-w-2xl mx-auto">
				Entre com seu e-mail e senha para acessar o painel.
			</p>
		</div>

		<div class="max-w-md mx-auto bg-white rounded-xl shadow-md p-6">
			<form action="{{ route('login.submit') }}" method="post">
				@csrf
				<div class="mb-4">
					<label class="block text-gray-700 font-semibold mb-2">E-mail</label>
					<input type="email" name="email"
						class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
						required>
				</div>

				<div class="mb-6">
					<label class="block text-gray-700 font-semibold mb-2">Senha</label>
					<input type="password" name="password"
						class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
						required>
				</div>

				<button type="submit"
					class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
					Entrar
				</button>
			</form>
		</div>
	</div>
</section>

		</main>

		<!-- Footer -->
		<footer class="bg-gray-800 text-white py-12">
			<div class="container mx-auto px-4">
				<div class="grid md:grid-cols-3 gap-8">
					<div>
						<h3 class="text-xl font-bold mb-4">Compliance</h3>
						<p class="text-gray-400">
							Sistema seguro e confidencial para reportar irregularidades e promover um ambiente ético.
						</p>
					</div>
					<div>
						<h4 class="font-semibold mb-4">Contato</h4>
						<p class="text-gray-400">
							<i class="fas fa-envelope mr-2"></i>compliance@empresa.com
						</p>
						<p class="text-gray-400">
							<i class="fas fa-phone mr-2"></i>(11) 9999-9999
						</p>
					</div>
					<div>
						<h4 class="font-semibold mb-4">Links Rápidos</h4>
						<ul class="space-y-2">
							<li><a href="#denuncia" class="text-gray-400 hover:text-white transition">Fazer Denúncia</a>
							</li>
							<li><a href="#acompanhar" class="text-gray-400 hover:text-white transition">Acompanhar</a></li>
						</ul>
					</div>
				</div>
				<div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
					<p>© 2025 Compliance. Todos os direitos reservados.</p>
				</div>
			</div>
		</footer>
		{{-- JS principal --}}
	@vite('resources/js/app.js')
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		<!-- Overlay de carregamento -->
	<div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
		<div class="w-14 h-14 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
	</div>

	<script>
	// Botões
	const btnDenuncia = document.getElementById('btnDenuncia');
	const btnAcompanhar = document.getElementById('btnAcompanhar');
	const btnLogin = document.getElementById('btnLogin');

	// Sections
	const secDenuncia = document.getElementById('denuncia');
	const secAcompanhar = document.getElementById('acompanhar');
	const secLogin = document.getElementById('login');

	// Esconde todas as sections
	function hideAllSections() {
		[secDenuncia, secAcompanhar, secLogin].forEach(sec =>
			sec.classList.add('hidden')
		);
	}

	// Controla estado visual dos botões
	function setActiveButton(activeBtn) {
		[btnDenuncia, btnAcompanhar, btnLogin].forEach(btn => {
			btn.classList.remove('bg-blue-600', 'text-white');
			btn.classList.add('bg-gray-200', 'text-gray-800');
			btn.setAttribute('aria-selected', 'false');
		});

		activeBtn.classList.remove('bg-gray-200', 'text-gray-800');
		activeBtn.classList.add('bg-blue-600', 'text-white');
		activeBtn.setAttribute('aria-selected', 'true');
	}

	function activateDenuncia() {
		hideAllSections();
		secDenuncia.classList.remove('hidden');
		setActiveButton(btnDenuncia);
		secDenuncia.scrollIntoView({ behavior: 'smooth', block: 'start' });
	}

	function activateAcompanhar() {
		hideAllSections();
		secAcompanhar.classList.remove('hidden');
		setActiveButton(btnAcompanhar);
		secAcompanhar.scrollIntoView({ behavior: 'smooth', block: 'start' });
	}

	function activateLogin() {
		hideAllSections();
		secLogin.classList.remove('hidden');
		setActiveButton(btnLogin);
		secLogin.scrollIntoView({ behavior: 'smooth', block: 'start' });
	}

	document.addEventListener('DOMContentLoaded', function () {
		// Default
		activateDenuncia();

		// Eventos
		btnDenuncia.addEventListener('click', activateDenuncia);
		btnAcompanhar.addEventListener('click', activateAcompanhar);
		btnLogin.addEventListener('click', activateLogin);

		// Formulário (identificação)
		const radiosIdent = document.querySelectorAll('input[name="identificar"]');
		const dadosIdent = document.getElementById('dados-identificacao');

		radiosIdent.forEach(radio => {
			radio.addEventListener('change', function () {
				dadosIdent.classList.toggle('hidden', this.value !== 'sim');
			});
		});
	});
</script>

	</body>
	</html>