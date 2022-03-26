<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isNull;

class Customers_balanceController extends Controller
{
    public function index($account, $filter = null, $paper_id = null)
    {
        if(!Auth::user()->can('customers_balance_read')){
            return response('Unauthorized.', 401);
        }
        $filter = explode('_', $filter);

        $customer_id = isset($filter[0])? intval($filter[0]) * 1 : null;
        $preview = isset($filter[1])? $filter[1] : null;
        $dt_from = isset($filter[2])? $filter[2] : null;
        $dt_to = isset($filter[3])? $filter[3] : null;

        $preview_url = url()->current();
        $customer = ($customer_id > 0)? Customer::find($customer_id) : null;
        $customer_name = isset($customer)? $customer->name_ar : null;

        $balances = null;
        $whr = [];
        if($customer_id) {
            $whr[] = ['payments.customer_id', '=', $customer_id];
            $balances = DB::table('payments')
                ->join('customers', 'customers.id', '=', 'payments.customer_id')
                ->selectRaw('payments.*,
            customers.name_ar as customer_name
            ')
                ->where($whr)
                ->orderBy('payments.dt', 'ASC')
                ->get();
        }

        if($dt_from && $dt_to) {
            $balances = DB::table('payments')
                ->join('customers', 'customers.id', '=', 'payments.customer_id')
                ->selectRaw('payments.*,
            customers.name_ar as customer_name
            ')
                ->where($whr)
                ->whereBetween('payments.dt', [$dt_from, $dt_to])
                ->orderBy('payments.dt', 'ASC')
                ->get();
        }
        $default_paper_id =  Paper::where('is_default', '=', 1)->first()->id;

        $paper = ($paper_id)?
            Paper::find($paper_id) :
            Paper::where('is_default', '=', 1)->first();
        $papers = Paper::all();

        $preview = explode('=', $preview);
        $view = (isset($preview[1]) && $preview[1] == 1)? 'preview_list' : 'index';
        return view('dashboard.customers_balance.'.$view, compact(
            'balances',
            'papers',
            'paper',
            'customer_id',
            'customer_name',
            'dt_from',
            'dt_to',
            'preview_url'
        ));
    }

}
