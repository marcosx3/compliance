<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TemplateController;



// Route::get('/compliant', function () {
//     return view('site');
// });

Route::get('/', [SiteController::class,'index']); // rota pública

Route::controller(AuthController::class)->group(function () {
    // Login
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.submit');
    // Cadastro
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register')->name('register.submit');
    // Logout
    Route::post('/logout', 'logout')->name('logout');
});


// Área restrita

Route::middleware(['auth'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
   
});

Route::middleware(['auth', 'admin'])->prefix('template')->name('template.')->group(function () {
    Route::get('/', [TemplateController::class, 'index'])->name('index');
    Route::get('/create', [TemplateController::class, 'create'])->name('create');
    Route::post('/', [TemplateController::class, 'store'])->name('store');
    Route::get('/{template}/edit', [TemplateController::class, 'edit'])->name('edit');
    Route::put('/{template}', [TemplateController::class, 'update'])->name('update');
    Route::delete('/{template}', [TemplateController::class, 'destroy'])->name('destroy');
});
//Denuncias
Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
