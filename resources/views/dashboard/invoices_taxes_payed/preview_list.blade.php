<?php
    $total_invoices = 0;
    $total_vat = 0;
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
                        <td width="100%" class="td_title">
                            <h3 class="text-center"><span>الضرائب المسددة</span></h3>
                        </td>
                    </tr>
                </table>
                <br>
                <table width="100%" class="a4_table table_balance" cellspacing="0">
                    <thead>
                    <tr>
                        <th>الرقم</th>
                        <th>التاريخ</th>
                        <th>الشهر</th>
                        <th>العميل</th>
                        <th>الإجمالي</th>
                        <th>الضريبة</th>
                        <th>مبلغ الضريبة</th>
                        <th>إيصال السداد</th>
                        <th>تاريخ السداد</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($invoices as $invoice):
                        $ttc = \App\Helper\Helper::double($invoice->ttc);
                        $vat = \App\Helper\Helper::double($invoice->total_vat);
                        $total_invoices += $ttc;
                        $total_vat += $vat;
                        ?>
                        <tr invoice_id="{{ $invoice->id }}">
                            <td class="currency">{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->dt }}</td>
                            <td>{{ $invoice->month_id }}</td>
                            <td>{{ $invoice->name_ar }}</td>
                            <td class="currency">{{ $invoice->ttc }}</td>
                            <td><b>{{ $invoice->vat }} %</b></td>
                            <td class="currency"><b>{{ $invoice->total_vat }}</b></td>
                            <td>{{ $invoice->vat_pay_ref }}</td>
                            <td>{{ $invoice->vat_pay_dt }}</td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="4" style="text-align: right"> المجموع:&nbsp;</th>
                        <th class="currency">{{ \App\Helper\Helper::nFormat($total_invoices) }}</th>
                        <th></th>
                        <th class="currency">{{ \App\Helper\Helper::nFormat($total_vat) }}</th>
                        <th colspan="3"></th>

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

