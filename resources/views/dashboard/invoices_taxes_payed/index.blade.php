@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i>الضرائب المسددة</h1>
                </div>
                <div class="col-md-6 text-left">
                    @if(auth()->user()->can('invoices_taxes_payed_create'))
                    <button type="button" class="btn_pay_invoice btn btn-warning" onclick="cancelPayInvoiceTaxes('{{ route('dashboard.invoices_taxes_payed.cancel_pay_invoices') }}')"><i class="fa fa-reply-all"></i> إلغاء سداد الضرائب المختارة</button>
                    @endif
                    <div class="btn-group">
                        <button onclick="previewSearch({{ $paper->id }})" class="btn btn-default" target="_blank"><i class="fa fa-print"></i> طباعة</button>
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="caret"></span>
                            <span class="sr-only">طباعة</span>
                        </button>
                        <ul class="dropdown-menu">
                            @foreach($papers as $paper)
                                <li><button class="btn btn-default" onclick="previewSearch({{ $paper->id }})" target="_blank"><i class="fa fa-file-o"></i> {{ $paper->paper_name }}</button></li>
                            @endforeach

                        </ul>
                    </div>
                </div>
            </div>

        </section>
        <section class="content">
            <input type="hidden" id="route_url" class="form-control" value="{{ url('/dashboard/invoices_taxes_payed/') }}">
            <input type="hidden" id="preview_url" class="form-control" value="{{ $preview_url }}">
            <div class="box box-primary">
                <div class="box-body with-border">
                    <div class="row">
                        <div class="col-sm-7 search_inline">

                        </div>
                        <div class="col-sm-5 search_inline">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>من تاريخ</label>
                                    <input type="date" name="dt_from" id="dt_from" class=""  value="{{ $dt_from }}" onchange="goSearchDates()">
                                </div>
                                <div class="col-sm-6">
                                    <label>الى تاريخ</label>
                                    <input type="date" name="dt_to" id="dt_to" class=""  value="{{ $dt_to }}" onchange="goSearchDates()">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box box-primary">
                <div class="box-body">
                    @if($invoices->count() > 0)
                        <div class="table-responsive">
                            <table id="example" class="table table-hover">
                        <thead>
                            <tr>
                                <th class="">&nbsp;
                                    <input type="checkbox" class="check_row" id="check_all" onchange="checkAllInvoices(this);">
                                </th>
                                <th>الرقم</th>
                                <th>التاريخ</th>
                                <th>الشهر</th>
                                <th>العميل</th>
                                <th>الإجمالي</th>
                                <th>الضريبة</th>
                                <th>مبلغ الضريبة</th>
                                <th>إيصال السداد</th>
                                <th>تاريخ السداد</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($invoices as $invoice)
                            <tr invoice_id="{{ $invoice->id }}">
                                <td class="text-center">
                                    <input type="checkbox" class="check_row" onchange="checkUncheckInvoice(this);">
                                </td>
                                <td class="currency">{{ $invoice->invoice_number }}</td>
                                <td>{{ $invoice->dt }}</td>
                                <td>{{ $invoice->month_id }}</td>
                                <td>{{ $invoice->name_ar }}</td>
                                <td class="currency">{{ $invoice->ttc }}</td>
                                <td><b>{{ $invoice->vat }} %</b></td>
                                <td class="currency"><b>{{ $invoice->total_vat }}</b></td>
                                <td>{{ $invoice->vat_pay_ref }}</td>
                                <td>{{ $invoice->vat_pay_dt }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                        </div>
                    @else
                        <h2>لا توجد بيانات !</h2>
                    @endif
                </div>
            </div>

        </section>
    </div>


@endsection
