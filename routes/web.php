<?php

use App\Http\Controllers\Modalidade\AcademiaUnidadeController;
use App\Http\Controllers\Modalidade\ModalidadeController;
use App\Http\Controllers\Planos\PlanosController;
use App\Http\Controllers\User\AlunoController;
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
   Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
   Route::middleware(['can:admin'])->group(function () {
      Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
      Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
      Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
      Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
      Route::post('/users', [UserController::class, 'store'])->name('user.store');
   });

   // ROTA DE ALUNOS
   Route::get('/alunos', [AlunoController::class, 'index'])->name('aluno.index');
   Route::post('/alunos', [AlunoController::class, 'store'])->name('aluno.store');
   Route::get('/aluno/{id}', [AlunoController::class, 'show'])->name('aluno.show');
   Route::get('/aluno/{id}/edit', [AlunoController::class, 'edit'])->name('aluno.edit');
   Route::put('/aluno/{id}', [AlunoController::class, 'update'])->name('aluno.update');

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

   // ROTA DE UNIDADES
   Route::get('/unidades', [AcademiaUnidadeController::class, 'index'])->name('unidade.index');
   Route::post('/unidades', [AcademiaUnidadeController::class, 'store'])->name('unidade.store');
   Route::get('/unidade/{id}/modalidadesUnidade', [AcademiaUnidadeController::class, 'modalidades'])->name('unidade.modalidadesUnidade');

   // ROTA DE PLANOS
   Route::get('/planos', [PlanosController::class, 'index'])->name('planos.index');
   Route::post('/planos', [PlanosController::class, 'store'])->name('planos.store');
   Route::get('/planos/create', [PlanosController::class, 'create'])->name('planos.create');
   Route::get('/planos/{id}', [PlanosController::class, 'show'])->name('planos.show');
   Route::get('/planos/{id}/edit', [PlanosController::class, 'edit'])->name('planos.edit');
   Route::put('/planos/{id}', [PlanosController::class, 'update'])->name('planos.update');
   Route::delete('/planos/{id}', [PlanosController::class, 'destroy'])->name('planos.destroy');
});
