<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Bulletin;
use App\Models\Payment_method;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::user()->can('customers_read')){
            return response('Unauthorized.', 401);
        }
        $customers = Customer::where('private', 0)->get();

        return view('dashboard.customers.index', compact('customers'));
    }

    public function modal($account, $source = null, $row_id = null)
    {
        if(!Auth::user()->can('customers_read')){
            return response('Unauthorized.', 401);
        }

        if($source == 'employee' || $source == 'paie') {
            $customers = Customer::all();
        } else {
            $customers = Customer::where('private', 0)->get();
        }
        return view('dashboard.customers.modal', compact('customers', 'source', 'row_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Auth::user()->can('customers_create')){
            return response('Unauthorized.', 401);
        }
        $customer = DB::table('customers')->latest()->first();
        $customer_code = ($customer)? intval($customer->code) + 1 : 1;

        $payments_methods = Payment_method::all();
        return view('dashboard.customers.edit', compact('payments_methods', 'customer_code'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::user()->can('customers_create')){
            return response('Unauthorized.', 401);
        }
        $request->validate([
            'code' => [
                'required',
                'unique:customers',
            ],
            'name_ar' => 'required',
            'city' => 'required',
            'responsible' => 'required',
            'mobile' => 'required',
        ], [
            'code.required' => 'رقم العميل ضروري.',
            'code.unique'      => 'رقم العميل يجب ألا يتكرر.',
            'name_ar.required' => 'اسم العميل عربي العميل ضروري.',
            'city.required' => 'المدينة ضرورية.',
            'responsible.required' => 'اسم المدير المسؤول ضروري.',
            'mobile.required' => 'رقم الجوال ضروري.',
        ]);

        $request_data = $request->all();

        Customer::create($request_data);

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.customers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    //public function show(Customer $customer)
    public function show($account, Customer $customer)
    {
        if(!Auth::user()->can('customers_read')){
            return response('Unauthorized.', 401);
        }

        if($customer->private) {
            if(auth()->user()->role != 'super_admin') {
                return response('Unauthorized.', 401);
            }
        }

        $customer = DB::table('customers')
            ->join('payment_methods', 'payment_methods.id', '=', 'customers.payment_method_id')
            ->selectRaw('customers.*, payment_methods.pm_name')
            ->where('customers.id', '=', $customer->id)
            ->latest()->first();

        $contracts = Contract::where('customer_id', '=', $customer->id)->get();

        $company = Company::find(1);

        return view('dashboard.customers.show', compact('customer', 'contracts', 'company'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit($account, Customer $customer)
    {
        if(!Auth::user()->can('customers_update')){
            return response('Unauthorized.', 401);
        }
        $payments_methods = Payment_method::all();
        return view('dashboard.customers.edit', compact('payments_methods', 'customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update($account, Request $request, Customer $customer)
    {
        if(!Auth::user()->can('customers_update')){
            return response('Unauthorized.', 401);
        }
        $request->validate([

            'code' => [
                'required',
                //'unique:customers,code,'.$customer->code,
                //Rule::unique('customers', 'code')->ignore($customer->code),
            ],
            'name_ar' => 'required',
            'city' => 'required',
            'responsible' => 'required',
            'mobile' => 'required',
        ], [
            'code.required' => 'رقم العميل ضروري.',
            //'code.unique'      => 'رقم العميل يجب ألا يتكرر.',
            'name_ar.required' => 'اسم العميل عربي العميل ضروري.',
            'city.required' => 'المدينة ضرورية.',
            'responsible.required' => 'اسم المدير المسؤول ضروري.',
            'mobile.required' => 'رقم الجوال ضروري.',
        ]);

        $params = [
            'code' => $request->code,
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'address_ar' => $request->address_ar,
            'address_en' => $request->address_en,
            'city' => $request->city,
            'vat' => $request->vat,
            'email' => $request->email,
            'fax' => $request->fax,
            'tel' => $request->tel,
            'responsible' => $request->responsible,
            'mobile' => $request->mobile,
            'payment_method_id' => $request->payment_method_id,
        ];

        DB::table('customers')
            ->where('id', $customer->id)
            ->update($params);

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.customers.index');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy($account, Customer $customer)
    {

    }
    public function delete($account, $id)
    {
        if(!Auth::user()->can('customers_delete')){
            return response('Unauthorized.', 401);
        }
        $response = ['status' => 0, 'message' => 'يتعذر الحذف !'];
        $customer = Customer::find($id);
        if(!$customer){
            return json_encode($response);
        }

        $contracts = Contract::where('customer_id', '=', $id)->get();
        if(count($contracts)){
            $response['message'] = "يرجى فك ارتباط العميل بالعقود قبل حذفه !";
            return json_encode($response);
        }



        $rs = $customer->delete();
        if($rs){
            Customer::where('id', '=', $id)->delete();
            $response['status'] = 1;
            session()->flash('success', __('site.deleted_successfully'));
            return json_encode($response);

        }
    }

}
