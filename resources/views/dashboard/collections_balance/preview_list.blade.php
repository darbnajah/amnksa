<?php
$total = 0;
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
                            <h3 class="text-center"><span>كشف حساب التحصيل </span></h3>
                        </td>
                    </tr>
                </table>
                <br>
                @if($collections)
                    <table width="100%" class="a4_table table_balance" cellspacing="0">
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

