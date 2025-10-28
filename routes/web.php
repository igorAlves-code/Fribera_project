<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\loginController;
use App\Http\Controllers\MecController;
use App\Http\Controllers\AlmoxController;


Route::middleware(['auth', 'role:mec'])->group(function(){
    Route::get('/mec', [mecController::class, 'mec'])->name('mec');
    Route::get('/pesquisa', [MecController::class, 'store'])->name('show');
    Route::get('/detalhar/{id}', [MecController::class, 'detalhar'])->name('detalhar');
    Route::post('/criarRequisicao', [MecController::class, 'store'])->name('criarRequisicao');
});
Route::middleware(['auth', 'role:almox'])->group(function(){
    Route::post('/atualizarPeca', [AlmoxController::class, 'update'])->name('atualizarPeca');
    Route::get('/almox', [AlmoxController::class, 'almox'])->name('almox');
});

Route::get('/', [loginController::class, 'index'])->name('/');
Route::post('/login', [loginController::class, 'loginAttempt'])->name('auth');
Route::get('/logout', [loginController::class, 'logout'])->name('logout');




