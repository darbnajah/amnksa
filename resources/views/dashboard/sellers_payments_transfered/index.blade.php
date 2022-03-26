@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> مدفوعات مندوبي التسويق</h1>
                </div>
                <div class="col-md-6 text-left">
                    <div class="btn-group">
                        <a href="{{ route('dashboard.sellers_payments_transfered.preview', $paper->id) }}" class="btn btn-default" target="_blank"><i class="fa fa-print"></i> طباعة</a>
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="caret"></span>
                            <span class="sr-only">طباعة</span>
                        </button>
                        <ul class="dropdown-menu">
                            @foreach($papers as $paper)
                                <li><a href="{{ route('dashboard.sellers_payments_transfered.preview', $paper->id) }}" class="btn btn-default" target="_blank"><i class="fa fa-file-o"></i> طباعة</a></li>
                            @endforeach

                        </ul>
                    </div>

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
