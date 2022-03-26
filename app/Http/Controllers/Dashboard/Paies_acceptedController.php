<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Paie_salaries;
use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Paies_acceptedController extends Controller
{
    public function index(){
        if(!Auth::user()->can('paies_accepted_read')){
            return response('Unauthorized.', 401);
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
            paie_salaries.city,
            paie_salaries.work_zone,
            employees.bank_account_name,
            employees.bank_account,
            employees.bank_name,
            employees.bank_iban,

            jobs.job_name
            ')
            ->where('status', '=', 1)
            ->where('trans_status', '=', 0)
            ->get();

        $paper = Paper::where('is_default', '=', 1)->first();
        $papers = Paper::all();

        return view('dashboard.paies_accepted.index', compact('paies', 'papers', 'paper'));
    }
    public function preview($account, $paper_id = null, $filter = null){
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
        ->where('status', '=', 1)
        ->where('trans_status', '=', 0);

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
            //->toSql();
            ->get();


        $paper = ($paper_id)?
            Paper::find($paper_id) :
            Paper::where('is_default', '=', 1)->first();
        $papers = Paper::all();
        return view('dashboard.paies_accepted.preview_list', compact('paies', 'paper',
            'papers'));

    }

    public function transfer_paies($account, Request $request){
        if(!Auth::user()->can('paies_accepted_create')){
            return response('Unauthorized.', 401);
        }
        $updated_count = 0;
        $params = [
            'trans_status' => 1,
            'trans_dt' => $request->transfer_dt,
            'trans_notes' => $request->trans_notes,
        ];
        if(isset($request->salaries)) {
            $salaries = explode(';', trim($request->salaries));

            if(count($salaries) > 0) {
                for($i = 0; $i < count($salaries) -1; $i++){
                    $inv = Paie_salaries::where('id', $salaries[$i])->update($params);
                    if($inv) {
                        $updated_count++;
                    }
                }
            }
        }

        if($updated_count) {
            session()->flash('success', __('تم صرف المستحقات المختارة بنجاح!'));
            return 1;
        }
        return 0;
    }

}
