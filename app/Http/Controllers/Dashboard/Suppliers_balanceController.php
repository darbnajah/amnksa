<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Paper;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isNull;

class Suppliers_balanceController extends Controller
{
    public function index($account, $filter = null, $paper_id = null)
    {
        if(!Auth::user()->can('suppliers_balance_read')){
            return response('Unauthorized.', 401);
        }
        $filter = explode('_', $filter);

        $supplier_id = isset($filter[0])? intval($filter[0]) * 1 : null;
        $preview = isset($filter[1])? $filter[1] : null;
        $dt_from = isset($filter[2])? $filter[2] : null;
        $dt_to = isset($filter[3])? $filter[3] : null;

        $preview_url = url()->current();
        $supplier = ($supplier_id > 0)? Supplier::find($supplier_id) : null;

        $supplier_name = isset($supplier)? $supplier->supplier_name : null;

        $balances = null;
        $whr = [];
        if($supplier_id) {
            $whr[] = ['suppliers_balance.supplier_id', '=', $supplier_id];
            $balances = DB::table('suppliers_balance')
                ->join('suppliers', 'suppliers.id', '=', 'suppliers_balance.supplier_id')
                ->selectRaw('suppliers_balance.*, suppliers.supplier_name ')
                ->where($whr)
                ->orderBy('suppliers_balance.dt', 'ASC')
                ->get();
        }

        if($dt_from && $dt_to) {
            $balances = DB::table('suppliers_balance')
                ->join('suppliers', 'suppliers.id', '=', 'suppliers_balance.supplier_id')
                ->selectRaw('suppliers_balance.*, suppliers.supplier_name ')
                ->where($whr)
                ->whereBetween('suppliers_balance.dt', [$dt_from, $dt_to])

                ->orderBy('suppliers_balance.dt', 'ASC')
                ->get();
        }
        $paper = ($paper_id)?
            Paper::find($paper_id) :
            Paper::where('is_default', '=', 1)->first();
        $papers = Paper::all();

        $preview = explode('=', $preview);
        $view = (isset($preview[1]) && $preview[1] == 1)? 'preview_list' : 'index';

        return view('dashboard.suppliers_balance.'.$view, compact(
            'balances',
            'papers',
            'paper',
            'supplier_id',
            'supplier_name',
            'dt_from',
            'dt_to',
            'preview_url'
        ));
    }

}
