<?php
namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BudgetInsentifController extends Controller
{
    public function index()
    {
        $budget = \App\Models\BudgetInsentif::first() ?? new \App\Models\BudgetInsentif(['total_insentif' => 0]);
        $totalBudget = $budget->total_insentif;
        
        $usedInsentif = \App\Models\Transaksi::with('produk')->get()->sum(function($t) {
            return $t->produk->produk_insentif ?? 0;
        });

        $sisaBudget = $totalBudget - $usedInsentif;
        
        return view('kasir.budget_insentif.index', [
            'sisaBudget' => $sisaBudget,
            'totalInsentif' => $usedInsentif,
            'totalBudget' => $totalBudget
        ]);
    }

    public function pantau()
    {
        $budget = \App\Models\BudgetInsentif::first() ?? new \App\Models\BudgetInsentif(['total_insentif' => 0]);
        $totalBudget = $budget->total_insentif;
        
        $usedInsentif = \App\Models\Transaksi::with('produk')->get()->sum(function($t) {
            return $t->produk->produk_insentif ?? 0;
        });

        $sisaBudget = $totalBudget - $usedInsentif;
        $budgetHistories = \App\Models\BudgetHistory::latest()->get();
        
        return view('kasir.budget_insentif.pantau', [
            'totalBudget' => $totalBudget,
            'totalInsentif' => $usedInsentif,
            'sisaBudget' => $sisaBudget,
            'budgetHistories' => $budgetHistories
        ]);
    }

    public function update(Request $request)
    {
        return redirect()->route('kasir.budget_insentif.index')->with('success', 'Budget updated');
    }
}
