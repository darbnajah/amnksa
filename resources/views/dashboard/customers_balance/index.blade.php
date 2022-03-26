<?php
$debit_count = 0;
$credit_count = 0;
$debit_total = 0;
$credit_total = 0;
?>
@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <input type="hidden" id="doc_type" value="customers_balance">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> كشف حساب العملاء</h1>
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
            <input type="hidden" id="route_url" class="form-control" value="{{ url('/dashboard/customers_balance/') }}">
            <input type="hidden" id="preview_url" class="form-control" value="{{ $preview_url }}">
            <div class="box box-primary">
                <div class="box-body with-border">
                    <div class="row">
                        <div class="col-sm-7 search_inline">
                            <label>العميل</label>
                            <input type="hidden" id="customer_id" value="{{ $customer_id }}" readonly>
                            <input type="text" id="customer_name" value="{{ $customer_name }}" readonly>
                            <button type="button" class="btn btn-default" onclick="modalCustomers('{{ route('dashboard.customers.modal', 'customer_balance') }}')"><i class="fa fa-search"></i></button>

                        </div>
                        <div class="col-sm-5 search_inline">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>من تاريخ</label>
                                    <input type="date" name="dt_from" id="dt_from" class=""  value="{{ $dt_from }}" onchange="goToBalance()">
                                </div>
                                <div class="col-sm-6">
                                <label>الى تاريخ</label>
                                <input type="date" name="dt_to" id="dt_to" class=""  value="{{ $dt_to }}" onchange="goToBalance()">
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-body">
                    @if($balances)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>رقم القيد</th>
                                    <th>تاريخ القيد</th>
                                    <th>العميل</th>
                                    <th>البيان</th>
                                    <th>رصيد دائن</th>
                                    <th>رصيد مدين</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($balances as $balance):
                                if($balance->doc_type == 'invoice') {
                                    $debit_count++;
                                    $debit_total += $balance->debit;
                                    ?>
                                <tr>
                                    <td class="text-center">{{ $balance->number }}</td>
                                    <td>{{ $balance->dt }}</td>
                                    <td>{{ $balance->customer_name }}</td>
                                    <td>
                                        <span>فاتورة عن شهر </span>
                                        <span>{{ \App\Helper\Helper::monthNameAr($balance->month_id) }}</span>
                                        <span> من </span>
                                        <span>{{ $balance->dt_from }}</span>
                                        <span> إلى </span>
                                        <span>{{ $balance->dt_to }}</span>
                                    </td>
                                    <td></td>
                                    <td class="currency">{{ \App\Helper\Helper::nFormat($balance->debit) }}</td>
                                </tr>

                                <?php } elseif($balance->doc_type == 'payment') {
                                    $credit_count++;
                                    $credit_total += $balance->credit;

                                ?>
                                <tr>
                                    <td class="text-center">{{ $balance->number }}</td>
                                    <td>{{ $balance->dt }}</td>
                                    <td>{{ $balance->customer_name }}</td>
                                    <td>
                                        <span>سداد مستحق</span> <span>{{ $balance->label }}</span>
                                    </td>
                                    <td class="currency">{{ \App\Helper\Helper::nFormat($balance->credit) }}</td>
                                    <td></td>
                                </tr>
                                <?php }
                                endforeach; ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="4" style="text-align: left"> مجموع الأرصدة:&nbsp;</th>
                                    <th class="currency">{{ \App\Helper\Helper::nFormat($credit_total) }}</th>
                                    <th class="currency">{{ \App\Helper\Helper::nFormat($debit_total) }}</th>
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

<div class="modal fade" id="modal_customers" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-danger btn-sm btn-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">العملاء</h4>
            </div>
            <div class="modal-body" style="padding-bottom: 0">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
<script>
    import Label from "@/Jetstream/Label";
    import Input from "@/Jetstream/Input";
    import Button from "@/Jetstream/Button";
    export default {
        components: {Button, Input, Label}
    }
</script>
