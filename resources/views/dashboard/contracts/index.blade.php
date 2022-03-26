@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> العقود</h1>
                </div>
                <div class="col-md-6 text-left">
                    @if(!Session::has('expiration_dt'))
                    <a href="{{ route('dashboard.contracts.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> @lang('site.create')</a>
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
                    @if($contracts->count() > 0)
                        <div class="table-responsive">
                            <table id="example" class="table table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th>رقم العقد</th>
                                <th>اسم العميل</th>
                                <th>العنوان</th>
                                <th>تاريخ بداية العقد</th>
                                <th>تاريخ نهاية العقد</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($contracts as $contract)
                            <tr>
                                <td class="table_actions">
                                    <a href="{{ route('dashboard.contracts.edit', $contract->id) }}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                                    <form
                                        action="{{ route('dashboard.contracts.destroy', $contract->id) }}"
                                        method="post" style="display: inline-block">
                                        {{ csrf_field() }}
                                        {{ method_field('delete') }}
                                        <button type="submit" class="delete btn btn-sm btn-danger"><i class="fa fa-times"></i></button>
                                    </form>
                                </td>
                                <td>{{ $contract->code }}</td>
                                <td>{{ $contract->name_ar }}</td>
                                <td>{{ $contract->address }}</td>
                                <td>{{ $contract->dt_start }}</td>
                                <td>{{ $contract->dt_end }}</td>
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
