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
use Illuminate\Support\Facades\Session;

class PaiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
            ->where('trans_status', '<>', 1)
            ->orderBy('paie_salaries.status', 'ASC')
            ->orderBy('paie_salaries.trans_status', 'ASC')
            ->get();

        $paper = Paper::where('is_default', '=', 1)->first();
        $papers = Paper::all();

        return view('dashboard.paies.index', compact('paies','papers', 'paper'));
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
            ->where('paie_salaries.trans_status', '<>', 1);

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
        return view('dashboard.paies.preview_list', compact('paies', 'paper',
            'papers'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Auth::user()->can('paies_create')){
            return response('Unauthorized.', 401);
        }
        return view('dashboard.paies.edit');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::user()->can('paies_create')){
            return response('Unauthorized.', 401);
        }
        $params = [
            'month_id' => $request->month_id,
            'nb_days' => $request->nb_days,
            'month_days' => $request->month_days,
            'paie_dt' => $request->paie_dt,
            'created_by' => Auth::id(),

        ];

        $paie = Paie::create($params);

        if($paie && isset($request->paies)) {
            $paies = explode('::', trim($request->paies));

            if(count($paies) > 0) {
                for($i = 0; $i < count($paies) -1; $i++){
                    $salary = explode(';', $paies[$i]);

                    $ps = Paie_salaries::create([
                        'employee_id' => $salary[0],
                        'city' => $salary[1],
                        'work_zone' => $salary[2],
                        'salary' => $salary[3],
                        'nb_days' => $salary[4] * 1,
                        'advance' => $salary[5] * 1,
                        'deduction' => $salary[6] * 1,
                        'extra' => $salary[7] * 1,
                        'salary_net' =>$salary[8],
                        'paie_id' => $paie->id,
                    ]);
                }
            }
        }

        session()->flash('success', __('site.added_successfully'));
        $data = ['valid' => 1, 'route' => route('dashboard.paies.index')];
        return json_encode($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Paie  $paie
     * @return \Illuminate\Http\Response
     */
    public function show($account, Paie $paie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Paie  $paie
     * @return \Illuminate\Http\Response
     */
    public function edit($account, $slaary_id)
    {
        if(!Auth::user()->can('paies_update')){
            return response('Unauthorized.', 401);
        }
        $salary = DB::table('paies')
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
            ->where('status', '=', -1)
            ->where('paie_salaries.id', '=', $slaary_id)
            ->get()->first();

        $da = new Deductions_advancesController();
        $da = $da->total_rest_deductions_advances_by_employee($account, $salary->employee_id);
        $da = json_decode($da);
        $total_deductions = $da->total_deductions;
        $total_advances = $da->total_advances;

        return view('dashboard.paies.edit', compact('salary', 'total_deductions',
'total_advances'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Paie  $paie
     * @return \Illuminate\Http\Response
     */
    public function update($account, Request $request, $id)
    {
        if(!Auth::user()->can('paies_update')){
            return response('Unauthorized.', 401);
        }
        //dd($request);
        if(isset($request->paies)) {
            $paies = explode('::', trim($request->paies));
            if(count($paies) > 0) {
                    $salary = explode(';', $paies[0]);

                    Paie_salaries::where('id', '=', $id)
                    ->update([
                        'city' => $salary[1],
                        'work_zone' => $salary[2],
                        'salary' => $salary[3],
                        'nb_days' => $salary[4] * 1,
                        'advance' => $salary[5] * 1,
                        'deduction' => $salary[6] * 1,
                        'extra' => $salary[7] * 1,
                        'salary_net' =>$salary[8],
                        'status' => 0,
                        'deny_notes' => NULL
                    ]);

            }
        }

        session()->flash('success', __('تم تعديل الراتب بنجاح !'));
        $data = ['valid' => 1, 'route' => route('dashboard.paies.index')];
        return json_encode($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Paie  $paie
     * @return \Illuminate\Http\Response
     */
    public function destroy($account, Paie $paie)
    {
        //
    }

    public function accept_paies($account, Request $request){
        if(!Auth::user()->can('paies_create')){
            return response('Unauthorized.', 401);
        }
        $updated_count = 0;
        $params = [
            'status' => 1,
            'accept_dt' => $request->accept_dt,
        ];
        if(isset($request->salaries)) {
            $salaries = explode(';', trim($request->salaries));

            if(count($salaries) > 0) {
                for($i = 0; $i < count($salaries) -1; $i++){
                    $inv = Paie_salaries::where('id', $salaries[$i])->update($params);
                    if($inv) {
                        $salary = Paie_salaries::find($salaries[$i]);
                        if($salary){
                            if($salary->advance > 0){
                                Deduction_advance::create([
                                    'dt' => $request->accept_dt,
                                    'label' => 'اقتطاع سلفة',
                                    'debit' => 0,
                                    'credit' => $salary->advance,
                                    'type' => 'advance',
                                    'employee_id' => $salary->employee_id,
                                    'salary_id' => $salary->id
                                ]);
                            }
                            if($salary->deduction > 0){
                                Deduction_advance::create([
                                    'dt' => $request->accept_dt,
                                    'label' => 'اقتطاع خصم',
                                    'debit' => 0,
                                    'credit' => $salary->deduction,
                                    'type' => 'deduction',
                                    'employee_id' => $salary->employee_id,
                                    'salary_id' => $salary->id
                                ]);
                            }
                        }

                        $updated_count++;
                    }
                }
            }
        }

        if($updated_count) {
            session()->flash('success', __('تم تعميد الرواتب المختارة بنجاح!'));
            return 1;
        }
        return 0;
    }

    public function deny_paies($account, Request $request){
        if(!Auth::user()->can('paies_create')){
            return response('Unauthorized.', 401);
        }
        $updated_count = 0;
        $params = [
            'status' => -1,
            'accept_dt' => NULL,
            'deny_notes' => $request->deny_notes,
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
            session()->flash('success', __('تم رفض تعميد الرواتب المختارة بنجاح!'));
            return 1;
        }
        return 0;
    }

    public function cancel_salary_accept($account, $salary_id){
        if(!Auth::user()->can('paies_create')){
            return response('Unauthorized.', 401);
        }
        $salary = Paie_salaries::where('id', $salary_id)->update([
            'status' => 0,
            'accept_dt' => NULL,
        ]);

        DB::table('deduction_advances')->where('salary_id', $salary_id)->delete();

        if($salary){
            session()->flash('success', __('تم إلغاء تعميد الراتب بنجاح !'));
            return 1;
        }
    }
    /*
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
    */
    public function delete_salary($account, $salary_id){
        if(!Auth::user()->can('paies_delete')){
            return response('Unauthorized.', 401);
        }
        $salary = Paie_salaries::find($salary_id);
        $rs = $salary->delete();
        DB::table('deduction_advances')
            ->where('salary_id', $salary_id)
            ->delete();
        if($rs){
            session()->flash('success', __('site.deleted_successfully'));
            return 1;
        }
    }

    public function contracts_by_customer($account, $customer_id){
        $company = Company::find(1);

        $contracts = DB::table('contracts')
            ->selectRaw('contracts.*')
            ->addSelect(['customer_name' => DB::table('customers')
                ->selectRaw('customers.name_ar')
                ->where('customers.id', '=', $customer_id)
                ->limit(1)
            ])
            ->where('contracts.status', '=', 1)
            ->where('customer_id', '=',$customer_id)
            ->get();
        //dd($contracts);
        return view('dashboard.paies.contracts_by_customer', compact(
            'contracts',
            'company'
        ));
    }

    public function setDtSearchSession($account, Request $request){
        Session::put('paies_search', $request->dt_search);
        return  Session::get('paies_search');
    }
}
