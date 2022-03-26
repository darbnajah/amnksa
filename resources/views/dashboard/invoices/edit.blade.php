@php
    $edit_mode = false;
    if(isset($invoice)){
        $edit_mode = true;
    }
@endphp

@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> الفواتير -
                        @if($edit_mode)
                            @lang('site.edit')
                        @else
                            @lang('site.create')
                        @endif
                    </h1>
                </div>
                <div class="col-md-6 text-left">
                    <a href="{{ route('dashboard.invoices.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('site.back')</a>
                </div>
            </div>

        </section>


        <section class="content">
            <input type="hidden" id="route_url" class="form-control" value="{{ url('/dashboard/invoices/new_invoice') }}">

            <div class="box box-primary">
                <div class="box-body">
                    @include('partials._errors')
                    <form action="{{ ($edit_mode)? route('dashboard.invoices.update', $invoice->id) : route('dashboard.invoices.store') }}" method="post" enctype="multipart/form-data" class="has_small_inputs" id="default_form">
                        {{ csrf_field() }}
                        {{ method_field(($edit_mode)? 'put' : 'post') }}
                        <input type="hidden" id="form_method" value="{{ ($edit_mode)? 'put' : 'post' }}">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <span>العميل</span>
                                        <button type="button" class="btn btn-success" onclick="modalCustomers('{{ route('dashboard.customers.modal', 'invoice') }}')"><i class="fa fa-search"></i></button>
                                    </div>
                                    <div class="panel-body" style="min-height: 291px;">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label>اسم العميل</label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="text" id="customer_name_ar" class="form-control"  value="{{ ($edit_mode)? $invoice->name_ar : null }}" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label>رقم العميل</label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="text" name="customer_code" id="customer_code" class="form-control"  value="{{ ($edit_mode)? $invoice->customer_code : null }}" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label>الرقم الضريبي</label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="text" name="customer_vat" id="customer_vat" class="form-control"  value="{{ ($edit_mode)? $invoice->customer_vat : null }}" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <hr>
                                            <div class="col-sm-4">
                                                <label>ترتيب الفاتورة</label>
                                            </div>
                                            <div class="col-sm-8">
                                                <input type="text" name="invoice_code" id="invoice_code" class="form-control" value="{{ ($edit_mode)? $invoice->invoice_code : null }}" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <input type="hidden" name="contract_code" id="contract_code" class="form-control" value="{{ ($edit_mode)? $invoice->contract_code : null }}">
                                                <input type="hidden" name="invoice_id" id="invoice_id" class="form-control" value="{{ ($edit_mode)? $invoice->id : null }}">

                                                <input type="hidden" name="customer_id" id="customer_id" class="form-control"  value="{{ ($edit_mode)? $invoice->customer_id : null }}" readonly>
                                                <input type="hidden" name="contract_id" id="contract_id" class="form-control"  value="{{ ($edit_mode)? $invoice->contract_id : null }}" readonly>
                                                <input type="hidden" name="nb_days" id="nb_days" class="form-control"  value="{{ ($edit_mode)? $invoice->nb_days : 30 }}" readonly>
                                                <input type="hidden" name="nb_days_period" id="nb_days_period" class="form-control"  value="{{ ($edit_mode)? $invoice->nb_days : 30 }}" readonly>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="panel panel-info">
                                    <div class="panel-heading">العقود</div>
                                    <div class="panel-body" style="padding: 0;min-height: 291px;">
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="contracts_table">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th>الرقم</th>
                                                <th>المدينة</th>
                                                <th>العنوان</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if($edit_mode)
                                                <tr>
                                                    <td class="table_actions">
                                                        <button type="button" onclick="selectContract({{ $contract }}, '{{ url()->to('dashboard/invoices/bulletins_by_contract') }}')" class="btn btn-success btn-sm"><i class="fa fa-check"></i> اختر العقد</button>
                                                    </td>
                                                    <td>{{ $contract->code }}</td>
                                                    <td>{{ $contract->city }}</td>
                                                    <td>{{ $contract->dt_start }}</td>
                                                    <td>{{ $contract->dt_end }}</td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3" style="padding-left: 15px">
                                <div class="panel panel-info">
                                    <div class="panel-heading">الترقيم</div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label>فاتورة رقم</label>
                                            </div>
                                            <div class="col-sm-7">
                                                <input type="text" name="invoice_number" id="invoice_number" class="form-control currency"  value="{{ ($edit_mode)? $invoice->invoice_number : null }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <hr>
                                            <div class="col-sm-5">
                                                <label>عن سنة</label>
                                            </div>
                                            <div class="col-sm-7">
                                                <input type="text" name="year_id" id="year_id" class="form-control"  value="{{ ($edit_mode)? $invoice->year_id : null }}" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label>ترتيب الشهر</label>
                                            </div>
                                            <div class="col-sm-7">
                                                <input type="text" name="month_id" id="month_id" class="form-control"  value="{{ ($edit_mode)? $invoice->month_id : null }}" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label>الشهر عربي</label>
                                            </div>
                                            <div class="col-sm-7">
                                                <input type="text" id="month_ar" class="form-control"  value="{{ ($edit_mode)? \App\Helper\Helper::monthNameAr($invoice->month_id) : null }}" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label>الشهر انجليزي</label>
                                            </div>
                                            <div class="col-sm-7">
                                                <input type="text" id="month_en" class="form-control"  value="{{ ($edit_mode)? \App\Helper\Helper::monthNameEn($invoice->month_id) : null }}" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                    <div class="col-sm-5">
                                        <label>عدد ايام الشهر</label>
                                    </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="month_days" id="month_days" class="form-control"  value="{{ ($edit_mode)? $invoice->month_days : null }}" readonly>
                                    </div>
                                </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row row_invoice_infos_bulletins" style="display: {{ ($edit_mode)? 'block' : 'none' }}">
                            <div class="col-sm-4">
                                <div class="panel panel-info">
                                    <div class="panel-heading">الفاتورة</div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label>تاريخ الفاتورة</label>
                                            </div>
                                            <div class="col-sm-7">
                                                <input type="date" name="dt" id="dt" class="form-control"  value="{{ ($edit_mode)? $invoice->dt : null }}" onchange="new_invoice()">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <hr>
                                            <div class="col-sm-5">
                                                <label>من تاريخ</label>
                                            </div>
                                            <div class="col-sm-7">
                                                <input type="date" name="dt_from" id="dt_from" class="form-control"  value="{{ ($edit_mode)? $invoice->dt_from : null }}" onchange="setNbDays()">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label>الى تاريخ</label>
                                            </div>
                                            <div class="col-sm-7">
                                                <input type="date" name="dt_to" id="dt_to" class="form-control"  value="{{ ($edit_mode)? $invoice->dt_to : null }}" onchange="setNbDays()">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <hr>
                                            <div class="col-sm-5">
                                                <label>الضريبة %</label>
                                            </div>
                                            <div class="col-sm-7">
                                                <input type="text" name="vat" id="vat" class="form-control"  value="{{ ($edit_mode)? $invoice->vat : null }}" onkeyup="syncVat(this)">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label>تاريخ استحقاق الضريبة</label>
                                            </div>
                                            <div class="col-sm-7">
                                                <input type="date" name="vat_due_dt" id="vat_due_dt" class="form-control"  value="{{ ($edit_mode)? $invoice->vat_due_dt : null }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label>سبب الخصم</label>
                                            </div>
                                            <div class="col-sm-7">
                                                <input type="text" name="discount_subject" id="discount_subject" class="form-control"  value="{{ ($edit_mode)? $invoice->discount_subject : null }}" onkeyup="syncDiscountSubject(this)">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <label>قيمة الخصم</label>
                                            </div>
                                            <div class="col-sm-7">
                                                <input type="text" name="discount_value"  id="discount_value" class="form-control"  value="{{ ($edit_mode)? $invoice->discount_value : 0 }}" onkeyup="syncDiscountValue(this)">
                                            </div>
                                        </div>
                                        <div class="row" style="display: none">
                                            <hr>

                                            <div class="col-sm-5">
                                                <label>نسبة الشركة</label>
                                            </div>
                                            <div class="col-sm-7">
                                                <input type="text" name="company_commission"  id="company_commission" class="form-control"  value="" onkeyup="checkNumber(this)">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-8">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <span>البيانات</span>
                                        <button type="button" class="btn btn-success btn-sm" onclick="appendBulletinToInvoice()"><i class="fa fa-plus"></i> إضافة بيان للفاتورة</button>

                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="invoice_bulletins_table">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th width="30%">البيان</th>
                                                <th>القيمة الشهرية</th>
                                                <th>العدد</th>
                                                <th>عدد الأيام</th>
                                                <th width="20%">الإجمالي</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if($edit_mode && $bulletins)
                                                @foreach($bulletins as $bulletin)
                                                    <tr row_id="" extra="{{ $bulletin->extra }}" row_nb_days="{{ $bulletin->row_nb_days }}">
                                                        <td class="table_actions">
                                                            <button type="button" class="btn_remove btn btn-danger" onclick="removeInvoiceRow(this)"><i class="fa fa-times"></i></button>
                                                        </td>
                                                        <td class="td_label">
                                                            <input type="text" class="form-control" value="{{ $bulletin->label }}" onkeyup="calcInvoiceRow(this)">
                                                        </td>
                                                        <td class="td_cost">
                                                            <input type="text" class="form-control currency" value="{{ $bulletin->cost }}" onkeyup="calcInvoiceRow(this)">
                                                        </td>
                                                        <td class="td_nb">
                                                            <input type="text" class="form-control text-center" value="{{ $bulletin->nb }}" onkeyup="calcInvoiceRow(this)">
                                                        </td>
                                                        <td class="td_nb_days">
                                                            <input type="text" class="form-control text-center" value="{{ $bulletin->nb_days }}" onkeyup="calcInvoiceRow(this)">
                                                        </td>
                                                        <td class="td_total">
                                                            <input type="text" class="form-control currency" value="{{ \App\Helper\Helper::nFormat(($bulletin->cost * $bulletin->nb / $bulletin->row_nb_days) * $bulletin->nb_days)
}}" readonly>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="5" class="" id="th_discount_subject">{{ ($edit_mode)? $invoice->discount_subject : null }}</th>
                                                    <th id="th_discount_value" class="currency">
                                                        <input type="text" class="form-control currency" value="{{ ($edit_mode)? \App\Helper\Helper::nFormat($invoice->discount_value) : '0.00' }}" readonly>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th colspan="5" class="">الإجمالي </th>
                                                    <th id="th_total_ht" class="currency">
                                                        <input type="text" name="ht" id="ht" class="form-control currency" value="{{ ($edit_mode)? \App\Helper\Helper::nFormat($invoice->ht) : '0.00' }}" readonly>

                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th colspan="5" class="">الضريبة (<span id="vat_percent">{{ ($edit_mode)? $invoice->vat : 0 }}</span>) %</th>
                                                    <th id="th_total_vat" class="currency">
                                                        <input type="text" id="total_vat" name="total_vat" class="form-control currency" value="{{ ($edit_mode)? \App\Helper\Helper::nFormat($invoice->total_vat) : '0.00' }}" readonly>

                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th colspan="5" class="" id="th_total_ttc_alpha">
                                                        الإجمالي مع احتساب الضريبة
                                                    </th>
                                                    <th id="th_total_ttc" class="currency">
                                                        <input type="text" name="total_ttc" id="total_ttc" class="form-control currency" value="{{ ($edit_mode)? \App\Helper\Helper::nFormat($invoice->ttc) : '0.00' }}" readonly>

                                                    </th>
                                                </tr>

                                            </tfoot>
                                        </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-lg-12">
                            <br>
                            <hr>
                            <div class="form-group text-center">
                                <button type="button" class="btn btn-primary btn-lg" onclick="saveInvoice()"><i class="fa fa-save"></i> @lang('site.save')</button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </section>
    </div>

@endsection

<div class="modal fade" id="modal_customers" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-danger btn-sm btn-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">العملاء</h4>
            </div>
            <div class="modal-body" style="padding-bottom: 0">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
<script>
    import Input from "@/Jetstream/Input";
    export default {
        components: {Input}
    }
</script>
