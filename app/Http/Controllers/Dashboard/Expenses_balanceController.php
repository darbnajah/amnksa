<?php

namespace App\Http\Controllers\Dashboard;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Paper;
use App\Models\Seller_deduction_advance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Expenses_balanceController extends Controller
{
    public function index($account, $filter = null, $paper_id = null){
        if(!Auth::user()->can('expenses_read')){
            return response('Unauthorized.', 401);
        }
        $filter = explode('_', $filter);

        $preview = isset($filter[0])? $filter[0] : null;
        $dt_from = isset($filter[1])? $filter[1] : null;
        $dt_to = isset($filter[2])? $filter[2] : null;

        $preview_url = url()->current();
        $expenses = DB::table('expense_bulletins')
            ->join('expenses', 'expenses.id', '=', 'expense_bulletins.parent_id')
            ->selectRaw('expense_bulletins.*, expenses.dt')
            ->where(function($query) use ($dt_from, $dt_to)  {
                if(isset($dt_from) && isset($dt_to)) {
                    $query->whereBetween('expenses.dt', [$dt_from, $dt_to]);
                }
            })
            ->orderBy('expenses.dt', 'ASC')
            ->get();

        $paper = ($paper_id)?
            Paper::find($paper_id) :
            Paper::where('is_default', '=', 1)->first();
        $papers = Paper::all();

        $preview = explode('=', $preview);
        $view = (isset($preview[1]) && $preview[1] == 1)? 'preview_list' : 'index';


            return view('dashboard.expenses_balance.'.$view, compact(
                'expenses',
                'papers',
                'paper',
                'dt_from',
                'dt_to',
                'preview_url'
            ));

    }
}
