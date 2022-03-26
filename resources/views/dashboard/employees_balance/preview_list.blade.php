<?php
    $paies_count = 0;
    $paies_total = 0;
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
                            <h3 class="text-center"><span>كشف حساب الموظف: </span><span>{{ $employee_name }}</span></h3>
                        </td>
                    </tr>
                </table>
                <br>
                <table width="100%" class="a4_table table_balance" cellspacing="0">
                    <thead>
                    <tr>
                        <th>تاريخ المسير</th>
                        <th>الشهر</th>
                        <th>الوظيفة</th>
                        <th>موقع العمل</th>
                        <th>الصافي</th>
                        <th>الحالة</th>
                    </tr>
                    </thead>
                    <tbody>
                   <?php if($paies):
                        foreach($paies as $paie):
                            $paies_count++;
                            $paies_total += $paie->salary_net;
                            ?>
                            <tr>
                                <td>{{ $paie->paie_dt }}</td>
                                <td>{{ \App\Helper\Helper::monthNameAr($paie->month_id) }} {{ \App\Helper\Helper::year($paie->paie_dt) }}</td>
                                <td>{{ $paie->job_name }}</td>
                                <td>{{ $paie->work_zone }}</td>
                                <td class="currency bold">{{ \App\Helper\Helper::nFormat($paie->salary_net) }}</td>
                                <td>
                                    @if($paie->status == -1)
                                        <span> مرفوض</span>
                                        <span>بتاريخ: </span>
                                        <span>{{ App\Helper\Helper::dt($paie->updated_at) }}</span>
                                    @elseif($paie->status == 0)
                                        <span> قيد التعميد</span>
                                    @elseif($paie->status == 1 && $paie->trans_status == 0)
                                        <span>  تم التعميد</span>
                                        <span>بتاريخ: </span>
                                        <span>{{ App\Helper\Helper::dt($paie->accept_dt) }}</span>
                                    @elseif($paie->status == 1 && $paie->trans_status == 1)
                                        <span> تم الصرف</span>
                                        <span>بتاريخ: </span>
                                        <span>{{ App\Helper\Helper::dt($paie->trans_dt) }}</span>
                                    @endif
                                </td>
                            </tr>
                        <?php endforeach;
                        endif
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="4" style="text-align: left"> المجموع <span> ( {{ $paies_count }} ) </span> :&nbsp;</th>
                        <th colspan="2" class="currency">{{ \App\Helper\Helper::nFormat($paies_total) }}</th>
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

