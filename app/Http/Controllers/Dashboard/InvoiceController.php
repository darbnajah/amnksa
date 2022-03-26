<?php

namespace App\Http\Controllers\Dashboard;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Bulletin;
use App\Models\Company;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Invoice_bulletins;
//use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Paper;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use PDFAnony\TCPDF\Facades\AnonyPDF as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::user()->can('invoices_read')){
            return response('Unauthorized.', 401);
        }
        $whr = [];
        if(!Auth::user()->can('privacy_all')){
            $whr[] = ['invoices.created_by', '=', Auth::id()];
        }

        $invoices = DB::table('invoices')
            ->join('customers', 'customers.id', '=', 'invoices.customer_id')
            ->join('users', 'users.id', '=', 'invoices.created_by')
            ->selectRaw('
                    invoices.*,
                    customers.name_ar,
                    customers.city,
                    CONCAT(users.first_name, " ", users.last_name) AS username
            ')
            ->where($whr)
            ->orderBy('invoices.dt', 'DESC')
            ->get();
        $paper = Paper::where('is_default', '=', 1)->first();
        $paper_id = ($paper)? $paper->id : 1;

        $papers = Paper::all();
        return view('dashboard.invoices.index', compact('invoices', 'papers', 'paper_id'));
    }

    public function contracts_by_customer($account, $customer_id){
        $contracts = Contract::where('customer_id', '=',$customer_id)
            ->where('status', '=', 1)
            ->get();
        return view('dashboard.invoices.contracts_by_customer', compact(
            'contracts'
        ));
    }
    public function bulletins_by_contract($account, $contract_id){
        $nb_days = request()->nb_days;
        $bulletins = Bulletin::where('contract_id', '=',$contract_id)->get();
        return view('dashboard.invoices.bulletins_by_contract', compact(
            'bulletins',
            'nb_days'
        ));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function new_invoice($account, $customer_id, $contract_id, $dt){
        if(!Auth::user()->can('invoices_create')){
            return response('Unauthorized.', 401);
        }
        $customer_id = request()->customer_id;
        $contract_id = request()->contract_id;
        $dt = request()->dt;
        $date = \DateTime::createFromFormat("Y-m-d", $dt);
        $year_id = $date->format("Y");
        $year_id = substr($year_id, -2);
        $month_id = $date->format("m");
        //var_dump($year_id, $month_id, $customer_id, $contract_id);

        $invoice = DB::table('invoices')
            ->where('customer_id', '=', $customer_id)
            ->where([
                ['customer_id', '=', $customer_id],
                ['contract_id', '=', $contract_id],
                ['month_id', '=', $month_id],
                ['year_id', '=', $year_id]
            ])
            ->latest()->first();

        $invoice_code = ($invoice)? intval($invoice->invoice_code) + 1 : 1;
        return $invoice_code;

    }
    public function create()
    {
        if(!Auth::user()->can('invoices_create')){
            return response('Unauthorized.', 401);
        }
        $company = Company::find(1);

        $customers = Customer::all();
        return view('dashboard.invoices.edit', compact(
            'customers',
            'company'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if(!Auth::user()->can('invoices_create')){
            return response('Unauthorized.', 401);
        }
        /*$request->validate([
            'customer_code' => [
                'required',
                //'unique:contracts',
            ],
            'customer_id' => 'required'
        ], [
            'code.required' => 'رقم العقد ضروري.',
            //'code.unique'      => 'رقم العق يجب ألا يتكرر.',
            'customer_id.required' => 'العميل ضروري.',
        ]);*/

        //$request_data = $request->all();


        $params = [
            'invoice_number' => $request->invoice_number,
            'customer_id' => $request->customer_id,
            'contract_id' => $request->contract_id,
            'year_id' => $request->year_id,
            'month_id' => $request->month_id,
            'month_days' => $request->month_days,
            'nb_days' => $request->nb_days,
            'customer_code' => $request->customer_code,
            'contract_code' => $request->contract_code,
            'invoice_code' => $request->invoice_code,
            'dt' => $request->dt,
            'dt_from' => $request->dt_from,
            'dt_to' => $request->dt_to,
            'vat' => Helper::double($request->vat),
            'vat_due_dt' => $request->vat_due_dt,
            'total_vat' => Helper::double($request->total_vat),
            'discount_subject' => $request->discount_subject,
            'discount_value' => Helper::double($request->discount_value),
            'ht' => Helper::double($request->ht),
            'ttc' => Helper::double($request->total_ttc),
            'created_by' => Auth::id(),
        ];

        $invoice = Invoice::create($params);

        if($invoice && isset($request->bulletins)) {
            $bulletins = explode('::', trim($request->bulletins));

            if(count($bulletins) > 0) {
                for($i = 0; $i < count($bulletins) -1; $i++){
                    $bulletin = explode(';', $bulletins[$i]);

                    Invoice_bulletins::create([
                        'label' => $bulletin[0],
                        'nb' => Helper::double($bulletin[1]),
                        'cost' => Helper::double($bulletin[2]),
                        'nb_days' => $bulletin[3],
                        'row_nb_days' => $bulletin[4],
                        'extra' =>$bulletin[5],
                        'invoice_id' => $invoice->id,
                    ]);

                }
            }
            Payment::create([
                'doc_id' => $invoice->id,
                'doc_type' => 'invoice',
                'dt' => $request->dt,
                'number' => $request->invoice_number,
                'month_id' => $request->month_id,
                'dt_from' => $request->dt_from,
                'dt_to' => $request->dt_to,
                'customer_id' => $request->customer_id,
                'contract_id' => $request->contract_id,
                'debit' => Helper::double($request->total_ttc),
            ]);
        }

        session()->flash('success', __('site.added_successfully'));
        $data = ['valid' => 1, 'route' => route('dashboard.invoices.index')];
        return json_encode($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show($account, Invoice $invoice)
    {
        if(!Auth::user()->can('invoices_read')){
            return response('Unauthorized.', 401);
        }
        $bulletins = Invoice_bulletins::where('invoice_id', '=', $invoice->id)->get();

        return view('dashboard.invoices.show', compact(
        'invoice',
            'bulletins'
        ));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit($account, Invoice $invoice)
    {
        if(!Auth::user()->can('invoices_update')){
            return response('Unauthorized.', 401);
        }
        $invoice = DB::table('invoices')
            ->join('customers', 'customers.id', '=', 'invoices.customer_id')
            ->where('invoices.id', '=', $invoice->id)
            ->selectRaw('invoices.*,
            customers.name_ar,
            customers.code as customer_code,
            customers.vat as customer_vat
            ')
            ->get()->first();

        $contract = Contract::find($invoice->contract_id);

        $bulletins = Invoice_bulletins::where('invoice_id', '=', $invoice->id)->get();

        $company = Company::find(1);
        return view('dashboard.invoices.edit', compact(
            'invoice',
            'contract',
            'bulletins',
            'company'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update($account, Request $request, Invoice $invoice)
    {
        if(!Auth::user()->can('invoices_update')){
            return response('Unauthorized.', 401);
        }
        $invoice_id = $invoice->id;
        $params = [
            'invoice_number' => $request->invoice_number,
            'customer_id' => $request->customer_id,
            'contract_id' => $request->contract_id,
            'year_id' => $request->year_id,
            'month_id' => $request->month_id,
            'month_days' => $request->month_days,
            'nb_days' => $request->nb_days,
            'customer_code' => $request->customer_code,
            'contract_code' => $request->contract_code,
            'invoice_code' => $request->invoice_code,
            'dt' => $request->dt,
            'dt_from' => $request->dt_from,
            'dt_to' => $request->dt_to,
            'vat' => Helper::double($request->vat),
            'vat_due_dt' => $request->vat_due_dt,
            'total_vat' => Helper::double($request->total_vat),
            'discount_subject' => $request->discount_subject,
            'discount_value' => Helper::double($request->discount_value),
            'ht' => Helper::double($request->ht),
            'ttc' => Helper::double($request->total_ttc),
            'created_by' => Auth::id(),
        ];

        $invoice = Invoice::where('id', $invoice_id)->update($params);

        if($invoice && isset($request->bulletins)) {
            Payment::where('doc_id', $invoice_id)
            ->where('doc_type', 'invoice')
                ->update([
                'dt' => $request->dt,
                'number' => $request->invoice_number,
                'month_id' => $request->month_id,
                'dt_from' => $request->dt_from,
                'dt_to' => $request->dt_to,
                'customer_id' => $request->customer_id,
                'contract_id' => $request->contract_id,
                'debit' => Helper::double($request->total_ttc),
            ]);
            $bulletins = explode('::', trim($request->bulletins));

            if(count($bulletins) > 0) {

                Invoice_bulletins::where('invoice_id', '=', $invoice_id)->delete();

                for($i = 0; $i < count($bulletins) -1; $i++){
                    $bulletin = explode(';', $bulletins[$i]);

                    Invoice_bulletins::create([
                        'label' => $bulletin[0],
                        'nb' => Helper::double($bulletin[1]),
                        'cost' => Helper::double($bulletin[2]),
                        'nb_days' => $bulletin[3],
                        'row_nb_days' => $bulletin[4],
                        'extra' => $bulletin[5],
                        'invoice_id' => $invoice_id,
                    ]);
                }
            }
        }

        session()->flash('success', __('site.updated_successfully'));
        $data = ['valid' => 1, 'route' => route('dashboard.invoices.index')];
        return json_encode($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy($account, Invoice $invoice)
    {
        if(!Auth::user()->can('invoices_delete')){
            return response('Unauthorized.', 401);
        }
        $rs = $invoice->delete();
        DB::table('invoice_bulletins')
            ->where('invoice_id', $invoice->id)
            ->delete();
        DB::table('payments')
            ->where('doc_id', $invoice->id)
            ->where('doc_type', 'invoice')
            ->delete();
        if($rs){
            session()->flash('success', __('site.deleted_successfully'));
            return redirect()->route('dashboard.invoices.index');
        }
    }

    public function preview($account, $id, $paper_id = null, $signature = null, $cachet = null){
        if(!Auth::user()->can('invoices_read')){
            return response('Unauthorized.', 401);
        }
        $invoice = DB::table('invoices')
            ->join('customers', 'customers.id', '=', 'invoices.customer_id')
            ->where('invoices.id', '=', $id)
            ->selectRaw('invoices.*,
            customers.name_ar,
            customers.name_en,
            customers.code as customer_code,
            customers.vat as customer_vat
            ')
            ->get()->first();

        $contract = Contract::find($invoice->contract_id);

        $bulletins = Invoice_bulletins::where('invoice_id', '=', $invoice->id)->get();

        $company = Company::find(1);

        $bank = Bank::where([
            ['company_id', '=', $company->id],
            ['is_default', '=', 1]
        ])->first();

        $paper = ($paper_id)?
            Paper::find($paper_id) :
            Paper::where('is_default', '=', 1)->first();

        //var_dump($bank, $company, $invoice, $contract, $bulletins);
        return view('dashboard.invoices.preview', compact(
            'invoice',
            'contract',
            'bulletins',
            'company',
            'bank',
            'paper',
            'signature',
            'cachet'
        ));
    }
}
