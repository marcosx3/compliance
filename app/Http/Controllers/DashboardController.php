<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{


public function index()
{   
    /**
     * @var User
     */
    $user = Auth::user();

    if ($user->isAdmin()) {
        // Admin vê todas as denúncias
        $tot_denuncias = Complaint::count();

        $denuncias_status = Complaint::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status'); // retorna array [status => total]

        $denuncias_pendentes = Complaint::where('status', 'EM_ANALISE')->count();
        $denuncias_concluidas = Complaint::where('status', 'CONCLUIDA')->count();

        $denuncias = Complaint::orderBy('created_at', 'desc')->paginate(10);

        return view('dashboard_admin', compact(
            'tot_denuncias',
            'denuncias_status',
            'denuncias_pendentes',
            'denuncias_concluidas',
            'denuncias'
        ));
    } else {
        // Usuário vê apenas suas próprias denúncias
        $denuncias = Complaint::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard_user', compact('denuncias'));
    }
}



}
