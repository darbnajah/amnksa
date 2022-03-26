<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Deduction_advance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Deductions_advancesBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($account)
    {
        if(!Auth::user()->can('deductions_advances_read')){
            return response('Unauthorized.', 401);
        }

        $dabs = DB::table('deduction_advances')
            ->join('employees', 'employees.id', '=', 'deduction_advances.employee_id')
            ->selectRaw('employees.id,
                employees.employee_name,
                employees.work_zone,
                deduction_advances.type,
                COALESCE(SUM(deduction_advances.debit) - SUM(deduction_advances.credit), 0) AS rest
                ')
            ->groupBy('employees.id', 'employees.employee_name', 'employees.work_zone', 'deduction_advances.type' )
            ->get();

        return view('dashboard.deductions_advances_balance.index', compact('dabs'));

    }

    public function total_rest_deduction_advances_by_employee($account, $employee_id){
        $data = [
            'total_advances' => $this->total_rest_advances_by_employee($employee_id),
            'total_deductions' => $this->total_rest_deductions_by_employee($employee_id),
        ];
        return json_encode($data);
    }

    public function total_rest_advances_by_employee($employee_id){
        $total_advance = Deduction_advance::where('employee_id', '=',$employee_id)
            ->where('type', '=', 'advance')
            ->selectRaw('COALESCE(SUM(debit) - SUM(credit), 0) AS total_rest')->get()->first();
        return ($total_advance)? $total_advance->total_rest : 0;
    }
    public function total_rest_deductions_by_employee($employee_id){
        $total_deduction = Deduction_advance::where('employee_id', '=',$employee_id)
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
        if(!Auth::user()->can('deduction_advances_create')){
            return response('Unauthorized.', 401);
        }
        $ad = Deduction_advance::create([
            'dt' => $request->ad_dt,
            'label' => $request->ad_label,
            'debit' => $request->ad_debit,
            'credit' => 0,
            'type' => $request->type,
            'employee_id' => $request->employee_id
        ]);

        if($ad){
            session()->flash('success', __('site.added_successfully'));
           return 1;
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Deduction_advance  $deduction_advance
     * @return \Illuminate\Http\Response
     */
    public function show($account, $employee_id = null)
    {
        return $this->index($account, $employee_id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Deduction_advance  $deduction_advance
     * @return \Illuminate\Http\Response
     */
    public function edit($account, Deduction_advance $deduction_advance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Deduction_advance  $deduction_advance
     * @return \Illuminate\Http\Response
     */
    public function update($account, Request $request, Deduction_advance $deduction_advance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Deduction_advance  $deduction_advance
     * @return \Illuminate\Http\Response
     */
    public function destroy($account, Deduction_advance $deduction_advance)
    {

    }

    public function delete($account, $id){
        if(!Auth::user()->can('deduction_advances_delete')){
            return response('Unauthorized.', 401);
        }
        $row = Deduction_advance::find($id);
        $rs = $row->delete();
        if($rs){
            session()->flash('success', __('site.deleted_successfully'));
            return 1;
        }
    }
}
