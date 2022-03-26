@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> مندوبي التسويق</h1>
                </div>
                <div class="col-md-6 text-left">
                    @if(!Session::has('expiration_dt') && auth()->user()->can('sellers_create'))
                    <a href="{{ route('dashboard.sellers.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> @lang('site.create')</a>
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
                    @if($sellers->count() > 0)
                        <div class="table-responsive">
                            <table id="example" class="table table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th>اسم المندوب</th>
                                <th>رقم الجوال</th>
                                <th>الإيميل</th>
                                <th>المستخدم</th>

                            </tr>
                        </thead>
                        <tbody>
                        @foreach($sellers as $seller)
                            <tr>
                                <td class="table_actions">
                                    <a href="{{ route('dashboard.sellers.show', $seller->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>
                                    @if(auth()->user()->can('sellers_update'))
                                    <a href="{{ route('dashboard.sellers.edit', $seller->id) }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                                    @endif
                                    @if(auth()->user()->can('sellers_delete'))
                                    <button type="button" onclick="deleteDefault('{{ route('dashboard.sellers.delete', $seller->id) }}')" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button>
                                    @endif
                                </td>
                                <td>{{ $seller->first_name }}</td>
                                <td>{{ $seller->mobile_1 }}</td>
                                <td>{{ $seller->email }}</td>
                                <td>{{ $seller->username }}</td>
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
