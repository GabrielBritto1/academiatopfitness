<?php

use App\Http\Controllers\Modalidade\ModalidadeController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    // ROTA DE USUÁRIOS
    // Route::resource('/users', UserController::class);
    Route::get('/users', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/list', [UserController::class, 'list'])->name('user.list');
    Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
    Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/users', [UserController::class, 'store'])->name('user.store');

    // ROTA DE MODALIDADES
    // Route::resource('/modalidades', ModalidadeController::class);
    Route::get('/modalidades', [ModalidadeController::class, 'index'])->name('modalidade.index');
    Route::get('/modalidade/{id}/edit', [ModalidadeController::class, 'edit'])->name('modalidade.edit');
    Route::get('/modalidade/{id}', [ModalidadeController::class, 'show'])->name('modalidade.show');
    Route::put('/modalidade/{id}', [ModalidadeController::class, 'update'])->name('modalidade.update');
    Route::delete('/modalidade/{id}', [ModalidadeController::class, 'destroy'])->name('modalidade.destroy');
    Route::get('/modalidade/create', [ModalidadeController::class, 'create'])->name('modalidade.create');
    Route::post('/modalidades', [ModalidadeController::class, 'store'])->name('modalidade.store');
    Route::post('/modalidade/ativar/{id}', [ModalidadeController::class, 'ativador'])->name('modalidade.ativar');
});
