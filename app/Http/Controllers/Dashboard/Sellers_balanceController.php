<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Paper;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isNull;

class Sellers_balanceController extends Controller
{
    public function index($account, $filter = null, $paper_id = null)
    {
        if(!Auth::user()->can('sellers_balance_read')){
            return response('Unauthorized.', 401);
        }
        $filter = explode('_', $filter);

        $seller_id = isset($filter[0])? intval($filter[0]) * 1 : null;
        $preview = isset($filter[1])? $filter[1] : null;
        $dt_from = isset($filter[2])? $filter[2] : null;
        $dt_to = isset($filter[3])? $filter[3] : null;

        $preview_url = url()->current();
        $seller = ($seller_id > 0)? Seller::find($seller_id) : null;

        $seller_name = isset($seller)? $seller->first_name : null;

        $balances = null;
        $whr = [];
        if($seller_id) {
            $whr[] = ['sellers_balance.seller_id', '=', $seller_id];
            $balances = DB::table('sellers_balance')
                ->join('sellers', 'sellers.id', '=', 'sellers_balance.seller_id')
                ->selectRaw('sellers_balance.*, sellers.first_name as seller_name ')
                ->where($whr)
                ->orderBy('sellers_balance.dt', 'ASC')
                ->get();
        }

        if($dt_from && $dt_to) {
            $balances = DB::table('sellers_balance')
                ->join('contracts', 'contracts.id', '=', 'sellers_payments.contract_id')
                ->join('customers', 'customers.id', '=', 'contracts.customer_id')
                ->join('sellers', 'sellers.id', '=', 'sellers_balance.seller_id')
                ->selectRaw('sellers_balance.*, sellers.first_name as seller_name ')
                ->where($whr)
                ->whereBetween('sellers_balance.dt', [$dt_from, $dt_to])

                ->orderBy('sellers_balance.dt', 'ASC')
                ->get();
        }
        $paper = ($paper_id)?
            Paper::find($paper_id) :
            Paper::where('is_default', '=', 1)->first();
        $papers = Paper::all();

        $preview = explode('=', $preview);
        $view = (isset($preview[1]) && $preview[1] == 1)? 'preview_list' : 'index';

        return view('dashboard.sellers_balance.'.$view, compact(
            'balances',
            'papers',
            'paper',
            'seller_id',
            'seller_name',
            'dt_from',
            'dt_to',
            'preview_url'
        ));
    }

}
