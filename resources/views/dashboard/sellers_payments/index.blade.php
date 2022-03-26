@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> مستحقات مندوبي التسويق</h1>
                </div>
                <div class="col-md-6 text-left">
                    @if(auth()->user()->can('sellers_payments_accept_deny_yes'))
                    <button type="button" class="btn btn-info" onclick="showModalAcceptSellersPayments()"><i class="fa fa-check"></i> تعميد المستحقات المختارة</button>
                    <button type="button" class="btn btn-danger" onclick="showModalDenySellersPayments()"><i class="fa fa-warning"></i> رفض المستحقات المختارة</button>
                    @endif
                @if(!Session::has('expiration_dt') && auth()->user()->can('sellers_payments_create'))
                    <a href="{{ route('dashboard.sellers_payments.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> إضافة </a>
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
                                <th style="display: none"></th>
                                <th>
                                    <input type="checkbox" class="check_row" id="check_all" onchange="checkAllInvoices(this);">
                                </th>
                                <th>تاريخ العملية</th>
                                <th>الشهر</th>
                                <th>اسم المندوب</th>
                                <th>العميل</th>
                                <th>المدينة</th>
                                <th>المبلغ</th>
                                <th>الخصم</th>
                                <th>السلفة</th>
                                <th>الصافي</th>
                                <th>الإجمالي</th>
                                <th>الحالة</th>
                                <th>المستخدم</th>

                            </tr>
                            </thead>
                            <tbody>
                            @if($sellers_payments)
                                @foreach($sellers_payments as $seller_payment)
                                    <tr payment_id="{{ $seller_payment->id }}">
                                    <td class="text-center table_actions">
                                            @if($seller_payment->status == 0)
                                                <input type="checkbox" class="check_row" onchange="checkUncheckInvoice(this);">
                                            @endif
                                            @if($seller_payment->status <= 0)
                                                @if(auth()->user()->can('sellers_payments_update'))
                                                    <a href="{{ route('dashboard.sellers_payments.edit', $seller_payment->id) }}" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                                @endif
                                            @endif
                                            @if(auth()->user()->can('sellers_payments_delete'))
                                            <button type="button" onclick="deleteSellerPayment('{{ route('dashboard.sellers_payments.delete_seller_payment', $seller_payment->id) }}')" class="btn btn-danger"><i class="fa fa-times"></i></button>
                                            @endif

                                            @if(auth()->user()->can('sellers_payments_accept_deny_yes'))
                                                @if($seller_payment->status == 1 && $seller_payment->trans_status == 1)
                                                    <button type="button" onclick="cancelSellerPaymentTransfer('{{ route('dashboard.sellers_payments.cancel_seller_payment_transfer', $seller_payment->id) }}')" class="btn btn-warning btn-sm"><i class="fa fa-reply"></i> إلغاء الصرف</button>

                                                @elseif($seller_payment->status == 1 && $seller_payment->trans_status == 0)
                                                    <button type="button" onclick="cancelSellerPaymentAccept('{{ route('dashboard.sellers_payments.cancel_seller_payment_accept', $seller_payment->id) }}')" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i> إلغاء التعميد</button>
                                                @endif
                                            @endif

                                        </td>
                                        <td>{{ $seller_payment->dt }}</td>
                                        <td>{{ \App\Helper\Helper::monthNameAr($seller_payment->month_id) }}</td>
                                        <td>{{ $seller_payment->seller_name }}</td>
                                        <td>{{ $seller_payment->customer_name }}</td>
                                        <td>{{ $seller_payment->city }}</td>
                                        <td class="currency">{{ \App\Helper\Helper::nFormat($seller_payment->amount) }}</td>
                                        <td class="currency">{{ \App\Helper\Helper::nFormat($seller_payment->deduction) }}</td>
                                        <td class="currency">{{ \App\Helper\Helper::nFormat($seller_payment->advance) }}</td>
                                        <td class="currency bold">{{ \App\Helper\Helper::nFormat($seller_payment->amount_net) }}</td>
                                        <td class="currency bold">{{ \App\Helper\Helper::nFormat($seller_payment->amount_net + $seller_payment->advance) }}</td>

                                        <td>
                                            @if($seller_payment->status == -2)
                                                <label class="label label-primary"><i class="fa fa-warning"></i> قيد المراجعة</label>
                                                <small style="color: #337ab7">تم عن طريق التحصيل</small>
                                            @elseif($seller_payment->status == -1)
                                                <label class="label label-danger"><i class="fa fa-warning"></i> مرفوض</label>
                                                <small style="color: #9f3a38">{{ $seller_payment->deny_notes }}</small>
                                            @elseif($seller_payment->status == 0)
                                                <label class="label label-warning"><i class="fa fa-clock-o"></i> قيد التعميد</label>
                                            @elseif($seller_payment->status == 1 && $seller_payment->trans_status == 0)
                                                <label class="label label-info"><i class="fa fa-check"></i>  تم التعميد</label>

                                            @elseif($seller_payment->status == 1 && $seller_payment->trans_status == 1)
                                                <label class="label label-success"><i class="fa fa-clock-o"></i> تم الصرف</label>
                                            @endif
                                        </td>
                                        <td>{{ $seller_payment->username }}</td>

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

<div class="modal fade" id="modal_accept_sellers_payments" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-danger btn-sm btn-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">تعميد المستحقات</h4>
            </div>
            <div class="modal-body" style="padding: 20px">
                <div class="form-group">
                    <label>تاريخ التعميد</label>
                    <input type="date" class="form-control accept_dt">
                </div>
            </div>
            <div class="modal-footer">
                <div class="pull-right">
                    <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">إغلاق</button>
                </div>
                <div class="pull-left">
                    <button type="button" class="btn btn-info btn-lg" onclick="acceptSellersPayments('{{ route('dashboard.sellers_payments.accept_payments') }}')"><i class="fa fa-check"></i> تعميد المستحقات المختارة</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_deny_sellers_payments" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-danger btn-sm btn-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">رفض تعميد المستحقات</h4>
            </div>
            <div class="modal-body" style="padding: 20px">
                <div class="form-group">
                    <label>ملاحظات</label>
                    <input type="text" class="form-control deny_notes">
                </div>
            </div>
            <div class="modal-footer">
                <div class="pull-right">
                    <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">إغلاق</button>
                </div>
                <div class="pull-left">
                    <button type="button" class="btn btn-danger btn-lg" onclick="denySellersPayments('{{ route('dashboard.sellers_payments.deny_payments') }}')"><i class="fa fa-check"></i> رفض المستحقات المختارة</button>
                </div>
            </div>
        </div>
    </div>
</div>
