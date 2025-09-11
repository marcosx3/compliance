<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Response;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class ComplaintController extends Controller
{
    /**
     * Summary of index
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $complaints = Complaint::with('user')->orderBy('created_at','desc')->paginate(10);
        return view('compliant.index', compact('complaints'));
    }

    /**
     * Summary of create
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('compliant.create');
    }

    /**
     * Summary of store
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $userId = null;

        // Validação dos campos principais
        $validated = $request->validate([
            'title' => 'nullable|string|max:80',
            'description' => 'required|string|max:255',
        ]);

        $userValidated = $request->validate([
            'name' => ['nullable', 'string', 'max:150'],
            'email' => ['nullable', 'string', 'email', 'max:150', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:3'],
        ]);

        // Criação do usuário (se fornecido)
        if (!empty($userValidated['name']) && !empty($userValidated['email']) && !empty($userValidated['password'])) {
            $user = User::create([
                'name' => $userValidated['name'],
                'email' => $userValidated['email'],
                'password' => Hash::make($userValidated['password']),
                'role' => 'user',
            ]);
            $userId = $user->id;
        } else {
            $userId = Auth::id();
        }

        try {
            // Criação da denúncia principal
            $complaint = Complaint::create([
                'protocol' => "FR" . strtoupper(Str::random(12)) . date("Ymd"),
                'user_id' => $userId,
                'title' => $validated['title'],
                'description' => $validated['description'],
            ]);

            // Garante que a pasta exista
            Storage::disk('public')->makeDirectory('complaints_files');

            // ------------------------------
            // 1) Respostas de texto e opções
            // ------------------------------
            foreach ($request->input('answers', []) as $questionId => $answer) {
                // Texto simples (input, textarea, select, radio)
                if (is_string($answer) && !empty($answer)) {
                    Response::create([
                        'complaint_id' => $complaint->id,
                        'question_id' => $questionId,
                        'text_response' => $answer,
                        'response_option_id' => null,
                    ]);
                }
                // Checkbox (array de opções)
                elseif (is_array($answer) && !empty($answer) && is_string($answer[0] ?? null)) {
                    foreach ($answer as $optionId) {
                        if (is_numeric($optionId) && \App\Models\OptionQuestion::where('id', $optionId)->exists()) {
                            Response::create([
                                'complaint_id' => $complaint->id,
                                'question_id' => $questionId,
                                'text_response' => null,
                                'response_option_id' => $optionId,
                            ]);
                        }
                    }
                }
            }

            // ------------------------------
            // 2) Respostas de arquivos
            // ------------------------------
            foreach ($request->file('answers', []) as $questionId => $files) {
                foreach ((array) $files as $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        $path = $file->store('complaints_files', 'public');

                        Response::create([
                            'complaint_id' => $complaint->id,
                            'question_id' => $questionId,
                            'text_response' => $path, // salva o caminho do arquivo
                            'response_option_id' => null,
                        ]);
                    }
                }
            }

            // Log para auditoria
            Log::info($userId ? "Denúncia registrada" : "Denúncia registrada por usuário anônimo", [
                'complaint_id' => $complaint->id,
                'user_id' => $userId,
                'protocol' => $complaint->protocol,
            ]);

        } catch (\Throwable $th) {
            Log::error('Erro ao registrar denúncia', ['error' => $th->getMessage()]);
             return redirect()->back()->with('error', 'Erro ao registrar a denúncia!');
        }

        return redirect()->back()->with([
            'success' => 'Denúncia registrada com sucesso! Guarde esse protocolo, pois é único.',
            'protocol' => $complaint->protocol,
        ]);    
    }

    /**
     * Summary of show
     * @param mixed $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $complaint = Complaint::with(['user', 'responses.question.options', 'responses.option'])->findOrFail($id);
        return view('compliant.show', compact('complaint'));
    }

    /**
     * Summary of update
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:ABERTA,EM_ANALISE,CONCLUIDA',
            'response' => 'nullable|string|max:5000',
        ]);
        $userID = null;
        // Atualiza status da denúncia
        $complaint->status = $validated['status'];
        $complaint->save();

        // Se tiver resposta, cria um novo registro em complaint_responses
        if (!empty($validated['response'])) {
            $complaint->responses()->create([
                'response' => $validated['response'],
                'user_id' => Auth::id(), // quem respondeu
            ]);
        }
        Log::info("Denúncia atualizada: ", ['complaint_id' => $complaint->id, 'status' => $complaint->status]);
        return redirect()->back()->with('success', 'Denúncia atualizada com sucesso!');
    }

   
    public function comment(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);

        $validated = $request->validate([
            'response' => 'required|string|max:5000',
        ]);

        try {
            $complaint->complaintResponses()->create([
                // 'complaint_id' => $complaint->id,
                'response' => $validated['response'],
                'user_id' => Auth::id(), // se logado, salva, senão fica null
            ]);

            return redirect()->back()->with('success', 'Comentário enviado com sucesso!');

        } catch (\Throwable $th) {
            throw $th;
            return redirect()->back()->with('info', 'Comentário não enviado!');
        }
    }



    public function consulta(Request $request)
    {
        $protocol = $request->input('protocol');
        
        $complaint = null;
        if (!empty($protocol) ) {
            $complaint = Complaint::where('protocol', $protocol)->first();
            return view('compliant.consulta', compact('complaint', 'protocol'));
        }
        return view("site");
    }

}
