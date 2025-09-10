@extends('layout.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Novo Formulário</h1>

<form action="{{ route('template.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
    @csrf
    <div>
        <label class="block mb-1 font-semibold">Nome do formulário</label>
        <input type="text" name="name" class="w-full p-3 rounded border" required>
    </div>

    <div id="questions-container" class="space-y-4">
        <h2 class="text-lg font-semibold">Perguntas</h2>
        <!-- Pergunta inicial -->
    </div>

    <button type="button" id="add-pergunta" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-600">Adicionar Pergunta</button>

    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-500">Salvar Formulário</button>
</form>

<script>
let questionCount = 0;

function createPergunta() {
    questionCount++;
    const container = document.getElementById('questions-container');

    const div = document.createElement('div');
    div.classList.add('p-4', 'border', 'rounded', 'space-y-2', 'bg-gray-50');

    div.innerHTML = `
        <div>
            <label>Texto</label>
            <input type="text" name="questions[${questionCount}][order]" class="w-full p-2 border rounded" required>
        </div>
        <div class="flex justify-between items-center">
            <h3 class="font-semibold">Pergunta ${questionCount}</h3>
            <button type="button" class="text-red-600 font-bold" onclick="this.closest('div').remove()">X</button>
        </div>
        <div>
            <label>Texto</label>
            <input type="text" name="questions[${questionCount}][text]" class="w-full p-2 border rounded" required>
        </div>
        <div>
            <label>Tipo</label>
            <select name="questions[${questionCount}][type]" class="w-full p-2 border rounded" onchange="toggleOpcoes(this, ${questionCount})">
                <option value="file">Arquivo</option>
                <option value="text">Texto</option>
                <option value="number">Número</option>
                <option value="date">Data</option>
                <option value="select">Múltipla Escolha</option>
            </select>
        </div>
        <div>
            <label>
                <input type="checkbox" name="questions[${questionCount}][required]"> Obrigatória
            </label>
        </div>
        <div id="opcoes-container-${questionCount}" class="space-y-1 hidden">
            <button type="button" class="bg-gray-600 text-white px-2 py-1 rounded mt-2" onclick="addOpcao(${questionCount})">Adicionar Opção</button>
        </div>
    `;

    container.appendChild(div);
}

function toggleOpcoes(select, id) {
    const container = document.getElementById(`opcoes-container-${id}`);
    container.classList.toggle('hidden', select.value !== 'select');
}

function addOpcao(id) {
    const container = document.getElementById(`opcoes-container-${id}`);
    const index = container.querySelectorAll('input').length;
    const input = document.createElement('input');
    input.type = 'text';
    input.name = `questions[${id}][opcoes][]`;
    input.placeholder = `Opção ${index + 1}`;
    input.classList.add('w-full', 'p-2', 'border', 'rounded', 'mt-1');
    container.appendChild(input);
}

document.getElementById('add-pergunta').addEventListener('click', createPergunta);
</script>
@endsection
