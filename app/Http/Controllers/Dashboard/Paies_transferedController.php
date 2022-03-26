<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Paie;
use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Paies_transferedController extends Controller
{
    public function index(){
        if(!Auth::user()->can('paies_transfered_read')){
            return response('Unauthorized.', 401);
        }
        $paies = DB::table('paies')
            ->join('paie_salaries', 'paies.id', '=', 'paie_salaries.paie_id')
            ->selectRaw('paies.paie_dt, paies.month_id')
            ->where('paie_salaries.trans_status', '=', 1)
            ->groupBy('paies.paie_dt', 'paies.month_id')
            ->get();

        $papers = Paper::all();

        return view('dashboard.paies_transfered.index', compact('paies', 'papers'));
    }

    public function show($account, $paie_dt) {
        if(!Auth::user()->can('paies_transfered_read')){
            return response('Unauthorized.', 401);
        }
        Session::forget('paie_transfered_search');
        $paies = DB::table('paies')
            ->join('paie_salaries', 'paies.id', '=', 'paie_salaries.paie_id')
            ->join('employees', 'employees.id', '=', 'paie_salaries.employee_id')
            ->join('jobs', 'jobs.id', '=', 'employees.job_id')
            ->selectRaw('paie_salaries.*,
            paies.id as paie_id,
            paies.month_id,
            paies.nb_days as paie_nb_days,
            paies.month_days,
            paies.paie_dt,

            employees.employee_name,
            paie_salaries.city,
            paie_salaries.work_zone,
            employees.bank_account_name,
            employees.bank_account,
            employees.bank_name,
            employees.bank_iban,

            jobs.job_name
            ')
            ->where('status', '=', 1)
            ->where('trans_status', '=', 1)
            ->where('paies.paie_dt', '=', $paie_dt)
            ->get();
        $paper = Paper::where('is_default', '=', 1)->first();
        $papers = Paper::all();

        return view('dashboard.paies_transfered.show', compact('paies', 'paie_dt', 'papers', 'paper'));
    }
    public function preview($account, $paie_dt, $paper_id = null){
        if(!Auth::user()->can('paies_transfered_read')){
            return response('Unauthorized.', 401);
        }

        if(Session::has('paie_transfered_search')) {
            $columns = Session::get('paie_transfered_search');
            $columns = json_decode($columns);

            $whr = [];

            foreach ($columns as $column) {
                if(isset($column->value) && $column->value){
                    $whr[] = ["$column->name", "like", '%' . $column->value . '%'];
                }
            }
            //dd($whr);

            $paies = DB::table('paies')
                ->join('paie_salaries', 'paies.id', '=', 'paie_salaries.paie_id')
                ->join('employees', 'employees.id', '=', 'paie_salaries.employee_id')
                ->join('jobs', 'jobs.id', '=', 'employees.job_id')
                ->selectRaw('paie_salaries.*,
            paies.id as paie_id,
            paies.month_id,
            paies.nb_days as paie_nb_days,
            paies.month_days,
            paies.paie_dt,

            employees.employee_name,
            paie_salaries.city,
            paie_salaries.work_zone,
            employees.bank_account_name,
            employees.bank_account,
            employees.bank_name,
            employees.bank_iban,

            jobs.job_name
            ')
                ->where('status', '=', 1)
                ->where('trans_status', '=', 1)
                ->where('paies.paie_dt', '=', $paie_dt)

                ->where($whr)
                ->get();
            //dd($paies);
        }
        else {
            $paies = DB::table('paies')
                ->join('paie_salaries', 'paies.id', '=', 'paie_salaries.paie_id')
                ->join('employees', 'employees.id', '=', 'paie_salaries.employee_id')
                ->join('jobs', 'jobs.id', '=', 'employees.job_id')
                ->selectRaw('paie_salaries.*,
            paies.id as paie_id,
            paies.month_id,
            paies.nb_days as paie_nb_days,
            paies.month_days,
            paies.paie_dt,

            employees.employee_name,
            paie_salaries.city,
            paie_salaries.work_zone,
            employees.bank_account_name,
            employees.bank_account,
            employees.bank_name,
            employees.bank_iban,

            jobs.job_name
            ')
                ->where('status', '=', 1)
                ->where('trans_status', '=', 1)
                ->where('paies.paie_dt', '=', $paie_dt)
                ->get();
        }


        $paper = ($paper_id)?
            Paper::find($paper_id) :
            Paper::where('is_default', '=', 1)->first();
        $papers = Paper::all();
        return view('dashboard.paies_transfered.preview_list', compact('paies', 'paie_dt', 'paper',
'papers'));

    }

    /*

    public function setSearchSession($account, Request $request){
        Session::put('paie_transfered_search', $request->search);
        return  Session::get('paie_transfered_search');
    }
    */

    public function setSearchSession($account, Request $request){
        Session::put('paie_transfered_search', $request->columns);
        return  Session::get('paie_transfered_search');
    }

}
