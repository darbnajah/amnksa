@php
    $edit_mode = false;
    if(isset($payment)){
        $edit_mode = true;
    }
@endphp

@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> مستحقات مندوبي التسويق -
                        @if($edit_mode)
                            @lang('site.edit')
                        @else
                            @lang('site.create')
                        @endif
                    </h1>
                </div>
                <div class="col-md-6 text-left">
                    <a href="{{ route('dashboard.sellers_payments.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('site.back')</a>
                </div>
            </div>

        </section>


        <section class="content">
            <input type="hidden" class="form-control" id="da_totals_route" value="{{ route('dashboard.sellers_deductions_advances.total_rest_deductions_advances_by_seller') }}">

            <div class="box box-primary">
                <div class="box-body">
                    @include('partials._errors')
                    <form action="{{ ($edit_mode)? route('dashboard.sellers_payments.update', $payment->id) : route('dashboard.sellers_payments.store') }}" method="post" enctype="multipart/form-data" class="has_small_inputs" id="default_form">
                        {{ csrf_field() }}
                        {{ method_field(($edit_mode)? 'put' : 'post') }}
                        <input type="hidden" id="form_method" value="{{ ($edit_mode)? 'put' : 'post' }}">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <span>مندوب التسويق</span>
                                        <button type="button" class="btn btn-success" onclick="modalSellers('{{ route('dashboard.sellers.modal', 'payment') }}')"><i class="fa fa-search"></i></button>
                                    </div>
                                    <div class="panel-body" style="min-height: 200px;">
                                        <div class="form-group">
                                            <label>اسم المندوب</label>
                                            <input type="text" id="seller_name" class="form-control"  value="{{ ($edit_mode)? $payment->seller_name : null }}" readonly>
                                        </div>

                                        <input type="hidden" name="contract_id" id="contract_id" class="form-control" value="{{ ($edit_mode)? $payment->contract_id : null }}">
                                        <input type="hidden" name="seller_id" id="seller_id" class="form-control"  value="{{ ($edit_mode)? $payment->seller_id : null }}" readonly>
                                        <input type="hidden" value="{{ ($edit_mode)? $payment->contract_obj : null }}" name="contract_obj" id="contract_obj" class="form-control"  value="" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="panel panel-info">
                                    <div class="panel-heading">العقود</div>
                                    <div class="panel-body" style="padding: 0;min-height: 200px;">
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="contracts_table">
                                                <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>العميل</th>
                                                    <th>المدينة</th>
                                                    <th>نسبة المسوق</th>
                                                    <th>حالة العقد</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if($edit_mode)
                                                    <tr>
                                                        <td class="table_actions">
                                                            <button type="button" onclick="selectContractForSellerPayment({{ json_encode($contract) }})" class="btn btn-success btn-sm"><i class="fa fa-check"></i> اختر العقد</button>
                                                        </td>
                                                        <td>{{ $contract->customer_name }}</td>
                                                        <td>{{ $contract->city }}</td>
                                                        <td class="currency">{{ \App\Helper\Helper::nFormat($contract->seller_commission) }}</td>
                                                        <td>
                                                            @if($contract->status == 1)
                                                                <label class="label label-success"><i class="fa fa-check"></i> ساري</label>
                                                            @else
                                                                <label class="label label-danger"><i class="fa fa-warning"></i> مفسوخ</label>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row row_seller_payment_operation_wrap" style="display: {{ ($edit_mode)? 'block' : 'none' }}">
                            <div class="col-sm-12">
                                <div class="panel panel-info">
                                    <div class="panel-heading">العملية</div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-2"></div>
                                            <div class="col-sm-8" style="padding-left: 15px">
                                                <div class="form-group">
                                                    <label>تاريخ العملية</label>
                                                    <input type="date" name="dt" id="dt" class="form-control"  value="{{ ($edit_mode)? $payment->dt : null }}">

                                                </div>
                                                <div class="form-group">
                                                    <label>الشهر</label>
                                                    <select id="month_id" name="month_id" class="form-control">
                                                        <option value=""></option>
                                                        @foreach ($months as $month)
                                                            <option value="{{ $month['id'] }}"
                                                                    @if ($edit_mode && $month['id'] == $payment->month_id)
                                                                    selected="selected"
                                                                @endif
                                                            >{{ $month['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>المبلغ</label>
                                                    <input type="text" name="amount" id="amount" class="form-control"  value="{{ ($edit_mode)? $payment->amount : null }}" onkeyup="checkNumber(this);calcSellerContractAmountNet()">
                                                </div>
                                                <div class="form-group">
                                                    <label>مجموع الخصم</label>
                                                    <input type="text" id="total_deductions" class="form-control currency"  value="{{ ($edit_mode)? \App\Helper\Helper::nFormat($total_deductions) : null }}" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label>الخصم</label>
                                                    <input type="text" id="deduction" name="deduction" class="form-control"  value="{{ ($edit_mode)? $payment->deduction : null }}" onkeyup="checkNumber(this);calcSellerContractAmountNet()">
                                                </div>
                                                <div class="form-group">
                                                    <label>مجموع السلف</label>
                                                    <input type="text" id="total_advances" class="form-control currency"  value="{{ ($edit_mode)? \App\Helper\Helper::nFormat($total_advances) : null }}" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label>السلفة</label>
                                                    <input type="text" id="advance" name="advance" class="form-control"  value="{{ ($edit_mode)? $payment->advance : null }}" onkeyup="checkNumber(this);calcSellerContractAmountNet()">
                                                </div>
                                                <div class="form-group">
                                                    <label>المبلغ الصافي</label>
                                                    <input type="text" id="amount_net" name="amount_net" class="form-control currency bold"  value="{{ ($edit_mode)? $payment->amount_net : null }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-2"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <br>
                            <hr>
                            <div class="form-group text-center">
                                <button type="button" class="btn btn-primary btn-lg" onclick="saveSellerPayment()"><i class="fa fa-send"></i> إرسال للتعميد</button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </section>
    </div>

@endsection

<div class="modal fade" id="modal_sellers" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-danger btn-sm btn-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">مندوبي التسويق</h4>
            </div>
            <div class="modal-body" style="padding-bottom: 0">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
