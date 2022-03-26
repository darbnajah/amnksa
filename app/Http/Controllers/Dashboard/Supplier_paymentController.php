<?php

namespace App\Http\Controllers\Dashboard;

use App\Helper\Helper;
use App\Http\Controllers\Controller;

use App\Models\Supplier_balance;
use App\Models\Supplier_deduction_advance;
use App\Models\Supplier_payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Supplier_paymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::user()->can('suppliers_payments_read')){
            return response('Unauthorized.', 401);
        }
        $suppliers_payments = DB::table('suppliers_payments')
            ->join('contracts', 'contracts.id', '=', 'suppliers_payments.contract_id')
            ->join('customers', 'customers.id', '=', 'contracts.customer_id')
            ->join('suppliers', 'suppliers.id', '=', 'contracts.supplier_id')
            ->selectRaw('suppliers_payments.*,
            contracts.status as contract_status,
            contracts.supplier_commission,
            contracts.address,
            contracts.city,
            contracts.code,

            suppliers.supplier_name,

            customers.name_ar as customer_name

            ')
            ->orderBy('suppliers_payments.id', 'DESC')

            ->get();

        return view('dashboard.suppliers_payments.index', compact('suppliers_payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Auth::user()->can('suppliers_payments_create')){
            return response('Unauthorized.', 401);
        }
        $months = \App\Helper\Helper::months();
        return view('dashboard.suppliers_payments.edit', compact('months'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::user()->can('suppliers_payments_create')){
            return response('Unauthorized.', 401);
        }
        $params = $request->all();

        $payment = Supplier_payment::create($params);

        if($payment) {
            Supplier_balance::create([
                'doc_id' => $payment->id,
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
            session()->flash('success', __('site.added_successfully'));
            $data = ['valid' => 1, 'route' => route('dashboard.suppliers_payments.index')];
            return json_encode($data);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Paie  $paie
     * @return \Illuminate\Http\Response
     */
    public function show($account, Supplier_payment $supplier_payment)
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
        if(!Auth::user()->can('suppliers_payments_update')){
            return response('Unauthorized.', 401);
        }
        $payment = DB::table('suppliers_payments')
            ->join('contracts', 'contracts.id', '=', 'suppliers_payments.contract_id')
            ->join('customers', 'customers.id', '=', 'contracts.customer_id')
            ->join('suppliers', 'suppliers.id', '=', 'contracts.supplier_id')
            ->selectRaw('suppliers_payments.*,
            contracts.status as contract_status,
            contracts.supplier_commission,
            contracts.address,
            contracts.city,
            contracts.code,

            suppliers.supplier_name,

            customers.name_ar as customer_name

            ')
            ->where('suppliers_payments.id', '=', $id)
            ->get()->first();

        $contract = DB::table('contracts')
            ->join('customers', 'customers.id', '=', 'contracts.customer_id')
            ->selectRaw('contracts.*, customers.name_ar as customer_name')
            ->where('contracts.status', '=', 1)
            ->where('contracts.id', '=', $payment->contract_id)
            ->get()->first();

        $months = \App\Helper\Helper::months();

        return view('dashboard.suppliers_payments.edit', compact('payment',
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
        if(!Auth::user()->can('suppliers_payments_update')){
            return response('Unauthorized.', 401);
        }
        $payment = Supplier_payment::where('id', '=', $id)
            ->update([
                'dt' => $request->dt,
                'month_id' => $request->month_id,
                'supplier_id' => $request->supplier_id,
                'contract_id' => $request->contract_id,
                'supplier_amount' => Helper::double($request->supplier_amount),
            ]);

        if($payment) {
            Supplier_balance::where('doc_id', '=', $id)->where('doc_type', '=', 'payment_instance')
            ->update([
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

            session()->flash('success', __('تم تعديل المستحق على المورد بنجاح !'));
            $data = ['valid' => 1, 'route' => route('dashboard.suppliers_payments.index')];
            return json_encode($data);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Paie  $paie
     * @return \Illuminate\Http\Response
     */
    public function destroy($account, Supplier_payment $paie)
    {
        //
    }



    public function contracts_by_supplier($account, $supplier_id){
        $contracts = DB::table('contracts')
            ->join('customers', 'customers.id', '=', 'contracts.customer_id')
            ->selectRaw('contracts.*, customers.name_ar as customer_name')

            /*->addSelect(['total_contract' => DB::table('bulletins')
                ->join('contracts', 'contracts.id', '=', 'bulletins.contract_id')
                ->selectRaw('nb * cost')

                ->where('contracts.supplier_id', '=', $supplier_id)
                ->limit(1)
            ])*/
            ->where('contracts.status', '=', 1)
            ->where('supplier_id', '=',$supplier_id)
            ->get();

        return view('dashboard.suppliers_payments.contracts_by_supplier', compact(
            'contracts'
        ));
    }

    public function delete_supplier_payment($account, $payment_id){
        if(!Auth::user()->can('suppliers_payments_delete')){
            return response('Unauthorized.', 401);
        }
        $payment = Supplier_payment::find($payment_id);
        DB::table('suppliers_balance')
            ->where('doc_id', $payment_id)
            ->where('doc_type', 'payment_instance')
            //->orwhere('doc_type', 'payment_valid')
            ->delete();

        $rs = $payment->delete();
        if($rs){
            session()->flash('success', __('site.deleted_successfully'));
            return 1;
        }
    }
}
