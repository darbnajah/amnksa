<?php ob_start(); ?>
<div class="price_offer_models_wrap">
    <label>صيغة عرض السعر</label>
    <select class="price_offer_models form-control">
        @foreach($price_offers_models as $price_offer_model)
            <option {{ ($price_offer_model->is_default)? 'selected' : null }} value="{{ $price_offer_model->id }}">{{ $price_offer_model->model_name }}</option>
        @endforeach
    </select>
</div>
<?php $price_offers_models = ob_get_clean() ?>

@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> عروض السعر</h1>
                </div>
                <div class="col-md-6 text-left">
                    <label class="btn btn-default print_signature_wrap">
                        <input type="checkbox" id="print_signature" onclick="toggleBtnPrint()"><span style="position:relative; top: -5px;">التواقيع</span>
                    </label>
                    <label class="btn btn-default print_signature_wrap">
                        <input type="checkbox" id="print_cachet" onclick="toggleBtnPrint()"><span style="position:relative; top: -5px;">الختم</span>
                    </label>
                    @if(!Session::has('expiration_dt') && auth()->user()->can('price_offers_create'))
                    <a href="{{ route('dashboard.price_offers.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> @lang('site.create')</a>
                    @endif
                </div>
            </div>

        </section>
        <section class="content">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <input type="text" name="search" class="form-control"  id="dt_search" placeholder="@lang('site.search')">
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-body">
                    @if($price_offers->count() > 0)
                        <div class="table-responsive">
                            <table id="example" class="table table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th>الرقم</th>
                                <th>التاريخ</th>
                                <th>العميل</th>
                                <th>المدينة</th>
                                <th>الإجمالي</th>
                                <th>المسوق</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($price_offers as $price_offer)
                            <tr>
                                <td class="table_actions">
                                    @if($price_offer->status == 1)
                                        <div class="btn-group print_group">
                                            <a href="{{ url()->to('dashboard/price_offers/preview/'.$price_offer->id.'/'.$paper_id) }}" prefix_href="{{ url()->to('dashboard/price_offers/preview/'.$price_offer->id.'/'.$paper_id) }}" class="btn btn-default print_btn" target="_blank"><i class="fa fa-print"></i></a>
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="caret"></span>
                                                <span class="sr-only">طباعة</span>
                                            </button>
                                            <ul class="dropdown-menu">

                                                @foreach($papers as $paper)
                                                    <li><a href="{{ url()->to('dashboard/price_offers/preview/'.$price_offer->id.'/'.$paper->id) }}" prefix_href="{{ url()->to('dashboard/price_offers/preview/'.$price_offer->id.'/'.$paper->id) }}" target="_blank" class="print_btn"><i class="fa fa-file-o"></i> {{ $paper->paper_name }}</a></li>
                                                @endforeach

                                            </ul>
                                        </div>
                                    @endif
                                        @if(auth()->user()->can('price_offers_update'))
                                        <a href="{{ route('dashboard.price_offers.edit', $price_offer->id) }}" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                        @endif
                                        @if(auth()->user()->can('price_offers_delete'))
                                        <form
                                        action="{{ route('dashboard.price_offers.destroy', $price_offer->id) }}"
                                        method="post" style="display: inline-block">
                                        {{ csrf_field() }}
                                        {{ method_field('delete') }}
                                        <button type="submit" class="delete btn btn-danger"><i class="fa fa-times"></i></button>
                                    </form>
                                    @endif
                                </td>
                                <td class="currency">{{ $price_offer->id }}</td>
                                <td>{{ $price_offer->accept_dt }}</td>
                                <td>{{ $price_offer->customer_name }}</td>
                                <td>{{ $price_offer->customer_city }}</td>
                                <td class="currency">{{ $price_offer->total }}</td>
                                <td>{{ $price_offer->commercial_first_name.' '.$price_offer->commercial_last_name }}</td>
                                <td style="border-right: 1px solid #ccc">
                                    <p>
                                        @if($price_offer->status == 1)
                                            <label class="label label-success">مقبول</label>
                                            <small>بتاريخ:<b>{{ $price_offer->accept_dt }}</b></small>
                                        @elseif($price_offer->status == -1)
                                            <label class="label label-warning">مرفوض</label>
                                            <small style="color: #f62e40"> {{ $price_offer->notes }}</small>
                                        @else
                                            <label class="label label-info"><i class="fa fa-clock-o"></i>  قيد المعالجة</label>
                                        @endif
                                    </p>
                                    @if(auth()->user()->can('price_offers_accept_deny_yes'))
                                    <div class="price_offer_accept_deny_btns_wrap">
                                        @if($price_offer->status == 0)
                                        <button type="button" class="btn_accept_price_offer_show btn btn-success" onclick="showAcceptPriceOfferWrap(this)"><i class="fa fa-check"></i> قبول</button>
                                        <button type="button" class="btn_deny_price_offer_show btn btn-warning" onclick="showDenyPriceOfferWrap(this)"><i class="fa fa-warning"></i> رفض</button>
                                        @else
                                            <button type="button" class="btn btn-danger" onclick="resetPriceOffer(this, {{ $price_offer->id }}, '{{ route('dashboard.price_offers.reset_price_offer', $price_offer->id) }}')"><i class="fa fa-refresh"></i> Reset</button>
                                        @endif

                                    </div>
                                    <div class="accept_price_offer_wrap">
                                        <?= $price_offers_models ?>
                                        <div class="form-group" style="margin-bottom: 5px">
                                            <label>تاريخ عرض السعر</label>
                                            <input type="date" class="form-control accept_dt" value="{{ ($price_offer->status == 1)? $price_offer->accept_dt : date('Y-m-d') }}">
                                        </div>
                                        <button type="button" class="btn_accept_price_offer btn btn-success" onclick="acceptPrice_offer(this, {{ $price_offer->id }}, '{{ route('dashboard.price_offers.accept_price_offer', $price_offer->id) }}')"><i class="fa fa-check"></i> قبول</button>
                                        <button type="button" class="btn btn-info" onclick="resetPriceOfferAcceptDeny(this)"><i class="fa fa-reply"></i> رجوع</button>

                                    </div>
                                    <div class="deny_price_offer_wrap">
                                        <input type="text" class="form-control notes" placeholder="ملاحظات" style="margin-bottom: 5px" value="{{ $price_offer->notes }}">
                                        <button type="button" class="btn_deny_price_offer btn btn-warning" onclick="denyPrice_offer(this, {{ $price_offer->id }}, '{{ route('dashboard.price_offers.deny_price_offer', $price_offer->id) }}')"><i class="fa fa-warning"></i> رفض</button>
                                        <button type="button" class="btn btn-info" onclick="resetPriceOfferAcceptDeny(this)"><i class="fa fa-reply"></i> رجوع</button>

                                    </div>
                                    @endif

                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                        </div>
                    @else
                        <h2>@lang('site.no_data_found')</h2>
                    @endif
                </div>
            </div>

        </section>
    </div>

@endsection
<script>
    import Label from "@/Jetstream/Label";
    export default {
        components: {Label}
    }
</script>
