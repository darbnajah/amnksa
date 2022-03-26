<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd(auth()->user()->hasRole('super_admin'));
        //dd(auth()->user()->isA('super_admin'));
        //dd(auth()->user()->isAbleTo('users_create'));
        //dd(auth()->user()->can('users_create'));
        $roles = Role::all();
        return view('dashboard.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $models = Config::get('roles_models');

        return view('dashboard.roles.edit', compact('models'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
                'name' => [
                    'required',
                    'regex:/^[a-z_]+$/i'
                ],
                'display_name' => 'required',
            ],
            [
                'name.required' => 'كود الوظيفة ضروري.',
                'name.regex' => 'كود الوظيفة غير صحيح.',
                'display_name.required' => 'اسم الوظيفة ضروري.',
            ]);

        $request_data = (object)$request->all();

        // Create a new role
        $role = Role::firstOrCreate([
            'name' => $request_data->name,
            'display_name' => $request_data->display_name,
            'description' => $request_data->description
        ]);

        $permissions = [];

        foreach ($request_data->permissions as $perm) {
            $permissions[] = Permission::firstOrCreate([
                'name' => $perm,
                'display_name' => $perm,
                'description' => $perm,
            ])->id;
        }

        // Attach all permissions to the role
        $r = $role->permissions()->sync($permissions);

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.roles.index');

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $rs = $role->delete();
        if($rs){
            session()->flash('success', __('site.deleted_successfully'));
            return redirect()->route('dashboard.roles.index');
        }
    }
}
