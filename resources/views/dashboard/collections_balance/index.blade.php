<?php
$total = 0;
?>
@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> كشف حساب التحصيل</h1>
                </div>
                <div class="col-md-6 text-left">
                    <div class="btn-group">
                        <button onclick="previewSearch({{ $paper->id }})" class="btn btn-default" target="_blank"><i class="fa fa-print"></i> طباعة</button>
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="caret"></span>
                            <span class="sr-only">طباعة</span>
                        </button>
                        <ul class="dropdown-menu">
                            @foreach($papers as $paper)
                                <li><button class="btn btn-default" onclick="previewSearch({{ $paper->id }})" target="_blank"><i class="fa fa-file-o"></i> {{ $paper->paper_name }}</button></li>
                            @endforeach

                        </ul>
                    </div>

                </div>
            </div>

        </section>
        <section class="content">
            <input type="hidden" id="route_url" class="form-control" value="{{ url('/dashboard/collections_balance/') }}">
            <input type="hidden" id="preview_url" class="form-control" value="{{ $preview_url }}">
            <div class="box box-primary">
                <div class="box-body row">
                    <div class="col-sm-6 search_inline text-left">
                        <label>من تاريخ</label>
                        <input type="date" name="dt_from" id="dt_from" class=""  value="{{ $dt_from }}" onchange="goToPurchaseBalance()">
                    </div>
                    <div class="col-sm-6 search_inline">
                        <label>الى تاريخ</label>
                        <input type="date" name="dt_to" id="dt_to" class=""  value="{{ $dt_to }}" onchange="goToPurchaseBalance()">
                    </div>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-body">
                    @if($collections)
                        <div class="table-responsive">
                            <table id="example" class="table table-hover">
                                <thead>
                                <tr>
                                    <th>تاريخ العملية</th>
                                    <th>البيان</th>
                                    <th>المبلغ</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($collections as $collection){
                                    $total += $collection->amount;
                                    ?>
                                    <tr>
                                        <td>{{ $collection->dt }}</td>
                                        <td width="50%">{{ $collection->label }}</td>
                                        <td class="currency bold">{{ \App\Helper\Helper::nFormat($collection->amount) }}</td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="2" class="text-left">المجموع</th>
                                    <th class="currency bold">{{ \App\Helper\Helper::nFormat($total) }}</th>

                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </section>
    </div>

@endsection

