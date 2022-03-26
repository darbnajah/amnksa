<?php
$debit_count = 0;
$credit_count = 0;
$debit_total = 0;
$credit_total = 0;
?>
@extends('layouts.dashboard.print_lg')

@section('content')
    <div id="print_wrap">
        <a href="#" id="print_btn" type="button" onclick="print()">طباعة</a>

        <div class="print_wrapper a4_printer ">
            @if($paper)
            <div class="header">
                <img src="{{ asset('storage/'.$paper->header_img) }}" class="header_img">
            </div>
            @endif
            <div class="print_content ">
                <br>
                <table width="100%" class="" cellspacing="0">
                    <tr>
                        <td width="50%" class="td_title">
                            <h3 class="text-center"><span>كشف حساب : </span><span>{{ $supplier_name }}</span></h3>
                        </td>
                    </tr>
                </table>
                <br>
                <table width="100%" class="a4_table table_balance" cellspacing="0">
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
                    $credit_count++;
                    $credit_total += $balance->credit;
                    $contract = isset($label->contract_obj)? json_decode($label->contract_obj) : null;
                    ?>
                    <tr>
                        <td>{{ $balance->dt }}</td>
                        <td class="text-right">{{ $label }}</td>
                        <td class="currency">{{ \App\Helper\Helper::nFormat($balance->credit) }}</td>
                        <td></td>
                    </tr>
                    <?php }
                    elseif($balance->doc_type == 'payment_instance') {
                    $debit_count++;
                    $debit_total += $balance->debit;
                    $contract = isset($label->contract_obj)? json_decode($label->contract_obj) : null;
                    ?>
                    <tr>
                        <td>{{ $balance->dt }}</td>
                        <td class="text-right">
                            <span>مستحق عن شهر </span>
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
                @if($debit_total > $credit_total)
                    <br>
                    <table>
                        <tr>
                        <td>المتبقي في ذمة </td>
                        <td class="bold">{{ $supplier_name }}</td>
                        <td class="currency bold">{{ \App\Helper\Helper::nFormat($debit_total - $credit_total) }}</td>
                        </tr>
                    </table>
                @elseif($debit_total < $credit_total)
                    <br>
                    <table>
                        <tr>
                    <td> المتبقي لـ</td>
                    <td class="bold">{{ $supplier_name }}</td>
                    <td> </td>
                    <td class="currency bold">{{ \App\Helper\Helper::nFormat($credit_total - $debit_total) }}</td>
                        </tr>
                    </table>
                @else
                    <p class="text-center">لا يوجد مستحقات متعلقة.</p>
                @endif
            </div>
            @if($paper)
                <div class="footer">
                    <img src="{{ asset('storage/'.$paper->footer_img) }}" class="footer_img">
                </div>
            @endif
        </div>
    </div>
@endsection

