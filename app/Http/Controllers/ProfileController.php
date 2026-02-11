<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    /**
     * Exibir perfil
     */
    public function index()
    {
        $user = Auth::user();

        return view('profile.index', compact('user'));
    }

    /**
     * Tela de edição
     */
    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    /**
     * Atualizar dados
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()
            ->route('profile.index')
            ->with('success', 'Perfil atualizado com sucesso.');
    }

    /**
     * Exclusão de conta (LGPD)
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();

        // Confirmar senha antes de excluir
        $request->validate([
            'password' => 'required'
        ]);

        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Senha incorreta.');
        }

        Auth::logout();

        // Se estiver usando SoftDeletes:
        // $user->delete();

        // Se for excluir definitivamente:
        $user->delete();

        return redirect('/')->with('success', 'Conta excluída com sucesso.');
    }
}
