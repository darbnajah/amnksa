@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> العملاء</h1>
                </div>
                <div class="col-md-6 text-left">
                    @if(!Session::has('expiration_dt') && auth()->user()->can('customers_create'))
                    <a href="{{ route('dashboard.customers.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> @lang('site.create')</a>
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
                    @if($customers->count() > 0)
                        <div class="table-responsive">
                            <table id="example" class="table table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th>رقم العميل</th>
                                <th>اسم العميل</th>
                                <th>عنوان العميل</th>
                                <th>المدينة</th>
                                <th>رقم الجوال</th>
                                <th>اسم المدير المسؤول</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($customers as $customer)
                            <tr>
                                <td class="table_actions">
                                    <a href="{{ route('dashboard.customers.show', $customer->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-list-ul"></i> العقود</a>
                                @if(auth()->user()->can('customers_update'))
                                    <a href="{{ route('dashboard.customers.edit', $customer->id) }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                                @endif
                                @if(auth()->user()->can('customers_delete'))
                                    <button type="button" onclick="deleteDefault('{{ route('dashboard.customers.delete', $customer->id) }}')" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button>
                                @endif
                                </td>
                                <td>{{ $customer->code }}</td>
                                <td>{{ $customer->name_ar }}</td>
                                <td>{{ $customer->address_ar }}</td>
                                <td>{{ $customer->city }}</td>
                                <td>{{ $customer->mobile }}</td>
                                <td>{{ $customer->responsible }}</td>
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
