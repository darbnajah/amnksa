@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> الفواتير الآجلة</h1>
                </div>
                <div class="col-md-6 text-left">
                    <label class="btn btn-default print_signature_wrap">
                        <input type="checkbox" id="print_signature" onclick="toggleBtnPrint()"><span style="position:relative; top: -5px;">التواقيع</span>
                    </label>
                    <label class="btn btn-default print_signature_wrap">
                        <input type="checkbox" id="print_cachet" onclick="toggleBtnPrint()"><span style="position:relative; top: -5px;">الختم</span>
                    </label>
                    @if(!Session::has('expiration_dt'))
                    <a href="{{ route('dashboard.invoices.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> @lang('site.create')</a>
                    @endif
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
                                <th>المدينة</th>
                                <th>الإجمالي</th>
                                <th>المستخدم</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($invoices as $invoice)
                            <tr>
                                <td class="table_actions">
                                    <div class="btn-group print_group">
                                        <a href="{{ url()->to('dashboard/invoices/preview/'.$invoice->id.'/'.$paper_id) }}" prefix_href="{{ url()->to('dashboard/invoices/preview/'.$invoice->id.'/'.$paper_id) }}" class="btn btn-default print_btn" target="_blank"><i class="fa fa-print"></i></a>
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="caret"></span>
                                            <span class="sr-only">طباعة</span>
                                        </button>
                                        <ul class="dropdown-menu">

                                            @foreach($papers as $paper)
                                                <li><a href="{{ url()->to('dashboard/invoices/preview/'.$invoice->id.'/'.$paper->id) }}" prefix_href="{{ url()->to('dashboard/invoices/preview/'.$invoice->id.'/'.$paper->id) }}" target="_blank" class="print_btn"><i class="fa fa-file-o"></i> {{ $paper->paper_name }}</a></li>
                                            @endforeach

                                        </ul>
                                    </div>
                                    @if(auth()->user()->can('invoices_update'))
                                    <a href="{{ route('dashboard.invoices.edit', $invoice->id) }}" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                    @endif
                                    @if(auth()->user()->can('invoices_delete'))
                                    <form
                                        action="{{ route('dashboard.invoices.destroy', $invoice->id) }}"
                                        method="post" style="display: inline-block">
                                        {{ csrf_field() }}
                                        {{ method_field('delete') }}
                                        <button type="submit" class="delete btn btn-danger"><i class="fa fa-times"></i></button>
                                    </form>
                                    @endif
                                </td>
                                <td class="currency">{{ $invoice->invoice_number }}</td>
                                <td>{{ $invoice->dt }}</td>
                                <td>{{ $invoice->month_id }}</td>
                                <td>{{ $invoice->name_ar }}</td>
                                <td>{{ $invoice->city }}</td>
                                <td class="currency bold">{{ \App\Helper\Helper::nFormat($invoice->ttc) }}</td>
                                <td>{{ $invoice->username }}</td>
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
