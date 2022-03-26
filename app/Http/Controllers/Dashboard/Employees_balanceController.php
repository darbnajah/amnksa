<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isNull;

class Employees_balanceController extends Controller
{
    public function index($account, $filter = null, $paper_id = null)
    {
        if(!Auth::user()->can('employees_balance_read')){
            return response('Unauthorized.', 401);
        }
        $filter = explode('_', $filter);

        $employee_id = isset($filter[0])? intval($filter[0]) * 1 : null;
        $preview = isset($filter[1])? $filter[1] : null;
        $dt_from = isset($filter[2])? $filter[2] : null;
        $dt_to = isset($filter[3])? $filter[3] : null;

        $preview_url = url()->current();
        $employee = ($employee_id > 0)? Employee::find($employee_id) : null;

        $employee_name = isset($employee)? $employee->employee_name : null;

        $whr = [];
        if($employee_id) {
            $whr[] = ['paie_salaries.employee_id', '=', $employee_id];
        }

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
            employees.city,
            employees.work_zone,

            jobs.job_name
            ')
            ->where($whr)
            ->get();

        if($dt_from && $dt_to) {
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

            jobs.job_name
            ')
                ->where($whr)
                ->whereBetween('paies.paie_dt', [$dt_from, $dt_to])
                ->get();
        }
        $paper = ($paper_id)?
            Paper::find($paper_id) :
            Paper::where('is_default', '=', 1)->first();
        $papers = Paper::all();

        $preview = explode('=', $preview);
        $view = (isset($preview[1]) && $preview[1] == 1)? 'preview_list' : 'index';

        return view('dashboard.employees_balance.'.$view, compact(
            'paies',
            'papers',
            'paper',
            'employee_id',
            'employee_name',
            'dt_from',
            'dt_to',
            'preview_url'
        ));
    }

}
