<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Response;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        $validated = $request->validate([
            'title' => 'string|max:80',
            'description' => 'required|string|max:255',
        ]);

        $userValidated = $request->validate([
            'name' => ['nullable', 'string', 'max:150'],
            'email' => ['nullable', 'string', 'email', 'max:150', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:3'],
        ]);

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

        // cria a denuncia principal
        $complaint = Complaint::create([
            'protocol'   => "FR" . strtoupper(Str::random(12)) . date("Ymd"),
            'user_id'    => $userId,
            'title'      => $validated["title"],
            'description'=> $validated["description"],
        ]);

        $answers = $request->input('answers', []);

        foreach ($answers as $questionId => $answer) {

            // 🔹 Caso seja upload de arquivo(s)
            if ($request->hasFile("answers.$questionId")) {
                $files = $request->file("answers.$questionId");

                // Se for múltiplo, $files é array. Se for único, é UploadedFile.
                if (!is_array($files)) {
                    $files = [$files];
                }

                foreach ($files as $file) {
                    $path = $file->store('complaints_files', 'public');

                    Response::create([
                        'complaint_id'       => $complaint->id,
                        'question_id'        => $questionId,
                        'text_response'      => $path, // caminho do arquivo
                        'response_option_id' => null,
                    ]);
                }
            }

            // 🔹 Caso seja checkbox (múltiplos valores)
            elseif (is_array($answer)) {
                foreach ($answer as $optionId) {
                    Response::create([
                        'complaint_id'       => $complaint->id,
                        'question_id'        => $questionId,
                        'text_response'      => null,
                        'response_option_id' => $optionId,
                    ]);
                }
            }

            // 🔹 Caso seja radio/select (1 valor só → option_id)
            elseif (is_numeric($answer)) {
                Response::create([
                    'complaint_id'       => $complaint->id,
                    'question_id'        => $questionId,
                    'text_response'      => null,
                    'response_option_id' => $answer,
                ]);
            }

            // 🔹 Caso seja texto/textarea
            else {
                Response::create([
                    'complaint_id'       => $complaint->id,
                    'question_id'        => $questionId,
                    'text_response'      => $answer,
                    'response_option_id' => null,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Denúncia registrada com sucesso!');
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

        return redirect()->back()->with('success', 'Denúncia atualizada com sucesso!');
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
