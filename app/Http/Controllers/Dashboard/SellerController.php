<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Contract;
use App\Models\Payment_method;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Seller;
use App\Models\Seller_deduction_advance;
use App\Models\Seller_payment;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::user()->can('sellers_read')){
            return response('Unauthorized.', 401);
        }
        $sellers = DB::table('sellers')
            ->join('users', 'users.id', '=', 'sellers.created_by')
            ->selectRaw('
                    sellers.*,
                    CONCAT(users.first_name, " ", users.last_name) AS username
            ')
            ->get();
        return view('dashboard.sellers.index', compact('sellers'));
    }
    public function modal($account, $source = null)
    {
        if(!Auth::user()->can('sellers_read')){
            return response('Unauthorized.', 401);
        }
        $sellers = Seller::all();
        return view('dashboard.sellers.modal', compact('sellers', 'source'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Auth::user()->can('sellers_create')){
            return response('Unauthorized.', 401);
        }
        $last_seller = DB::table('sellers')->latest()->first();//->id;
        $seller_id = ($last_seller)? $last_seller->id + 1 : 1;

        $payments_methods = Payment_method::all();

        return view('dashboard.sellers.edit', compact('seller_id', 'payments_methods'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($account, Request $request)
    {
        if(!Auth::user()->can('sellers_create')){
            return response('Unauthorized.', 401);
        }
        $request_data = $request->all();

        $request_data['password'] = bcrypt(trim($request->password_visible));
        $request_data['permissions_json'] = json_encode($request->permissions);
        $request_data['created_by'] = Auth::id();

        $seller = Seller::create($request_data);

        if($seller && isset($request->can_login)) {
            $user = User::create([
                'first_name' => $request->first_name,
                'email' => $request->email,
                'seller_id' => $seller->id,
                'mobile_1' => $seller->mobile_1,
                'mobile_2' => $seller->mobile_2,
                'password_visible' => $request->password_visible,
                'password' => bcrypt(trim($request->password_visible)),
                'permissions_json' => json_encode($request->permissions)
            ]);

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
        }

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.sellers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function show($account, Seller $seller)
    {
        if(!Auth::user()->can('sellers_read')){
            return response('Unauthorized.', 401);
        }
        $seller = DB::table('sellers')
            ->join('payment_methods', 'payment_methods.id', '=', 'sellers.payment_method_id')
            ->selectRaw('sellers.*, payment_methods.pm_name')
            ->where('sellers.id', '=', $seller->id)
            ->latest()->first();

        $contracts = DB::table('contracts')
            ->join('customers', 'customers.id', '=', 'contracts.customer_id')
            ->selectRaw('contracts.*, customers.name_ar as customer_name')
            ->where('contracts.status', '=', 1)
            ->where('seller_id', '=', $seller->id)
            ->get();

        $permissions = DB::table('permissions')
            ->join('permission_user', 'permissions.id', '=', 'permission_user.permission_id')
            ->join('users', 'users.id', '=', 'permission_user.user_id')
            ->where('users.seller_id', '=', $seller->id)
            ->get();

        $perm_arr = [];
        foreach ($permissions as $p) {
            $perm_arr[] = $p->name;
        }
        $permissions = $perm_arr;
        return view('dashboard.sellers.show', compact(
            'seller',
            'contracts',
            'permissions'
        ));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function edit($account, Seller $seller)
    {
        if(!Auth::user()->can('sellers_update')){
            return response('Unauthorized.', 401);
        }
        $permissions = DB::table('permissions')
            ->join('permission_user', 'permissions.id', '=', 'permission_user.permission_id')
            ->join('users', 'users.id', '=', 'permission_user.user_id')
            ->where('users.seller_id', '=', $seller->id)
            ->get();

        $perm_arr = [];
        foreach ($permissions as $p) {
            $perm_arr[] = $p->name;
        }
        $permissions = $perm_arr;

        $payments_methods = Payment_method::all();

        return view('dashboard.sellers.edit', compact('seller', 'permissions', 'payments_methods'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update($account, Request $request, Seller $seller)
    {
        if(!Auth::user()->can('sellers_update')){
            return response('Unauthorized.', 401);
        }
        $seller_id = $seller->id;
        /*
        $request->validate([
            'first_name' => 'required',
            'email' => 'required',
            'password_visible' => 'required',
            //'role' => 'required',
        ], [
            'first_name.required' => 'اسم المندوب  ضروري.',
            'email.required' => ' الإيميل ضروري.',
            'password_visible.required' => ' كلمة المرور ضرورية.',
            //'role.required' => 'الوظيفة ضرورية.',
        ]);
        */

        $params = [
            'first_name' => $request->first_name,
            'mobile_1' => $request->mobile_1,
            'mobile_2' => $request->mobile_2,

            'payment_method_id' => $request->payment_method_id,

            'bank_name' => $request->bank_name,
            'bank_iban' => $request->bank_iban,
            'bank_account' => $request->bank_account,

            'can_login' => $request->can_login,

            'email' => $request->email,
            'password_visible' => $request->password_visible,
            'created_by' => Auth::id(),

        ];

        $seller = DB::table('sellers')
            ->where('id', $seller_id)
            ->update($params);

        $user = User::where('seller_id', '=', $seller_id)->first();

        if($user){
            $permissions_json = json_encode($request->permissions);

            DB::table('users')
                ->where('seller_id', $seller_id)
                ->update([
                    'first_name' => $request->first_name,
                    'email' => $request->email,
                    'mobile_1' => $request->mobile_1,
                    'mobile_2' => $request->mobile_2,
                    'password_visible' => $request->password_visible,
                    'password' => bcrypt(trim($request->password_visible)),
                    'permissions_json' => $permissions_json
                ]);
            if(!empty($request->permissions)) {
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
            }

        }

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.sellers.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy($account, Seller $seller)
    {

    }

    public function delete($account, $id)
    {
        if(!Auth::user()->can('sellers_delete')){
            return response('Unauthorized.', 401);
        }
        $response = ['status' => 0, 'message' => 'يتعذر الحذف !'];
        $seller = Seller::find($id);
        if(!$seller){
            return json_encode($response);
        }

        $contracts = Contract::where('seller_id', '=', $id)->get();
        if(count($contracts)){
            $response['message'] = "يرجى حذف كل العقود المرتبطة بهذا المسوق قبل حذفه !";
            return json_encode($response);
        }
        $seller_payments = Seller_payment::where('seller_id', '=', $id)->get();
        if(count($seller_payments)){
            $response['message'] = "يرجى حذف كل المستحقات المرتبطة بهذا المسوق قبل حذفه !";
            return json_encode($response);
        }
        $seller_deduction_advances = Seller_deduction_advance::where('seller_id', '=', $id)->get();
        if(count($seller_deduction_advances)){
            $response['message'] = "يرجى حذف كل الخصومات والسلف المرتبطة بهذا المسوق قبل حذفه !";
            return json_encode($response);
        }

        $rs = $seller->delete();
        if($rs){
            Seller::where('id', '=', $id)->delete();
            User::where('seller_id', '=', $id)->delete();
            $response['status'] = 1;
            session()->flash('success', __('site.deleted_successfully'));
            return json_encode($response);

        }
    }

}
