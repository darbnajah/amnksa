<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($company_id = null)
    {
        if($company_id){
            $banks = DB::table('banks')->where('company_id', '=', $company_id)->get();
        } else {
            $banks = Bank::all();

        }
        return view('dashboard.banks.index', compact('banks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.banks.edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function show(Bank $bank)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function edit(Bank $bank)
    {
        return view('dashboard.banks.edit', compact('bank'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function update($account, Request $request, Bank $bank)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function destroy($account, $id)
    {

        $bank = Bank::find($id);
        if($bank){
            $rs = $bank->delete();
            if($rs) {
                session()->flash('success', __('site.deleted_successfully'));
                return redirect()->route('dashboard.companies.show', $bank->company_id);
            }
            session()->flash('success', __('site.delete_error'));
            return redirect()->route('dashboard.companies.show', $bank->company_id);

        }

    }
    public function set_default_bank($account, $id)
    {

        $bank = Bank::find($id);
        if($bank){
            Bank::where('company_id', '=', $bank->company_id)->update(['is_default' => 0]);
            $bank->is_default = 1;
            $rs = $bank->save();
            session()->flash('success', __('site.updated_successfully'));
            return $rs;
        }

    }

    public function bycompany($id)
    {

        $banks = DB::table('banks')->where('company_id', '=', $id)->get();

        return view('dashboard.banks.index', compact( 'banks'));

    }

}
