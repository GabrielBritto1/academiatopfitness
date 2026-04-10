<?php

namespace App\Http\Controllers;

use App\Models\AcademiaUnidade;
use App\Models\FinancialCategory;
use App\Models\FinancialTransaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RelatorioPdfController extends Controller
{
   public function relatorioFinanceiroFiltro()
   {
      $unidades = AcademiaUnidade::orderBy('nome')->get();
      $categories = FinancialCategory::where('is_active', true)->orderBy('type')->orderBy('name')->get();

      $paymentMethods = [
         'dinheiro' => 'Dinheiro',
         'cartao' => 'Cartão',
         'pix' => 'Pix',
         'boleto' => 'Boleto',
         'transferencia' => 'Transferência',
      ];

      return view('relatorio.relatorio_financeiro_filtro', compact('unidades', 'categories', 'paymentMethods'));
   }

   public function relatorioFinanceiro(Request $request)
   {
      $filters = $request->validate([
         'date_from' => 'nullable|date',
         'date_to' => 'nullable|date',
         'academia_unidade_id' => 'nullable|exists:academia_unidades,id',
         'kind' => 'nullable|in:conta_receber,conta_pagar',
         'status' => 'nullable|in:pendente,pago,vencido,cancelado',
         'financial_category_id' => 'nullable|exists:financial_categories,id',
         'payment_method' => 'nullable|string|max:255',
      ]);

      $query = FinancialTransaction::with(['category', 'unidade', 'user'])->orderBy('created_at', 'desc');

      if (!empty($filters['date_from'])) {
         $query->whereDate('created_at', '>=', $filters['date_from']);
      }
      if (!empty($filters['date_to'])) {
         $query->whereDate('created_at', '<=', $filters['date_to']);
      }
      if (!empty($filters['academia_unidade_id'])) {
         $query->where('academia_unidade_id', $filters['academia_unidade_id']);
      }
      if (!empty($filters['kind'])) {
         $query->where('kind', $filters['kind']);
      }
      if (!empty($filters['status'])) {
         $query->where('status', $filters['status']);
      }
      if (!empty($filters['financial_category_id'])) {
         $query->where('financial_category_id', $filters['financial_category_id']);
      }
      if (!empty($filters['payment_method'])) {
         $query->where('payment_method', $filters['payment_method']);
      }

      $transactions = $query->get();

      $totais = [
         'receitas_pagas' => 0,
         'despesas_pagas' => 0,
         'receitas_a_receber' => 0,
         'despesas_pendentes' => 0,
      ];

      foreach ($transactions as $t) {
         $valorEfetivo = $t->amount_paid ?? ($t->amount - $t->discount + $t->addition);

         if ($t->kind === 'conta_receber') {
            if ($t->status === 'pago') $totais['receitas_pagas'] += $valorEfetivo;
            if ($t->status === 'pendente' || $t->status === 'vencido') $totais['receitas_a_receber'] += $valorEfetivo;
         }

         if ($t->kind === 'conta_pagar') {
            if ($t->status === 'pago') $totais['despesas_pagas'] += $valorEfetivo;
            if ($t->status === 'pendente' || $t->status === 'vencido') $totais['despesas_pendentes'] += $valorEfetivo;
         }
      }

      $totais['saldo_pago'] = $totais['receitas_pagas'] - $totais['despesas_pagas'];

      // Resumo por categoria
      $resumoPorCategoria = [];
      foreach ($transactions as $t) {
         $categoriaId = $t->financial_category_id;
         $categoriaNome = $t->category->name ?? 'Sem categoria';
         $valorEfetivo = $t->amount_paid ?? ($t->amount - $t->discount + $t->addition);

         if (!isset($resumoPorCategoria[$categoriaId])) {
            $resumoPorCategoria[$categoriaId] = [
               'nome' => $categoriaNome,
               'tipo' => $t->category->type ?? 'receita',
               'receitas_pagas' => 0,
               'despesas_pagas' => 0,
               'receitas_a_receber' => 0,
               'despesas_pendentes' => 0,
            ];
         }

         if ($t->kind === 'conta_receber') {
            if ($t->status === 'pago') {
               $resumoPorCategoria[$categoriaId]['receitas_pagas'] += $valorEfetivo;
            } elseif ($t->status === 'pendente' || $t->status === 'vencido') {
               $resumoPorCategoria[$categoriaId]['receitas_a_receber'] += $valorEfetivo;
            }
         }

         if ($t->kind === 'conta_pagar') {
            if ($t->status === 'pago') {
               $resumoPorCategoria[$categoriaId]['despesas_pagas'] += $valorEfetivo;
            } elseif ($t->status === 'pendente' || $t->status === 'vencido') {
               $resumoPorCategoria[$categoriaId]['despesas_pendentes'] += $valorEfetivo;
            }
         }
      }

      // Ordenar por tipo (receita primeiro) e depois por nome
      usort($resumoPorCategoria, function ($a, $b) {
         if ($a['tipo'] !== $b['tipo']) {
            return $a['tipo'] === 'receita' ? -1 : 1;
         }
         return strcmp($a['nome'], $b['nome']);
      });

      $unidadeSelecionada = null;
      if (!empty($filters['academia_unidade_id'])) {
         $unidadeSelecionada = AcademiaUnidade::find($filters['academia_unidade_id']);
      }

      $categoriaSelecionada = null;
      if (!empty($filters['financial_category_id'])) {
         $categoriaSelecionada = FinancialCategory::find($filters['financial_category_id']);
      }

      $pdf = Pdf::loadView('relatorio.relatorio_pdf.relatorio_financeiro', [
         'transactions' => $transactions,
         'filters' => $filters,
         'totais' => $totais,
         'resumoPorCategoria' => $resumoPorCategoria,
         'unidadeSelecionada' => $unidadeSelecionada,
         'categoriaSelecionada' => $categoriaSelecionada,
      ])->setPaper('a4', 'landscape');

      return $pdf->stream('relatorio_financeiro.pdf');
   }
}
