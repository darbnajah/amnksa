<?php

namespace App\Http\Controllers\Dashboard;

use App\Helper\Helper;
use App\Http\Controllers\Controller;

use App\Models\Bulletin;
use App\Models\Company;
use App\Models\Payment;
use App\Models\Seller_balance;
use App\Models\Seller_payment;
use App\Models\Supplier_balance;
use App\Models\Supplier_payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::user()->can('payments_read')){
            return response('Unauthorized.', 401);
        }
        $payments = DB::table('payments')
            ->join('customers', 'customers.id', '=', 'payments.customer_id')
            ->join('users', 'users.id', '=', 'payments.created_by')
            ->selectRaw('payments.*,
                    customers.name_ar as customer_name,
                    CONCAT(users.first_name, " ", users.last_name) AS username
            ')
            ->where('payments.doc_type', '=', 'payment')
            ->orderBy('payments.id', 'DESC')
            ->get();

        return view('dashboard.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Auth::user()->can('payments_create')){
            return response('Unauthorized.', 401);
        }
        $payment = DB::table('payments')->latest()->first();
        $doc_id = ($payment)? $payment->id + 1 : 1;

        $months = \App\Helper\Helper::months();
        $company = Company::find(1);

        return view('dashboard.payments.edit', compact('doc_id', 'months', 'company'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($account, Request $request)
    {
        if(!Auth::user()->can('payments_create')){
            return response('Unauthorized.', 401);
        }
        $company = Company::find(1);

        $payment = Payment::create([
            'doc_id' => $request->doc_id,
            'doc_type' => 'payment',
            'dt' => $request->dt,
            'month_id' => Helper::month($request->dt),
            'label' => $request->label,
            'number' => $request->number,
            'customer_id' => $request->customer_id,
            'contract_id' => $request->contract_id,
            'seller_id' => $request->seller_id,
            'credit' => $request->credit,
            'created_by' => Auth::id(),
        ]);

        if($payment) {
            if(!$company->factor && intval($request->seller_id) > 0){
                $seller_payment = Seller_payment::create([
                    'dt' => $request->dt,
                    'month_id' => Helper::month($request->dt),
                    'seller_id' => $request->seller_id,
                    'contract_id' => $request->contract_id,
                    'contract_obj' => $request->contract_obj,
                    'amount' => Helper::double($request->amount),
                    'advance' => Helper::double($request->advance),
                    'deduction' => Helper::double($request->deduction),
                    'amount_net' => Helper::double($request->amount_net),
                    'status' => -2,
                    'created_by' => Auth::id(),
                ]);
                if($seller_payment) {
                    Seller_balance::create([
                        'doc_id' => $seller_payment->id,
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
                }
            }
            if($company->factor && intval($request->supplier_id) > 0){
                $supplier_payment = Supplier_payment::create([
                    'dt' => $request->dt,
                    'month_id' => $request->month_id,
                    'supplier_id' => $request->supplier_id,
                    'contract_id' => $request->contract_id,
                    'contract_obj' => $request->contract_obj,
                    'supplier_amount' => $request->supplier_amount,
                ]);

                if($supplier_payment) {
                    Supplier_balance::create([
                        'doc_id' => $supplier_payment->id,
                        'doc_type' => 'payment_instance',
                        'dt' => $request->dt,
                        'supplier_id' => $request->supplier_id,
                        'label' => json_encode([
                            'month_id' => $request->month_id,
                            'contract_id' => $request->contract_id,
                            'contract_obj' => $request->contract_obj,
                            'supplier_amount' => Helper::double($request->supplier_amount),
                        ]),
                        'debit' => Helper::double($request->supplier_amount),
                    ]);
                }
            }

            session()->flash('success', __('site.added_successfully'));
            $data = ['valid' => 1, 'route' => route('dashboard.payments.index')];
            return json_encode($data);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show($account, Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit($account, Payment $payment)
    {
        if(!Auth::user()->can('payments_update')){
            return response('Unauthorized.', 401);
        }
        $payment = DB::table('payments')
            ->join('customers', 'customers.id', '=', 'payments.customer_id')
            ->selectRaw('payments.*,
            customers.name_ar as customer_name
            ')
            ->where('payments.id', '=', $payment->id)
            ->get()->first();

        $contract = DB::table('contracts')
            ->join('customers', 'customers.id', '=', 'contracts.customer_id')
            ->selectRaw('contracts.*, customers.name_ar as customer_name')

            ->where('contracts.status', '=', 1)
            ->where('contracts.id', '=', $payment->contract_id)
            ->get()->first();

        $months = \App\Helper\Helper::months();
        $company = Company::find(1);

        return view('dashboard.payments.edit', compact('payment',
            'months',
            'contract',
            'company'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update($account, Request $request, $id)
    {
        if(!Auth::user()->can('payments_update')){
            return response('Unauthorized.', 401);
        }
        $payment = Payment::where('id', '=', $id)
            ->update([
                'dt' => $request->dt,
                'month_id' => Helper::month($request->dt),
                'label' => $request->label,
                'number' => $request->number,
                'customer_id' => $request->customer_id,
                'contract_id' => $request->contract_id,
                'seller_id' => $request->seller_id,
                'credit' => Helper::double($request->credit),
                'created_by' => Auth::id(),
            ]);
        if($payment){
            session()->flash('success', __('تم تعديل التحصيل بنجاح !'));
            $data = ['valid' => 1, 'route' => route('dashboard.payments.index')];
            return json_encode($data);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy($account, Payment $payment)
    {
        if(!Auth::user()->can('payments_delete')){
            return response('Unauthorized.', 401);
        }
        $rs = $payment->delete();
        if($rs){
            session()->flash('success', __('site.deleted_successfully'));
            return redirect()->route('dashboard.payments.index');
        }
    }

    public function contracts_by_customer($account, $customer_id){
        $company = Company::find(1);

        $contracts = DB::table('contracts')
            ->selectRaw('contracts.*')
            ->addSelect(['customer_name' => DB::table('customers')
                ->selectRaw('customers.name_ar')
                ->where('customers.id', '=', $customer_id)
                ->limit(1)
            ])
            ->where('contracts.status', '=', 1)
            ->where('customer_id', '=',$customer_id)
            ->get();

        return view('dashboard.payments.contracts_by_customer', compact(
            'contracts',
            'company'
        ));
    }

    public function last_payment_by_contract($account, $contract_id){
        $balance = DB::table('sellers_balance')
            ->join('sellers', 'sellers.id', '=', 'sellers_balance.seller_id')
            ->selectRaw('sellers_balance.*, sellers.first_name as seller_name ')
            ->where('sellers_balance.contract_id', '=', $contract_id)
            ->orderBy('sellers_balance.id', 'DESC')
            ->get()->first();

        return view('dashboard.payments.last_payment_by_contract', compact(
            'balance'
        ));
    }

}
