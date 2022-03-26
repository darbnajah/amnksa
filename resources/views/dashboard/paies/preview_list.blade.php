<?php
    $paies_count = 0;
    $paies_total = 0;
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
                            <h3 class="text-center"><b>الرواتب</b></h3>
                        </td>
                    </tr>
                </table>
                <br>
                <table width="100%" class="a4_table table_balance" cellspacing="0">
                    <thead>
                    <tr>
                        <th>اسم الموظف</th>
                        <th>موقع العمل</th>
                        <th>الراتب</th>
                        <th>الدوام الفعلي</th>
                        <th>الخصم</th>
                        <th>السلفة</th>
                        <th>الاضافي</th>
                        <th>الصافي</th>
                    </tr>
                    </thead>
                    <tbody>
                   <?php if($paies):
                        foreach($paies as $paie):
                            $paies_count++;
                            $paies_total += $paie->salary_net;
                            ?>
                           <tr>
                               <td>{{ $paie->employee_name }}</td>
                               <td>{{ $paie->work_zone }}</td>
                               <td class="currency">{{ \App\Helper\Helper::nFormat($paie->salary) }}</td>
                               <td class="currency">{{ $paie->nb_days }}</td>
                               <td class="currency td_deduction">{{ \App\Helper\Helper::nFormat($paie->deduction) }}</td>
                               <td class="currency td_advance">{{ \App\Helper\Helper::nFormat($paie->advance) }}</td>
                               <td class="currency">{{ \App\Helper\Helper::nFormat($paie->extra) }}</td>
                               <td class="currency bold td_net">{{ \App\Helper\Helper::nFormat($paie->salary_net) }}</td>
                           </tr>
                        <?php
                        endforeach;
                        endif
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="6" style="text-align: left"> المجموع <span> ( {{ $paies_count }} ) </span> :&nbsp;</th>
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

