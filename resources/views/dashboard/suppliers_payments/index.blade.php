@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> المستحقات على الموردين</h1>
                </div>
                <div class="col-md-6 text-left">
                    @if(!Session::has('expiration_dt') && auth()->user()->can('suppliers_payments_create'))
                    <a href="{{ route('dashboard.suppliers_payments.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> إضافة </a>
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
                                <th></th>
                                <th>تاريخ العملية</th>
                                <th>الشهر</th>
                                <th>اسم المورد</th>
                                <th>العميل</th>
                                <th>المدينة</th>
                                <th>المبلغ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($suppliers_payments)
                                @foreach($suppliers_payments as $supplier_payment)
                                    <tr payment_id="{{ $supplier_payment->id }}">
                                        <td class="text-center table_actions">
                                            @if(auth()->user()->can('suppliers_payments_update'))
                                            <a href="{{ route('dashboard.suppliers_payments.edit', $supplier_payment->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                            @endif
                                            @if(auth()->user()->can('suppliers_payments_delete'))
                                            <button type="button" onclick="deleteSellerPayment('{{ route('dashboard.suppliers_payments.delete_supplier_payment', $supplier_payment->id) }}')" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button>
                                            @endif
                                        </td>
                                        <td>{{ $supplier_payment->dt }}</td>
                                        <td>{{ \App\Helper\Helper::monthNameAr($supplier_payment->month_id) }}</td>
                                        <td>{{ $supplier_payment->supplier_name }}</td>
                                        <td>{{ $supplier_payment->customer_name }}</td>
                                        <td>{{ $supplier_payment->city }}</td>
                                        <td class="currency">{{ \App\Helper\Helper::nFormat($supplier_payment->supplier_amount) }}</td>
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
