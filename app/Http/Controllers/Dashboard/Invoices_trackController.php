<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Invoices_trackController extends Controller
{
    public function index(){
        $invoices = DB::table('invoices')
            ->join('customers', 'customers.id', '=', 'invoices.customer_id')
            ->where('status', '=', 1)
            ->selectRaw('invoices.*, customers.name_ar')
            ->get();

        $papers = Paper::all();
        return view('dashboard.invoices_track.index', compact('invoices', 'papers'));
    }

    public function cancel_pay($id){
        $invoice = DB::table('invoices')
            ->where('id', $id)
            ->update([
                'status' => 0,
                'pay_ref' => NULL,
                'pay_dt' => NULL,
            ]);
        if($invoice) {
            session()->flash('success', __('تم إلغاء سداد الفاتورة بنجاح!'));
            return 1;
        }
        return 0;
    }

}
