<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Contract;
use App\Models\Supplier;
use App\Models\Bulletin;
use App\Models\Payment_method;
use App\Models\Supplier_payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::user()->can('suppliers_read')){
            return response('Unauthorized.', 401);
        }
        $suppliers = Supplier::all();

        return view('dashboard.suppliers.index', compact('suppliers'));
    }

    public function modal($account, $source = null)
    {
        if(!Auth::user()->can('suppliers_read')){
            return response('Unauthorized.', 401);
        }
        $suppliers = Supplier::all();

        return view('dashboard.suppliers.modal', compact('suppliers', 'source'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Auth::user()->can('suppliers_create')){
            return response('Unauthorized.', 401);
        }
        return view('dashboard.suppliers.edit', compact('supplier_code'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::user()->can('suppliers_create')){
            return response('Unauthorized.', 401);
        }
        $request->validate([
            'supplier_name' => 'required'
        ], [
            'supplier_name.required' => 'اسم المورد  ضروري.',
        ]);

        $request_data = $request->all();

        Supplier::create($request_data);

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.suppliers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */

    public function show($account, Supplier $supplier)
    {
        if(!Auth::user()->can('suppliers_read')){
            return response('Unauthorized.', 401);
        }
        $contracts = Contract::where('supplier_id', '=', $supplier->id)->get();

        return view('dashboard.suppliers.show', compact('supplier', 'contracts'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit($account, Supplier $supplier)
    {
        if(!Auth::user()->can('suppliers_update')){
            return response('Unauthorized.', 401);
        }
        return view('dashboard.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update($account, Request $request, Supplier $supplier)
    {
        if(!Auth::user()->can('suppliers_update')){
            return response('Unauthorized.', 401);
        }
        $request->validate([
            'supplier_name' => 'required'
        ], [
            'supplier_name.required' => 'اسم المورد ضروري.',
        ]);

        $params = [
            'supplier_name' => $request->supplier_name,
            'mobile' => $request->mobile,
        ];

        DB::table('suppliers')
            ->where('id', $supplier->id)
            ->update($params);

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.suppliers.index');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy($account, Supplier $supplier)
    {
        if(!Auth::user()->can('suppliers_delete')){
            return response('Unauthorized.', 401);
        }
        $rs = $supplier->delete();
        if($rs){
            session()->flash('success', __('site.deleted_successfully'));
            return redirect()->route('dashboard.suppliers.index');
        }
    }
    public function delete($account, $id)
    {
        if(!Auth::user()->can('suppliers_delete')){
            return response('Unauthorized.', 401);
        }
        $response = ['status' => 0, 'message' => 'يتعذر الحذف !'];
        $supplier = Supplier::find($id);
        if(!$supplier){
            return json_encode($response);
        }

        $contracts = Contract::where('supplier_id', '=', $id)->get();
        if(count($contracts)){
            $response['message'] = "يرجى فك ارتباط المورد بالعقود قبل حذفه !";
            return json_encode($response);
        }
        $supplier_payments = Supplier_payment::where('supplier_id', '=', $id)->get();
        if(count($supplier_payments)){
            $response['message'] = "يرجى حذف كل المستحقات المرتبطة بهذا المورد قبل حذفه !";
            return json_encode($response);
        }
        $collections = Collection::where('supplier_id', '=', $id)->get();
        if(count($collections)){
            $response['message'] = "يرجى حذف كل عمليات التحصيل المرتبطة بهذا المورد قبل حذفه !";
            return json_encode($response);
        }

        $rs = $supplier->delete();
        if($rs){
            Supplier::where('id', '=', $id)->delete();
            $response['status'] = 1;
            session()->flash('success', __('site.deleted_successfully'));
            return json_encode($response);

        }
    }

}
