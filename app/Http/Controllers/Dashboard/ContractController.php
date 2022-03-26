<?php

namespace App\Http\Controllers\Dashboard;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Bulletin;
use App\Models\Company;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Seller;
use App\Models\Seller_payment;
use App\Models\Supplier;
use App\Models\Supplier_payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contracts = DB::table('contracts')
            ->join('customers', 'customers.id', '=', 'contracts.customer_id')
            ->selectRaw('contracts.*, customers.name_ar')
            ->where('contracts.status', '=', 1)
            ->get();

        return view('dashboard.contracts.index', compact('contracts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($account, $customer_id)
    {
        $contract = DB::table('contracts')->where('customer_id', '=', $customer_id)->latest()->first();
        $contract_code = ($contract)? intval($contract->code) + 1 : 1;

        $bulletins = null;

        $customer = Customer::find($customer_id);

        $sellers = Seller::all();

        $suppliers = Supplier::all();
        $company = Company::find(1);

        return view('dashboard.contracts.edit', compact('customer', 'contract_code', 'bulletins', 'sellers', 'company', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'code' => [
                'required',
                //'unique:contracts',
            ],
            'city' => 'required',
            'address' => 'required',
            'dt_start' => 'required',
            'dt_end' => 'required',
        ], [
            'code.required' => 'رقم العقد ضروري.',
            //'code.unique'      => 'رقم العق يجب ألا يتكرر.',
            'customer_id.required' => 'العميل ضروري.',
            'city.required' => 'المدينة  ضرورية.',
            'address.required' => 'العنوان  ضروري.',
            'dt_start.required' => 'تاريخ بداية العقد  ضروري.',
            'dt_end.required' => 'تاريخ نهاية العقد  ضروري.',
            'status.required' => 'حالة العقد  ضروري.',
        ]);

        $request_data = $request->all();

        $request_data['seller_id'] = ($request->seller_id)? $request->seller_id : null;
        $request_data['contract_total'] = Helper::double($request->contract_total);

        $request_data['seller_commission'] = ($request->seller_id)? $request->seller_commission : 0;

        $request_data['supplier_id'] = ($request->supplier_id)? $request->supplier_id : null;
        $request_data['supplier_commission'] = ($request->supplier_id)? $request->supplier_commission : 0;

        $contract = Contract::create($request_data);

        if($contract && isset($request_data['bulletins'])) {
            $bulletins = explode('::', trim($request_data['bulletins']));

            if(count($bulletins) > 0) {
                for($i = 0; $i < count($bulletins) -1; $i++){
                    $bulletin = explode(';', $bulletins[$i]);

                    Bulletin::create([
                        'label' => $bulletin[0],
                        'nb' => $bulletin[1],
                        'cost' => $bulletin[2],
                        'contract_id' => $contract->id,
                        'customer_id' => $request_data['customer_id'],
                    ]);
                }
            }
        }

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.customers.show', $request->customer_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function show($account, Contract $contract)
    {
        $customer = Customer::find($contract->customer_id);
        $bulletins = Bulletin::where('contract_id', '=', $contract->id)->get();
        return view('dashboard.contracts.show', compact('bulletins', 'customer', 'contract'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function edit($account, Contract $contract)
    {
        $customer = Customer::find($contract->customer_id);
        $bulletins = ($contract)? Bulletin::where('contract_id', '=', $contract->id)
            ->where('customer_id', '=', $contract->customer_id)
            ->get() : null;

        $sellers = Seller::all();
        $suppliers = Supplier::all();
        $company = Company::find(1);

        return view('dashboard.contracts.edit', compact('customer', 'contract', 'bulletins', 'sellers', 'suppliers', 'company'));
    }

    public function bulletions($account, $contract_id){
        $bulletins = Bulletin::where('contract_id', '=', $contract_id)->get();
        return view('dashboard.bulletins._modal', compact('bulletins'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function update($account, Request $request, Contract $contract)
    {
        $contract_id = $contract->id;
        $customer_id = $request->customer_id;

        $request->validate([
            'code' => [
                'required',
                //'unique:contracts,code,'.$contract->code,
            ],
            'city' => 'required',
            'address' => 'required',
            'dt_start' => 'required',
            'dt_end' => 'required',
        ], [
            'code.required' => 'رقم العقد ضروري.',
            //'code.unique'      => 'رقم العق يجب ألا يتكرر.',
            'customer_id.required' => 'العميل ضروري.',
            'city.required' => 'المدينة  ضرورية.',
            'address.required' => 'العنوان  ضروري.',
            'dt_start.required' => 'تاريخ بداية العقد  ضروري.',
            'dt_end.required' => 'تاريخ نهاية العقد  ضروري.',
            'status.required' => 'حالة العقد  ضروري.',
        ]);

        $params = [
            'code' => $request->code,
            'customer_id' => $request->customer_id,
            'address' => $request->address,
            'city' => $request->city,
            'dt_start' => $request->dt_start,
            'dt_end' => $request->dt_end,
            'status' => $request->status,
            'seller_id' => ($request->seller_id)? $request->seller_id : null,
            'contract_total' => Helper::double($request->contract_total),
            'seller_commission' => ($request->seller_id)? $request->seller_commission : 0,

            'supplier_id' => ($request->supplier_id)? $request->supplier_id : null,
            'supplier_commission' => ($request->supplier_id)? $request->supplier_commission : 0,
        ];

        $contract = DB::table('contracts')
            ->where('id', $contract->id)
            ->update($params);

        if(isset($request->bulletins)) {
            $bulletins = explode('::', trim($request->bulletins));

            if(count($bulletins) > 0) {
                $query = DB::table('bulletins');
                $query->where('contract_id', $contract_id);
                $query->where('customer_id', $customer_id);
                $query->delete();

                for($i = 0; $i < count($bulletins) -1; $i++){
                    $bulletin = explode(';', $bulletins[$i]);

                    Bulletin::create([
                        'label' => $bulletin[0],
                        'nb' => $bulletin[1],
                        'cost' => $bulletin[2],
                        'contract_id' => $contract_id,
                        'customer_id' => $request->customer_id,
                    ]);
                }
            }
        }

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.customers.show', $request->customer_id);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function destroy($account, Contract $contract)
    {
        $rs = $contract->delete();
        if($rs){
            session()->flash('success', __('site.deleted_successfully'));
            return redirect()->route('dashboard.customers.show', $contract->customer_id);
        }
    }
    public function delete($account, $id)
    {
        $response = ['status' => 0, 'message' => 'يتعذر الحذف !'];

        $contract = Contract::find($id);
        if(!$contract){
            return json_encode($response);
        }
        $invoices = Invoice::where('contract_id', '=', $id)->get();
        if(count($invoices)){
            $response['message'] = "يرجى فك ارتباط العقد بالفواتير قبل حذفه !";
            return json_encode($response);
        }
        $payments = Payment::where('contract_id', '=', $id)->get();
        if(count($payments)){
            $response['message'] = "يرجى فك ارتباط العقد بالتحصيل قبل حذفه !";
            return json_encode($response);
        }
        $sellers_payments = Seller_payment::where('contract_id', '=', $id)->get();
        if(count($sellers_payments)){
            $response['message'] = "يرجى حذف كل مستحقات المسوقين المرتبطة بهذا العقد قبل حذفه !";
            return json_encode($response);
        }
        $suppliers_payments = Supplier_payment::where('contract_id', '=', $id)->get();
        if(count($suppliers_payments)){
            $response['message'] = "يرجى حذف كل مستحقات الموردين المرتبطة بهذا العقد قبل حذفه !";
            return json_encode($response);
        }

        $rs = $contract->delete();
        if($rs){
            $response['status'] = 1;
            session()->flash('success', __('site.deleted_successfully'));
            return json_encode($response);

        }
    }
}
