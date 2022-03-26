@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> الفواتير المسددة</h1>
                </div>
                <div class="col-md-6 text-left">

                </div>
            </div>

        </section>
        <section class="content">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <input type="text" name="search" class="form-control"  id="dt_search" placeholder="@lang('site.search')">
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-body">
                    @if($invoices->count() > 0)
                        <div class="table-responsive">
                            <table id="example" class="table table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th>الرقم</th>
                                <th>التاريخ</th>
                                <th>الشهر</th>
                                <th>العميل</th>
                                <th>الإجمالي</th>
                                <th>إيصال السداد</th>
                                <th>تاريخ السداد</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($invoices as $invoice)
                            <tr>
                                <td class="table_actions">
                                    <div class="btn-group">
                                        <a href="{{ route('dashboard.invoices.preview', $invoice->id) }}" class="btn btn-default" target="_blank"><i class="fa fa-print"></i></a>
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="caret"></span>
                                            <span class="sr-only">طباعة</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @foreach($papers as $paper)
                                                <li><a href="{{ route('dashboard.invoices.preview', ['id' => $invoice->id, 'paper_id' => $paper->id]) }}" target="_blank"><i class="fa fa-file-o"></i> {{ $paper->paper_name }}</a></li>
                                            @endforeach

                                        </ul>
                                    </div>
                                    <button type="button" class="btn_pay_invoice btn btn-warning" onclick="CancelPayInvoice({{ $invoice->id }}, '{{ route('dashboard.invoices_track.cancel_pay', $invoice->id) }}')"><i class="fa fa-reply-all"></i> إلغاء السداد</button>

                                </td>
                                <td class="currency">{{ $invoice->invoice_number }}</td>
                                <td>{{ $invoice->dt }}</td>
                                <td>{{ $invoice->month_id }}</td>
                                <td>{{ $invoice->name_ar }}</td>
                                <td class="currency">{{ $invoice->ttc }}</td>
                                <td>{{ $invoice->pay_ref }}</td>
                                <td>{{ $invoice->pay_dt }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                        </div>
                    @else
                        <h2>@lang('site.no_data_found')</h2>
                    @endif
                </div>
            </div>

        </section>
    </div>

@endsection
<script>
    import Label from "@/Jetstream/Label";
    export default {
        components: {Label}
    }
</script>
