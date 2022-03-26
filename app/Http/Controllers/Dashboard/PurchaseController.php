<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Purchase_bulletins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::user()->can('purchases_read')){
            return response('Unauthorized.', 401);
        }
        $purchases = DB::table('purchases')
            ->join('users', 'users.id', '=', 'purchases.created_by')
            ->selectRaw('
                    purchases.*,
                    CONCAT(users.first_name, " ", users.last_name) AS username
            ')
            ->orderBy('purchases.id', 'DESC')

            ->get();

        return view('dashboard.purchases.index', compact('purchases'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Auth::user()->can('purchases_create')){
            return response('Unauthorized.', 401);
        }
        $purchase = DB::table('purchases')->latest()->first();
        $purchase_id = ($purchase)? $purchase->id + 1 : 1;

        return view('dashboard.purchases.edit', compact('purchase_id'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return false|\Illuminate\Http\Response|string
     */
    public function store(Request $request)
    {
        if(!Auth::user()->can('purchases_create')){
            return response('Unauthorized.', 401);
        }
        $purchase = Purchase::create([
            'dt' => $request->dt,
            'total' => $request->total,
            'created_by' => Auth::id(),

        ]);

        if($purchase && isset($request->rows)) {
            $bulletins = explode('::', trim($request->rows));

            if(count($bulletins) > 0) {
                for($i = 0; $i < count($bulletins) -1; $i++){
                    $bulletin = explode(';', $bulletins[$i]);

                    Purchase_bulletins::create([
                        'label' => $bulletin[0],
                        'amount' => $bulletin[1],
                        'parent_id' => $purchase->id,
                    ]);
                }
            }
            session()->flash('success', __('site.added_successfully'));
            $data = ['valid' => 1, 'route' => route('dashboard.purchases.index')];
            return json_encode($data);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show($account, Purchase $purchase)
    {
        if(!Auth::user()->can('purchases_read')){
            return response('Unauthorized.', 401);
        }
        $purchase_bulletins = DB::table('purchase_bulletins')
            ->join('purchases', 'purchases.id', '=', 'purchase_bulletins.parent_id')
            ->selectRaw('purchase_bulletins.*, purchases.dt')
            ->where('.parent_id', '=', $purchase->id)
            ->get();
        return view('dashboard.purchases.show', compact('purchase', 'purchase_bulletins'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit($account, Purchase $purchase)
    {
        if(!Auth::user()->can('purchases_update')){
            return response('Unauthorized.', 401);
        }
        $purchase_bulletins = DB::table('purchase_bulletins')
            ->join('purchases', 'purchases.id', '=', 'purchase_bulletins.parent_id')
            ->selectRaw('purchase_bulletins.*, purchases.dt')
            ->where('.parent_id', '=', $purchase->id)
            ->get();
        return view('dashboard.purchases.edit', compact('purchase', 'purchase_bulletins'));    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return false|\Illuminate\Http\Response|string
     */
    public function update($account, Request $request, $id)
    {
        if(!Auth::user()->can('purchases_update')){
            return response('Unauthorized.', 401);
        }
        $purchase_id = $id;

        $purchase = Purchase::where('id', $purchase_id)->update([
            'dt' => $request->dt,
            'total' => $request->total,
            'created_by' => Auth::id(),
        ]);

        if($purchase && isset($request->rows)) {
            $bulletins = explode('::', trim($request->rows));

            if(count($bulletins) > 0) {
                Purchase_bulletins::where('parent_id', '=', $purchase_id)->delete();

                for($i = 0; $i < count($bulletins) -1; $i++){
                    $bulletin = explode(';', $bulletins[$i]);

                    Purchase_bulletins::create([
                        'label' => $bulletin[0],
                        'amount' => $bulletin[1],
                        'parent_id' => $purchase_id,
                    ]);
                }
            }
            session()->flash('success', __('site.updated_successfully'));
            $data = ['valid' => 1, 'route' => route('dashboard.purchases.index')];
            return json_encode($data);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy($account, Purchase $purchase)
    {
        if(!Auth::user()->can('purchases_delete')){
            return response('Unauthorized.', 401);
        }
        $rs = $purchase->delete();
        DB::table('purchase_bulletins')
            ->where('parent_id', $purchase->id)
            ->delete();
        if($rs){
            session()->flash('success', __('site.deleted_successfully'));
            return redirect()->route('dashboard.purchases.index');
        }
    }
}
