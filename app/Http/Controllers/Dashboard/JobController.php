<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Job;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::user()->can('jobs_read')){
            return response('Unauthorized.', 401);
        }
        $jobs = Job::all();
        return view('dashboard.jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Auth::user()->can('jobs_create')){
            return response('Unauthorized.', 401);
        }
        return view('dashboard.jobs.edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($account, Request $request)
    {
        if(!Auth::user()->can('jobs_create')){
            return response('Unauthorized.', 401);
        }
        $request->validate([
            'job_name' => 'required',
        ],
        [
            'job_name.required' => 'مسمى الوظيفة ضروري.',
        ]);

        $job = Job::create([
            'job_name' => $request->job_name,
        ]);

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.jobs.index');

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($account, $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($account, $id)
    {
        if(!Auth::user()->can('jobs_update')){
            return response('Unauthorized.', 401);
        }
        $job = Job::find($id);
        return view('dashboard.jobs.edit', compact('job'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($account, Request $request, $id)
    {
        if(!Auth::user()->can('jobs_update')){
            return response('Unauthorized.', 401);
        }
        $request->validate([
            'job_name' => 'required',
        ],
        [
            'job_name.required' => 'مسمى الوظيفة ضروري.',
        ]);

        $params = [
            'job_name' => $request->job_name,
        ];
        DB::table('jobs')
            ->where('id', $id)
            ->update($params);


        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.jobs.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($account, Job $job)
    {

    }
    public function delete($account, $id)
    {
        if(!Auth::user()->can('jobs_delete')){
            return response('Unauthorized.', 401);
        }
        $response = ['status' => 0, 'message' => 'يتعذر الحذف !'];
        $job = Job::find($id);
        if(!$job){
            return json_encode($response);
        }

        $employees = Employee::where('job_id', '=', $id)->get();
        if(count($employees)){
            $response['message'] = "يرجى فك ارتباط الوظيفة بالموظفين قبل حذفها !";
            return json_encode($response);
        }
        $rs = $job->delete();
        if($rs){
            Job::where('id', '=', $id)->delete();
            $response['status'] = 1;
            session()->flash('success', __('site.deleted_successfully'));
            return json_encode($response);

        }
    }

}
