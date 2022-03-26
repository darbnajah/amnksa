@extends('layouts.dashboard.app')

@section('content')

    @permission('users_create')
        <!--  -->
    @endpermission

    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> الوظائف</h1>
                </div>
                <div class="col-md-6 text-left">
                    @if(!Session::has('expiration_dt'))
                    <a href="{{ route('dashboard.roles.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> @lang('site.create')</a>
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
                    @if($roles->count() > 0)
                        <div class="table-responsive">
                            <table id="example" class="table table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th>كود الوظيفة</th>
                                <th>اسم الوظيفة</th>
                                <th>ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td class="table_actions">
                                    <a href="{{ route('dashboard.roles.edit', $role->id) }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                                    <form
                                        action="{{ route('dashboard.roles.destroy', $role->id) }}"
                                        method="post" style="display: inline-block">
                                        {{ csrf_field() }}
                                        {{ method_field('delete') }}
                                        <button type="submit" class="delete btn btn-sm btn-danger"><i class="fa fa-times"></i></button>

                                    </form>
                                </td>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->display_name }}</td>
                                <td>{{ $role->discription }}</td>
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
