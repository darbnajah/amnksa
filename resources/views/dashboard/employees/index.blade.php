@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> الموظفين</h1>
                </div>
                <div class="col-md-6 text-left">
                    @if(!Session::has('expiration_dt') && auth()->user()->can('employees_create'))
                    <a href="{{ route('dashboard.employees.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> @lang('site.create')</a>
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
                    @if($employees->count() > 0)
                        <div class="table-responsive">
                            <table id="example" class="table table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th>الإسم الموظف</th>
                                <th>الوظيفة</th>
                                <th>موقع العمل</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($employees as $employee)
                            <tr>
                                <td class="table_actions">
                                    @if(auth()->user()->can('employees_update'))
                                    <a href="{{ route('dashboard.employees.edit', $employee->id) }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                                    @endif
                                    @if(auth()->user()->can('employees_delete'))
                                        <button type="button" onclick="deleteDefault('{{ route('dashboard.employees.delete', $employee->id) }}')" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button>
                                    @endif
                                </td>
                                <td>{{ $employee->employee_name }}</td>
                                <td>{{ $employee->job_name }}</td>
                                <td>{{ $employee->work_zone }}</td>
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
