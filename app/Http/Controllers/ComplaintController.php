<?php

namespace App\Http\Controllers;

use App\Mail\AdminComplaintCommentMail;
use App\Mail\AdminComplaintRegisteredMail;
use App\Mail\AdminComplaintUpdatedMail;
use App\Mail\ComplaintCommentMail;
use App\Mail\ComplaintUpdatedMail;
use App\Models\Complaint;
use App\Models\Response;
use App\Models\Template;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Mail\ComplaintRegisteredMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

use function Illuminate\Log\log;

class ComplaintController extends Controller
{
 public function index(Request $request)
    {
        $query = Complaint::with('user');

        // 🔎 FILTRO POR PROTOCOLO OU TÍTULO
        if ($request->filled('busca')) {
            $query->where(function ($q) use ($request) {
                $q->where('protocol', 'like', '%' . $request->busca . '%')
                  ->orWhere('title', 'like', '%' . $request->busca . '%');
            });
        }

        // 🔎 FILTRO POR STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 🔎 FILTRO POR DATA
        if ($request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }

        // 🔽 ORDENAÇÃO
        $ordenarPor = $request->get('ordenar_por', 'created_at');
        $direcao = $request->get('direcao', 'desc');

        $query->orderBy($ordenarPor, $direcao);

        $complaints = $query->paginate(10)->withQueryString();

        return view('compliant.index', compact('complaints'));
    }

    /**
     * Summary of create
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
          $activeForm = Template::with(['questions.options'])
            ->where('status', true)
            ->first();
        return view('compliant.create', compact('activeForm'));
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
            'compliance_juridico' => 'nullable|in:S,N|max:2',
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
            DB::beginTransaction();
            // Criação da denúncia principal
            $complaint = Complaint::create([
                'protocol' => "FR" . strtoupper(Str::random(12)) . date("Ymd"),
                'user_id' => $userId,
                'title' => $validated['title'],
                'description' => $validated['description'],
                'compliance_juridico' => $validated['compliance_juridico'],
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
            DB::commit();
            log("Denúncia criada com sucesso: " . $complaint->protocol);
            //  E-mail para o usuário (se existir)
            if ($complaint->user && $complaint->user->email) {
                $data["emailDenunciante"] = $complaint->user->email;
                $data["NomeDenunciante"] = $complaint->user->name;
                $data["protocol"] = $complaint->protocol;
                $data["title"] = $complaint->title;
                $data["description"] = $complaint->description;

                if ( !empty($data["emailDenunciante"])) {
                    Mail::to($complaint->user->email)
                        ->queue(new ComplaintRegisteredMail($data));
                }
            }

            if ($validated["compliance_juridico"] == 'N') {
                //  E-mail para administradores
                $admins = User::where('role', 'admin')->get();
                
                foreach ($admins as $admin) {
                    if ($admin->email) {
                        $data["emailAdmin"] = $admin->email;
                        $data["NomeAdmin"] = $admin->name;
                        $data["protocol"] = $complaint->protocol;
                        $data["title"] = $complaint->title;
                        $data["description"] = $complaint->description;
                        
                        if ( !empty($data["emailAdmin"]) ) {
                        Mail::to($admin->email)
                            ->queue(new AdminComplaintRegisteredMail($data));
                            Log::info("Email enviado para admin: " . $admin->email);
                        }
                    }
                }
            } else {
                // Email para o moderador
                $moderador = User::where('role', 'moderator')->first();
                if ($moderador && $moderador->email) {
                    $data["emailAdmin"] = $moderador->email;
                    $data["NomeAdmin"] = $moderador->name;
                    $data["protocol"] = $complaint->protocol;
                    $data["title"] = $complaint->title;
                    $data["description"] = $complaint->description;
                    
                    if ( !empty($data["emailAdmin"]) ) {
                        Mail::to($moderador->email)
                            ->queue(new AdminComplaintRegisteredMail($data));
                        Log::info("Email enviado para admin: " . $moderador->email);
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
            DB::rollBack();
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
        try {
            DB::beginTransaction();
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
            DB::commit();

            // 1. Dono da denúncia
            if ($complaint->user && $complaint->user->email) {
                $data = [
                    "emailDenunciante" => $complaint->user->email,
                    "NomeDenunciante"  => $complaint->user->name,
                    "protocol"         => $complaint->protocol,
                    "title"            => $complaint->title,
                    "description"      => $complaint->description,
                    "status"           => $complaint->status,
                ];
                Mail::to($complaint->user->email)
                    ->queue(new ComplaintUpdatedMail($data));
            }
            // 2. Administradores
            if ($complaint->compliance === 'S') {
                // Exemplo: enviar para o jurídico
                $juridicos = User::where('role', 'moderator')->get();

                foreach ($juridicos as $juridico) {
                    if ($juridico->email) {
                        $data = [
                            "emailJuridico" => $juridico->email,
                            "NomeJuridico"  => $juridico->name,
                            "protocol"      => $complaint->protocol,
                            "title"         => $complaint->title,
                            "description"   => $complaint->description,
                            "status"        => $complaint->status,
                        ];

                        Mail::to($juridico->email)
                            ->queue(new ComplaintRegisteredMail($data));
                        Log::info("Email enviado para o Moderador: " . $juridico->email);
                    }
                }
            } else {
                // Senão, envia para admins
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    if ($admin->email) {
                        $data = [
                            "emailAdmin" => $admin->email,
                            "NomeAdmin"  => $admin->name,
                            "protocol"   => $complaint->protocol,
                            "title"      => $complaint->title,
                            "description"=> $complaint->description,
                            "status"     => $complaint->status,
                        ];
                        Mail::to($admin->email)
                            ->queue(new AdminComplaintUpdatedMail($data));
                        Log::info("Email enviado para admin: " . $admin->email);
                    }
                }
            }
            return redirect()->back()->with('success', 'Denúncia atualizada com sucesso!');

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Erro ao atualizar denúncia', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', 'Erro ao atualizar a denúncia!');
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
