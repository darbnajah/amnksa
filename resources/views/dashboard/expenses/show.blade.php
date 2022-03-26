@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> المصروفات</h1>
                </div>
                <div class="col-md-6 text-left">
                    <a href="{{ route('dashboard.expenses.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('site.back')</a>
                </div>
            </div>

        </section>
        <section class="content">
            <div class="box box-primary">

            </div>

            <div class="box box-primary">
                <div class="box-body row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>	الرقم المرجعي</label>
                            <input type="text" class="form-control" value="{{ $expense->id }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>	التاريخ</label>
                            <input type="text" class="form-control" value="{{ $expense->dt }}" readonly>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <span>المصروفات</span>

                            </div>
                            <div class="panel-body">
                                @if($expense_bulletins->count() > 0)

                                    <input type="text" name="search" class="form-control"  id="dt_search" placeholder="@lang('site.search')">
                                    <br>
                                    <div class="table-responsive">
                                        <table id="example" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>البيان</th>
                                            <th>المبلغ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($expense_bulletins as $expense_bulletin)
                                        <tr>
                                            <td>{{ $expense_bulletin->label }}</td>
                                            <td class="currency bold">{{ \App\Helper\Helper::nFormat($expense_bulletin->amount) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                            <tr>
                                                <th class="text-left">الإجمالي: </th>
                                                <th class="currency">
                                                    <label class="label label-default" style="font-size: 18px">{{ \App\Helper\Helper::nFormat($expense->total) }}</label>
                                                </th>
                                            </tr>
                                            </tfoot>

                                        </table>
                                    </div>
                                @else
                                    <h2>@lang('site.no_data_found')</h2>
                                    </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </section>
    </div>

@endsection
