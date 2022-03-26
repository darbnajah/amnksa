<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Deduction_advance;
use App\Models\Employee;
use App\Models\Job;
use App\Models\Paie_salaries;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::user()->can('employees_read')){
            return response('Unauthorized.', 401);
        }
        $employees = DB::table('employees')
            ->join('jobs', 'jobs.id', '=', 'employees.job_id')
            ->selectRaw('employees.*, jobs.job_name')
            ->orderBy('employees.created_at', 'DESC')
            ->get();

        return view('dashboard.employees.index', compact('employees'));
    }

    public function modal($account, $source = null)
    {
        if(!Auth::user()->can('employees_read')){
            return response('Unauthorized.', 401);
        }
        $employees = DB::table('employees')
            ->join('jobs', 'jobs.id', '=', 'employees.job_id')
            ->selectRaw('employees.*, jobs.job_name')
            ->where('employees.civil_card_expire_dt', NULL)
            ->get();

        return view('dashboard.employees.modal', compact('employees', 'source'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Auth::user()->can('employees_create')){
            return response('Unauthorized.', 401);
        }
        $last_employee = DB::table('employees')->latest()->first();//->id;
        $employee_id = ($last_employee)? $last_employee->id + 1 : 1;

        $jobs = Job::all();
        $company = Company::find(1);

        return view('dashboard.employees.edit', compact('jobs', 'employee_id', 'company'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::user()->can('employees_create')){
            return response('Unauthorized.', 401);
        }
        $request->validate([
            'code' => 'required',
            'employee_name' => 'required',
            'job_id' => 'required',
        ], [
            'code.required' => 'كود الموظف ضروري.',
            'employee_name.required' => 'اسم الموظف ضروري.',
            'job_id.required' => 'الوظيفة ضرورية.',
        ]);
        $request_data = $request->all();

        $request_data['password'] = bcrypt($request->password);


        $employee = Employee::create($request_data);

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.employees.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show($account, Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit($account, Employee $employee)
    {
        if(!Auth::user()->can('employees_update')){
            return response('Unauthorized.', 401);
        }
        $jobs = Job::all();
        $company = Company::find(1);

        return view('dashboard.employees.edit', compact('employee', 'jobs', 'company'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update($account, Request $request, Employee $employee)
    {
        if(!Auth::user()->can('employees_update')){
            return response('Unauthorized.', 401);
        }
        $request->validate([
            'code' => 'required',
            'employee_name' => 'required',
            'job_id' => 'required',
        ], [
            'code.required' => 'كود الموظف ضروري.',
            'employee_name.required' => 'الإسم الموظف ضروري.',
            'job_id.required' => 'الوظيفة ضرورية.',
        ]);
        $params = [
            'code' => $request->code,
            'employee_name' => $request->employee_name,

            'city' => $request->city,
            'work_zone' => $request->work_zone,
            'dt_start' => $request->dt_start,
            'salary' => $request->salary,
            'mobile_1' => $request->mobile_1,

            'civil_card_number' => $request->civil_card_number,
            'civil_card_issue' => $request->civil_card_issue,
            'civil_card_expire_dt' => $request->civil_card_expire_dt,
            'attach_civil_card' => null,

            'bank_account_name' => $request->bank_account_name,
            'bank_name' => $request->bank_name,
            'bank_iban' => $request->bank_iban,
            'bank_account' => $request->bank_account,
            'attach_bank' => null,
        ];
        if(isset($request->password_visible)) {
            $params['password'] = bcrypt($request->password_visible);
        }


        DB::table('employees')
            ->where('id', $employee->id)
            ->update($params);


        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.employees.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy($account, Employee $employee)
    {

    }
    public function delete($account, $id)
    {
        if(!Auth::user()->can('employees_delete')){
            return response('Unauthorized.', 401);
        }
        $response = ['status' => 0, 'message' => 'يتعذر الحذف !'];
        $employee = Employee::find($id);
        if(!$employee){
            return json_encode($response);
        }

        $salaries = Paie_salaries::where('employee_id', '=', $id)->get();
        if(count($salaries)){
            $response['message'] = "يرجى حذف كل الرواتب المرتبطة بهذا الموظف قبل حذفه !";
            return json_encode($response);
        }

        $deduction_advances = Deduction_advance::where('employee_id', '=', $id)->get();
        if(count($deduction_advances)){
            $response['message'] = "يرجى حذف كل الخصومات والسلف المرتبطة بهذا المظف قبل حذفه !";
            return json_encode($response);
        }

        $rs = $employee->delete();
        if($rs){
            Employee::where('id', '=', $id)->delete();
            $response['status'] = 1;
            session()->flash('success', __('site.deleted_successfully'));
            return json_encode($response);

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
        return view('dashboard.employees.contracts_by_customer', compact(
            'contracts',
            'company'
        ));
    }

}
