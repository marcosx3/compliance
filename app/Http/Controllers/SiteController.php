<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    // public function index()
    // {
    //      $activeForm = Template::where('status', true)->first(); // pega apenas     
    //     return view('site', compact('activeForm'));
    // }

     public function index()
    {
        $activeForm = Template::with(['questions.options'])
            ->where('status', true)
            ->first();

        return view('site', compact('activeForm'));
    }

}
