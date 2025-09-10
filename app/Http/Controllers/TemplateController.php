<?php

namespace App\Http\Controllers;

use App\Models\OptionQuestion;
use App\Models\Question;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TemplateController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Summary of index
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $templates = Template::with('questions')->get();
        return view('templates.index', compact('templates'));
    }

    /**
     * Summary of create
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('templates.create');
    }


    /**
     * Summary of templates and questions
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'questions' => 'required|array',
            'questions.*.text' => 'required|string|max:255',
            'questions.*.type' => 'required|in:file,text,number,date,select,radio',
            'questions.*.required' => 'boolean',
            'questions.*.order' => 'nullable|string|integer',
            'questions.*.options' => 'array', // só para MULTIPLA_ESCOLHA
        ]);
        Template::query()->update(['status' => false]);  // desativa todos 
        
        $template = Template::create([
            'name' => $data['name'],
            'status' => true,
        ]);
        Log::info('Template criado: ', ['template_id' => $template->id, 'name' => $template->name]);
        foreach ($data['questions'] as $order => $p) {
            $question = Question::create([
                'template_id' => $template->id,
                'text' => $p['text'],
                'type' => $p['type'],
                'required' => $p['required'] ?? false,
                'order' => $p['order'],
            ]);

            if ($p['type'] === 'select' && !empty($p['options'])) {
                foreach ($p['options'] as $op) {
                    OptionQuestion::create([
                        'question_id' => $question->id,
                        'value' => $op,
                    ]);
                }
            }
            Log::info('  Question criada: ', ['question_id' => $question->id, 'text' => $question->text, 'type' => $question->type]);
        }
        
        return redirect()->route('template.index')->with('success', 'Formulário criado com sucesso!');
    }


    /**
     * Editar formulário
     * @param int $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(int  $template)
    {
        $template = Template::findOrFail($template);
        $template->load('questions');
        return view('templates.edit', compact('template'));
    }


 public function update(Request $request, Template $template)
{
    
    $data = $request->validate([
        'name' => 'required|string|max:150',
        'questions' => 'required|array',
        'questions.*.text' => 'required|string|max:255',
        'questions.*.type' => 'required|in:file,text,number,date,select,radio',
        'questions.*.required' => 'boolean',
        'questions.*.order' => 'nullable|integer',
        'questions.*.opcoes' => 'array', // só para múltipla escolha
    ]);

    // Atualiza apenas esse template para "ativo"
    Template::query()->update(['status' => false]);
    $template->update([
        'name' => $data['name'],
        'status' => true,
    ]);
    Log::info('Template atualizado: ', ['template_id' => $template->id, 'name' => $template->name]);
    // Remove perguntas antigas (cascade apaga opções se FK estiver configurada)
    $template->questions()->delete();

    foreach ($data['questions'] as $order => $p) {
        $question = $template->questions()->create([
            'text' => $p['text'],
            'type' => $p['type'],
            'required' => $p['required'] ?? false,
            'order' => $p['order'] ?? $order,
        ]);

        if ($p['type'] === 'select' && !empty($p['opcoes'])) {
            foreach ($p['opcoes'] as $op) {
                $question->options()->create([
                    'value' => $op,
                ]);
            }
        }
    }

    return redirect()->route('template.index')->with('success', 'Formulário atualizado com sucesso!');
}

    /**
     * Exclui formulario
     * int id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {   
        $template = Template::findOrFail($id);
        $template->delete();
        return redirect()->route('template.index')->with('success', 'Formulário excluído!');
    }
}
