<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\Income_bulletins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::user()->can('incomes_read')){
            return response('Unauthorized.', 401);
        }
        $incomes = DB::table('incomes')
            ->join('users', 'users.id', '=', 'incomes.created_by')
            ->selectRaw('
                    incomes.*,
                    CONCAT(users.first_name, " ", users.last_name) AS username
            ')
            ->orderBy('incomes.id', 'DESC')
            ->get();
        return view('dashboard.incomes.index', compact('incomes'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Auth::user()->can('incomes_create')){
            return response('Unauthorized.', 401);
        }
        $income = DB::table('incomes')->latest()->first();
        $income_id = ($income)? $income->id + 1 : 1;

        return view('dashboard.incomes.edit', compact('income_id'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return false|\Illuminate\Http\Response|string
     */
    public function store(Request $request)
    {
        if(!Auth::user()->can('incomes_create')){
            return response('Unauthorized.', 401);
        }
        $income = Income::create([
            'dt' => $request->dt,
            'total' => $request->total,
            'created_by' => Auth::id(),

        ]);

        if($income && isset($request->rows)) {
            $bulletins = explode('::', trim($request->rows));

            if(count($bulletins) > 0) {
                for($i = 0; $i < count($bulletins) -1; $i++){
                    $bulletin = explode(';', $bulletins[$i]);

                    Income_bulletins::create([
                        'label' => $bulletin[0],
                        'amount' => $bulletin[1],
                        'parent_id' => $income->id,
                    ]);
                }
            }
            session()->flash('success', __('site.added_successfully'));
            $data = ['valid' => 1, 'route' => route('dashboard.incomes.index')];
            return json_encode($data);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function show($account, Income $income)
    {
        if(!Auth::user()->can('incomes_read')){
            return response('Unauthorized.', 401);
        }
        $income_bulletins = DB::table('income_bulletins')
            ->join('incomes', 'incomes.id', '=', 'income_bulletins.parent_id')
            ->selectRaw('income_bulletins.*, incomes.dt')
            ->where('.parent_id', '=', $income->id)
            ->get();
        return view('dashboard.incomes.show', compact('income', 'income_bulletins'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function edit($account, Income $income)
    {
        if(!Auth::user()->can('incomes_update')){
            return response('Unauthorized.', 401);
        }
        $income_bulletins = DB::table('income_bulletins')
            ->join('incomes', 'incomes.id', '=', 'income_bulletins.parent_id')
            ->selectRaw('income_bulletins.*, incomes.dt')
            ->where('.parent_id', '=', $income->id)
            ->get();
        return view('dashboard.incomes.edit', compact('income', 'income_bulletins'));    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Income  $income
     * @return false|\Illuminate\Http\Response|string
     */
    public function update($account, Request $request, $id)
    {
        if(!Auth::user()->can('incomes_update')){
            return response('Unauthorized.', 401);
        }
        $income_id = $id;

        $income = Income::where('id', $income_id)->update([
            'dt' => $request->dt,
            'total' => $request->total,
            'created_by' => Auth::id(),

        ]);

        if($income && isset($request->rows)) {
            $bulletins = explode('::', trim($request->rows));

            if(count($bulletins) > 0) {
                Income_bulletins::where('parent_id', '=', $income_id)->delete();

                for($i = 0; $i < count($bulletins) -1; $i++){
                    $bulletin = explode(';', $bulletins[$i]);

                    Income_bulletins::create([
                        'label' => $bulletin[0],
                        'amount' => $bulletin[1],
                        'parent_id' => $income_id,
                    ]);
                }
            }
            session()->flash('success', __('site.updated_successfully'));
            $data = ['valid' => 1, 'route' => route('dashboard.incomes.index')];
            return json_encode($data);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function destroy($account, Income $income)
    {
        if(!Auth::user()->can('incomes_delete')){
            return response('Unauthorized.', 401);
        }
        $rs = $income->delete();
        DB::table('income_bulletins')
            ->where('parent_id', $income->id)
            ->delete();
        if($rs){
            session()->flash('success', __('site.deleted_successfully'));
            return redirect()->route('dashboard.incomes.index');
        }
    }
}
