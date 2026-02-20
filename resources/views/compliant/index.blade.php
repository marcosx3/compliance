@extends('layout.app')

@section('content')

	<div class="flex justify-between items-center mb-6">
		<h1 class="text-2xl font-bold">Denúncias</h1>
	</div>

	<!-- 🔎 FILTROS -->
	<form method="GET" class="mb-4 grid grid-cols-1 md:grid-cols-5 gap-3">

		<input type="text" name="busca" placeholder="Protocolo ou título" value="{{ request('busca') }}"
			class="border rounded px-3 py-2">

		<select name="status" class="border rounded px-3 py-2">
			<option value="">Todos Status</option>
			<option value="ABERTA" @selected(request('status') == 'ABERTA')>Aberta</option>
			<option value="EM_ANALISE" @selected(request('status') == 'EM_ANALISE')>Em análise</option>
			<option value="CONCLUIDA" @selected(request('status') == 'CONCLUIDA')>Concluída</option>
		</select>

		<input type="date" name="data_inicio" value="{{ request('data_inicio') }}" class="border rounded px-3 py-2">

		<input type="date" name="data_fim" value="{{ request('data_fim') }}" class="border rounded px-3 py-2">

		<div class="flex gap-2">
			<button class="bg-gray-600 text-white rounded px-4 py-2 w-full">
				Filtrar
			</button>

			<a href="{{ route(Route::currentRouteName()) }}" class="bg-gray-300 rounded px-4 py-2 w-full text-center">
				Limpar
			</a>
		</div>
	</form>

	<table class="w-full bg-white rounded shadow overflow-hidden">
		<thead class="bg-gray-600 text-gray-100">
			<tr>

				{{-- PROTOCOLO --}}
				<th class="p-3 text-left">
					<a href="{{ request()->fullUrlWithQuery([
		'ordenar_por' => 'protocol',
		'direcao' => request('ordenar_por') == 'protocol' && request('direcao') == 'asc' ? 'desc' : 'asc'
	]) }}" class="flex items-center gap-1">
						Protocolo
						@if(request('ordenar_por') == 'protocol')
							<i class="fas fa-arrow-{{ request('direcao') == 'asc' ? 'up' : 'down' }}"></i>
						@else
							<i class="fas fa-sort text-gray-400"></i>
						@endif
					</a>
				</th>

				{{-- TITULO --}}
				<th class="p-3 text-left">
					<a href="{{ request()->fullUrlWithQuery([
		'ordenar_por' => 'title',
		'direcao' => request('ordenar_por') == 'title' && request('direcao') == 'asc' ? 'desc' : 'asc'
	]) }}" class="flex items-center gap-1">
						Título
						@if(request('ordenar_por') == 'title')
							<i class="fas fa-arrow-{{ request('direcao') == 'asc' ? 'up' : 'down' }}"></i>
						@else
							<i class="fas fa-sort text-gray-400"></i>
						@endif
					</a>
				</th>

				<th class="p-3 text-left">Usuário</th>

				{{-- STATUS --}}
				<th class="p-3 text-left">
					<a href="{{ request()->fullUrlWithQuery([
		'ordenar_por' => 'status',
		'direcao' => request('ordenar_por') == 'status' && request('direcao') == 'asc' ? 'desc' : 'asc'
	]) }}" class="flex items-center gap-1">
						Status
						@if(request('ordenar_por') == 'status')
							<i class="fas fa-arrow-{{ request('direcao') == 'asc' ? 'up' : 'down' }}"></i>
						@else
							<i class="fas fa-sort text-gray-400"></i>
						@endif
					</a>
				</th>

				{{-- DATA --}}
				<th class="p-3 text-left">
					<a href="{{ request()->fullUrlWithQuery([
		'ordenar_por' => 'created_at',
		'direcao' => request('ordenar_por') == 'created_at' && request('direcao') == 'asc' ? 'desc' : 'asc'
	]) }}" class="flex items-center gap-1">
						Data
						@if(request('ordenar_por') == 'created_at')
							<i class="fas fa-arrow-{{ request('direcao') == 'asc' ? 'up' : 'down' }}"></i>
						@else
							<i class="fas fa-sort text-gray-400"></i>
						@endif
					</a>
				</th>

				<th class="p-3 text-center">Ações</th>
			</tr>
		</thead>

		<tbody class="divide-y divide-gray-200">
			@foreach($complaints as $complaint)
				<tr class="hover:bg-gray-50">
					<td class="p-3 font-mono">{{ $complaint->protocol }}</td>
					<td class="p-3">{{ $complaint->title }}</td>
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

					<td class="p-3">{{ $complaint->created_at->format('d/m/Y') }}</td>

					<td class="p-3 text-center space-x-2">
						<a href="{{ route('complaints.show', $complaint->id) }}" class="text-gray-600 hover:underline">Ver</a>
						@can('update', $complaint)
							<a href="{{ route('complaints.edit', $complaint->id) }}"
								class="text-green-600 hover:underline">Editar</a>
						@endcan

						@can('delete', $complaint)
							<form action="{{ route('complaints.destroy', $complaint->id) }}" method="POST" class="inline">
								@csrf
								@method('DELETE')
								<button class="text-red-600 hover:underline" onclick="return confirm('Deseja excluir?')">
									Excluir
								</button>
							</form>
						@endcan
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>

	<div class="mt-4">
		{{ $complaints->withQueryString()->links() }}
	</div>

@endsection