<?php

namespace App\Http\Controllers\Dashboard;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Collection_bulletins;
use App\Models\Supplier;
use App\Models\Supplier_balance;
use App\Models\Supplier_payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::user()->can('collections_read')){
            return response('Unauthorized.', 401);
        }
        $collections = DB::table('collections')
            ->join('suppliers', 'suppliers.id', '=', 'collections.supplier_id')
            ->selectRaw('collections.*, suppliers.supplier_name')
            ->orderBy('collections.id', 'DESC')
            ->get();

        return view('dashboard.collections.index', compact('collections'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Auth::user()->can('collections_create')){
            return response('Unauthorized.', 401);
        }
        $collection = DB::table('collections')->latest()->first();
        $collection_id = ($collection)? $collection->id + 1 : 1;
        $suppliers = Supplier::all();

        return view('dashboard.collections.edit', compact('collection_id', 'suppliers'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return false|\Illuminate\Http\Response|string
     */
    public function store(Request $request)
    {
        if(!Auth::user()->can('collections_create')){
            return response('Unauthorized.', 401);
        }
        $collection = Collection::create([
            'dt' => $request->dt,
            'total' => $request->total,
            'supplier_id' => $request->supplier_id
        ]);
        $collection_id = $collection->id;

        if($collection && isset($request->rows)) {
            $bulletins = explode('::', trim($request->rows));

            if(count($bulletins) > 0) {
                for($i = 0; $i < count($bulletins) -1; $i++){
                    $bulletin = explode(';', $bulletins[$i]);

                    $cb = Collection_bulletins::create([
                        'supplier_id' => $request->supplier_id,
                        'label' => $bulletin[0],
                        'amount' => $bulletin[1],
                        'parent_id' => $collection_id,
                    ]);
                    if($cb){
                        Supplier_balance::create([
                            'doc_id' => $cb->id,
                            'doc_type' => 'payment_valid',
                            'dt' => $request->dt,
                            'supplier_id' => $request->supplier_id,
                            'parent_id' => $collection_id,
                            'label' => json_encode($bulletin[0]),
                            'credit' => Helper::double($bulletin[1]),
                        ]);

                    }
                }
            }
            session()->flash('success', __('site.added_successfully'));
            $data = ['valid' => 1, 'route' => route('dashboard.collections.index')];
            return json_encode($data);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function show($account, Collection $collection)
    {
        if(!Auth::user()->can('collections_read')){
            return response('Unauthorized.', 401);
        }
        $collection = DB::table('collections')
            ->join('suppliers', 'suppliers.id', '=', 'collections.supplier_id')
            ->selectRaw('collections.*, suppliers.supplier_name')
            ->where('collections.id', '=', $collection->id)
            ->first();

        $collection_bulletins = DB::table('collection_bulletins')
            ->join('collections', 'collections.id', '=', 'collection_bulletins.parent_id')
            ->join('suppliers', 'suppliers.id', '=', 'collection_bulletins.supplier_id')
            ->selectRaw('collection_bulletins.*, collections.dt, suppliers.supplier_name')
            ->where('.parent_id', '=', $collection->id)
            ->get();

        return view('dashboard.collections.show', compact('collection', 'collection_bulletins'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function edit($account, Collection $collection)
    {
        if(!Auth::user()->can('collections_update')){
            return response('Unauthorized.', 401);
        }
        $collection_bulletins = DB::table('collection_bulletins')
            ->join('collections', 'collections.id', '=', 'collection_bulletins.parent_id')
            ->join('suppliers', 'suppliers.id', '=', 'collection_bulletins.supplier_id')
            ->selectRaw('collection_bulletins.*, collections.dt, suppliers.supplier_name')
            ->where('.parent_id', '=', $collection->id)
            ->get();

        $suppliers = Supplier::all();

        return view('dashboard.collections.edit', compact('collection', 'collection_bulletins', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Collection  $collection
     * @return false|\Illuminate\Http\Response|string
     */
    public function update($account, Request $request, $id)
    {
        if(!Auth::user()->can('collections_update')){
            return response('Unauthorized.', 401);
        }
        $collection_id = $id;

        $collection = Collection::where('id', $collection_id)->update([
            'dt' => $request->dt,
            'total' => $request->total,
            'supplier_id' => $request->supplier_id

        ]);

        if($collection && isset($request->rows)) {
            $bulletins = explode('::', trim($request->rows));

            if(count($bulletins) > 0) {
                Collection_bulletins::where('parent_id', '=', $collection_id)->delete();
                Supplier_balance::where('parent_id', '=', $collection_id)->delete();

                for($i = 0; $i < count($bulletins) -1; $i++){
                    $bulletin = explode(';', $bulletins[$i]);

                    $cb = Collection_bulletins::create([
                        'supplier_id' => $request->supplier_id,
                        'label' => $bulletin[0],
                        'amount' => $bulletin[1],
                        'parent_id' => $collection_id,
                    ]);
                    if($cb){
                        Supplier_balance::create([
                            'doc_id' => $cb->id,
                            'doc_type' => 'payment_valid',
                            'dt' => $request->dt,
                            'supplier_id' => $request->supplier_id,
                            'parent_id' => $collection_id,
                            'label' => json_encode($bulletin[0]),
                            'credit' => Helper::double($bulletin[1]),
                        ]);

                    }
                }
            }
            session()->flash('success', __('site.updated_successfully'));
            $data = ['valid' => 1, 'route' => route('dashboard.collections.index')];
            return json_encode($data);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function destroy($account, Collection $collection)
    {
        if(!Auth::user()->can('collections_delete')){
            return response('Unauthorized.', 401);
        }
        $collection_id = $collection->id;
        $rs = $collection->delete();
        DB::table('collection_bulletins')
            ->where('parent_id', $collection_id)
            ->delete();
        DB::table('suppliers_balance')
            ->where('parent_id', $collection_id)
            ->where('doc_type', 'payment_valid')
            ->delete();

        if($rs){
            session()->flash('success', __('site.deleted_successfully'));
            return redirect()->route('dashboard.collections.index');
        }
    }
}
