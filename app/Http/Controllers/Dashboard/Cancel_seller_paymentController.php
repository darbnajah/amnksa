<?php

namespace App\Http\Controllers\Dashboard;

use App\Helper\Helper;
use App\Http\Controllers\Controller;

use App\Models\Seller_balance;
use App\Models\Seller_deduction_advance;
use App\Models\Seller_payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Cancel_seller_paymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::user()->can('sellers_payments_read')){
            return response('Unauthorized.', 401);
        }
        $sellers_payments = DB::table('sellers_payments')
            ->join('contracts', 'contracts.id', '=', 'sellers_payments.contract_id')
            ->join('customers', 'customers.id', '=', 'contracts.customer_id')
            ->join('sellers', 'sellers.id', '=', 'contracts.seller_id')
            ->join('users', 'users.id', '=', 'sellers_payments.created_by')

            ->selectRaw('sellers_payments.*,
            contracts.status as contract_status,
            contracts.seller_commission,
            contracts.address,
            contracts.city,
            contracts.code,

            sellers.first_name as seller_name,

            customers.name_ar as customer_name,

            CONCAT(users.first_name, " ", users.last_name) AS username

            ')
            ->where('sellers_payments.trans_status', '=', 1)

            ->orderBy('sellers_payments.status', 'ASC')
            ->orderBy('sellers_payments.trans_status', 'ASC')

            ->get();

        return view('dashboard.cancel_sellers_payments.index', compact('sellers_payments'));
    }

    public function cancel_seller_payment_transfer($account, $payment_id){

        $payment = Seller_payment::where('id', $payment_id)->update([
            'trans_status' => 0,
            'trans_dt' => NULL,
        ]);

        DB::table('sellers_balance')
            ->where('doc_id', $payment_id)
            ->where('doc_type', 'payment_valid')
            ->delete();

        if($payment){
            session()->flash('success', __('تم إلغاء صرف المستحق بنجاح !'));
            return 1;
        }
    }
}
