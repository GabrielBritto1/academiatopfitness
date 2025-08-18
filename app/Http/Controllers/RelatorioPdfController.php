<?php

namespace App\Http\Controllers;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RelatorioPdfController extends Controller
{
   public function relatorioFinanceiro(Request $request)
   {
      $status = $request->input('status');
      $users = User::whereHas('roles', function ($query) use ($status) {
         $query->where('name', 'aluno');
         if (!is_null($status) && $status !== '') {
            $query->where('status', $status);
         }
      })->get();
      $pdf = Pdf::loadView('relatorio.relatorio_pdf.relatorio_financeiro', compact('users'))
         ->setPaper('a4', 'landscape');
      return $pdf->stream('relatorio_financeiro.pdf');
   }
}
