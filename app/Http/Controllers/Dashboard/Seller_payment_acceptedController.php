<?php

namespace App\Http\Controllers\Dashboard;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Paie_salaries;
use App\Models\Seller_balance;
use App\Models\Seller_payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Seller_payment_acceptedController extends Controller
{
    public function index(){
        if(!Auth::user()->can('sellers_payments_accepted_read')){
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
            ->where('sellers_payments.status', '=', 1)
            ->where('sellers_payments.trans_status', '=', 0)
            ->get();

        return view('dashboard.sellers_payments_accepted.index', compact('sellers_payments'));
    }

    public function transfer_payments($account, Request $request){
        $updated_count = 0;
        $params = [
            'trans_status' => 1,
            'trans_dt' => $request->transfer_dt,
        ];
        if(isset($request->payments)) {
            $payments = explode(';', trim($request->payments));

            if(count($payments) > 0) {
                for($i = 0; $i < count($payments) -1; $i++){
                    $inv = Seller_payment::where('id', $payments[$i])->update($params);
                    if($inv) {
                        $payment = Seller_payment::find($payments[$i]);
                        if($payment){
                            Seller_balance::create([
                                'doc_id' => $payment->id,
                                'doc_type' => 'payment_valid',
                                'dt' => $request->transfer_dt,
                                'seller_id' => $payment->seller_id,
                                'contract_id' => $payment->contract_id,
                                'contract_obj' => $payment->contract_obj,
                                'label' => json_encode([
                                    'month_id' => $payment->month_id,
                                    'contract_id' => $payment->contract_id,
                                    'contract_obj' => $payment->contract_obj,
                                    'amount' => Helper::double($payment->amount),
                                    'advance' => Helper::double($payment->advance),
                                    'deduction' => Helper::double($payment->deduction),
                                    'amount_net' => Helper::double($payment->amount_net),
                                ]),
                                'debit' => Helper::double($payment->amount_net),
                            ]);
                        }

                        $updated_count++;
                    }
                }
            }
        }

        if($updated_count) {
            session()->flash('success', __('تم صرف المستحقات المختارة بنجاح!'));
            return 1;
        }
        return 0;
    }

}
