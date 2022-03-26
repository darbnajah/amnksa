@php
    $edit_mode = false;
    if(isset($expense_bulletins)){
        $edit_mode = true;
    }

@endphp

@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> المصروفات -
                        @if($edit_mode)
                            @lang('site.edit')
                        @else
                            @lang('site.create')
                        @endif
                    </h1>
                </div>
                <div class="col-md-6 text-left">
                    <a href="{{ route('dashboard.expenses.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('site.back')</a>
                </div>
            </div>

        </section>

        <section class="content">
            @include('partials._errors')
            <form action="{{ ($edit_mode)? route('dashboard.expenses.update', $expense->id) : route('dashboard.expenses.store') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field(($edit_mode)? 'put' : 'post') }}
                <div class="box box-primary">
                    <div class="box-body row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>	الرقم المرجعي</label>
                                <input type="text" id="parent_id" name="parent_id" class="form-control" value="{{($edit_mode)? $expense->id : $expense_id }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>	التاريخ</label>
                                <input type="date" id="dt" name="dt" class="form-control" value="{{ ($edit_mode)? $expense->dt : null}}">
                            </div>


                        </div>
                        <div class="col-sm-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <span>المصروفات</span>
                                    <button type="button" class="btn btn-success btn-sm" onclick="appendRow()"><i class="fa fa-plus"></i> إضافة بيان</button>

                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table id="bulletins" class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th width="70%">البيان</th>
                                                <th>القيمة</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if($edit_mode && $expense_bulletins->count() > 0)
                                                @foreach($expense_bulletins as $expense_bulletin)
                                                <tr row_id="{{ $expense_bulletin->id }}">
                                                    <td class="table_actions">
                                                        <button type="button" class="btn_remove btn btn-danger" onclick="deleteRow(this)"><i class="fa fa-times"></i></button>
                                                    </td>
                                                    <td class="td_label">
                                                        <input type="text" class="form-control" placeholder="البيان" value="{{ $expense_bulletin->label }}">
                                                    </td>
                                                    <td class="td_amount">
                                                        <input type="text" class="form-control currency" onkeyup="checkNumber(this);calcRowTotal(this)" placeholder="القيمة" value="{{ \App\Helper\Helper::double($expense_bulletin->amount) }}">
                                                    </td>

                                                </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th colspan="2" class="text-left">الإجمالي: </th>
                                                <th class="currency">
                                                    <label id="total_amount" class="label label-default" style="font-size: 18px">{{ ($edit_mode)? \App\Helper\Helper::nFormat($expense->total) : '0.00' }}</label>
                                                </th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group text-center">
                                    @if(!$edit_mode)
                                    <button type="button" onclick="saveRows('{{ route('dashboard.expenses.store') }}', 'post')" class="btn btn-primary btn-lg"><i class="fa fa-save"></i> @lang('site.save')</button>
                                    @else
                                        <button type="button" onclick="saveRows('{{ route('dashboard.expenses.update', $expense->id) }}', 'put')" class="btn btn-warning btn-lg"><i class="fa fa-save"></i> @lang('site.save')</button>

                                    @endif

                                </div>
                            </div>
                            <div class="col-sm-6"></div>

                        </div>


                    </div>
                </div>
            </form>
        </section>
    </div>

@endsection
