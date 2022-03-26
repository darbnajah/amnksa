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


class CostController extends Controller
{

    public function index()
    {
        if (!Auth::user()->can('cost_read')) {
            return response('Unauthorized.', 401);
        }
        $company = Company::find(1);
        $months = \App\Helper\Helper::months();
        if($company->factor) {
            return view('dashboard.cost.factor', compact('company', 'months'));
        }
        else {
            return view('dashboard.cost.owner', compact('company', 'months'));
        }
    }

    public function customer_cost($account, $customer_id){
        $req = \request()->all();
        $month = \request()->month;
        $year = \request()->year;
        $all = \request()->all;
        $customer_name = \request()->customer_name;
        $company = Company::find(1);

        $suppliers_payments_total = DB::table('suppliers_payments')
            ->join('contracts', 'contracts.id', '=', 'suppliers_payments.contract_id')
            ->join('customers', 'customers.id', '=', 'contracts.customer_id')
            ->join('suppliers', 'suppliers.id', '=', 'contracts.supplier_id')
            ->selectRaw('suppliers_payments.supplier_amount')
            ->where('customers.id', $customer_id)
            ->where(function($query) use ($month, $year, $all)  {
                if($all == '0' && isset($month)) {
                    $query->where('suppliers_payments.month_id', $month);
                }
                if($all == '0' && isset($year)) {

                    $query->whereYear('suppliers_payments.dt', $year);
                }
            })//->toSql();
            ->sum('supplier_amount');

        //var_dump($customer_name);

        $paies_transfered = DB::table('paies')
            ->join('paie_salaries', 'paies.id', '=', 'paie_salaries.paie_id')
            ->join('employees', 'employees.id', '=', 'paie_salaries.employee_id')
            ->join('jobs', 'jobs.id', '=', 'employees.job_id')
            ->join('users', 'users.id', '=', 'paies.created_by')
            ->select(DB::raw("COALESCE(SUM(paie_salaries.salary_net + paie_salaries.advance),0) as global"))
            //->select(DB::raw("paie_salaries.salary_net + paie_salaries.extra as global"))

            ->where('trans_status', '=', 1)
            ->where('paie_salaries.work_zone', $customer_name)
            ->where(function($query) use ($month, $year, $all)  {
                if($all == '0' && isset($month)) {
                    if(strlen($month) == 1){
                        $month = '0'.$month;
                    }
                    $query->where('paies.month_id', $month);
                }
                if($all == '0' && isset($year)) {
                    $query->whereYear('paies.paie_dt', $year);
                }
            })//->toSql();
            ->get();
        if($paies_transfered){
            $paies_transfered_total = $paies_transfered->first()->global;
        } else {
            $paies_transfered_total = 0;
        }

        $customers_expenses_total = DB::table('customer_expenses')
            ->join('customers', 'customers.id', '=', 'customer_expenses.customer_id')
            ->join('users', 'users.id', '=', 'customer_expenses.created_by')
            ->selectRaw('customer_expenses.credit')
            ->where('customer_expenses.doc_type', '=', 'customer_expense')
            ->where('customers.id', $customer_id)
            ->where(function($query) use ($month, $year, $all)  {
                if($all == '0' && isset($month)) {
                    $query->where('customer_expenses.month_id', $month);
                }
                if($all == '0' && isset($year)) {
                    $query->whereYear('customer_expenses.dt', $year);
                }
            })//->toSql();
            ->sum('credit');

/*
        $sellers_payments_total = DB::table('sellers_payments')
            ->join('contracts', 'contracts.id', '=', 'sellers_payments.contract_id')
            ->join('customers', 'customers.id', '=', 'contracts.customer_id')
            ->join('sellers', 'sellers.id', '=', 'contracts.seller_id')
            ->join('users', 'users.id', '=', 'sellers_payments.created_by')

            ->selectRaw('sellers_payments.amount_net')
            ->where('sellers_payments.trans_status', '=', 1)
            ->where('customers.id', $customer_id)
            ->where(function($query) use ($month, $year, $all)  {
                if($all == '0' && isset($month)) {
                    $query->where('sellers_payments.month_id', $month);
                }
                if($all == '0' && isset($year)) {
                    $query->whereYear('sellers_payments.trans_dt', $year);
                }
            })//->toSql();
            ->sum('amount_net');
        */
        if($company->factor) {
            $sellers_payments_total = DB::table('sellers_payments')
                ->join('contracts', 'contracts.id', '=', 'sellers_payments.contract_id')
                ->join('customers', 'customers.id', '=', 'contracts.customer_id')
                ->join('sellers', 'sellers.id', '=', 'contracts.seller_id')
                ->join('users', 'users.id', '=', 'sellers_payments.created_by')
                ->selectRaw('sellers_payments.amount_net')
                //->where('sellers_payments.trans_status', '=', 1)
                ->where('customers.id', $customer_id)
                ->where(function ($query) use ($month, $year, $all, $company) {
                    if ($all == '0' && isset($month)) {
                        $query->where('sellers_payments.month_id', $month);
                    }
                    if ($all == '0' && isset($year)) {
                        $query->whereYear('sellers_payments.trans_dt', $year);
                    }

                })//->get()//->toSql();
                ->sum('amount_net');
        }
        else {
            $sellers_payments_total = 0;
            $sellers_payments = DB::table('sellers_payments')
                ->join('contracts', 'contracts.id', '=', 'sellers_payments.contract_id')
                ->join('customers', 'customers.id', '=', 'contracts.customer_id')
                ->join('sellers', 'sellers.id', '=', 'contracts.seller_id')
                ->join('users', 'users.id', '=', 'sellers_payments.created_by')
                ->selectRaw('COALESCE(SUM(sellers_payments.amount_net + sellers_payments.advance),0) as total_amount')
                //->where('sellers_payments.trans_status', '=', 1)
                ->where('customers.id', $customer_id)
                ->get();
            if($sellers_payments){
                $sellers_payments_total = $sellers_payments[0]->total_amount;
            }
        }
        //dd($sellers_payments_total);

        $customers_payments_total = DB::table('payments')
            ->join('customers', 'customers.id', '=', 'payments.customer_id')
            ->selectRaw('payments.credit')
            ->where('payments.doc_type', '=', 'payment')
            ->where('customers.id', $customer_id)
            ->where(function($query) use ($month, $year, $all)  {
                if($all == '0' && isset($month)) {
                    if(strlen($month) == 1){
                        $month = '0'.$month;
                    }
                    $query->where('payments.month_id', $month);
                }
                if($all == '0' && isset($year)) {
                    $query->whereYear('payments.dt', $year);
                }
            })//->toSql();
            ->sum('credit');


        $payed_taxes_total = DB::table('invoices')
            ->join('customers', 'customers.id', '=', 'invoices.customer_id')
            ->selectRaw('invoices.total_vat')
            ->where('vat_status', '=', 1)
            ->where('invoices.vat', '>', 0)

            ->where('customers.id', $customer_id)
            ->where(function($query) use ($month, $year, $all)  {
                if($all == '0' && isset($month)) {
                    if(strlen($month) == 1){
                        $month = '0'.$month;
                    }
                    $query->where('invoices.month_id', $month);
                }
                if($all == '0' && isset($year)) {
                    $year = substr($year, -2);
                    $query->where('invoices.year_id', $year);
                }
            })//->toSql();
            ->sum('total_vat');


        $company = Company::find(1);
        if($company->factor) {
            $net_total = $suppliers_payments_total - $paies_transfered_total - $customers_expenses_total;
        }
        else {
            $net_total = $customers_payments_total - $payed_taxes_total - $sellers_payments_total - $paies_transfered_total - $customers_expenses_total;
        }

        $obj = [
            'suppliers_payments_total' => $suppliers_payments_total,
            'paies_transfered_total' => $paies_transfered_total,
            'customers_expenses_total' => $customers_expenses_total,
            'sellers_payments_total' => $sellers_payments_total,
            'customers_payments_total' => $customers_payments_total,
            'payed_taxes_total' => $payed_taxes_total,
            'net_total' => $net_total,

        ];

        return json_encode($obj);
    }

}
