@extends('layouts.dashboard.print')

@section('content')
    <div id="print_wrap">
        <a href="#" id="print_btn" type="button" onclick="print()">طباعة</a>

        <div class="print_wrapper a4_printer">
            <div class="header">
                <img src="{{ asset('storage/'.$paper->header_img) }}" class="header_img">
            </div>
            <div class="print_content invoice">
                <table width="100%" class="" cellspacing="0">
                    <tr>
                        <td width="40%" class="text-right">
                            <p><span>التاريخ: </span><span>{{ $price_offer->accept_dt }}</span></p>
                        </td>
                        <td width="60%" class="text-right">
                            <h3 style="text-decoration: underline"><span>الموضوع: </span><span>عرض سعر</span></h3>
                        </td>
                    </tr>
                </table>
                <table width="100%" class="" cellspacing="0">
                    <tr>
                        <td width="50%" class="text-right">
                            <p><span>السادة: </span><span style="text-decoration: underline">{{ $price_offer->customer_name }}</span></p>
                        </td>
                        <td width="50%" class="text-right">

                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-right">
                            <p style="line-height: 30px"><?= nl2br($price_offer_model->model_text) ?></p>
                        </td>
                    </tr>
                </table>
                <table width="100%" class="a4_table price_offer_bulletins_table" cellspacing="0">
                    <thead>
                        <tr>
                            <th>م</th>
                            <th width="40%">
                                <p>البيان</p>
                                <p>The Description</p>
                            </th>
                            <th>
                                <p>ساعات العمل</p>
                                <p>Work hours</p>
                            </th>
                            <th>
                                <p>التكلفة الشهرية</p>
                                <p>Monthly individual value</p>
                            </th>
                            <th>
                                <p>العدد</p>
                                <p>Quantity</p>
                            </th>
                            <th>
                                <p>الإجمالي</p>
                                <p>Total</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($bulletins as $index => $bulletin):
                            $total = $bulletin->cost * $bulletin->nb;
                        ?>
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $bulletin->label }}</td>
                                <td>{{ $bulletin->nb_hours }}</td>
                                <td class="currency">{{ $bulletin->cost }}</td>
                                <td>{{ $bulletin->nb }}</td>
                                <td class="currency">{{ \App\Helper\Helper::nFormat($total) }}</td>
                            </tr>
                        <?php
                        endforeach;
                        $min_per_page = 1;
                        $arts_count = count($bulletins);
                        //$loop_count = ($arts_count > $min_per_page)? $arts_count - $min_per_page : $min_per_page - $arts_count;
                        $loop_count = ($arts_count > $min_per_page)? 0 : $min_per_page - $arts_count;

                        for($i = 0; $i < $loop_count; $i++){ ?>
                        <tr>
                            <td><br></td>
                            <td></td>
                            <td class="currency"></td>
                            <td></td>
                            <td></td>
                            <td class="currency"></td>
                        </tr>
                        <?php } ?>


                    </tbody>
                </table>
                <table width="100%" class="foot_table" cellspacing="0">
                <tr>
                    <td style="padding-right: 80px" colspan="3">
                        @if($price_offer->commercial_mobile_1 != '' || $price_offer->commercial_mobile_2 != '')
                        <p style="line-height: 20px">
                           <span>في حال وجود إي استفسار يرجى الإتصال على </span>
                            @if($price_offer->commercial_mobile_1)
                            <span>{{ $price_offer->commercial_mobile_1 }}</span>
                            @endif
                            @if($price_offer->commercial_mobile_1 && $price_offer->commercial_mobile_2)
                                <span>&nbsp; / &nbsp; أو</span>
                            @endif
                           @if($price_offer->commercial_mobile_2)
                           <span>{{ $price_offer->commercial_mobile_2 }}</span>
                           @endif
                       </p>
                        @endif

                    </td>
                </tr>
                <tr>
                    <td class="text-center" colspan="3">

                        <p style="line-height: 20px" class="text-center">شاكرين ومقدرين حسن تعاونكم،،،</p>
                    </td>
                </tr>
                <tr>
                    <td class="underline"></td>
                    <td class="underline"></td>
                    <td class="underline text-center" width="30%">
                        @if($company->sign_price_offer_label)
                            {{ $company->sign_price_offer_label }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td class="text-center" style="vertical-align: top">
                        @if($cachet && $company->cachet)
                            <img src="{{ asset('storage/'.$company->cachet) }}" class="cachet img-responsive thumb_preview">
                        @endif
                    </td>
                    <td class="text-center sinature_wrap">
                        @if($signature && $company->sign_price_offer)
                            <img src="{{ asset('storage/'.$company->sign_price_offer) }}" class="sinature img-responsive thumb_preview">
                        @endif
                    </td>
                </tr>
            </table>
                <p>
                    <br>
                    <br>
                </p>
            </div>
            <div class="footer">
                <img src="{{ asset('storage/'.$paper->footer_img) }}" class="footer_img">
            </div>
        </div>
    </div>
@endsection

