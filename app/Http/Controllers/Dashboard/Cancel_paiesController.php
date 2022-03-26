<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Deduction_advance;
use App\Models\Paie;
use App\Models\Paie_salaries;
use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Cancel_paiesController extends Controller
{

    public function index()
    {
        if(!Auth::user()->can('cancel_paies_read')){
            return response('Unauthorized.', 401);
        }
        $paies = DB::table('paies')
            ->join('paie_salaries', 'paies.id', '=', 'paie_salaries.paie_id')
            ->join('employees', 'employees.id', '=', 'paie_salaries.employee_id')
            ->join('jobs', 'jobs.id', '=', 'employees.job_id')
            ->join('users', 'users.id', '=', 'paies.created_by')
            ->selectRaw('paie_salaries.*,
            paies.id as paie_id,
            paies.month_id,
            paies.nb_days as paie_nb_days,
            paies.month_days,
            paies.paie_dt,

            employees.employee_name,
            paie_salaries.city,
            paie_salaries.work_zone,

            jobs.job_name,
            CONCAT(users.first_name, " ", users.last_name) AS username
            ')
            ->where('trans_status', '=', 1)
            ->orderBy('paie_salaries.status', 'ASC')
            ->orderBy('paie_salaries.trans_status', 'ASC')
            ->get();

        $paper = Paper::where('is_default', '=', 1)->first();
        $papers = Paper::all();

        return view('dashboard.cancel_paies.index', compact('paies', 'papers', 'paper'));
    }

    public function preview($account, $paper_id = null, $filter = null){
        if(!Auth::user()->can('paies_read')){
            return response('Unauthorized.', 401);
        }
         $paies = DB::table('paies')
            ->join('paie_salaries', 'paies.id', '=', 'paie_salaries.paie_id')
            ->join('employees', 'employees.id', '=', 'paie_salaries.employee_id')
            ->join('jobs', 'jobs.id', '=', 'employees.job_id')
            ->join('users', 'users.id', '=', 'paies.created_by')
            ->selectRaw('paie_salaries.*,
            paies.id as paie_id,
            paies.month_id,
            paies.nb_days as paie_nb_days,
            paies.month_days,
            paies.paie_dt,

            employees.employee_name,
            paie_salaries.city,
            paie_salaries.work_zone,

            jobs.job_name,
            CONCAT(users.first_name, " ", users.last_name) AS username
            ')
            ->where('trans_status', '=', 1);

        if(isset($filter)) {
            $columns = [
                'paies.paie_dt',
                'employees.employee_name',
                'paie_salaries.work_zone',
                'paie_salaries.city',
            ];
            $paies->where(function ($query) use ($columns, $filter) {
                foreach ($columns as $column) {
                    $query->orWhere("$column", "like", '%' . $filter . '%');
                }

            });

        }

        $paies = $paies
                ->orderBy('paie_salaries.status', 'ASC')
                ->orderBy('paie_salaries.trans_status', 'ASC')
                //->toSql();
                ->get();

        $paper = ($paper_id)?
            Paper::find($paper_id) :
            Paper::where('is_default', '=', 1)->first();
        $papers = Paper::all();
        return view('dashboard.cancel_paies.preview_list', compact('paies', 'paper',
            'papers'));

    }


    public function cancel_salary_transfer($account, $salary_id){
        if(!Auth::user()->can('paies_create')){
            return response('Unauthorized.', 401);
        }
        $salary = Paie_salaries::where('id', $salary_id)->update([
            'trans_status' => 0,
            'trans_dt' => NULL,
        ]);

        if($salary){
            session()->flash('success', __('تم إلغاء صرف الراتب بنجاح !'));
            return 1;
        }
    }

}
