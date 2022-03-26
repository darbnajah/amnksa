<?php
$payments_count = 0;
$payments_total = 0;
?>
@extends('layouts.dashboard.print')

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
                            <h3 class="text-center"><span>مدفوعات مندوبي التسويق</span></h3>
                        </td>
                    </tr>
                </table>
                <br>
                <table width="100%" class="a4_table table_balance" cellspacing="0">
                    <thead>
                    <tr>
                        <th>تاريخ العملية</th>
                        <th>الشهر</th>
                        <th>اسم المندوب</th>
                        <th>العميل</th>
                        <th>المدينة</th>
                        <th>الصافي</th>
                    </tr>
                    </thead>
                    <tbody>
                   <?php if($sellers_payments):
                        foreach($sellers_payments as $seller_payment):
                            $payments_count++;
                            $payments_total += \App\Helper\Helper::double($seller_payment->amount_net);
                            ?>
                            <tr>
                                <td>{{ $seller_payment->dt }}</td>
                                <td>{{ \App\Helper\Helper::monthNameAr($seller_payment->month_id) }}</td>
                                <td>{{ $seller_payment->seller_name }}</td>
                                <td>{{ $seller_payment->customer_name }}</td>
                                <td>{{ $seller_payment->city }}</td>
                                <td class="currency bold">{{ \App\Helper\Helper::nFormat($seller_payment->amount_net) }}</td>
                            </tr>
                        <?php endforeach;
                        endif
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="5" style="text-align: left"> المجموع <span> ( {{ $payments_count }} ) </span> :&nbsp;</th>
                        <th colspan="2" class="currency">{{ \App\Helper\Helper::nFormat($payments_total) }}</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
            @if($paper)
            <div class="footer">
            <img src="{{ asset('storage/'.$paper->footer_img) }}" class="footer_img">
        </div>
            @endif
        </div>
    </div>
@endsection

