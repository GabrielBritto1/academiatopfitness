<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Planos;
use App\Models\AcademiaUnidade;
use App\Models\Avaliacao;
use App\Models\PlanilhaTreino;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Contagem de alunos
        $totalAlunos = User::role('aluno')->count();

        // Contagem de professores
        $totalProfessores = User::role('professor')->count();

        // Contagem de planos
        $totalPlanos = Planos::count();

        // Contagem de unidades
        $totalUnidades = AcademiaUnidade::count();

        // Contagem de avaliações
        $totalAvaliacoes = Avaliacao::count();

        // Contagem de planilhas de treino
        $totalPlanilhas = PlanilhaTreino::where('is_padrao', false)->count();

        // Últimas avaliações
        $ultimasAvaliacoes = Avaliacao::with(['aluno', 'professor'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Últimas planilhas de treino
        $ultimasPlanilhas = PlanilhaTreino::where('is_padrao', false)
            ->with(['aluno', 'professor'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('home', compact(
            'totalAlunos',
            'totalProfessores',
            'totalPlanos',
            'totalUnidades',
            'totalAvaliacoes',
            'totalPlanilhas',
            'ultimasAvaliacoes',
            'ultimasPlanilhas'
        ));
    }
}
