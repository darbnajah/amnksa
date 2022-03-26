
@extends('layouts.dashboard.print')

@section('content')
    <div id="print_wrap">
        <a href="#" id="print_btn" type="button" onclick="print()">طباعة</a>
        <!--<label class="btn btn-default" id="print_signature_wrap">
            <input type="checkbox" id="print_signature" onclick="toggleBtnPrint(this)" checked style="height: 20px; width: 20px"><span>التواقيع</span>
        </label>
        -->
        <div class="print_wrapper a4_printer">
            <div class="header">
                <img src="{{ asset('storage/'.$paper->header_img) }}" class="header_img">
            </div>
            <div class="print_content invoice">
                <table width="100%" class="" cellspacing="0">
                    <tr>
                        <td width="30%" class="has_border">
                            <table width="100%" class="header_table" cellspacing="0">
                                <tr>
                                    <td width="60%">
                                        <p>رقــم الفاتــــــورة</p>
                                        <p>Invoice number</p>
                                    </td>
                                    <td>{{ $invoice->invoice_number }}</td>
                                </tr>
                            </table>
                        </td>
                        <td></td>
                        <td width="20%" class="has_border">
                            <table width="100%" class="header_table" cellspacing="0">
                                <tr>
                                    <td width="50%">
                                        <p>التاريخ</p>
                                        <p>date</p>
                                    </td>
                                    <td>{{ \App\Helper\Helper::dtFr($invoice->dt) }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <table width="100%" class="" cellspacing="0">
                    <tr>
                        <td class="header_info">
                            <p><span>عن تقديم الحراسات الأمنية لشهر </span><span>{{ \App\Helper\Helper::monthNameAr($invoice->month_id) }} {{ \App\Helper\Helper::year($invoice->dt) }}</span></p>
                            <p>{{ ($bank)? $bank->company_name_at_bank : $company->company_name_ar }}</p>
                            <p><span>الرقم الضريبي</span>&nbsp;<span>{{ $bank->vat_number }}</span></p>
                            <p>السادة: <span class="underline">{{ $invoice->name_ar }}</span></p>
                            @if($invoice->customer_vat)
                                <p><span>الرقم الضريبي</span>&nbsp;<span>{{ $invoice->customer_vat }}</span></p>
                            @else
                                <p><br></p>
                            @endif
                            <p>الموضوع / خدمات الحراسات الأمنية عن الفترة:</p>
                            <p>
                                <span>من تاريخ</span>&nbsp;<span>{{ \App\Helper\Helper::dtFr($invoice->dt_from) }}</span>
                                <span>إلى تاريخ</span>&nbsp;<span>{{ \App\Helper\Helper::dtFr($invoice->dt_to) }}</span>
                            </p>
                            <p>وذلك وفقا للجدول التالي:</p>
                        </td>
                        <td class="header_info text-left" dir="ltr">
                            @if($invoice->name_en)
                                <p><span>The bill for security guard services includes </span><span>{{ \App\Helper\Helper::monthNameEn($invoice->month_id) }}</span></p>
                                <p>{{ ($bank)? $bank->company_name_at_bank_en : $company->company_name_ar }}</p>
                                <p><span>VAT Number </span>&nbsp;<span>{{ $bank->vat_number }}</span></p>
                                <p>Gentlemen : <span class="underline">{{ $invoice->name_en }}</span></p>
                                @if($invoice->customer_vat)
                                    <p><span>VAT Number </span>&nbsp;<span>{{ $invoice->customer_vat }}</span></p>
                                @else
                                    <p><br></p>
                                @endif
                                <p>Subject / Monthly Invoice for security guards: </p>
                                <p>
                                    <span>From date </span>&nbsp;<span>{{ \App\Helper\Helper::dtFr($invoice->dt_from) }}</span>
                                    <span>To date </span>&nbsp;<span>{{ \App\Helper\Helper::dtFr($invoice->dt_to) }}</span>
                                </p>
                                <p>According to the following table : </p>
                            @endif
                        </td>
                    </tr>
                </table>
                <table width="100%" class="a4_table" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="40%">
                                <p>البيان</p>
                                <p>The Description</p>
                            </th>
                            <th>
                                <p>الـكـمـيـة</p>
                                <p>Quantity</p>
                            </th>
                            <th>
                                <p>القيمة الفردية الشهرية</p>
                                <p>Monthly individual value</p>
                            </th>
                            <th>
                                <p>عدد الأيام</p>
                                <p>Total days</p>
                            </th>
                            <th>
                                <p>إجمالي القيمة الشهرية</p>
                                <p>Total value of the month</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($bulletins as $bulletin):

                        if($bulletin->nb_days <  $invoice->month_days || $bulletin->nb_days > $invoice->month_days) {
                            $realMonthDays = 30;
                        } else {
                            $realMonthDays =  $invoice->month_days;
                        }

                        $total = ($bulletin->cost * $bulletin->nb / $realMonthDays) * $bulletin->nb_days;



                        //$total = ($bulletin->cost * $bulletin->nb / $invoice->nb_days) * $bulletin->nb_days;
                        ?>
                            <tr>
                                <td>{{ $bulletin->label }}</td>
                                <td>{{ $bulletin->nb }}</td>
                                <td class="currency">{{ \App\Helper\Helper::nFormat($bulletin->cost) }}</td>
                                <td>{{ $bulletin->nb_days }}</td>
                                <td class="currency">{{ \App\Helper\Helper::nFormat($total) }}</td>
                            </tr>
                        <?php
                        endforeach;
                        $min_per_page = 7;
                        $arts_count = count($bulletins);
                        //$loop_count = ($arts_count > $min_per_page)? $arts_count - $min_per_page : $min_per_page - $arts_count;
                        $loop_count = ($arts_count > $min_per_page)? 0 : $min_per_page - $arts_count;

                        for($i = 0; $i < $loop_count; $i++){ ?>
                        <tr>
                            <td><br></td>
                            <td></td>
                            <td class="currency"></td>
                            <td></td>
                            <td class="currency"></td>
                        </tr>
                        <?php } ?>


                    </tbody>
                    <tfoot>
                        @if($invoice->discount_value > 0)
                        <tr>
                            <th colspan="4">
                                <span>{{ $invoice->discount_subject }}</span>
                            </th>
                            <th class="currency">{{ \App\Helper\Helper::nFormat($invoice->discount_value) }}</th>
                        </tr>
                        @endif
                        <tr>
                            <th colspan="4">
                                <span>الإجمالي</span>
                                <span>&nbsp;</span>
                                <span>Total</span>
                            </th>
                            <th class="currency">{{ \App\Helper\Helper::nFormat($invoice->ht) }}</th>
                        </tr>
                        @if($invoice->vat > 0)
                        <tr>
                            <th colspan="4">
                                <span>قيمة الضريبة المضافة</span>
                                <span>&nbsp;</span>
                                <span>{{ $invoice->vat }}</span>
                                <span>&nbsp;</span>
                                <span dir="ltr">VAT %</span>
                            </th>
                            <th class="currency">{{ \App\Helper\Helper::nFormat($invoice->total_vat) }}</th>
                        </tr>
                        @endif
                        <tr>
                            <th colspan="4">
                                <p>الصافي شامل الضريبة المضافة </p>
                                <p>
                                    <span>فقط وقدره</span>
                                    <span>{{ \App\Helper\Helper::nAlphaAr($invoice->ttc) }}</span>
                                    <span>لا غير</span>
                                </p>
                            </th>
                            <th class="currency">{{ \App\Helper\Helper::nFormat($invoice->ttc) }}</th>
                        </tr>
                    </tfoot>
                </table>

                <table width="100%" class="foot_table" cellspacing="0">
                <tr>
                    <td colspan="4">
                       <p>
                           <span>نرجو منكم صرف شيك بإسم: </span>
                           <span class="underline">{{ ($bank)? $bank->company_name_at_bank : $company->company_name_ar }}</span>
                       </p>
                       <p>
                           <span>أو إيداع المبلغ على رقم حساب الشركة لدى </span> &nbsp; &nbsp; &nbsp;
                           <span class="underline">{{ ($bank)? $bank->bank_name : null }}</span>&nbsp; &nbsp; &nbsp;
                           <span>ايبان</span>&nbsp;
                           <span>{{ ($bank)? $bank->iban : null }}</span>
                       </p>
                    </td>
                </tr>
                <tr>
                    <td class="underline text-center">
                        @if($company->sign_accountant_label)
                            {{ $company->sign_accountant_label }}
                        @endif
                    </td>
                    <td class="underline text-center">
                        @if($company->sign_operational_director_label)
                            {{ $company->sign_operational_director_label }}
                        @endif
                    </td>
                    <td class="underline text-center">
                        @if($company->sign_financial_director_label)
                            {{ $company->sign_financial_director_label }}
                        @endif
                    </td>
                    <td rowspan="2" class="text-center text-center" style="vertical-align: top">
                        @if($cachet && $company->cachet)
                            <img src="{{ asset('storage/'.$company->cachet) }}" class="cachet img-responsive thumb_preview">
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="text-center sinature_wrap">
                        @if($signature && $company->sign_accountant)
                            <img src="{{ asset('storage/'.$company->sign_accountant) }}" class="sinature img-responsive thumb_preview">
                        @endif
                    </td>
                    <td class="text-center sinature_wrap">
                        @if($signature && $company->sign_operational_director)
                            <img src="{{ asset('storage/'.$company->sign_operational_director) }}" class="sinature img-responsive thumb_preview">
                        @endif
                    </td>
                    <td class="text-center sinature_wrap">
                        @if($signature && $company->sign_financial_director)
                            <img src="{{ asset('storage/'.$company->sign_financial_director) }}" class="sinature img-responsive thumb_preview">
                        @endif
                    </td>
                </tr>
            </table>
                <p>
                    <br>
                </p>
            </div>
            <div class="footer">
                <img src="{{ asset('storage/'.$paper->footer_img) }}" class="footer_img">
            </div>
        </div>
    </div>
@endsection

