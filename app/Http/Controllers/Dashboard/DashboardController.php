<?php

namespace App\Http\Controllers\Dashboard;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Contract;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;


class DashboardController extends Controller
{

    public function index(){
        $company = Company::find(1);
        $invoices_count = DB::table('invoices')
            ->join('customers', 'customers.id', '=', 'invoices.customer_id')
            ->selectRaw('COUNT(invoices.id) as invoices_count')
            ->where('month_id', '=', date('m'))
            ->count();

        return view('dashboard.index', compact('company'));
    }
    public function statistics(){
        if(!Auth::user()->can('home_read')){
            return response('Unauthorized.', 401);
        }
        $company = Company::find(1);
        $invoices_count = DB::table('invoices')
            ->join('customers', 'customers.id', '=', 'invoices.customer_id')
            ->selectRaw('COUNT(invoices.id) as invoices_count')
            ->where('month_id', '=', date('m'))
            ->where('year_id', '=', date('y'))
            ->count();

        $contracts = DB::table('contracts')
            ->selectRaw(DB::raw('COUNT(contracts.id) contracts_count'))
            ->where('status', '=', 1)
            ->groupBy("contracts.id")
            ->get();
        $contracts_count = count($contracts);

        return view('dashboard.statistics', compact('company', 'invoices_count', 'contracts_count'));
    }

    public function customer_statistics($account, $customer_id){
        $customer_invoices = DB::table('invoices')
            ->join('customers', 'customers.id', '=', 'invoices.customer_id')
            ->selectRaw(DB::raw('COUNT(invoices.id) as invoices_count, SUM(invoices.ttc) as invoices_total'))
            ->where('customer_id', '=', $customer_id)
            //->where('month_id', '=', date('m'))
            ->groupBy("invoices.customer_id")
            ->first();

        $customer_invoices_count = ($customer_invoices)? $customer_invoices->invoices_count : 0;
        $customer_invoices_total = ($customer_invoices)? $customer_invoices->invoices_total : 0;

        $last_invoice = DB::table('invoices')
            ->join('customers', 'customers.id', '=', 'invoices.customer_id')
            ->selectRaw(DB::raw('invoices.dt, invoices.month_id, invoices.ttc'))
            ->where('customer_id', '=', $customer_id)
            //->where('month_id', '=', date('m'))
            ->orderBy('invoices.id', 'DESC')
            ->limit(1)
            ->first();

        $payments = DB::table('payments')
            ->join('customers', 'customers.id', '=', 'payments.customer_id')
            ->selectRaw('payments.credit')
            ->where('payments.doc_type', '=', 'payment')
            ->where('customer_id', '=', $customer_id)
            //->where('month_id', '=', date('m'))

            ->orderBy('payments.id', 'DESC')
            ->get();

        $last_payment = DB::table('payments')
            ->join('customers', 'customers.id', '=', 'payments.customer_id')
            ->selectRaw('payments.dt')
            ->where('payments.doc_type', '=', 'payment')
            ->where('customer_id', '=', $customer_id)
            ->orderBy('payments.id', 'DESC')
            ->first();

        $last_payment_dt = ($last_payment)? $last_payment->dt : null;

        $payments_total = ($payments->count() > 0)? $payments->sum('credit'): 0;
        $last_payment_total = ($payments->count() > 0)? $payments->first()->credit : 0;

        $customer_rest_balance = $customer_invoices_total - $payments_total;

        $obj = [
            'customer_invoices_count' => $customer_invoices_count,
            'customer_invoices_total' => Helper::nFormat($customer_invoices_total),
            'last_invoice_amount' => ($last_invoice)? Helper::nFormat($last_invoice->ttc) : 0,
            'last_invoice_month_ar' => ($last_invoice)? Helper::monthNameAr($last_invoice->month_id) : null,
            'last_invoice_dt' => ($last_invoice)? $last_invoice->dt : null,
            'payments_total' => Helper::nFormat($payments_total),
            'last_payment_total' => Helper::nFormat($last_payment_total),
            'last_payment_dt' => $last_payment_dt,
            'customer_rest_balance' => Helper::nFormat($customer_rest_balance)
        ];

        return json_encode($obj);
    }

    public function reset()
    {
        $company = Company::find(1);
        $response = ['status' => 0, 'message' => 'يتعذر تصفير القاعدة !'];

        $excluded_tables = [
            'migrations',
            'companies',
            'papers',
            'users',
            'payment_methods',
            'jobs',
        ];

        Schema::disableForeignKeyConstraints();

        $tables = DB::select('SHOW TABLES');
        /*foreach($tables as $table)
        {
            $db = 'Tables_in_'.$company->company_db_name;
            echo $table->$db;
        }*/
        //dd($tables);
        $rs = 0;
        foreach ($tables as $table) {
            //if you don't want to truncate migrations
            //if ($name == 'migrations') {
            $db = 'Tables_in_'.$company->company_db_name;
            $table_name =  $table->$db;

            if (in_array($table_name, $excluded_tables)) {
                continue;
            }
            DB::table($table_name)->truncate();
            $rs++;
        }
        Schema::enableForeignKeyConstraints();

        User::where('id', '>', 2)->delete();
        Job::where('id', '>', 1)->delete();

        if($rs){
            $response['status'] = 1;
            session()->flash('success', __('تم تصفير القاعدة بنجاح'));
            return json_encode($response);

        }
    }

}
