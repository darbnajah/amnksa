<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Expense_bulletins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::user()->can('expenses_read')){
            return response('Unauthorized.', 401);
        }
        $expenses = DB::table('expenses')
            ->join('users', 'users.id', '=', 'expenses.created_by')
            ->selectRaw('
                    expenses.*,
                    CONCAT(users.first_name, " ", users.last_name) AS username
            ')
            ->orderBy('expenses.id', 'DESC')
            ->get();

        return view('dashboard.expenses.index', compact('expenses'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Auth::user()->can('expenses_create')){
            return response('Unauthorized.', 401);
        }
        $expense = DB::table('expenses')->latest()->first();
        $expense_id = ($expense)? $expense->id + 1 : 1;

        return view('dashboard.expenses.edit', compact('expense_id'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return false|\Illuminate\Http\Response|string
     */
    public function store(Request $request)
    {
        if(!Auth::user()->can('expenses_create')){
            return response('Unauthorized.', 401);
        }
        $expense = Expense::create([
            'dt' => $request->dt,
            'total' => $request->total,
            'created_by' => Auth::id(),
        ]);

        if($expense && isset($request->rows)) {
            $bulletins = explode('::', trim($request->rows));

            if(count($bulletins) > 0) {
                for($i = 0; $i < count($bulletins) -1; $i++){
                    $bulletin = explode(';', $bulletins[$i]);

                    Expense_bulletins::create([
                        'label' => $bulletin[0],
                        'amount' => $bulletin[1],
                        'parent_id' => $expense->id,
                    ]);
                }
            }
            session()->flash('success', __('site.added_successfully'));
            $data = ['valid' => 1, 'route' => route('dashboard.expenses.index')];
            return json_encode($data);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show($account, Expense $expense)
    {
        if(!Auth::user()->can('expenses_read')){
            return response('Unauthorized.', 401);
        }
        $expense_bulletins = DB::table('expense_bulletins')
            ->join('expenses', 'expenses.id', '=', 'expense_bulletins.parent_id')
            ->selectRaw('expense_bulletins.*, expenses.dt')
            ->where('.parent_id', '=', $expense->id)
            ->get();
        return view('dashboard.expenses.show', compact('expense', 'expense_bulletins'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit($account, Expense $expense)
    {
        if(!Auth::user()->can('expenses_update')){
            return response('Unauthorized.', 401);
        }
        $expense_bulletins = DB::table('expense_bulletins')
            ->join('expenses', 'expenses.id', '=', 'expense_bulletins.parent_id')
            ->selectRaw('expense_bulletins.*, expenses.dt')
            ->where('.parent_id', '=', $expense->id)
            ->get();
        return view('dashboard.expenses.edit', compact('expense', 'expense_bulletins'));    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense  $expense
     * @return false|\Illuminate\Http\Response|string
     */
    public function update($account, Request $request, $id)
    {
        if(!Auth::user()->can('expenses_update')){
            return response('Unauthorized.', 401);
        }
        $expense_id = $id;

        $expense = Expense::where('id', $expense_id)->update([
            'dt' => $request->dt,
            'total' => $request->total,
            'created_by' => Auth::id(),
        ]);

        if($expense && isset($request->rows)) {
            $bulletins = explode('::', trim($request->rows));

            if(count($bulletins) > 0) {
                Expense_bulletins::where('parent_id', '=', $expense_id)->delete();

                for($i = 0; $i < count($bulletins) -1; $i++){
                    $bulletin = explode(';', $bulletins[$i]);

                    Expense_bulletins::create([
                        'label' => $bulletin[0],
                        'amount' => $bulletin[1],
                        'parent_id' => $expense_id,
                    ]);
                }
            }
            session()->flash('success', __('site.updated_successfully'));
            $data = ['valid' => 1, 'route' => route('dashboard.expenses.index')];
            return json_encode($data);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy($account, Expense $expense)
    {
        if(!Auth::user()->can('expenses_delete')){
            return response('Unauthorized.', 401);
        }
        $rs = $expense->delete();
        DB::table('expense_bulletins')
            ->where('parent_id', $expense->id)
            ->delete();
        if($rs){
            session()->flash('success', __('site.deleted_successfully'));
            return redirect()->route('dashboard.expenses.index');
        }
    }
}
