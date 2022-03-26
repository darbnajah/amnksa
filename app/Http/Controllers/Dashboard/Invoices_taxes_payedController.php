<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Invoices_taxes_payedController extends Controller
{
    public function index($account, $filter = null, $paper_id = null){
        if(!Auth::user()->can('invoices_taxes_payed_read')){
            return response('Unauthorized.', 401);
        }
        $filter = explode('_', $filter);

        $preview = isset($filter[0])? $filter[0] : null;
        $dt_from = isset($filter[1])? $filter[1] : null;
        $dt_to = isset($filter[2])? $filter[2] : null;

        $preview_url = url()->current();

        if($dt_from && $dt_to) {
            $invoices = DB::table('invoices')
                ->join('customers', 'customers.id', '=', 'invoices.customer_id')
                ->where('vat_status', '=', 1)
                ->where('invoices.vat', '>', 0)
                ->whereBetween('invoices.vat_due_dt', [$dt_from, $dt_to])
                ->selectRaw('invoices.*, customers.name_ar')
                ->orderBy('invoices.vat_pay_dt', 'DESC')
                ->get();
        } else {
            $invoices = DB::table('invoices')
                ->join('customers', 'customers.id', '=', 'invoices.customer_id')
                ->where('vat_status', '=', 1)
                ->where('invoices.vat', '>', 0)
                ->selectRaw('invoices.*, customers.name_ar')
                ->orderBy('invoices.vat_pay_dt', 'DESC')
                ->get();
        }
        $paper = ($paper_id)?
            Paper::find($paper_id) :
            Paper::where('is_default', '=', 1)->first();
        $papers = Paper::all();

        $preview = explode('=', $preview);
        $view = (isset($preview[1]) && $preview[1] == 1)? 'preview_list' : 'index';

        return view('dashboard.invoices_taxes_payed.'.$view, compact(
            'invoices',
            'paper',
                'papers',
                'dt_from',
                'dt_to',
                'preview_url'
        ));
    }

    public function cancel_pay_invoices($account, Request $request){
        if(!Auth::user()->can('invoices_taxes_payed_create')){
            return response('Unauthorized.', 401);
        }
        $updated_count = 0;
        $params = [
            'vat_status' => 0,
            'vat_pay_ref' => NULL,
            'vat_pay_dt' => NULL,
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
            session()->flash('success', __('تم إلغاء السداد الضريبي بالنسبة للفواتير المختارة بنجاح!'));
            return 1;
        }
        return 0;
    }

}
