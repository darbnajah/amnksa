<?php

namespace App\Http\Controllers\Dashboard;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Paper;
use App\Models\Seller_deduction_advance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Company_balanceController extends Controller
{
    public function index($account = null, $filter = null, $paper_id = null){
        if(!Auth::user()->can('company_balance_read')){
            return response('Unauthorized.', 401);
        }
        $company = Company::find(1);
        $filter = explode('_', $filter);

        $preview = isset($filter[0])? $filter[0] : null;
        $dt_from = isset($filter[1])? $filter[1] : null;
        $dt_to = isset($filter[2])? $filter[2] : null;

        $preview_url = url()->current();
        $payed_invoices_total = DB::table('payments')
            ->join('customers', 'customers.id', '=', 'payments.customer_id')
            ->selectRaw('payments.credit')
            ->where('payments.doc_type', '=', 'payment')
            ->where(function($query) use ($dt_from, $dt_to)  {
                if(isset($dt_from) && isset($dt_to)) {
                    $query->whereBetween('payments.dt', [$dt_from, $dt_to]);
                }
            })
            ->orderBy('payments.id', 'DESC')
            ->sum('credit');

        $invoices_total = DB::table('invoices')
            ->join('customers', 'customers.id', '=', 'invoices.customer_id')
            ->selectRaw('invoices.ttc')
            ->where(function($query) use ($dt_from, $dt_to)  {
                if(isset($dt_from) && isset($dt_to)) {
                    $query->whereBetween('invoices.dt', [$dt_from, $dt_to]);
                }
            })
            ->sum('ttc');

        $not_payed_invoices_total = $invoices_total - $payed_invoices_total;

        $incomes_total = DB::table('incomes')
            ->where(function($query) use ($dt_from, $dt_to)  {
                if(isset($dt_from) && isset($dt_to)) {
                    $query->whereBetween('incomes.dt', [$dt_from, $dt_to]);
                }
            })
            ->sum('total');
        $purchases_total = DB::table('purchases')
            ->where(function($query) use ($dt_from, $dt_to)  {
                if(isset($dt_from) && isset($dt_to)) {
                    $query->whereBetween('purchases.dt', [$dt_from, $dt_to]);
                }
            })
            ->sum('total');
        $expenses_total = DB::table('expenses')
            ->where(function($query) use ($dt_from, $dt_to)  {
                if(isset($dt_from) && isset($dt_to)) {
                    $query->whereBetween('expenses.dt', [$dt_from, $dt_to]);
                }
            })
            ->sum('total');

        $customer_expenses_total = DB::table('customer_expenses')
            ->join('customers', 'customers.id', '=', 'customer_expenses.customer_id')
            ->join('users', 'users.id', '=', 'customer_expenses.created_by')
            ->selectRaw('customer_expenses.credit')
            ->where('customer_expenses.doc_type', '=', 'customer_expense')
            ->where(function($query) use ($dt_from, $dt_to)  {
                if(isset($dt_from) && isset($dt_to)) {
                    $query->whereBetween('customer_expenses.dt', [$dt_from, $dt_to]);
                }
            })
            ->sum('credit');

        $transfered_paies = DB::table('paies')
            ->join('paie_salaries', 'paies.id', '=', 'paie_salaries.paie_id')
            //->selectRaw('paie_salaries.salary_net')
            ->selectRaw(DB::raw("COALESCE(SUM(paie_salaries.salary_net + paie_salaries.advance),0) as global"))
            ->where('paie_salaries.trans_status', '=', 1)
            ->where(function($query) use ($dt_from, $dt_to)  {
                if(isset($dt_from) && isset($dt_to)) {
                    $query->whereBetween('paies.paie_dt', [$dt_from, $dt_to]);
                }
            })
            //->sum('salary_net');
            ->get();
        if($transfered_paies){
            $transfered_paies_total = $transfered_paies->first()->global;
        } else {
            $transfered_paies_total = 0;
        }


        $accepted_not_payed_paies_total = DB::table('paies')
            ->join('paie_salaries', 'paies.id', '=', 'paie_salaries.paie_id')
            ->selectRaw('paie_salaries.salary_net')
            ->where('paie_salaries.status', '=', 1)
            ->where('paie_salaries.trans_status', '=', 0)
            ->where(function($query) use ($dt_from, $dt_to)  {
                if(isset($dt_from) && isset($dt_to)) {
                    $query->whereBetween('paies.paie_dt', [$dt_from, $dt_to]);
                }
            })
            ->sum('salary_net');

       /* $transfered_sellers_payments_total = DB::table('sellers_payments')
            ->join('contracts', 'contracts.id', '=', 'sellers_payments.contract_id')
            ->join('customers', 'customers.id', '=', 'contracts.customer_id')
            ->join('sellers', 'sellers.id', '=', 'contracts.seller_id')
            ->selectRaw('sellers_payments.amount_net')
            ->where('sellers_payments.trans_status', '=', 1)
            ->where(function($query) use ($dt_from, $dt_to)  {
                if(isset($dt_from) && isset($dt_to)) {
                    $query->whereBetween('sellers_payments.dt', [$dt_from, $dt_to]);
                }
            })
            ->sum('amount_net');
        */


        $transfered_sellers_payments_total = 0;
        $transfered_sellers_payments = DB::table('sellers_payments')
            ->join('contracts', 'contracts.id', '=', 'sellers_payments.contract_id')
            ->join('customers', 'customers.id', '=', 'contracts.customer_id')
            ->join('sellers', 'sellers.id', '=', 'contracts.seller_id')
            ->join('users', 'users.id', '=', 'sellers_payments.created_by')
            ->selectRaw('COALESCE(SUM(sellers_payments.amount_net + sellers_payments.advance),0) as total_amount')
            //->where('sellers_payments.trans_status', '=', 1)
            ->where(function($query) use ($dt_from, $dt_to)  {
                if(isset($dt_from) && isset($dt_to)) {
                    $query->whereBetween('sellers_payments.dt', [$dt_from, $dt_to]);
                }
            })
            ->get();
        if($transfered_sellers_payments){
            $transfered_sellers_payments_total = $transfered_sellers_payments[0]->total_amount;
        }

        $accepted_not_payed_sellers_payments_total = DB::table('sellers_payments')
            ->join('contracts', 'contracts.id', '=', 'sellers_payments.contract_id')
            ->join('customers', 'customers.id', '=', 'contracts.customer_id')
            ->join('sellers', 'sellers.id', '=', 'contracts.seller_id')
            ->selectRaw('sellers_payments.amount_net')
            ->where('sellers_payments.status', '=', 1)
            ->where('sellers_payments.trans_status', '=', 0)
            ->where(function($query) use ($dt_from, $dt_to)  {
                if(isset($dt_from) && isset($dt_to)) {
                    $query->whereBetween('sellers_payments.dt', [$dt_from, $dt_to]);
                }
            })
            ->sum('amount_net');

        $collections_total = DB::table('collections')
            ->join('suppliers', 'suppliers.id', '=', 'collections.supplier_id')
            ->selectRaw('collections.total')
            ->where(function($query) use ($dt_from, $dt_to)  {
                if(isset($dt_from) && isset($dt_to)) {
                    $query->whereBetween('collections.dt', [$dt_from, $dt_to]);
                }
            })
            ->sum('total');

        $suppliers_payments_total = DB::table('suppliers_payments')
            ->join('contracts', 'contracts.id', '=', 'suppliers_payments.contract_id')
            ->join('customers', 'customers.id', '=', 'contracts.customer_id')
            ->join('suppliers', 'suppliers.id', '=', 'contracts.supplier_id')
            ->selectRaw('suppliers_payments.supplier_amount')
            ->where(function($query) use ($dt_from, $dt_to)  {
                if(isset($dt_from) && isset($dt_to)) {
                    $query->whereBetween('suppliers_payments.dt', [$dt_from, $dt_to]);
                }
            })
            ->sum('supplier_amount');

        $sellers_deductions_total = DB::table('seller_deduction_advances')
            ->join('sellers', 'sellers.id', '=', 'seller_deduction_advances.seller_id')
            ->selectRaw('COALESCE(SUM(debit) - SUM(credit),0) as rest')
            ->where('type', '=', 'deduction')
            ->where(function($query) use ($dt_from, $dt_to)  {
                if(isset($dt_from) && isset($dt_to)) {
                    $query->whereBetween('seller_deduction_advances.dt', [$dt_from, $dt_to]);
                }
            })
            ->first()->rest;

        $sellers_advances_total = DB::table('seller_deduction_advances')
            ->join('sellers', 'sellers.id', '=', 'seller_deduction_advances.seller_id')
            ->selectRaw('COALESCE(SUM(debit) - SUM(credit),0) as rest')
            ->where('type', '=', 'advance')
            ->where(function($query) use ($dt_from, $dt_to)  {
                if(isset($dt_from) && isset($dt_to)) {
                    $query->whereBetween('seller_deduction_advances.dt', [$dt_from, $dt_to]);
                }
            })
            ->first()->rest;

        $employees_advances_total = DB::table('deduction_advances')
            ->join('employees', 'employees.id', '=', 'deduction_advances.employee_id')
            ->selectRaw('COALESCE(SUM(debit) - SUM(credit),0) as rest')
            ->where('type', '=', 'advance')
            ->where(function($query) use ($dt_from, $dt_to)  {
                if(isset($dt_from) && isset($dt_to)) {
                    $query->whereBetween('deduction_advances.dt', [$dt_from, $dt_to]);
                }
            })
            ->first()->rest;

        $employees_deductions_total = DB::table('deduction_advances')
            ->join('employees', 'employees.id', '=', 'deduction_advances.employee_id')
            ->selectRaw('COALESCE(SUM(debit) - SUM(credit),0) as rest')
            ->where('type', '=', 'deduction')
            ->where(function($query) use ($dt_from, $dt_to)  {
                if(isset($dt_from) && isset($dt_to)) {
                    $query->whereBetween('deduction_advances.dt', [$dt_from, $dt_to]);
                }
            })
            ->first()->rest;


        $payed_taxes_total = DB::table('invoices')
            ->join('customers', 'customers.id', '=', 'invoices.customer_id')
            ->selectRaw('SUM(invoices.total_vat) as total_taxes')
            ->where('vat_status', '=', 1)
            ->where('invoices.vat', '>', 0)
            ->where(function($query) use ($dt_from, $dt_to)  {
                if(isset($dt_from) && isset($dt_to)) {
                    $query->whereBetween('invoices.dt', [$dt_from, $dt_to]);
                }
            })
            ->first()->total_taxes;

        $not_payed_taxes_total = DB::table('invoices')
            ->join('customers', 'customers.id', '=', 'invoices.customer_id')
            ->selectRaw('SUM(invoices.total_vat) as total_taxes')
            ->where('vat_status', '=', 0)
            ->where('invoices.vat', '>', 0)
            ->where(function($query) use ($dt_from, $dt_to)  {
                if(isset($dt_from) && isset($dt_to)) {
                    $query->whereBetween('invoices.dt', [$dt_from, $dt_to]);
                }
            })
            ->first()->total_taxes;

        $suppliers_rest_total = $suppliers_payments_total - $collections_total;

        $owner_balance_net = $payed_invoices_total + $incomes_total - $payed_taxes_total - $transfered_paies_total - $transfered_sellers_payments_total - $employees_advances_total - $sellers_advances_total - $purchases_total - $expenses_total - $customer_expenses_total;

        $factor_balance_net = $collections_total + $incomes_total - $transfered_paies_total  - $employees_advances_total - $purchases_total - $expenses_total - $customer_expenses_total;

        $obj = [
            'payed_invoices_total' => Helper::nFormat($payed_invoices_total),
            'not_payed_invoices_total' => Helper::nFormat($not_payed_invoices_total),
            'incomes_total' => Helper::nFormat($incomes_total),
            'purchases_total' => Helper::nFormat($purchases_total),
            'expenses_total' => Helper::nFormat($expenses_total),
            'customer_expenses_total' => Helper::nFormat($customer_expenses_total),
            'purchases_and_expenses_total' => Helper::nFormat($purchases_total + $expenses_total),
            'transfered_paies_total' => Helper::nFormat($transfered_paies_total),
            'accepted_not_payed_paies_total' => Helper::nFormat($accepted_not_payed_paies_total),
            'transfered_sellers_payments_total' => Helper::nFormat($transfered_sellers_payments_total),
            'accepted_not_payed_sellers_payments_total' => Helper::nFormat($accepted_not_payed_sellers_payments_total),
            'collections_total' => Helper::nFormat($collections_total),
            'suppliers_payments_total' => Helper::nFormat($suppliers_payments_total),
            'suppliers_rest_total' => Helper::nFormat($suppliers_rest_total),
            'sellers_advances_total' => Helper::nFormat($sellers_advances_total),
            'sellers_deductions_total' => Helper::nFormat($sellers_deductions_total),
            'employees_advances_total' => Helper::nFormat($employees_advances_total),
            'employees_deductions_total' => Helper::nFormat($employees_deductions_total),
            'payed_taxes_total' => Helper::nFormat($payed_taxes_total),
            'not_payed_taxes_total' => Helper::nFormat($not_payed_taxes_total),
            'owner_balance_net' => Helper::nFormat($owner_balance_net),
            'factor_balance_net' => Helper::nFormat($factor_balance_net),
        ];
        $obj = (object)$obj;

        //var_dump($obj);
        $paper = ($paper_id)?
            Paper::find($paper_id) :
            Paper::where('is_default', '=', 1)->first();
        $papers = Paper::all();

        $preview = explode('=', $preview);
        $view = (isset($preview[1]) && $preview[1] == 1)? 'preview_list' : 'index';

        if($company->factor)
            return view('dashboard.factor_balance.'.$view, compact(
            'obj',
            'papers',
            'paper',
            'dt_from',
            'dt_to',
            'preview_url'
        ));
        else
            return view('dashboard.owner_balance.'.$view, compact(
                'obj',
                'papers',
                'paper',
                'dt_from',
                'dt_to',
                'preview_url'
            ));

    }
}
