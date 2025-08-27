<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    
    

    public function index()
    {
        $tot_denuncias = Complaint::count();
        $denuncias_status = Complaint::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
        return view('dashboard',[
            'tot_denuncias' => $tot_denuncias,
            'denuncias_pendentes' => $denuncias_status->where('status', 'EM_ANALISE')->first()->total ?? 0,
            'denuncias_concluidas' => $denuncias_status->where('status', 'CONCLUIDA')->first()->total ?? 0,
        ]);
    }
}
