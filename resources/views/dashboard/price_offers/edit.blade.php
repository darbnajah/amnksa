@php
    $edit_mode = false;
    if(isset($price_offer)){
        $edit_mode = true;
    }
@endphp

@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> عروض السعر -
                        @if($edit_mode)
                            @lang('site.edit')
                        @else
                            @lang('site.create')
                        @endif
                    </h1>
                </div>
                <div class="col-md-6 text-left">
                    <a href="{{ route('dashboard.price_offers.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('site.back')</a>
                </div>
            </div>

        </section>


        <section class="content">
            <input type="hidden" id="route_url" class="form-control" value="{{ url('/dashboard/price_offers/new_price_offer') }}">

            <div class="box box-primary">
                <div class="box-body">
                    @include('partials._errors')
                    <form action="{{ ($edit_mode)? route('dashboard.price_offers.update', $price_offer->id) : route('dashboard.price_offers.store') }}" method="post" enctype="multipart/form-data" class="has_small_inputs" id="default_form">
                        {{ csrf_field() }}
                        {{ method_field(($edit_mode)? 'put' : 'post') }}
                        <input type="hidden" id="form_method" value="{{ ($edit_mode)? 'put' : 'post' }}">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-info">
                                    <div class="panel-heading">العميل</div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>اسم الشركة <span class="required_field_star">*</span></label>
                                                    <input type="text" name="customer_name" id="customer_name" class="form-control"  value="{{ ($edit_mode)? $price_offer->customer_name : old('customer_name') }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>المدينة <span class="required_field_star">*</span></label>
                                                    <input type="text" name="customer_city" id="customer_city" class="form-control currency"  value="{{ ($edit_mode)? $price_offer->customer_city : old('customer_city') }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>الهاتف الأرضي</label>
                                                    <input type="text" name="customer_tel" id="customer_tel" class="form-control currency"  value="{{ ($edit_mode)? $price_offer->customer_tel : old('customer_tel') }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>اسم الموظف المسؤول <span class="required_field_star">*</span></label>
                                                    <input type="text" name="customer_dealer" id="customer_dealer" class="form-control"  value="{{ ($edit_mode)? $price_offer->customer_dealer : old('customer_dealer') }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>رقم جوال الموظف المسؤول <span class="required_field_star">*</span></label>
                                                    <input type="text" name="customer_dealer_mobile" id="customer_dealer_mobile" class="form-control currency"  value="{{ ($edit_mode)? $price_offer->customer_dealer_mobile : old('customer_dealer_mobile') }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>إيميل الموظف المسؤول</label>
                                                    <input type="text" name="customer_dealer_email" id="customer_dealer_email" class="form-control currency"  value="{{ ($edit_mode)? $price_offer->customer_dealer_email : null }}">
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>العنوان <span class="required_field_star">*</span></label>
                                                    <input type="text" name="customer_address" id="customer_address" class="form-control currency"  value="{{ ($edit_mode)? $price_offer->customer_address : old('customer_address') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <span>البيانات</span>
                                        <button type="button" class="btn btn-success btn-sm" onclick="appendBulletinToPrice_offer()"><i class="fa fa-plus"></i> إضافة بيان </button>

                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="price_offer_bulletins_table">
                                                <thead>
                                                <tr>
                                                    <th></th>
                                                    <th width="40%">البيان</th>
                                                    <th>ساعات العمل</th>
                                                    <th>القيمة الشهرية</th>
                                                    <th>العدد</th>
                                                    <th width="20%">القيمة الإجمالية</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if($edit_mode && $bulletins)
                                                    @foreach($bulletins as $bulletin)
                                                        <tr row_id="">
                                                            <td class="table_actions">
                                                                <button type="button" class="btn_remove btn btn-danger" onclick="removePrice_offerRow(this)"><i class="fa fa-times"></i></button>
                                                            </td>
                                                            <td class="td_label">
                                                                <input type="text" class="form-control" value="{{ $bulletin->label }}">
                                                            </td>
                                                            <td class="td_nb_hours">
                                                                <input type="text" class="form-control" value="{{ $bulletin->nb_hours }}">
                                                            </td>
                                                            <td class="td_cost">
                                                                <input type="text" class="form-control currency" value="{{ $bulletin->cost }}" onkeyup="calcPrice_offerRow(this)">
                                                            </td>
                                                            <td class="td_nb">
                                                                <input type="text" class="form-control text-center" value="{{ $bulletin->nb }}" onkeyup="calcPrice_offerRow(this)">
                                                            </td>
                                                            <td class="td_total">
                                                                <input type="text" class="form-control currency" value="{{ \App\Helper\Helper::nFormat($bulletin->cost * $bulletin->nb)
    }}" readonly>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th colspan="5" class="text-left">الإجمالي: </th>
                                                    <th class="currency">
                                                        <input type="text" name="price_offer_total" id="price_offer_total" class="form-control currency" value="{{ ($edit_mode)? $price_offer->total : '0.00' }}" readonly>

                                                    </th>
                                                </tr>

                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                        </div>

                        <div class="col-sm-12">
                            <br>
                            <hr>
                            <div class="form-group text-center">
                                <button type="button" class="btn btn-primary btn-lg" onclick="savePrice_offer()"><i class="fa fa-send"></i> تقديم عرض السعر</button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </section>
    </div>

@endsection

<div class="modal fade" id="modal_customers" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-danger btn-sm btn-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">العملاء</h4>
            </div>
            <div class="modal-body" style="padding-bottom: 0">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
