<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Paie;
use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Seller_payment_transferedController extends Controller
{
    public function index(){
        if(!Auth::user()->can('sellers_payments_transfered_read')){
            return response('Unauthorized.', 401);
        }
        $sellers_payments = DB::table('sellers_payments')
            ->join('contracts', 'contracts.id', '=', 'sellers_payments.contract_id')
            ->join('customers', 'customers.id', '=', 'contracts.customer_id')
            ->join('sellers', 'sellers.id', '=', 'contracts.seller_id')
            ->selectRaw('sellers_payments.*,
            contracts.status as contract_status,
            contracts.seller_commission,
            contracts.address,
            contracts.city,
            contracts.code,

            sellers.first_name as seller_name,

            customers.name_ar as customer_name,

            sellers.bank_account,
            sellers.bank_name,
            sellers.bank_iban
            ')
            ->where('sellers_payments.trans_status', '=', 1)
            ->get();
        $paper = Paper::where('is_default', '=', 1)->first();
        $papers = Paper::all();

        return view('dashboard.sellers_payments_transfered.index', compact('sellers_payments', 'paper', 'papers'));
    }

    public function preview($paper_id = null){
        $sellers_payments = DB::table('sellers_payments')
            ->join('contracts', 'contracts.id', '=', 'sellers_payments.contract_id')
            ->join('customers', 'customers.id', '=', 'contracts.customer_id')
            ->join('sellers', 'sellers.id', '=', 'contracts.seller_id')
            ->selectRaw('sellers_payments.*,
            contracts.status as contract_status,
            contracts.seller_commission,
            contracts.address,
            contracts.city,
            contracts.code,

            sellers.first_name as seller_name,

            customers.name_ar as customer_name,

            sellers.bank_account,
            sellers.bank_name,
            sellers.bank_iban
            ')
            ->where('sellers_payments.trans_status', '=', 1)
            ->get();

        $paper = ($paper_id)?
            Paper::find($paper_id) :
            Paper::where('is_default', '=', 1)->first();
        $papers = Paper::all();
        return view('dashboard.sellers_payments_transfered.preview_list', compact('sellers_payments', 'paper',
'papers'));

    }


    }
