<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Deduction_advance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Seller_deductions_advancesBalanceController extends Controller
{

    public function index($account)
    {
        if(!Auth::user()->can('sellers_deductions_advances_read')){
            return response('Unauthorized.', 401);
        }

        $dabs = DB::table('seller_deduction_advances')
            ->join('sellers', 'sellers.id', '=', 'seller_deduction_advances.seller_id')
            ->selectRaw('sellers.id,
                sellers.first_name,
                sellers.last_name,
                seller_deduction_advances.type,
                COALESCE(SUM(seller_deduction_advances.debit) - SUM(seller_deduction_advances.credit), 0) AS rest
                ')
            ->groupBy('sellers.id', 'sellers.first_name', 'sellers.last_name', 'seller_deduction_advances.type' )
            ->get();

        return view('dashboard.sellers_deductions_advances_balance.index', compact('dabs'));

    }

}
