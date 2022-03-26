@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> مستحقات مندوبي التسويق</h1>
                </div>
                <div class="col-md-6 text-left">
                    @if(auth()->user()->can('sellers_payments_accepted_create'))
                    <button type="button" class="btn btn-warning" onclick="showModalTransferSellersPayments()"><i class="fa fa-money"></i> صرف المستحقات المختارة</button>
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
                    <div class="table-responsive">
                        <table id="example" class="table table-hover paie_table">
                            <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" class="check_row" id="check_all" onchange="checkAllInvoices(this);">
                                </th>
                                <th>تاريخ العملية</th>
                                <th>الشهر</th>
                                <th>اسم المندوب</th>
                                <th>العميل</th>
                                <th>المدينة</th>
                                <th>اسم البنك</th>
                                <th>رقم الحساب</th>
                                <th>ايبان</th>
                                <th>الصافي</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($sellers_payments)
                                @foreach($sellers_payments as $seller_payment)
                                    <tr payment_id="{{ $seller_payment->id }}">
                                        <td class="text-center">
                                            <input type="checkbox" class="check_row" onchange="checkUncheckInvoice(this);">
                                        <td>{{ $seller_payment->dt }}</td>
                                        <td>{{ \App\Helper\Helper::monthNameAr($seller_payment->month_id) }}</td>
                                        <td>{{ $seller_payment->seller_name }}</td>
                                        <td>{{ $seller_payment->customer_name }}</td>
                                        <td>{{ $seller_payment->city }}</td>
                                        <td>{{ $seller_payment->bank_name }}</td>
                                        <td>{{ $seller_payment->bank_account }}</td>
                                        <td>{{ $seller_payment->bank_iban }}</td>
                                        <td class="currency bold">{{ \App\Helper\Helper::nFormat($seller_payment->amount_net) }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </section>
    </div>

@endsection

<div class="modal fade" id="modal_transfer_sellers_payments" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-danger btn-sm btn-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">صرف مستحقات مندوبي التسويق</h4>
            </div>
            <div class="modal-body" style="padding: 20px">
                <div class="form-group">
                    <label>تاريخ الصرف</label>
                    <input type="date" class="form-control transfer_dt">
                </div>
            </div>
            <div class="modal-footer">
                <div class="pull-right">
                    <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">إغلاق</button>
                </div>
                <div class="pull-left">
                    <button type="button" class="btn btn-warning btn-lg" onclick="transferSellersPayments('{{ route('dashboard.sellers_payments_accepted.transfer_payments') }}')"><i class="fa fa-check"></i> صرف المستحقات المختارة</button>
                </div>
            </div>
        </div>
    </div>
</div>
