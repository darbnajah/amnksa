<?php

namespace App\Http\Controllers\Dashboard;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\Seller_balance;
use App\Models\Seller_deduction_advance;
use App\Models\Seller_payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Seller_deduction_advanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($account, $seller_id = null)
    {
        if(!Auth::user()->can('sellers_deductions_advances_read')){
            return response('Unauthorized.', 401);
        }
        $seller = ($seller_id > 0)? Seller::find($seller_id) : null;
        $seller_name = isset($seller)? $seller->first_name : null;
        $deductions = isset($seller_id)? $this->deductions_by_seller($seller_id) : null;

        $advances = isset($seller_id)? $this->advances_by_seller($seller_id) : null;
        return view('dashboard.sellers_deductions_advances.index', compact('seller_id', 'seller_name', 'deductions', 'advances'));

    }

    public function deductions_by_seller($seller_id){
        if(!Auth::user()->can('sellers_deductions_advances_read')){
            return response('Unauthorized.', 401);
        }
        $deductions = Seller_deduction_advance::where('seller_id', '=',$seller_id)
            ->where('type', '=', 'deduction')
            ->get();
        return view('dashboard.sellers_deductions_advances.deductions', compact(
            'deductions'
        ));
    }
    public function advances_by_seller($seller_id){
        if(!Auth::user()->can('sellers_deductions_advances_read')){
            return response('Unauthorized.', 401);
        }
        $advances = Seller_deduction_advance::where('seller_id', '=',$seller_id)
            ->where('type', '=', 'advance')
            ->get();
        return view('dashboard.sellers_deductions_advances.advances', compact(
            'advances'
        ));
    }
    public function total_rest_deductions_advances_by_seller($account, $seller_id){
        if(!Auth::user()->can('sellers_deductions_advances_read')){
            return response('Unauthorized.', 401);
        }
        $data = [
            'total_advances' => $this->total_rest_advances_by_seller($seller_id),
            'total_deductions' => $this->total_rest_deductions_by_seller($seller_id)
        ];
        return json_encode($data);
    }


    public function total_rest_advances_by_seller($seller_id){
        if(!Auth::user()->can('sellers_deductions_advances_read')){
            return response('Unauthorized.', 401);
        }
        $total_advance = Seller_deduction_advance::where('seller_id', '=',$seller_id)
            ->where('type', '=', 'advance')
            ->selectRaw('COALESCE(SUM(debit) - SUM(credit), 0) AS total_rest')->get()->first();
        return ($total_advance)? $total_advance->total_rest : 0;
    }
    public function total_rest_deductions_by_seller($seller_id){
        if(!Auth::user()->can('sellers_deductions_advances_read')){
            return response('Unauthorized.', 401);
        }
        $total_deduction = Seller_deduction_advance::where('seller_id', '=',$seller_id)
            ->where('type', '=', 'deduction')
            ->selectRaw('COALESCE(SUM(debit) - SUM(credit), 0) AS total_rest')->get()->first();
        return ($total_deduction)? $total_deduction->total_rest : 0;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::user()->can('sellers_deductions_advances_create')){
            return response('Unauthorized.', 401);
        }
        $ad = Seller_deduction_advance::create([
            'dt' => $request->ad_dt,
            'label' => $request->ad_label,
            'debit' => $request->ad_debit,
            'credit' => 0,
            'type' => $request->type,
            'seller_id' => $request->seller_id
        ]);

        if($ad){
            $ad = Seller_deduction_advance::find($ad->id);
            if($ad){
                $label = ($ad->type == 'advance')? 'سلفة: ' : 'خصم: ';
                Seller_balance::create([
                    'doc_id' => $ad->id,
                    'doc_type' => 'given_'.$ad->type,
                    'dt' => $ad->dt,
                    'seller_id' => $ad->seller_id,
                    'label' => json_encode(['label' => $label.$ad->label]),
                    'debit' => Helper::double($ad->debit),
                ]);
            }
            session()->flash('success', __('site.added_successfully'));
            return 1;
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Seller_deduction_advance  $deduction_advance
     * @return \Illuminate\Http\Response
     */
    public function show($account, $seller_id = null)
    {
        return $this->index($account, $seller_id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Seller_deduction_advance  $deduction_advance
     * @return \Illuminate\Http\Response
     */
    public function edit(Seller_deduction_advance $deduction_advance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Seller_deduction_advance  $deduction_advance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller_deduction_advance $deduction_advance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seller_deduction_advance  $deduction_advance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller_deduction_advance $deduction_advance)
    {

    }

    public function delete($account, $id){
        if(!Auth::user()->can('sellers_deductions_advances_delete')){
            return response('Unauthorized.', 401);
        }
        $row = Seller_deduction_advance::find($id);
        if($row){
            DB::table('sellers_balance')
                ->where('doc_id', $id)
                ->where('doc_type', 'given_'.$row->type)
                ->delete();
            $rs = $row->delete();
            if($rs){
                session()->flash('success', __('site.deleted_successfully'));
                return 1;
            }
        }
    }
}
