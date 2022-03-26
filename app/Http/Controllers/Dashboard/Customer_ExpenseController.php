<?php

namespace App\Http\Controllers\Dashboard;

use App\Helper\Helper;
use App\Http\Controllers\Controller;

use App\Models\Company;
use App\Models\Customer_Expense;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Customer_ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*
        if(!Auth::user()->can('customer_expenses_read')){
            return response('Unauthorized.', 401);
        }
        */
        $customer_expenses = DB::table('customer_expenses')
            ->join('customers', 'customers.id', '=', 'customer_expenses.customer_id')
            ->join('users', 'users.id', '=', 'customer_expenses.created_by')
            ->selectRaw('customer_expenses.*,
                    customers.name_ar as customer_name,
                    CONCAT(users.first_name, " ", users.last_name) AS username
            ')
            ->where('customer_expenses.doc_type', '=', 'customer_expense')
            ->orderBy('customer_expenses.id', 'DESC')
            ->get();

        return view('dashboard.customer_expenses.index', compact('customer_expenses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /*if(!Auth::user()->can('customer_expenses_create')){
            return response('Unauthorized.', 401);
        }*/
        $customer_expense = DB::table('customer_expenses')->latest()->first();
        $doc_id = ($customer_expense)? $customer_expense->id + 1 : 1;

        $months = \App\Helper\Helper::months();
        $company = Company::find(1);
        $months = \App\Helper\Helper::months();

        return view('dashboard.customer_expenses.edit', compact('doc_id', 'months', 'company', 'months'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($account, Request $request)
    {
        /*if(!Auth::user()->can('customer_expenses_create')){
            return response('Unauthorized.', 401);
        }*/
        $company = Company::find(1);

        $customer_expense = Customer_Expense::create([
            'doc_id' => $request->doc_id,
            'doc_type' => 'customer_expense',
            'dt' => $request->dt,
            'month_id' => intval($request->month_id),
            'label' => $request->label,
            'number' => $request->number,
            'customer_id' => $request->customer_id,
            'contract_id' => $request->contract_id,
            'credit' => $request->credit,
            'created_by' => Auth::id(),
        ]);

        if($customer_expense) {
            session()->flash('success', __('site.added_successfully'));
            $data = ['valid' => 1, 'route' => route('dashboard.customer_expenses.index')];
            return json_encode($data);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer_Expense  $customer_expense
     * @return \Illuminate\Http\Response
     */
    public function show($account, Customer_Expense $customer_expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer_Expense  $customer_expense
     * @return \Illuminate\Http\Response
     */
    public function edit($account, Customer_Expense $customer_expense)
    {
        /*if(!Auth::user()->can('customer_expenses_update')){
            return response('Unauthorized.', 401);
        }*/
        $customer_expense = DB::table('customer_expenses')
            ->join('customers', 'customers.id', '=', 'customer_expenses.customer_id')
            ->selectRaw('customer_expenses.*,
            customers.name_ar as customer_name
            ')
            ->where('customer_expenses.id', '=', $customer_expense->id)
            ->get()->first();

        $contract = DB::table('contracts')
            ->join('customers', 'customers.id', '=', 'contracts.customer_id')
            ->selectRaw('contracts.*, customers.name_ar as customer_name')

            ->where('contracts.status', '=', 1)
            ->where('contracts.id', '=', $customer_expense->contract_id)
            ->get()->first();

        $company = Company::find(1);
        $months = \App\Helper\Helper::months();

        return view('dashboard.customer_expenses.edit', compact('customer_expense',
            'months',
            'contract',
            'company',
            'months'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer_Expense  $customer_expense
     * @return \Illuminate\Http\Response
     */
    public function update($account, Request $request, $id)
    {
        /*if(!Auth::user()->can('customer_expenses_update')){
            return response('Unauthorized.', 401);
        }*/
        $customer_expense = Customer_Expense::where('id', '=', $id)
            ->update([
                'dt' => $request->dt,
                'month_id' => intval($request->month_id),
                'label' => $request->label,
                'number' => $request->number,
                'customer_id' => $request->customer_id,
                'contract_id' => $request->contract_id,
                'credit' => Helper::double($request->credit),
                'created_by' => Auth::id(),
            ]);
        if($customer_expense){
            session()->flash('success', __('تم تعديل التحصيل بنجاح !'));
            $data = ['valid' => 1, 'route' => route('dashboard.customer_expenses.index')];
            return json_encode($data);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer_Expense  $customer_expense
     * @return \Illuminate\Http\Response
     */
    public function destroy($account, Customer_Expense $customer_expense)
    {
        /*if(!Auth::user()->can('customer_expenses_delete')){
            return response('Unauthorized.', 401);
        }*/
        $rs = $customer_expense->delete();
        if($rs){
            session()->flash('success', __('site.deleted_successfully'));
            return redirect()->route('dashboard.customer_expenses.index');
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
        //dd($contracts);
        return view('dashboard.customer_expenses.contracts_by_customer', compact(
            'contracts',
            'company'
        ));
    }

}
