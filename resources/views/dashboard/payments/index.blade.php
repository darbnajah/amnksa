@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i>التحصيل</h1>
                </div>
                <div class="col-md-6 text-left">
                    @if(!Session::has('expiration_dt') && auth()->user()->can('customers_create'))
                    <a href="{{ route('dashboard.payments.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> إضافة </a>
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
                                <th>الرقم المرجعي</th>
                                <th>تاريخ العملية</th>
                                <th>البيان</th>
                                <th>العميل</th>
                                <th>المبلغ المحصل</th>
                                <th>المستخدم</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($payments)
                                @foreach($payments as $payment)
                                    <tr payment_id="{{ $payment->id }}">
                                        <td class="text-center table_actions">
                                            @if(auth()->user()->can('customers_update'))
                                            <a href="{{ route('dashboard.payments.edit', $payment->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                            @endif
                                            @if(auth()->user()->can('customers_delete'))
                                                <form
                                                action="{{ route('dashboard.payments.destroy', $payment->id) }}"
                                                method="post" style="display: inline-block">
                                                {{ csrf_field() }}
                                                {{ method_field('delete') }}
                                                <button type="submit" class="delete btn btn-sm btn-danger"><i class="fa fa-times"></i></button>
                                            </form>
                                            @endif
                                        </td>
                                        <td>{{ $payment->number }}</td>
                                        <td>{{ $payment->dt }}</td>
                                        <td>{{ $payment->label }}</td>
                                        <td>{{ $payment->customer_name }}</td>
                                        <td class="currency">{{ \App\Helper\Helper::nFormat($payment->credit) }}</td>
                                        <td>{{ $payment->username }}</td>
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
