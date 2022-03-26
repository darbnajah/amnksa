@extends('layouts.dashboard.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-sm-12">
                    <h1 style="margin-bottom: 10px"><i class="fa fa-university"></i>
                        {{ date('Y-m-d') }} - <span>إحصائية شهر </span> {{ \App\Helper\Helper::monthNameAr(date('m')) }}</h1>

                </div>
            </div>

        </section>

        <section class="content">
            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>{{ $invoices_count }}</h3>
                            <p>عدد الفوتير</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-document-text"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>{{ $contracts_count }}<h3>
                            <p>العقود السارية</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-document-text"></i>
                        </div>
                    </div>
                </div>

            </div>
            <div class="box box-primary">
                <div class="box-body with-border" style="min-height: 600px">
                    @if(auth()->user()->can('customers_read'))
                    <div class="row">
                        <div class="col-sm-6 search_inline">
                            <label>العميل</label>
                            <input type="hidden" id="customer_id" value="{{ isset($customer_id)? $customer_id : null }}" readonly>
                            <input type="text" id="customer_name" value="{{ isset($customer_name)? $customer_name : null }}" readonly style="background-color: #eee !important;">
                            <button type="button" class="btn btn-info" onclick="modalCustomers('{{ route('dashboard.customers.modal', 'home') }}')"><i class="fa fa-search"></i></button>
                            <br>
                            <br>
                        </div>
                        <div class="col-md-6">

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-xs-6 col-sm-6">
                                    <div class="small-box bg-aqua">
                                        <div class="inner">
                                            <h3 id="customer_invoices_count">0</h3>
                                            <p>عدد الفوتير</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-document-text"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6">
                                    <div class="small-box bg-primary">
                                        <div class="inner">
                                            <h3 id="customer_invoices_total" class="currency">0</h3>
                                            <p>مجموع الفوتير</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-ios-list"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12">
                                    <div class="small-box bg-orange">
                                        <div class="inner">
                                            <h3 id="last_invoice_amount" class="currency">0</h3>
                                            <p>
                                                <span>آخر فاتورة عن شهر </span>
                                                <span id="last_invoice_month_ar">{{ \App\Helper\Helper::monthNameAr(date('m')) }}</span>
                                                <span> بتاريخ </span>
                                                <span id="last_invoice_dt">{{ date('Y-m-d') }}</span>
                                            </p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-document-text"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-xs-6 col-sm-6">
                                    <div class="small-box bg-green">
                                        <div class="inner">
                                            <h3 id="payments_total" class="currency">0<h3>
                                            <p>مجموع التحصيل </p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-cash"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6">
                                    <div class="small-box bg-olive">
                                        <div class="inner">
                                            <h3 id="last_payment_total" class="currency">0<h3>
                                            <p>اخر تحصيل <span id="last_payment_dt"></span></p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-cash"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12">
                                    <div class="small-box bg-red">
                                        <div class="inner">
                                            <h3 id="customer_rest_balance"  class="currency">0</h3>
                                            <p>المتبقي بذمة العميل </p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-cash"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    @endif
                @if($company->logo)
                    <img style="opacity: 0.8; margin-left: auto; margin-right: auto; width: auto" class="img-responsive home_img" src="{{ asset('storage/'.$company->logo) }}" width="100%">
                    @endif
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
