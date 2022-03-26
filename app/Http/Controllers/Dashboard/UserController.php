<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::user()->can('users_read')){
            return response('Unauthorized.', 401);
        }
        //dd(\auth()->user()->hasPermission('users_create'));
        $whr = [
            ['users.seller_id', '=', 0],
        ];
        if(Auth::id() > 1){
            $whr[] =  ['users.id', '>', 1];
        }

        if(auth()->user()->role != 'super_admin'){
            $whr[] = ['users.role', '<>', 'super_admin'];
        }
        $users = DB::table('users')
            ->where($whr)
            ->orWhere('users.role', '=', NULL)
            ->orderBy('users.id', 'DESC')
            ->get();

        return view('dashboard.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Auth::user()->can('users_create')){
            return response('Unauthorized.', 401);
        }
        $last_user = DB::table('users')->latest()->first();//->id;
        $user_id = ($last_user)? $last_user->id + 1 : 1;

        $roles = Role::all();
        $models = Config::get('roles_models.models');

        $sections = Config::get('roles_models.sections');

        return view('dashboard.users.edit', compact('roles', 'user_id', 'models', 'sections'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::user()->can('users_create')){
            return response('Unauthorized.', 401);
        }
        $request->validate([
           'code' => 'required',
           'first_name' => 'required',
           'last_name' => 'required',
           'email' => 'required',
           'password_visible' => 'required',
           'permissions' => 'required|array|min:1',
            //'role' => 'required',
        ], [
            'code.required' => 'كود المستخدم ضروري.',
            'first_name.required' => 'الإسم الأول ضروري.',
            'last_name.required' => 'الإسم الأخير ضروري.',
            'email.required' => ' الإيميل ضروري.',
            'password_visible.required' => ' كلمة المرور ضرورية.',
            'permissions.required' => ' الصلاحيات ضروري واحدة على الأقل.',
            //'role.required' => 'الوظيفة ضرورية.',
        ]);

        $request_data = $request->all();

        $request_data['password'] = bcrypt(trim($request->password_visible));
        $request_data['permissions_json'] = json_encode($request->permissions);
        $request_data['company_id'] = Auth::user()->company_id;
        $user = User::create($request_data);

        $permissions = [];
        foreach ($request->permissions as $perm) {
            $p_id = Permission::where('name', $perm)->value('id'); // check if permission already exists
            if($p_id) {
                $permissions[] = $p_id;
            } else {
                $p = Permission::create([
                    'name' => $perm,
                    'display_name' => $perm,
                    'description' => $perm,
                ]);
                $permissions[] = $p->id;
            }
        }

        $user->attachPermissions($permissions);

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($account, User $user)
    {
        if(Auth::id() > 1 && $user->id == 1){
            return response('Unauthorized.', 401);
        }
        if($user->seller_id > 0){
            return response('Unauthorized.', 401);
        }
        $roles = Role::all();
        $models = Config::get('roles_models.models');
        $sections = Config::get('roles_models.sections');

        $permissions = DB::table('permissions')
            ->join('permission_user', 'permissions.id', '=', 'permission_user.permission_id')
            ->join('users', 'users.id', '=', 'permission_user.user_id')
            ->where('users.id', '=', $user->id)
            ->get();

        return view('dashboard.users.show', compact('user', 'roles', 'models', 'sections', 'permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($account, User $user)
    {
        if(!Auth::user()->can('users_update')){
            return response('Unauthorized.', 401);
        }
        if(Auth::id() > 1 && $user->id == 1){
            return response('Unauthorized.', 401);
        }
        if(Auth::id() > 2 && $user->id <= 2){
            return response('Unauthorized.', 401);
        }
        if($user->seller_id > 0){
            return response('Unauthorized.', 401);
        }
        $roles = Role::all();
        $models = Config::get('roles_models.models');
        $sections = Config::get('roles_models.sections');

        $permissions = DB::table('permissions')
            ->join('permission_user', 'permissions.id', '=', 'permission_user.permission_id')
            ->join('users', 'users.id', '=', 'permission_user.user_id')
            ->where('users.id', '=', $user->id)
            ->get();

        return view('dashboard.users.edit', compact(
            'user',
            'roles',
            'models',
            'sections',
            'permissions'
        ));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update($account, Request $request, User $user)
    {
        if(!Auth::user()->can('users_update')){
            return response('Unauthorized.', 401);
        }

        $validations = [
            'code' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'password_visible' => 'required',
        ];
        $validation_msgs = [
            'code.required' => 'كود المستخدم ضروري.',
            'first_name.required' => 'الإسم الأول ضروري.',
            'last_name.required' => 'الإسم الأخير ضروري.',
            'email.required' => ' الإيميل ضروري.',
            'password_visible.required' => ' كلمة المرور ضرورية.',
        ];

        if(Auth::id() > 1 && $user->id <= 1){
            $validations['permissions'] = 'required|array|min:1';
            $validation_msgs['permissions.required'] = ' الصلاحيات ضروري واحدة على الأقل.';
        }

        $request->validate($validations, $validation_msgs);

        $permissions_json = json_encode($request->permissions);
        $params = [
            'code' => $request->code,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'mobile_1' => $request->mobile_1,
            'mobile_2' => $request->mobile_2,
            'email' => $request->email,
            'password_visible' => $request->password_visible,
            //'permissions_json' => $permissions_json,
        ];

        if(isset($request->password_visible)) {
            $params['password'] = bcrypt(trim($request->password_visible));
        }

  //      if(Auth::id() <= 2 && $user->id > 1){

            DB::table('permission_user')
                ->where('user_id', $user->id)
                ->delete();

            $params['permissions_json'] = $permissions_json;
            $permissions = [];
            foreach ($request->permissions as $perm) {
                $p_id = Permission::where('name', $perm)->value('id'); // check if permission already exists
                if($p_id) {
                    $permissions[] = $p_id;
                } else {
                    $p = Permission::create([
                        'name' => $perm,
                        'display_name' => $perm,
                        'description' => $perm,
                    ]);
                    $permissions[] = $p->id;
                }
            }
            $user->syncPermissions($permissions);
        //}


        DB::table('users')
            ->where('id', $user->id)
            ->update($params);

        //$user = User::find($user->id);
        //$user->detachPermissions($request->permissions);
        //dd($request->permissions);


        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.users.show', $user->id);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($account, User $user)
    {
        if(!Auth::user()->can('users_delete') || $user->id <= 2){
            return response('Unauthorized.', 401);
        }

        $rs = $user->delete();
        if($rs){
            DB::table('permission_user')
                ->where('user_id', $user->id)
                ->delete();
            session()->flash('success', __('site.deleted_successfully'));
            return redirect()->route('dashboard.users.index');
        }
    }
}
