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
                            <h3 class="text-center"><span>كشف حساب العميل: </span><span>{{ $customer_name }}</span></h3>
                        </td>
                    </tr>
                </table>
                <br>
                    @if($balances)
                    <table width="100%" class="a4_table table_balance" cellspacing="0">
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
                        <td>{{ $balance->number }}</td>
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
                        <td>{{ $balance->number }}</td>
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
                        <th colspan="4" style="text-align: left"> مجموع الأرصدة:&nbsp;&nbsp;</th>
                        <th class="currency">{{ \App\Helper\Helper::nFormat($credit_total) }}</th>
                        <th class="currency">{{ \App\Helper\Helper::nFormat($debit_total) }}</th>
                    </tr>
                    <tr>
                        <th colspan="4" style="text-align: left">المتبقي: &nbsp;</th>
                        <th colspan="2" class="currency">{{ \App\Helper\Helper::nFormat($debit_total - $credit_total) }}</th>
                    </tr>
                    </tfoot>
                    </table>

                    <p><span>عدد الفواتير: </span><span>{{ $debit_count }}</span><br>
               <span>عدد المسدد: </span><span>{{ $credit_count }}</span></p>
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

