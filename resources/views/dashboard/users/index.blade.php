@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> المستخدمين</h1>
                </div>
                <div class="col-md-6 text-left">
                    @if(!Session::has('expiration_dt') && auth()->user()->can('users_create'))
                    <a href="{{ route('dashboard.users.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> @lang('site.create')</a>
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
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table id="example" class="table table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th>الإسم الأول</th>
                                <th>الإسم الأخير</th>
                                <th>الإيميل</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            @if($user->seller_id == 0)
                            <tr>
                                <td class="table_actions">
                                    <a href="{{ route('dashboard.users.show', $user->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>
                                    @if(auth()->user()->can('users_update'))
                                    <a href="{{ route('dashboard.users.edit', $user->id) }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                                    @endif
                                    @if(auth()->user()->can('users_delete') && $user->id > 2 && $user->id != auth()->user()->id)
                                        <form
                                        action="{{ route('dashboard.users.destroy', $user->id) }}"
                                        method="post" style="display: inline-block">
                                        {{ csrf_field() }}
                                        {{ method_field('delete') }}
                                        <button type="submit" class="delete btn btn-sm btn-danger"><i class="fa fa-times"></i></button>
                                    </form>
                                    @endif
                                </td>
                                <td>{{ $user->first_name }}</td>
                                <td>{{ $user->last_name }}</td>
                                <td>{{ $user->email }}</td>
                            </tr>
                            @endif
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
