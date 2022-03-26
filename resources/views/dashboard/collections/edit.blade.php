@php
    $edit_mode = false;
    if(isset($collection_bulletins)){
        $edit_mode = true;
    }

@endphp

@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> التحصيل -
                        @if($edit_mode)
                            @lang('site.edit')
                        @else
                            @lang('site.create')
                        @endif
                    </h1>
                </div>
                <div class="col-md-6 text-left">
                    <a href="{{ route('dashboard.collections.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('site.back')</a>
                </div>
            </div>

        </section>

        <section class="content">


            @include('partials._errors')
            <form action="{{ ($edit_mode)? route('dashboard.collections.update', $collection->id) : route('dashboard.collections.store') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field(($edit_mode)? 'put' : 'post') }}
                <div class="box box-primary">
                    <div class="box-body row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>	الرقم المرجعي</label>
                                <input type="text" id="parent_id" name="parent_id" class="form-control" value="{{($edit_mode)? $collection->id : $collection_id }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>	التاريخ</label>
                                <input type="date" id="dt" name="dt" class="form-control" value="{{ ($edit_mode)? $collection->dt : null}}">
                            </div>
                            <div class="form-group">
                                <label>	اسم المورد</label>
                                <select id="supplier_id" name="supplier_id" class="form-control">
                                    <option value="" selected>اختر المورد</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ ($edit_mode && $collection->supplier_id == $supplier->id)? 'selected' : null }}>{{ $supplier->supplier_name }}</option>
                                    @endforeach
                                </select>
                            </div>


                        </div>
                        <div class="col-sm-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <span>التحصيل</span>
                                    <button type="button" class="btn btn-success btn-sm" onclick="appendRowCollection()"><i class="fa fa-plus"></i> إضافة بيان</button>

                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table id="bulletins" class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th width="60%">البيان</th>
                                                <th>القيمة</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if($edit_mode && $collection_bulletins->count() > 0)
                                                @foreach($collection_bulletins as $collection_bulletin)
                                                <tr row_id="{{ $collection_bulletin->id }}">
                                                    <td class="table_actions">
                                                        <button type="button" class="btn_remove btn btn-danger" onclick="deleteRow(this)"><i class="fa fa-times"></i></button>
                                                    </td>
                                                    <td class="td_label">
                                                        <input type="text" class="form-control" placeholder="البيان" value="{{ $collection_bulletin->label }}">
                                                    </td>
                                                    <td class="td_amount">
                                                        <input type="text" class="form-control currency" onkeyup="checkNumber(this);calcRowTotal(this)" placeholder="القيمة" value="{{ \App\Helper\Helper::double($collection_bulletin->amount) }}">
                                                    </td>

                                                </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th colspan="2" class="text-left">الإجمالي: </th>
                                                <th class="currency">
                                                    <label id="total_amount" class="label label-default" style="font-size: 18px">{{ ($edit_mode)? \App\Helper\Helper::nFormat($collection->total) : '0.00' }}</label>
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
                                    <button type="button" onclick="saveRowsCollections('{{ route('dashboard.collections.store') }}', 'post')" class="btn btn-primary btn-lg"><i class="fa fa-save"></i> @lang('site.save')</button>
                                    @else
                                        <button type="button" onclick="saveRowsCollections('{{ route('dashboard.collections.update', $collection->id) }}', 'put')" class="btn btn-warning btn-lg"><i class="fa fa-save"></i> @lang('site.save')</button>

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
