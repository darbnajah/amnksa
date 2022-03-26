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

class Seller_paymentController extends Controller
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
            ->where('sellers_payments.trans_status', '<>', 1)

            ->orderBy('sellers_payments.status', 'ASC')
            ->orderBy('sellers_payments.trans_status', 'ASC')

            ->get();

        return view('dashboard.sellers_payments.index', compact('sellers_payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Auth::user()->can('sellers_payments_create')){
            return response('Unauthorized.', 401);
        }
        $months = \App\Helper\Helper::months();
        return view('dashboard.sellers_payments.edit', compact('months'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::user()->can('sellers_payments_create')){
            return response('Unauthorized.', 401);
        }
        $params = $request->all();
        $params['amount'] = Helper::double($request->amount);
        $params['advance'] = Helper::double($request->advance);
        $params['deduction'] = Helper::double($request->deduction);
        $params['amount_net'] = Helper::double($request->amount_net);
        $params['created_by'] = Auth::id();

        $payment = Seller_payment::create($params);

        if($payment) {
            Seller_balance::create([
                'doc_id' => $payment->id,
                'doc_type' => 'payment_instance',
                'dt' => $request->dt,
                'seller_id' => $request->seller_id,
                'label' => json_encode([
                    'month_id' => $request->month_id,
                    'contract_id' => $request->contract_id,
                    'contract_obj' => $request->contract_obj,
                    'amount' => Helper::double($request->amount),
                    'advance' => Helper::double($request->advance),
                    'deduction' => Helper::double($request->deduction),
                    'amount_net' => Helper::double($request->amount_net),
                ]),
                'credit' => Helper::double($request->amount_net),
            ]);
            session()->flash('success', __('site.added_successfully'));
            $data = ['valid' => 1, 'route' => route('dashboard.sellers_payments.index')];
            return json_encode($data);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Paie  $paie
     * @return \Illuminate\Http\Response
     */
    public function show($account, Seller_payment $seller_payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Paie  $paie
     * @return \Illuminate\Http\Response
     */
    public function edit($account, $id)
    {
        if(!Auth::user()->can('sellers_payments_update')){
            return response('Unauthorized.', 401);
        }
        $payment = DB::table('sellers_payments')
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

            customers.name_ar as customer_name

            ')
            ->where('sellers_payments.id', '=', $id)
            ->get()->first();

        $contract = DB::table('contracts')
            ->join('customers', 'customers.id', '=', 'contracts.customer_id')
            ->selectRaw('contracts.*, customers.name_ar as customer_name')

            ->where('contracts.status', '=', 1)
            ->where('contracts.id', '=', $payment->contract_id)
            ->get()->first();
        $da = new Seller_deduction_advanceController();
        $da = $da->total_rest_deductions_advances_by_seller($account, $payment->seller_id);
        $da = json_decode($da);
        $total_deductions = $da->total_deductions;
        $total_advances = $da->total_advances;
        $months = \App\Helper\Helper::months();

        return view('dashboard.sellers_payments.edit', compact('payment', 'total_deductions',
            'total_advances',
            'months',
            'contract'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Paie  $paie
     * @return \Illuminate\Http\Response
     */
    public function update($account, Request $request, $id)
    {
        if(!Auth::user()->can('sellers_payments_update')){
            return response('Unauthorized.', 401);
        }
        $payment = Seller_payment::where('id', '=', $id)
            ->update([
                'dt' => $request->dt,
                'month_id' => $request->month_id,
                'seller_id' => $request->seller_id,
                'contract_id' => $request->contract_id,
                'amount' => Helper::double($request->amount),
                'advance' => Helper::double($request->advance),
                'deduction' => Helper::double($request->deduction),
                'amount_net' => Helper::double($request->amount_net),
                'status' => 0,
                'deny_notes' => NULL,
                'created_by' => Auth::id(),

            ]);
        if($payment){
            session()->flash('success', __('تم تعديل المستحق بنجاح !'));
            $data = ['valid' => 1, 'route' => route('dashboard.sellers_payments.index')];
            return json_encode($data);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Paie  $paie
     * @return \Illuminate\Http\Response
     */
    public function destroy($account, Seller_payment $paie)
    {
        //
    }

    public function accept_payments($account, Request $request){
        $updated_count = 0;
        $params = [
            'status' => 1,
            'accept_dt' => $request->accept_dt,
        ];
        if(isset($request->payments)) {
            $payments = explode(';', trim($request->payments));

            if(count($payments) > 0) {
                for($i = 0; $i < count($payments) -1; $i++){
                    $inv = Seller_payment::where('id', $payments[$i])->update($params);
                    if($inv) {
                        $payment = Seller_payment::find($payments[$i]);
                        if($payment){
                            if($payment->advance > 0){
                                $sda = Seller_deduction_advance::create([
                                    'dt' => $request->accept_dt,
                                    'label' => 'اقتطاع سلفة',
                                    'debit' => 0,
                                    'credit' => $payment->advance,
                                    'type' => 'advance',
                                    'seller_id' => $payment->seller_id,
                                    'payment_id' => $payment->id
                                ]);
                                if($sda) {
                                    Seller_balance::create([
                                        'doc_id' => $payment->id,
                                        'doc_type' => 'advance',
                                        'dt' => $request->accept_dt,
                                        'seller_id' => $payment->seller_id,
                                        'label' => json_encode(['label' => 'اقتطاع سلفة']),
                                        'credit' => Helper::double($payment->advance),
                                    ]);
                                }
                            }
                            if($payment->deduction > 0){
                                $sda = Seller_deduction_advance::create([
                                    'dt' => $request->accept_dt,
                                    'label' => 'اقتطاع خصم',
                                    'debit' => 0,
                                    'credit' => $payment->deduction,
                                    'type' => 'deduction',
                                    'seller_id' => $payment->seller_id,
                                    'payment_id' => $payment->id
                                ]);
                                if($sda) {
                                    Seller_balance::create([
                                        'doc_id' => $payment->id,
                                        'doc_type' => 'deduction',
                                        'dt' => $request->accept_dt,
                                        'seller_id' => $payment->seller_id,
                                        'label' => json_encode(['label' => 'اقتطاع خصم']),
                                        'credit' => Helper::double($payment->deduction),
                                    ]);
                                }
                            }
                        }

                        $updated_count++;
                    }
                }
            }
        }

        if($updated_count) {
            session()->flash('success', __('تم تعميد المستحقات المختارة بنجاح!'));
            return 1;
        }
        return 0;
    }

    public function deny_payments($account, Request $request){
        $updated_count = 0;
        $params = [
            'status' => -1,
            'accept_dt' => NULL,
            'deny_notes' => $request->deny_notes,
        ];
        if(isset($request->payments)) {
            $payments = explode(';', trim($request->payments));

            if(count($payments) > 0) {
                for($i = 0; $i < count($payments) -1; $i++){
                    $inv = Seller_payment::where('id', $payments[$i])->update($params);
                    if($inv) {
                        $updated_count++;
                    }
                }
            }
        }

        if($updated_count) {
            session()->flash('success', __('تم رفض تعميد المستحقات المختارة بنجاح!'));
            return 1;
        }
        return 0;
    }

    public function contracts_by_seller($account, $seller_id){
        $contracts = DB::table('contracts')
            ->join('customers', 'customers.id', '=', 'contracts.customer_id')
            ->selectRaw('contracts.*, customers.name_ar as customer_name')

            ->where('contracts.status', '=', 1)
            ->where('seller_id', '=',$seller_id)
            ->get();
        return view('dashboard.sellers_payments.contracts_by_seller', compact(
            'contracts'
        ));
    }

    public function cancel_seller_payment_accept($account, $payment_id){
        $payment = Seller_payment::where('id', $payment_id)->update([
            'status' => 0,
            'accept_dt' => NULL,
        ]);

        DB::table('seller_deduction_advances')->where('payment_id', $payment_id)->delete();
        DB::table('sellers_balance')
            ->where('doc_id', $payment_id)
            ->where('doc_type', 'deduction')
            ->orwhere('doc_type', 'advance')
            ->delete();


        if($payment){
            session()->flash('success', __('تم إلغاء تعميد المستحق بنجاح !'));
            return 1;
        }
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
    public function delete_seller_payment($account, $payment_id){
        if(!Auth::user()->can('sellers_payments_delete')){
            return response('Unauthorized.', 401);
        }
        $payment = Seller_payment::find($payment_id);
        DB::table('seller_deduction_advances')->where('payment_id', $payment_id)->delete();
        DB::table('sellers_balance')
            ->where('doc_id', $payment_id)
            ->where('doc_type', 'payment_instance')
            ->orwhere('doc_type', 'payment_valid')
            ->orwhere('doc_type', 'deduction')
            ->orwhere('doc_type', 'advance')
            ->delete();

        $rs = $payment->delete();
        if($rs){
            session()->flash('success', __('site.deleted_successfully'));
            return 1;
        }
    }
}
