<?php

use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\AccessControlController;
use App\Http\Controllers\Financeiro\FinancialCategoryController;
use App\Http\Controllers\Financeiro\FinancialNotificationController;
use App\Http\Controllers\Financeiro\FinancialTransactionController;
use App\Http\Controllers\Modalidade\AcademiaUnidadeController;
use App\Http\Controllers\Modalidade\ModalidadeController;
use App\Http\Controllers\PlanilhaTreinoController;
use App\Http\Controllers\Planos\PlanosController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RelatorioPdfController;
use App\Http\Controllers\TreinoController;
use App\Http\Controllers\TreinoExercicioController;
use App\Http\Controllers\User\AlunoController;
use App\Http\Controllers\User\ProfessorController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\WhatsappInstanceController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', function () {
   return view('auth.login');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
   Route::get('/register', function () {
      return redirect()->route('home');
   })->name('register');
   Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
   Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');
   Route::get('/perfil/senha', [ProfileController::class, 'editPassword'])->name('profile.password.edit');
   Route::put('/perfil/senha', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

   // ROTA DE USUÁRIOS
   Route::middleware(['can:users.manage'])->group(function () {
      Route::get('/users', [UserController::class, 'index'])->name('user.index');
      Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
      Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
      Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
      Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
      Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
      Route::post('/users', [UserController::class, 'store'])->name('user.store');
   });

   Route::middleware(['can:roles.manage'])->group(function () {
      Route::get('/access-control', [AccessControlController::class, 'index'])->name('access-control.index');
      Route::post('/access-control/roles', [AccessControlController::class, 'storeRole'])->name('access-control.roles.store');
      Route::post('/access-control/permissions', [AccessControlController::class, 'storePermission'])->name('access-control.permissions.store');
      Route::put('/access-control/roles/{id}/permissions', [AccessControlController::class, 'syncRolePermissions'])->name('access-control.roles.permissions.sync');
   });

   // ROTA DE ALUNOS
   Route::get('/alunos', [AlunoController::class, 'index'])->name('aluno.index');
   Route::get('/aluno-foto', [AlunoController::class, 'photo'])->name('aluno.photo');
   Route::get('/aluno/{id}', [AlunoController::class, 'show'])->name('aluno.show');
   Route::post('/carrinhodeplanos', [AlunoController::class, 'store2'])->name('aluno.store2');
   Route::post('/aluno/{id}/whatsapp-cobranca', [AlunoController::class, 'sendBillingWhatsappAlert'])
      ->middleware(['can:financeiro.manage'])
      ->name('aluno.whatsapp.billing-alert.send');
   Route::post('/aluno/{id}/cobranca/email', [AlunoController::class, 'sendBillingEmailAlert'])
      ->middleware(['can:financeiro.manage'])
      ->name('aluno.billing.email.send');
   Route::post('/aluno/{id}/aniversario/email', [AlunoController::class, 'sendBirthdayEmailGreeting'])
      ->middleware(['can:financeiro.manage'])
      ->name('aluno.birthday.email.send');
   Route::post('/aluno/{id}/aniversario/whatsapp', [AlunoController::class, 'sendBirthdayWhatsappGreeting'])
      ->middleware(['can:financeiro.manage'])
      ->name('aluno.birthday.whatsapp.send');
   Route::middleware(['can:students.manage'])->group(function () {
      Route::post('/alunos', [AlunoController::class, 'store'])->name('aluno.store');
      Route::get('/aluno/{id}/edit', [AlunoController::class, 'edit'])->name('aluno.edit');
      Route::put('/aluno/{id}', [AlunoController::class, 'update'])->name('aluno.update');
      Route::get('/aluno/{id}/planos/{contractId}/edit', [AlunoController::class, 'editPlan'])->name('aluno.planos.edit');
      Route::put('/aluno/{id}/planos/{contractId}', [AlunoController::class, 'updatePlan'])->name('aluno.planos.update');
      Route::post('/aluno/{id}/toggleStatus', [AlunoController::class, 'toggleStatus'])->name('aluno.toggleStatus');
   });

   Route::middleware(['can:whatsapp.manage'])->group(function () {
      Route::get('/whatsapp/instancias', [WhatsappInstanceController::class, 'index'])->name('whatsapp.instances.index');
      Route::get('/whatsapp/instancias/{id}/editar', [WhatsappInstanceController::class, 'edit'])->name('whatsapp.instances.edit');
      Route::post('/whatsapp/instancias', [WhatsappInstanceController::class, 'store'])->name('whatsapp.instances.store');
      Route::put('/whatsapp/instancias/{id}', [WhatsappInstanceController::class, 'update'])->name('whatsapp.instances.update');
      Route::delete('/whatsapp/instancias/{id}', [WhatsappInstanceController::class, 'destroy'])->name('whatsapp.instances.destroy');
   });

   // ROTA DE PROFESSORES
   Route::get('/professores', [ProfessorController::class, 'index'])->name('professor.index');
   Route::middleware(['can:professors.manage'])->group(function () {
      Route::post('/professores', [ProfessorController::class, 'store'])->name('professor.store');
   });

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
   Route::get('/unidade/{id}/edit', [AcademiaUnidadeController::class, 'edit'])->name('unidade.edit');
   Route::put('/unidade/{id}', [AcademiaUnidadeController::class, 'update'])->name('unidade.update');
   Route::get('/unidade/{id}/modalidadesUnidade', [AcademiaUnidadeController::class, 'modalidades'])->name('unidade.modalidadesUnidade');

   // ROTA DE PLANOS
   Route::get('/planos', [PlanosController::class, 'index'])->name('planos.index');
   Route::get('/planos/{id}', [PlanosController::class, 'show'])->name('planos.show');
   Route::get('/carrinhodeplanos', [PlanosController::class, 'carrinho'])->name('planos.carrinho');
   Route::middleware(['can:plans.manage'])->group(function () {
      Route::post('/planos', [PlanosController::class, 'store'])->name('planos.store');
      Route::get('/planos/create', [PlanosController::class, 'create'])->name('planos.create');
      Route::get('/planos/{id}/edit', [PlanosController::class, 'edit'])->name('planos.edit');
      Route::put('/planos/{id}', [PlanosController::class, 'update'])->name('planos.update');
      Route::delete('/planos/{id}', [PlanosController::class, 'destroy'])->name('planos.destroy');
   });

   // ROTA DE PLANILHA DE TREINO
   Route::get('/planilha-treino', [PlanilhaTreinoController::class, 'index'])->name('planilha-treino.index');
   Route::get('/planilha-treino/create', [PlanilhaTreinoController::class, 'create'])->name('planilha-treino.create');
   Route::post('/planilha-treino', [PlanilhaTreinoController::class, 'store'])->name('planilha-treino.store');
   Route::get('/planilha-treino/{id}', [PlanilhaTreinoController::class, 'show'])->name('planilha-treino.show');
   Route::get('/planilha-treino/{id}/edit', [PlanilhaTreinoController::class, 'edit'])->name('planilha-treino.edit');
   Route::put('/planilha-treino/{id}', [PlanilhaTreinoController::class, 'update'])->name('planilha-treino.update');
   Route::delete('/planilha-treino/{id}', [PlanilhaTreinoController::class, 'destroy'])->name('planilha-treino.destroy');
   Route::get('/planilha-treino/{id}/planilha_treino_pdf', [PlanilhaTreinoController::class, 'planilhaTreinoPdf'])->name('planilha-treino.planilha_treino_pdf');

   // ROTA DE TREINOS
   Route::get('/treino/create', [TreinoController::class, 'create'])->name('treino.create');
   Route::post('/treino', [TreinoController::class, 'store'])->name('treino.store');
   Route::get('/treino/{id}', [TreinoController::class, 'show'])->name('treino.show');
   Route::get('/treino/{id}/edit', [TreinoController::class, 'edit'])->name('treino.edit');
   Route::put('/treino/{id}', [TreinoController::class, 'update'])->name('treino.update');
   Route::delete('/treino/{id}', [TreinoController::class, 'destroy'])->name('treino.destroy');

   // ROTA DE EXERCÍCIOS DE TREINO
   Route::get('/treino-exercicio/create', [TreinoExercicioController::class, 'create'])->name('treino-exercicio.create');
   Route::post('/treino-exercicio', [TreinoExercicioController::class, 'store'])->name('treino-exercicio.store');
   Route::get('/treino-exercicio/{id}/edit', [TreinoExercicioController::class, 'edit'])->name('treino-exercicio.edit');
   Route::put('/treino-exercicio/{id}', [TreinoExercicioController::class, 'update'])->name('treino-exercicio.update');
   Route::delete('/treino-exercicio/{id}', [TreinoExercicioController::class, 'destroy'])->name('treino-exercicio.destroy');

   // ROTA DE AVALIAÇÕES
   Route::get('/avaliacao', [AvaliacaoController::class, 'index'])->name('avaliacao.index');
   Route::get('/avaliacao/create', [AvaliacaoController::class, 'create'])->name('avaliacao.create');
   Route::post('/avaliacao', [AvaliacaoController::class, 'store'])->name('avaliacao.store');
   Route::get('/avaliacao/{id}/view-pdf', [AvaliacaoController::class, 'viewPdf'])->name('avaliacao.view_pdf');
   Route::get('/avaliacao/{id}/filtro-pdf', [AvaliacaoController::class, 'filtroPdf'])->name('avaliacao.filtro_pdf');
   Route::get('/avaliacao/{id}/avaliacao_pdf', [AvaliacaoController::class, 'avaliacaoPdf'])->name('avaliacao.avaliacao_pdf');
   Route::get('/avaliacao/{id}/comparacao', [AvaliacaoController::class, 'comparacao'])->name('avaliacao.comparacao');
   Route::post('/avaliacao/{id}/comparacao-pdf', [AvaliacaoController::class, 'comparacaoPdf'])->name('avaliacao.comparacao_pdf');
   Route::get('/avaliacao/{id}/avaliacao_grafico', [AvaliacaoController::class, 'avaliacaoGrafico'])->name('avaliacao.avaliacao_grafico');
   Route::get('/avaliacao/{id}', [AvaliacaoController::class, 'show'])->name('avaliacao.show');
   Route::get('/avaliacao/{id}/edit', [AvaliacaoController::class, 'edit'])->name('avaliacao.edit');
   Route::put('/avaliacao/{id}', [AvaliacaoController::class, 'update'])->name('avaliacao.update');
   Route::delete('/avaliacao/{id}', [AvaliacaoController::class, 'destroy'])->name('avaliacao.destroy');

   // ROTA DE RELATÓRIOS
   Route::get('/relatorio', function () {
      return view('relatorio.index');
   })->name('relatorio.index');
   Route::get('/relatorio/relatorio_financeiro_filtro', [RelatorioPdfController::class, 'relatorioFinanceiroFiltro'])
      ->name('relatorio.relatorio_financeiro_filtro');
   Route::get('/relatorio/relatorio_pdf/relatorio_financeiro', [RelatorioPdfController::class, 'relatorioFinanceiro'])->name('relatorio.relatorio_pdf.relatorio_financeiro');

   // ROTAS DE FINANCEIRO
   // Categorias Financeiras
   Route::get('/financeiro/notificacoes/vencimentos', [FinancialNotificationController::class, 'dueSoon'])->name('financeiro.notificacoes.vencimentos');
   Route::get('/financeiro/categorias', [FinancialCategoryController::class, 'index'])->name('financeiro.categorias.index');
   Route::post('/financeiro/categorias', [FinancialCategoryController::class, 'store'])->name('financeiro.categorias.store');
   Route::put('/financeiro/categorias/{id}', [FinancialCategoryController::class, 'update'])->name('financeiro.categorias.update');
   Route::delete('/financeiro/categorias/{id}', [FinancialCategoryController::class, 'destroy'])->name('financeiro.categorias.destroy');

   // Transações Financeiras (Caixa/Fluxo de Caixa)
   Route::get('/financeiro/caixa', [FinancialTransactionController::class, 'index'])->name('financeiro.caixa.index');
   Route::get('/financeiro/transacoes/create', [FinancialTransactionController::class, 'create'])->name('financeiro.transacoes.create');
   Route::post('/financeiro/transacoes', [FinancialTransactionController::class, 'store'])->name('financeiro.transacoes.store');
   Route::get('/financeiro/transacoes/{id}', [FinancialTransactionController::class, 'show'])->name('financeiro.transacoes.show');
   Route::get('/financeiro/transacoes/{id}/edit', [FinancialTransactionController::class, 'edit'])->name('financeiro.transacoes.edit');
   Route::put('/financeiro/transacoes/{id}', [FinancialTransactionController::class, 'update'])->name('financeiro.transacoes.update');
   Route::delete('/financeiro/transacoes/{id}', [FinancialTransactionController::class, 'destroy'])->name('financeiro.transacoes.destroy');
   Route::post('/financeiro/transacoes/{id}/marcar-pago', [FinancialTransactionController::class, 'markAsPaid'])->name('financeiro.transacoes.marcar-pago');

   // Contas a Receber
   Route::get('/financeiro/contas-receber', [FinancialTransactionController::class, 'contasReceber'])->name('financeiro.contas-receber.index');

   // Contas a Pagar
   Route::get('/financeiro/contas-pagar', [FinancialTransactionController::class, 'contasPagar'])->name('financeiro.contas-pagar.index');
});
