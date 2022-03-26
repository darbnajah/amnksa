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
                            <h3 class="text-center"><span>تاريخ المسير: </span><span>{{ $paie_dt }}</span></h3>
                        </td>
                    </tr>
                </table>
                <br>
                <table width="100%" class="a4_table table_balance" cellspacing="0">
                    <thead>
                    <tr>
                        <th>اسم الموظف</th>
                        <th>اسم مالك الحساب</th>
                        <th>اسم البنك</th>
                        <th>رقم الحساب</th>
                        <th>ايبان</th>
                        <th>صافي الراتب</th>
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
                               <td>{{ $paie->bank_account_name }}</td>
                               <td>{{ $paie->bank_name }}</td>
                               <td>{{ $paie->bank_account }}</td>
                               <td>{{ $paie->bank_iban }}</td>
                               <td class="currency bold">{{ \App\Helper\Helper::nFormat($paie->salary_net) }}</td
                           </tr>
                        <?php endforeach;
                        endif
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="5" style="text-align: left"> المجموع <span> ( {{ $paies_count }} ) </span> :&nbsp;</th>
                        <th class="currency">{{ \App\Helper\Helper::nFormat($paies_total) }}</th>
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

