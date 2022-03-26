@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> البيان الضريبي</h1>
                </div>
                <div class="col-md-6 text-left">
                    <button type="button" class="btn_pay_invoice btn btn-danger" onclick="showModalPayTaxes()"><i class="fa fa-money"></i> تسديد الضرائب المختارة</button>
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
            <input type="hidden" id="route_url" class="form-control" value="{{ url('/dashboard/invoices_taxes/') }}">
            <input type="hidden" id="preview_url" class="form-control" value="{{ $preview_url }}">
            <div class="box box-primary">
                <div class="box-body with-border">
                    <div class="row">
                        <div class="col-sm-6 search_inline">
                            <label>الفترة</label>
                            <select id="period" class="form-control" onchange="goSearchTaxes()">
                                <option value="all" {{ ($period == 'all')? 'selected' : null }}>الكل</option>
                                <option value="normal" {{ ($period == 'normal')? 'selected' : null }}>غير مستحقة</option>
                                <option value="month" {{ ($period == 'month')? 'selected' : null }}>مستحقة خلال شهر</option>
                                <option value="echeance" {{ ($period == 'echeance')? 'selected' : null }}>تجاوزت تاريخ الإستحقاق</option>
                            </select>
                        </div>
                        <div class="col-sm-5 search_inline">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>من تاريخ</label>
                                    <input type="date" name="dt_from" id="dt_from" class=""  value="{{ $dt_from }}" onchange="goSearchTaxes()">
                                </div>
                                <div class="col-sm-6">
                                    <label>الى تاريخ</label>
                                    <input type="date" name="dt_to" id="dt_to" class=""  value="{{ $dt_to }}" onchange="goSearchTaxes()">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-1 search_inline text-left">
                            <button type="button" class="btn btn-default" onclick="resetSearchTaxes()"><i class="fa fa-refresh"></i></button>

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
                                <th>استحقاق الضريبة</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($invoices as $invoice):
                            $vat_due_dt = \App\Helper\Helper::dt($invoice->vat_due_dt);

                            if($vat_due_dt <= $sys_dt){
                                $class = 'td_danger';
                            } else if($vat_due_dt > $sys_dt && $vat_due_dt < $after_month){
                                $class = 'td_warning';
                            } else {
                                $class = '';
                            }

                        ?>
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
                                <td class="text-center {{ $class }}">{{ $invoice->vat_due_dt }}</td>
                            </tr>
                        <?php endforeach ?>
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
    <div class="modal fade" id="modal_pay_taxes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn btn-danger btn-sm btn-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">تسديد ضرائب الفواتير</h4>
                </div>
                <div class="modal-body" style="padding: 20px">
                    <div class="form-group">
                        <label>إيصال السداد</label>
                        <input type="text" class="form-control pay_ref" placeholder="إيصال السداد">
                    </div>
                    <div class="form-group">
                        <label>تاريخ السداد</label>
                        <input type="date" class="form-control pay_dt">
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">إغلاق</button>
                    </div>
                    <div class="pull-left">
                        <button type="button" class="btn_pay_invoice btn btn-danger btn-lg" onclick="payInvoiceTaxes('{{ route('dashboard.invoices_taxes.pay_invoices') }}')"><i class="fa fa-money"></i> تسديد الفواتير المختارة</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
<script>
    import Label from "@/Jetstream/Label";
    import Button from "@/Jetstream/Button";
    export default {
        components: {Button, Label}
    }
</script>
