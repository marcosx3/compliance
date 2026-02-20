<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /**
         * @var User
         */
        $user = Auth::user();

        // =============================
        // BASE DA QUERY (LISTAGEM)
        // =============================
        if ($user->isAdmin()) {
            $query = Complaint::query();
        } else {
            $query = Complaint::where('user_id', $user->id);
        }

        // =============================
        // 🔎 FILTROS
        // =============================

        // Protocolo
        if ($request->filled('protocolo')) {
            $query->where('protocol', 'like', '%' . $request->protocolo . '%');
        }

        // Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Data início
        if ($request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }

        // Data fim
        if ($request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }

        // =============================
        // 🔽 ORDENAÇÃO
        // =============================
        $ordenarPor = $request->get('ordenar_por', 'created_at');
        $direcao = $request->get('direcao', 'desc');

        $query->orderBy($ordenarPor, $direcao);

        // =============================
        // 📄 PAGINAÇÃO
        // =============================
        $denuncias = $query->paginate(10)->withQueryString();

        // =============================
        // 📊 TOTAIS (SEM FILTRO)
        // =============================
        if ($user->isAdmin()) {

            $tot_denuncias = Complaint::count();

            $denuncias_status = Complaint::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status');

            $denuncias_pendentes = Complaint::where('status', 'EM_ANALISE')->count();
            $denuncias_concluidas = Complaint::where('status', 'CONCLUIDA')->count();

            return view('dashboard_admin', compact(
                'tot_denuncias',
                'denuncias_status',
                'denuncias_pendentes',
                'denuncias_concluidas',
                'denuncias'
            ));
        }

        // =============================
        // USUÁRIO COMUM
        // =============================
        return view('dashboard_user', compact('denuncias'));
    }
}