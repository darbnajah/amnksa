<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Company;
use App\Models\Paper;
use App\Models\Permission;
use App\Models\User;
use App\Traits\CreateCompanyDB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    public $company_id;
    public function __construct()
    {

    }

    /**
     * Display a dlisting of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::id() > 1){
            return response('Unauthorized.', 401);
        }
        $companies = Company::all();
        return view('dashboard.companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::id() > 1 || (isset($_SESSION["subdomain"]) && $_SESSION["subdomain"] != 'admin')){
            return response('Unauthorized.', 401);
        }
        $last_company = DB::table('companies')->latest()->first();//->id;
        $company_id = ($last_company)? $last_company->id + 1 : 1;

        return view('dashboard.companies.edit', compact('company_id'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Auth::id() > 1){
            return response('Unauthorized.', 401);
        }
        $request->validate([
            'company_id' => 'unique:companies',
            'company_name_ar' => 'required',
            'company_db_name' => [
                'required',
                'regex:/^[a-z0-9_]+$/i'
            ],
            'company_db_user_email' => [
                'unique:companies',
                'regex:/^.+@.+$/i'
            ],
            'company_db_user_name' => [
                'required',
            ],
            'company_db_user_password' => [
                'required',
                'min:6',
                //'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/'
            ]

        ],
            [
                'company_id.unique'      => 'كود شركة الحراسة يجب ألا يتكرر.',
                'company_name_ar.required'      => 'اسم الشركة بالعربي ضروري.',
                'company_db_name.required' => 'اسم القاعدة ضروري.',
                'company_db_name.regex' => 'اسم القاعدة غير صحيح.',

                'company_db_user_email.regex'      => 'إيميل المشرف غير صحيح.',
                'company_db_user_email.unique'      => 'إيميل المشرف موجود مسبقا.',

                'company_db_user_name.required'      => 'اسم المستخدم للقاعدة ضروري.',

                'company_db_user_password.required'      => 'كلمة المرور للمشرف ضروري.',
                'company_db_user_password.min'      => 'كلمة المرور للمشرف يجب أن يحتوي على الأقل 6 حروف.',
                //'company_db_user_password.regex'      => 'كلمة المرور للمشرف غير صحيح.',
            ]
        );

        $this->company_id = $request->company_id;

        $logo = null;

        $cachet = null;

        $sign_accountant = null;
        $sign_operational_director = null;
        $sign_financial_director = null;
        $sign_price_offer = null;

        $request_data = $request->all();
        $request_data['factor'] = $request->factor[0];

        if ($request->hasFile('logo')) {
            $request_data['logo'] = $request->logo->store('img/companies/'.$this->company_id.'/'.$this->company_id.'/logos', 'public');
        }
        if ($request->hasFile('cachet')) {
            $request_data['cachet'] = $request->cachet->store('img/companies/'.$this->company_id.'/cachets', 'public');
        }
        if ($request->hasFile('sign_accountant')) {
            $request_data['sign_accountant'] = $request->sign_accountant->store('img/companies/'.$this->company_id.'/signs', 'public');
        }
        if ($request->hasFile('sign_operational_director')) {
            $request_data['sign_operational_director'] = $request->sign_operational_director->store('img/companies/'.$this->company_id.'/signs', 'public');
        }
        if ($request->hasFile('sign_financial_director')) {
            $request_data['sign_financial_director'] = $request->sign_financial_director->store('img/companies/'.$this->company_id.'/signs', 'public');
        }
        if ($request->hasFile('sign_price_offer')) {
            $request_data['sign_price_offer'] = $request->sign_price_offer->store('img/companies/'.$this->company_id.'/signs', 'public');
        }

        $company = Company::create($request_data);

        if($company) {
            Session::put('expiration_dt', $request->expiration_dt);

            //create super_admin & admin users
            /*
            $permissions_json = [];
            $permissions = [];

            $mapPermission = collect(config('laratrust_seeder.permissions_map'));

            $models = Config::get('roles_models.models');

            foreach ($models as $module => $value) {

                foreach (explode(',', $value) as $p => $perm) {

                    $permissionValue = $mapPermission->get($perm);

                    $permission = Permission::firstOrCreate([
                        'name' => $module . '_' . $permissionValue,
                        'display_name' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                        'description' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                    ]);
                    $permissions[] = $permission->id;
                    $permissions_json[] = $permission->name;
                }
            }

            $last_user = DB::table('users')->latest()->first();//->id;
            $admin_user_id = ($last_user)? $last_user->id + 1 : 2;

            $user_admin = User::create([
                'company_id' => $company->id,
                'code' => $admin_user_id,
                'first_name' => $request->company_db_user_first_name,
                'last_name' => $request->company_db_user_last_name,
                'email' => $request->company_db_user_email,
                'role' => 'admin',
                'password' => bcrypt( $request->company_db_user_password),
                'password_visible' => $request->company_db_user_password,
                'permissions_json' => json_encode($permissions_json),
            ]);
            $user_admin->attachPermissions($permissions);
*/
            //CreateCompanyDB::create($request_data);
            session()->flash('success', __('site.added_successfully'));
            return redirect()->route('dashboard.companies.show', $company->id);
        }

    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show($account, Company $company)
    {

        $company = Company::find($company->id);

        /*$default_bank = Bank::where([
            ['company_id', '=', $company->id],
            ['is_default', '=', 1]
        ])->first();*/

        $banks = Bank::where([
            ['company_id', '=', $company->id]
        ])->get();
        $papers = Paper::where([
            ['company_id', '=', $company->company_id]
        ])->get();

        $company_db_sql = CreateCompanyDB::create($company);

        if($company->id > 1) {
            $permissions_json = User::find(1)->permissions_json;
            $user_password = bcrypt($company->company_db_user_password);

            $super_user = User::find(1);
            $update_super_user = "UPDATE users SET
                    first_name='{$super_user->first_name}',
                    last_name='{$super_user->last_name}',
                    mobile_1='{$super_user->mobile_1}',
                    mobile_2='{$super_user->mobile_2}',
                    email='{$super_user->email}',
                    password='" . bcrypt($super_user->password_visible) . "',
                    password_visible='{$super_user->password_visible}'
                 WHERE users.id = 1; ";

            $insert_new_user = "INSERT INTO users (
                   company_id,
                   code,
                   first_name,
                   last_name,
                   email,
                   role,

                   password,
                   password_visible,

                   permissions_json,
                   created_at,
                   updated_at
                   ) VALUES (
            '{$company->id}',
            '2',
            '{$company->company_db_user_first_name}',
            '{$company->company_db_user_last_name}',
            '{$company->company_db_user_email}',
            'admin',
            '{$user_password}',
            '{$company->company_db_user_password}',
            '{$permissions_json}',
            CURRENT_TIMESTAMP,
            CURRENT_TIMESTAMP
            );";

            $insert_user_permissions = CreateCompanyDB::syncUserPermissions(2);

            $insert_new_company = "INSERT INTO companies (
                        id,
                        company_id,
                        company_name_ar,
                        company_name_en,
                        address_ar,
                        address_en,
                        vat_number,
                        license_number,
                        commercial_record_date,
                        license_date,
                        notes,

                        company_db_name,
                        company_db_user_first_name,
                        company_db_user_last_name,
                        company_db_user_name,
                        company_db_user_email,
                        company_db_user_password,

                        factor,
                        expiration_dt,

                        sign_accountant_label,
                        sign_operational_director_label,
                        sign_financial_director_label,
                        sign_price_offer_label,

                        logo,
                        cachet,
                        sign_accountant,
                        sign_operational_director,
                        sign_financial_director,
                        sign_price_offer,

                        created_at,
                        updated_at
                    )
                    VALUES (
                        1,
                        '{$company->id}',
                        '{$company->company_name_ar}',
                        '{$company->company_name_en}',
                        '{$company->address_ar}',
                        '{$company->address_en}',
                        '{$company->vat_number}',
                        '{$company->license_number}',
                        '{$company->commercial_record_date}',
                        '{$company->license_date}',
                        '{$company->notes}',

                        '{$company->company_db_name}',
                        '{$company->company_db_user_first_name}',
                        '{$company->company_db_user_last_name}',
                        '{$company->company_db_user_name}',
                        '{$company->company_db_user_email}',
                        '{$company->company_db_user_password}',

                        '{$company->factor}',
                        '{$company->expiration_dt}',

                        '{$company->sign_accountant_label}',
                        '{$company->sign_operational_director_label}',
                        '{$company->sign_financial_director_label}',
                        '{$company->sign_price_offer_label}',

                        '{$company->logo}',
                        '{$company->cachet}',
                        '{$company->sign_accountant}',
                        '{$company->sign_operational_director}',
                        '{$company->sign_financial_director}',
                        '{$company->sign_price_offer}',

                        CURRENT_TIMESTAMP,
                        CURRENT_TIMESTAMP
                    );";
            $insert_new_company_as_customer = "INSERT INTO customers (name_ar, private) VALUES('{$company->company_name_ar}', 1);";
            $company_db_sql .= $insert_new_company . "\n\n".$insert_new_company_as_customer . "\n\n" .$update_super_user. "\n\n" .$insert_new_user. "\n\n" .$insert_user_permissions;
        }

        return view('dashboard.companies.show', compact('company', 'papers', 'banks', 'company_db_sql'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit($account, Company $company)
    {
        return view('dashboard.companies.edit', compact('company'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update($account, Request $request, Company $company)
    {
        if(Auth::id() > 2){
            return response('Unauthorized.', 401);
        }
        $validation_params = [
            //'company_id' => 'unique:companies',
            'company_name_ar' => 'required',
            'company_db_name' => [
                'required',
                //'regex:/^[a-z_]+$/i'
            ],
            'company_db_user_email' => [
                //'unique:companies',
                'regex:/^.+@.+$/i'
            ],
        ];
        $validation_msgs = [
            // 'company_id.unique'      => 'كود شركة الحراسة يجب ألا يتكرر.',
            'company_name_ar.required'      => 'اسم الشركة بالعربي ضروري.',
            'company_db_name.required' => 'اسم القاعدة ضروري.',
            'company_db_name.regex' => 'اسم القاعدة غير صحيح.',

            'company_db_user_email.regex'      => 'إيميل المشرف غير صحيح.',
            // 'company_db_user_email.unique'      => 'إيميل المشرف موجود مسبقا.',

        ];

        if(Auth::id() == 1){
            $validation_params['company_db_user_password'] = [
                'required',
                'min:6',
                //'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/'
            ];

            $validation_msgs['company_db_user_password.required'] = 'كلمة المرور للمشرف ضروري.';
            $validation_msgs['company_db_user_password.min'] = 'كلمة المرور للمشرف يجب أن يحتوي على الأقل 6 حروف.';
            //$validation_msgs['company_db_user_password.regex'] = 'كلمة المرور للمشرف غير صحيح.';
        }

            $request->validate($validation_params, $validation_msgs);


        Rule::unique('companies')->ignore($company->id, 'company_id');
        Rule::unique('companies')->ignore($company->company_db_user_email, 'company_db_user_email');


        $params = [
            'company_id' => $request->company_id,
            'company_name_ar' => $request->company_name_ar,
            'company_name_en' => $request->company_name_en,
            'address_ar' => $request->address_ar,
            'address_en' => $request->address_en,
            'vat_number' => $request->vat_number,
            'license_number' => $request->license_number,
            'commercial_record_date' => $request->commercial_record_date,
            'license_date' => $request->license_date,
            'notes' => $request->notes,
            'company_db_name' => $request->company_db_name,
            'company_db_user_first_name' => $request->company_db_user_first_name,
            'company_db_user_last_name' => $request->company_db_user_last_name,
            'company_db_user_email' => $request->company_db_user_email,

            'company_db_user_password' => $request->company_db_user_password,

            'factor' => $request->factor[0],
            'expiration_dt' => $request->expiration_dt,

            'sign_accountant_label' => $request->sign_accountant_label,
            'sign_operational_director_label' => $request->sign_operational_director_label,
            'sign_financial_director_label' => $request->sign_financial_director_label,
            'sign_price_offer_label' => $request->sign_price_offer_label,
        ];

       $this->company_id = $company->company_id;

        $logo = null;
        $cachet = null;

        $sign_accountant = null;
        $sign_operational_director = null;
        $sign_financial_director = null;
        $sign_price_offer = null;

        $request_data = $request->all();
        $request_data['factor'] = $request->factor[0];

        if ($request->hasFile('logo')) {
            $params['logo'] = $request->logo->store('img/companies/'.$this->company_id.'/logos', 'public');
        }
        if ($request->hasFile('cachet')) {
            $params['cachet'] = $request->cachet->store('img/companies/'.$this->company_id.'/cachets', 'public');
        }
        if ($request->hasFile('sign_accountant')) {
            $params['sign_accountant'] = $request->sign_accountant->store('img/companies/'.$this->company_id.'/signs', 'public');
        }
        if ($request->hasFile('sign_operational_director')) {
            $params['sign_operational_director'] = $request->sign_operational_director->store('img/companies/'.$this->company_id.'/signs', 'public');
        }
        if ($request->hasFile('sign_financial_director')) {
            $params['sign_financial_director'] = $request->sign_financial_director->store('img/companies/'.$this->company_id.'/signs', 'public');
        }
        if ($request->hasFile('sign_price_offer')) {
            $params['sign_price_offer'] = $request->sign_price_offer->store('img/companies/'.$this->company_id.'/signs', 'public');
        }


        $c = DB::table('companies')
            ->where('id', $company->id)
            ->update($params);

        if($c) {
            Session::put('expiration_dt', $request->expiration_dt);
        }
        session()->flash('success', __('site.updated_successfully'));
        if(Auth::id() == 1) {
            return redirect()->route('dashboard.companies.index');
        } else {
            return redirect()->route('dashboard.companies.show', 1);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy($account, Company $company)
    {
        if(Auth::id() > 1){
            return response('Unauthorized.', 401);
        }
        $rs = $company->delete();
        if($rs){
            session()->flash('success', __('site.deleted_successfully'));
            return redirect()->route('dashboard.companies.index');
        }

    }

    // banks
    public function banks($account, $id)
    {
        if(Auth::id() > 2){
            return response('Unauthorized.', 401);
        }
        $company = Company::find($id);
        $banks = DB::table('banks')
            ->where('company_id', '=', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('dashboard.companies.banks', compact('company', 'banks'));

    }
    public function add_bank($account, $id)
    {
        if(Auth::id() > 2){
            return response('Unauthorized.', 401);
        }
        $company = Company::find($id);
        return view('dashboard.companies.add_bank', compact('company'));

    }
    public function edit_bank($account, $id, $bank_id)
    {
        if(Auth::id() > 2){
            return response('Unauthorized.', 401);
        }
        $company = Company::find($id);
        $bank = Bank::find($bank_id);
        return view('dashboard.companies.add_bank', compact('company', 'bank'));

    }
    public function store_bank($account, Request $request)
    {
        if(Auth::id() > 2){
            return response('Unauthorized.', 401);
        }
        $company_id = $request->company_id;

        $request->validate([
            'company_id' => 'required',
        ]);
        $request_data = $request->all();

        $bank = Bank::create($request_data);

        if($bank) {

            $has_default_bank = Bank::where([
                ['company_id', '=', $company_id],
                ['is_default', '=', 1]
            ])->first();

            if(!$has_default_bank) {
                DB::table('banks')
                    ->where('company_id', $company_id)
                    ->update([
                        'is_default' => 0
                    ]);

                DB::table('banks')
                    ->where('id', $bank->id)
                    ->update([
                        'is_default' => 1
                    ]);
            }


            session()->flash('success', __('site.added_successfully'));
            //url()->to('dashboard/companies/'.$company_id.'/banks');
            return redirect()->route('dashboard.companies.show', $company_id);


        }
    }
    public function update_bank($account, $bank_id)
    {
        if(Auth::id() > 2){
            return response('Unauthorized.', 401);
        }
        $request = request()->all();
        $company_id = request()->company_id;

        DB::table('banks')
            ->where('id', $bank_id)
            ->update([
                'company_id' => $company_id,
                'bank_name' => request()->bank_name,
                'company_name_at_bank' => request()->company_name_at_bank,
                'company_name_at_bank_en' => request()->company_name_at_bank_en,
                'iban' => request()->iban,
                'vat_number' => request()->vat_number,
            ]);

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.companies.show', $company_id);

    }

    // papers
    public function papers($account, $id)
    {
        if(Auth::id() > 2){
            return response('Unauthorized.', 401);
        }
        $company = Company::find($id);
        $papers = DB::table('papers')
            ->where('company_id', '=', $company->company_id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('dashboard.companies.papers', compact('company', 'papers'));

    }
    public function add_paper($account, $id)
    {
        if(Auth::id() > 2){
            return response('Unauthorized.', 401);
        }
        $company = Company::find($id);
        return view('dashboard.companies.add_paper', compact('company'));

    }
    public function edit_paper($account, $id, $paper_id)
    {
        if(Auth::id() > 2){
            return response('Unauthorized.', 401);
        }
        $company = Company::find($id);
        $paper = Paper::find($paper_id);
        return view('dashboard.companies.add_paper', compact('company', 'paper'));

    }
    public function store_paper($account, Request $request)
    {
        if(Auth::id() > 2){
            return response('Unauthorized.', 401);
        }
        $company_id = Company::find(1)->company_id;

        $request->validate([
            'paper_name' => 'required',
            'header_img' => 'required',
            'footer_img' => 'required',
        ],
            [
                'paper_name.required' => 'تسمية الورق الرسمي ضرورية.',
                'header_img.required' => 'صورة رأس الصفحة ضرورية.',
                'footer_img.required' => 'صورة أسفل الصفحة ضرورية.',
            ]
        );
        $request_data = [
            'company_id' => $request->company_id,
            'paper_name' => $request->paper_name,
        ];

        if ($request->hasFile('header_img')) {
            $request_data['header_img'] = $request->header_img->store('img/companies/'.$company_id.'/papers', 'public');

        } if ($request->hasFile('footer_img')) {
            $request_data['footer_img'] = $request->footer_img->store('img/companies/'.$company_id.'/papers', 'public');
        }

        $paper = Paper::create($request_data);

        if($paper) {

            $has_default_paper = Paper::where([
                ['company_id', '=', $company_id],
                ['is_default', '=', 1]
            ])->first();

            if(!$has_default_paper) {
                DB::table('papers')
                    ->where('company_id', $company_id)
                    ->update([
                        'is_default' => 0
                    ]);

                DB::table('papers')
                    ->where('id', $paper->id)
                    ->update([
                        'is_default' => 1
                    ]);
            }

            session()->flash('success', __('site.added_successfully'));
            return redirect()->route('dashboard.companies.show', 1);

        }
    }
    public function update_paper($account, $paper_id)
    {
        if(Auth::id() > 2){
            return response('Unauthorized.', 401);
        }
       /* request()->validate([
            'company_id' => 'required',
            'paper_name' => 'required',
            'footer_img' => 'required',
        ],
            [
                'company_id.required'      => 'المرجو تحديد الشركة.',
                'paper_name.required' => 'اسم الورق الرسمي ضروري.',
                'header_img.required' => 'صورة الورق الرسمي ضرورية.',
                'footer_img.required' => 'صورة الورق الرسمي ضرورية.',
            ]
        );*/

        $company_id = Company::find(1)->company_id;

        $params = [
            'paper_name' => request()->paper_name,
        ];

        if (request()->hasFile('header_img')) {
            $params['header_img'] = request()->header_img->store('img/companies/'.$company_id.'/papers', 'public');
        } if (request()->hasFile('footer_img')) {
        $params['footer_img'] = request()->footer_img->store('img/companies/'.$company_id.'/papers', 'public');
    }
        DB::table('papers')
            ->where('id', $paper_id)
            ->update($params);

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.companies.show', 1);

    }



}
