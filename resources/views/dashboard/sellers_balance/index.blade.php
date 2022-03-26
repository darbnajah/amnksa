<?php
$debit_count = 0;
$credit_count = 0;
$debit_total = 0;
$credit_total = 0;
?>
@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <input type="hidden" id="doc_type" value="sellers_balance">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> كشف حساب مندوبي التسويق</h1>
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
            <input type="hidden" id="route_url" class="form-control" value="{{ url('/dashboard/sellers_balance/') }}">
            <input type="hidden" id="preview_url" class="form-control" value="{{ $preview_url }}">
            <div class="box box-primary">
                <div class="box-body with-border">
                    <div class="row">
                        <div class="col-sm-7 search_inline">
                            <label>مندوب التسويق</label>
                            <input type="hidden" id="seller_id" value="{{ $seller_id }}" readonly>
                            <input type="text" id="seller_name" value="{{ $seller_name }}" readonly>
                            <button type="button" class="btn btn-default" onclick="modalSellers('{{ route('dashboard.sellers.modal', 'sellers_balance') }}')"><i class="fa fa-search"></i></button>

                        </div>
                        <div class="col-sm-5 search_inline">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>من تاريخ</label>
                                    <input type="date" name="dt_from" id="dt_from" class=""  value="{{ $dt_from }}" onchange="goToSellerBalance()">
                                </div>
                                <div class="col-sm-6">
                                <label>الى تاريخ</label>
                                <input type="date" name="dt_to" id="dt_to" class=""  value="{{ $dt_to }}" onchange="goToSellerBalance()">
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
                                    <th>تاريخ العملية</th>
                                    <th>البيان</th>
                                    <th>رصيد دائن</th>
                                    <th>رصيد مدين</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($balances as $balance):
                                $label = json_decode($balance->label);
                                if($balance->doc_type == 'payment_valid') {
                                    $debit_count++;
                                    $debit_total += $balance->debit;
                                    $contract = isset($label->contract_obj)? json_decode($label->contract_obj) : null;

                                ?>
                                <tr>
                                    <td>{{ $balance->dt }}</td>
                                    <td>
                                        <span>سداد مستحق عن شهر </span>
                                        <span>{{ \App\Helper\Helper::monthNameAr($label->month_id) }}</span>
                                        @if($contract)
                                            - <span>العميل: {{ $contract->customer_name }}</span>
                                            - <span>المدينة: {{ $contract->city }}</span>
                                        @endif
                                    </td>
                                    <td></td>
                                    <td class="currency">{{ \App\Helper\Helper::nFormat($balance->debit) }}</td>
                                </tr>

                                <?php }
                                elseif($balance->doc_type == 'payment_instance') {
                                    $credit_count++;
                                    $credit_total += $balance->credit;
                                    $contract = isset($label->contract_obj)? json_decode($label->contract_obj) : null;
                                ?>
                                <tr>
                                    <td>{{ $balance->dt }}</td>
                                    <td>
                                        <span>مستحق عن شهر </span>
                                        <span>{{ \App\Helper\Helper::monthNameAr($label->month_id) }}</span>
                                        @if($contract)
                                         - <span>العميل: {{ $contract->customer_name }}</span>
                                         - <span>المدينة: {{ $contract->city }}</span>
                                        @endif
                                    </td>
                                    <td class="currency">{{ \App\Helper\Helper::nFormat($balance->credit) }}</td>
                                    <td></td>
                                </tr>
                                <?php }
                                elseif(
                                    $balance->doc_type == 'advance' ||
                                    $balance->doc_type == 'deduction' ||
                                    $balance->doc_type == 'given_advance' ||
                                    $balance->doc_type == 'given_deduction'
                                ) {
                               if($balance->debit > 0) {
                                   $debit_count++;
                                   $debit_total += $balance->debit;
                               } elseif($balance->credit > 0) {
                                   $credit_count++;
                                   $credit_total += $balance->credit;
                               }
                                    ?>
                                <tr>
                                    <td>{{ $balance->dt }}</td>
                                    <td>{{ $label->label }}</td>
                                    <td class="currency">{{ ($balance->credit > 0)? \App\Helper\Helper::nFormat($balance->credit)  : null }}</td>
                                    <td class="currency">{{ ($balance->debit > 0)? \App\Helper\Helper::nFormat($balance->debit)  : null }}</td>
                                </tr>
                                <?php
                                }
                                endforeach; ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="2" style="text-align: left"> مجموع الأرصدة:&nbsp;</th>
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

<div class="modal fade" id="modal_sellers" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-danger btn-sm btn-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">مندوبي التسويق</h4>
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
