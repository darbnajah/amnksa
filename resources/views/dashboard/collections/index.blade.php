@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> التحصيل من الموردين</h1>
                </div>
                <div class="col-md-6 text-left">
                    @if(!Session::has('expiration_dt') && auth()->user()->can('collections_create'))
                    <a href="{{ route('dashboard.collections.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> @lang('site.create')</a>
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
                    @if($collections->count() > 0)
                        <div class="table-responsive">
                            <table id="example" class="table table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th>الرقم المرجعي</th>
                                <th>اسم المورد</th>
                                <th>التاريخ</th>
                                <th>الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($collections as $collection)
                            <tr>
                                <td class="table_actions">
                                    <a href="{{ route('dashboard.collections.show', $collection->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>
                                    @if(auth()->user()->can('collections_update'))
                                    <a href="{{ route('dashboard.collections.edit', $collection->id) }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                                    @endif
                                    @if(auth()->user()->can('collections_delete'))
                                    <form
                                        action="{{ route('dashboard.collections.destroy', $collection->id) }}"
                                        method="post" style="display: inline-block">
                                        {{ csrf_field() }}
                                        {{ method_field('delete') }}
                                        <button type="submit" class="delete btn btn-sm btn-danger"><i class="fa fa-times"></i></button>
                                    </form>
                                    @endif
                                </td>
                                <td>{{ $collection->id }}</td>
                                <td>{{ $collection->supplier_name }}</td>
                                <td>{{ $collection->dt }}</td>
                                <td class="currency bold">{{ \App\Helper\Helper::nFormat($collection->total) }}</td>
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
