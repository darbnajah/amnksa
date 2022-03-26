<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Bulletin;
use App\Models\Contract;
use App\Models\Customer;
use Illuminate\Http\Request;

class BulletinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($contract_id = null)
    {
        $contract = Contract::find($contract_id);
        $customer = Customer::find($contract->customer_id);

        return view('dashboard.bulletins.edit', compact('contract', 'customer'));
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
            'contract_id' => 'required',
        ], [
            'contract_id.required' => 'رقم العقد ضروري.',
        ]);

        $request_data = $request->all();

        Bulletin::create($request_data);

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.contracts.show', $request->contract_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bulletin  $bulletin
     * @return \Illuminate\Http\Response
     */
    public function show(Bulletin $bulletin)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bulletin  $bulletin
     * @return \Illuminate\Http\Response
     */
    public function edit(Bulletin $bulletin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bulletin  $bulletin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bulletin $bulletin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bulletin  $bulletin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bulletin $bulletin)
    {
        //
    }
}
