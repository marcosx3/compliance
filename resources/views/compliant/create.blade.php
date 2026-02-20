@extends('layout.app')

@section('content')

<div class="max-w-4xl mx-auto py-10 px-4">

    <div class="bg-white shadow-xl rounded-2xl p-6 md:p-8">

        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">
            Registrar Denúncia
        </h1>

        <form id="compliantForm" action="{{ route('complaints.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- DADOS DA DENÚNCIA --}}
            <div class="mb-8 border-b pb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">
                    Dados da Denúncia
                </h2>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Título</label>
                    <input type="text" name="title" class="w-full px-4 py-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Descrição</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-2 border rounded-lg"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">
                        Denunciado é do setor de Compliance / Jurídico?
                    </label>

                    <div class="flex gap-6">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="compliance_juridico" value="S">
                            Sim
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="radio" name="compliance_juridico" value="N">
                            Não
                        </label>
                    </div>

                    <p class="text-sm text-gray-500 mt-1">
                        Se "Sim", a denúncia será encaminhada diretamente para o moderador.
                    </p>
                </div>
            </div>

            {{-- PERGUNTAS DINÂMICAS --}}
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">
                    Informações Complementares
                </h2>

                @foreach($activeForm?->questions ?? [] as $question)

                    <div class="mb-5">
                        <label class="block text-gray-700 font-medium mb-2">
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
                                        <option value="{{ $option->value }}">{{ $option->value }}</option>
                                    @endforeach
                                </select>
                            @break

                            @case('radio')
                                <div class="flex flex-wrap gap-4">
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
                                       class="w-full px-4 py-2 border rounded-lg">
                            @break

                        @endswitch
                    </div>

                @endforeach
            </div>

            {{-- BOTÃO --}}
            <button type="submit"
                    class="w-full bg-gray-700 text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition">
                Efetuar Denúncia
            </button>

        </form>

    </div>

</div>

@endsection