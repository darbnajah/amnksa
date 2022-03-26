<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Invoices_taxesController extends Controller
{
    public function index($account, $filter = null, $paper_id = null){
        if(!Auth::user()->can('invoices_taxes_read')){
            return response('Unauthorized.', 401);
        }
        $sys_dt = date('Y-m-d');
        $after_month = \App\Helper\Helper::addDays($sys_dt, 30);

        $filter = explode('_', $filter);

        $preview = isset($filter[0])? $filter[0] : null;
        $dt_from = isset($filter[1])? $filter[1] : null;
        $dt_to = isset($filter[2])? $filter[2] : null;
        $period = isset($filter[3])? $filter[3] : null;

        $preview_url = url()->current();


        $whr = [
            ['vat_status', '=', 0],
            ['invoices.vat', '>', 0]
        ];

        if($period) {
            if($period == 'normal') {
                $whr[] = ['invoices.vat_due_dt', '>', $after_month];
            } elseif($period == 'month') {
                $whr[] = ['invoices.vat_due_dt', '<', $after_month];
                $whr[] = ['invoices.vat_due_dt', '>', $sys_dt];
            } elseif ($period == 'echeance'){
                $whr[] = ['invoices.vat_due_dt', '<=', $sys_dt];
            }
        }

        if($dt_from && $dt_to) {
            $invoices = DB::table('invoices')
                ->join('customers', 'customers.id', '=', 'invoices.customer_id')
                ->where($whr)
                ->whereBetween('invoices.vat_due_dt', [$dt_from, $dt_to])
                ->selectRaw('invoices.*, customers.name_ar')
                ->orderBy('invoices.dt', 'DESC')
                ->get();
        } else {
            $invoices = DB::table('invoices')
                ->join('customers', 'customers.id', '=', 'invoices.customer_id')
                ->where($whr)
                ->selectRaw('invoices.*, customers.name_ar')
                ->orderBy('invoices.dt', 'DESC')
                ->get();
        }

        $paper = ($paper_id)?
            Paper::find($paper_id) :
            Paper::where('is_default', '=', 1)->first();
        $papers = Paper::all();

        $preview = explode('=', $preview);
        $view = (isset($preview[1]) && $preview[1] == 1)? 'preview_list' : 'index';

        return view('dashboard.invoices_taxes.'.$view, compact(
            'invoices',
            'paper',
            'papers',
            'dt_from',
            'dt_to',
            'preview_url',
            'sys_dt',
            'after_month',
            'period'
        ));
    }

    public function pay_invoices($account, Request $request){
        if(!Auth::user()->can('invoices_taxes_create')){
            return response('Unauthorized.', 401);
        }

        $updated_count = 0;
        $params = [
            'vat_status' => 1,
            'vat_pay_ref' => $request->pay_ref,
            'vat_pay_dt' => $request->pay_dt,
        ];
        if(isset($request->invoices)) {
            $invoices = explode(';', trim($request->invoices));

            if(count($invoices) > 0) {
                for($i = 0; $i < count($invoices) -1; $i++){
                    $inv = Invoice::where('id', $invoices[$i])->update($params);
                    if($inv) {
                        $updated_count++;
                    }
                }
            }
        }

        if($updated_count) {
            session()->flash('success', __('تم السداد الضريبي بالنسبة للفواتير المختارة بنجاح!'));
            return 1;
        }
        return 0;
    }

}
