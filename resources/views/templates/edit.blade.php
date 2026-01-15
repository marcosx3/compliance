
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
                <input type="text"
                       name="questions[{{ $index }}][text]"
                       class="w-full p-2 border rounded"
                       value="{{ $question->text }}"
                       required>
            </div>

            <div>
                <label>Tipo</label>
                <select name="questions[{{ $index }}][type]"
                        class="w-full p-2 border rounded"
                        onchange="toggleOpcoes(this, {{ $index }})">
                    <option value="file"   @selected($question->type === 'file')>Arquivos</option>
                    <option value="text"   @selected($question->type === 'text')>Texto</option>
                    <option value="number" @selected($question->type === 'number')>Número</option>
                    <option value="date"   @selected($question->type === 'date')>Data</option>
                    <option value="select" @selected($question->type === 'select')>Múltipla Escolha</option>
                </select>
            </div>

            <div>
                <label>
                    <input type="checkbox"
                           name="questions[{{ $index }}][required]"
                           {{ $question->required ? 'checked' : '' }}>
                    Obrigatória
                </label>
            </div>

            <div id="opcoes-container-{{ $index }}"
                 class="space-y-1 {{ $question->type !== 'select' ? 'hidden' : '' }}">

                @foreach($question->options as $option)
                    <input type="text"
                           name="questions[{{ $index }}][options][]"
                           value="{{ $option->value }}"
                           class="w-full p-2 border rounded mt-1">
                @endforeach

                <button type="button"
                        class="bg-gray-600 text-white px-2 py-1 rounded mt-2"
                        onclick="addOpcao({{ $index }})">
                    Adicionar Opção
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <button type="button"
            id="add-pergunta"
            class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-600">
        Adicionar Pergunta
    </button>

    <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">
        Atualizar Formulário
    </button>
</form>

<script>
let questionCount = {{ $template->questions->count() }};

function createPergunta() {
    const index = questionCount++;
    const container = document.getElementById('questions-container');

    const div = document.createElement('div');
    div.className = 'p-4 border rounded space-y-2 bg-gray-50 question-block';

    div.innerHTML = `
        <div class="flex justify-between items-center">
            <h3 class="font-semibold">Pergunta ${index + 1}</h3>
            <button type="button" class="text-red-600 font-bold"
                    onclick="this.closest('.question-block').remove()">X</button>
        </div>

        <div>
            <label>Texto</label>
            <input type="text"
                   name="questions[${index}][text]"
                   class="w-full p-2 border rounded"
                   required>
        </div>

        <div>
            <label>Tipo</label>
            <select name="questions[${index}][type]"
                    class="w-full p-2 border rounded"
                    onchange="toggleOpcoes(this, ${index})">
                <option value="file">Arquivos</option>
                <option value="text">Texto</option>
                <option value="number">Número</option>
                <option value="date">Data</option>
                <option value="select">Múltipla Escolha</option>
            </select>
        </div>

        <div>
            <label>
                <input type="checkbox" name="questions[${index}][required]">
                Obrigatória
            </label>
        </div>

        <div id="opcoes-container-${index}" class="space-y-1 hidden">
            <button type="button"
                    class="bg-gray-600 text-white px-2 py-1 rounded mt-2"
                    onclick="addOpcao(${index})">
                Adicionar Opção
            </button>
        </div>
    `;

    container.appendChild(div);
}

function toggleOpcoes(select, id) {
    document
        .getElementById(`opcoes-container-${id}`)
        .classList.toggle('hidden', select.value !== 'select');
}

function addOpcao(id) {
    const container = document.getElementById(`opcoes-container-${id}`);
    const input = document.createElement('input');

    input.type = 'text';
    input.name = `questions[${id}][options][]`;
    input.placeholder = 'Nova opção';
    input.className = 'w-full p-2 border rounded mt-1';

    container.appendChild(input);
}

document.getElementById('add-pergunta').addEventListener('click', createPergunta);
</script>
@endsection
