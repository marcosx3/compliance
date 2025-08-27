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
        $compliant = Complaint::with('user')->orderBy('order')->paginate(10);
        return view('compliant.index', compact('compliant'));
    }

    public function create()
    {
        return view('compliant.create');
    }

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
            // cria o usuário se os dados forem fornecidos
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
            'protocol' => "FR" . strtoupper(Str::random(12)) . date("Ymd"),
            'user_id' => Auth::id(),
            'title' => $validated["title"],
            'description' => $validated["description"],
        ]);

        $answers = $request->input('answers', []);

        foreach ($answers as $questionId => $answer) {

            // Caso seja upload
            if ($request->hasFile("answers.$questionId")) {
                $file = $request->file("answers.$questionId");
                $path = $file->store('complaints_files', 'public');

                Response::create([
                    'complaint_id' => $complaint->id,
                    'question_id' => $questionId,
                    'text_response' => $path,
                    'response_option_id' => null,
                ]);
            }
            elseif (is_array($answer)) { // Caso seja checkbox (múltiplos valores)
                foreach ($answer as $optionId) {
                    Response::create([
                        'complaint_id' => $complaint->id,
                        'question_id' => $questionId,
                        'text_response' => null,
                        'response_option_id' => $optionId,
                    ]);
                }
            }

            // Caso seja radio/select (1 valor só → option_id)
            elseif (is_numeric($answer)) {
                Response::create([
                    'complaint_id' => $complaint->id,
                    'question_id' => $questionId,
                    'text_response' => null,
                    'response_option_id' => $answer,
                ]);
            }

            // Caso seja texto/textarea
            else {
                Response::create([
                    'complaint_id' => $complaint->id,
                    'question_id' => $questionId,
                    'text_response' => $answer,
                    'response_option_id' => null,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Denúncia registrada com sucesso!');
    }
}
