<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        $activeForm = Template::where('status', true)->first(); // pega apenas 1
        
        return view('site', compact('activeForm'));
    }

}
