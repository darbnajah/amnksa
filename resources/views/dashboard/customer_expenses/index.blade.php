@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i>المصروفات على العملاء</h1>
                </div>
                <div class="col-md-6 text-left">
                    <!--@i_f(!Session::has('expiration_dt') && auth()->user()->can('customers_create'))-->
                    <a href="{{ route('dashboard.customer_expenses.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> إضافة </a>
                    <!--@_endif-->
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
                                <th>الشهر</th>
                                <th>البيان</th>
                                <th>العميل</th>
                                <th>المبلغ</th>
                                <th>المستخدم</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($customer_expenses)
                                @foreach($customer_expenses as $customer_expense)
                                    <tr>
                                        <td class="text-center table_actions">
                                            <!--@_if(auth()->user()->can('customers_update'))-->
                                            <a href="{{ route('dashboard.customer_expenses.edit', $customer_expense->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                            <!--@e_ndif-->
                                            @if(auth()->user()->can('customers_delete'))
                                                <form
                                                action="{{ route('dashboard.customer_expenses.destroy', $customer_expense->id) }}"
                                                method="post" style="display: inline-block">
                                                {{ csrf_field() }}
                                                {{ method_field('delete') }}
                                                <button type="submit" class="delete btn btn-sm btn-danger"><i class="fa fa-times"></i></button>
                                            </form>
                                            @endif
                                        </td>
                                        <td>{{ $customer_expense->number }}</td>
                                        <td>{{ $customer_expense->dt }}</td>
                                        <td>{{ \App\Helper\Helper::monthNameAr($customer_expense->month_id) }}</td>
                                        <td>{{ $customer_expense->label }}</td>
                                        <td>{{ $customer_expense->customer_name }}</td>
                                        <td class="currency">{{ \App\Helper\Helper::nFormat($customer_expense->credit) }}</td>
                                        <td>{{ $customer_expense->username }}</td>
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
