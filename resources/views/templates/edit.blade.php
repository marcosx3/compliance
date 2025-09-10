@extends('layout.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Editar Formulário</h1>

<form action="{{ route('template.update', $template->id) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    <div>
        <label class="block mb-1 font-semibold">Nome do formulário</label>
        <input type="text" name="name" class="w-full p-3 rounded border" value="{{ $template->name }}" required>
    </div>

    <div id="questions-container" class="space-y-4">
        <h2 class="text-lg font-semibold">Perguntas</h2>

        @foreach($template->questions as $index => $question)
        <div class="p-4 border rounded space-y-2 bg-gray-50 question-block">
            <div class="flex justify-between items-center">
                <h3 class="font-semibold">Pergunta {{ $index + 1 }}</h3>
                <button type="button" class="text-red-600 font-bold" onclick="this.closest('.question-block').remove()">X</button>
            </div>
            <div>
                <label>Texto</label>
                <input type="text" name="questions[{{ $index }}][text]" class="w-full p-2 border rounded" value="{{ $question->text }}" required>
            </div>
            <div>
                <label>Tipo</label>
                <select name="questions[{{ $index }}][type]" class="w-full p-2 border rounded" onchange="toggleOpcoes(this, {{ $index }})">
                    <option value="file" {{ $question->type=='file'?'selected':'' }}>Arquivos</option>
                    <option value="text" {{ $question->type=='text'?'selected':'' }}>Texto</option>
                    <option value="number" {{ $question->type=='number'?'selected':'' }}>Número</option>
                    <option value="date" {{ $question->type=='date'?'selected':'' }}>Data</option>
                    <option value="select" {{ $question->type=='select'?'selected':'' }}>Múltipla Escolha</option>
                </select>
            </div>
            <div>
                <label>
                    <input type="checkbox" name="questions[{{ $index }}][required]" {{ $question->obrigatoria?'checked':'' }}> Obrigatória
                </label>
            </div>
            <div id="opcoes-container-{{ $index }}" class="space-y-1 {{ $question->type != 'select' ? 'hidden' : '' }}">
                @foreach($question->options as $oIndex => $option)
                <input type="text" name="questions[{{ $index }}][opcoes][]" value="{{ $option->valor }}" class="w-full p-2 border rounded mt-1">
                @endforeach
                <button type="button" class="bg-gray-600 text-white px-2 py-1 rounded mt-2" onclick="addOpcao({{ $index }})">Adicionar Opção</button>
            </div>
        </div>
        @endforeach
    </div>

    <button type="button" id="add-pergunta" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-600">Adicionar Pergunta</button>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">Atualizar Formulário</button>
</form>

<script>
let questionCount = {{ $template->questions->count() }};

// Função para criar nova pergunta dinâmica
function createPergunta() {
    const index = questionCount++;
    const container = document.getElementById('questions-container');

    const div = document.createElement('div');
    div.classList.add('p-4', 'border', 'rounded', 'space-y-2', 'bg-gray-50', 'question-block');

    div.innerHTML = `
        <div class="flex justify-between items-center">
            <h3 class="font-semibold">Pergunta ${index + 1}</h3>
            <button type="button" class="text-red-600 font-bold" onclick="this.closest('.question-block').remove()">X</button>
        </div>
        <div>
            <label>Texto</label>
            <input type="text" name="questions[${index}][text]" class="w-full p-2 border rounded" required>
        </div>
        <div>
            <label>Tipo</label>
            <select name="questions[${index}][type]" class="w-full p-2 border rounded" onchange="toggleOpcoes(this, ${index})">
                <option value="file">Arquivos</option>
                <option value="text">Texto</option>
                <option value="number">Número</option>
                <option value="date">Data</option>
                <option value="select">Múltipla Escolha</option>
            </select>
        </div>
        <div>
            <label>
                <input type="checkbox" name="questions[${index}][required]"> Obrigatória
            </label>
        </div>
        <div id="opcoes-container-${index}" class="space-y-1 hidden">
            <button type="button" class="bg-gray-600 text-white px-2 py-1 rounded mt-2" onclick="addOpcao(${index})">Adicionar Opção</button>
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
