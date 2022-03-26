@php
    $edit_mode = false;

    if(isset($contract)){
        $edit_mode = true;
    }
$total_amount = 0;
@endphp

@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> العقود -
                        @if($edit_mode)
                            @lang('site.edit')
                        @else
                            @lang('site.create')
                        @endif
                    </h1>
                </div>
                <div class="col-md-6 text-left">
                    <a href="{{ isset($customer_id)? route('dashboard.customers.show', $customer_id) : route('dashboard.customers.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('site.back')</a>
                </div>
            </div>

        </section>


        <section class="content">

            <div class="box box-primary">
                <div class="box-body">
                    @include('partials._errors')
                    <form action="{{ ($edit_mode)? route('dashboard.contracts.update', $contract->id) : route('dashboard.contracts.store') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field(($edit_mode)? 'put' : 'post') }}


                        <div class="col-sm-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">العميل</div>
                                <div class="panel-body">
                                    <input type="hidden" name="customer_id" class="form-control" value="{{ $customer->id  }}">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <h4 dir="rtl">
                                                    <span>  كود العميل: </span>
                                                    <span class="label label-primary">{{ $customer->code }}</span>
                                                </h4>

                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <h4 dir="rtl">
                                                    <span>  اسم العميل: </span>
                                                    <b>{{ $customer->name_ar }}</b>
                                                </h4>

                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <h4 dir="rtl">
                                                   <span>المدينة: </span> <i class="fa fa-map-marker"></i> {{ $customer->city }}
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-primary">
                                <div class="panel-heading">العقد</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>رقم العقد</label>
                                                <input type="text" id="code" name="code" class="form-control" readonly value="{{ ($edit_mode)? $contract->code : (old('code')? old('code') : $contract_code) }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>المدينة <span class="required_field_star">*</span></label>
                                                <input type="text" id="city" name="city" class="form-control" value="{{ ($edit_mode)? $contract->city : old('city') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>العنوان <span class="required_field_star">*</span></label>
                                                <input type="text" id="address" name="address" class="form-control" value="{{ ($edit_mode)? $contract->address : old('address') }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>تاريخ بداية العقد <span class="required_field_star">*</span></label>
                                                <input type="date" id="dt_start" name="dt_start" class="form-control" value="{{ ($edit_mode)? $contract->dt_start : old('dt_start') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>تاريخ نهاية العقد <span class="required_field_star">*</span></label>
                                                <input type="date" id="dt_end"  name="dt_end" class="form-control" value="{{ ($edit_mode)? $contract->dt_end : old('dt_end') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>حالة العقد <span class="required_field_star">*</span></label>
                                                <select name="status" class="form-control" required>
                                                    @if($edit_mode && $contract->status == 0)
                                                        <option value="0" selected>مفسوخ</option>
                                                        <option value="1">ساري</option>
                                                    @else
                                                        <option value="1" selected>ساري</option>
                                                        <option value="0">مفسوخ</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    @if(!$company->factor)
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <p><input type="checkbox" name="link_to_seller" id="link_to_seller" style="position:relative; top: 5px" onclick="toggleSellerWrap(this)" {{ (isset($contract) && ($contract->seller_id))? 'checked' : null }}><label for="link_to_seller"> تسجيل العقد باسم مسوق</label></p>
                                            <div class="row contract_seller_wrap" style="display:{{ (isset($contract) && ($contract->seller_id))? 'block' : 'none' }}">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>اسم المندوب</label>
                                                        <select name="seller_id" class="form-control">
                                                            <option value=""
                                                                    @if (!$edit_mode)
                                                                    selected="selected"
                                                                @endif
                                                            ></option>
                                                            @foreach ($sellers as $seller)
                                                                <option value="{{ $seller->id }}"
                                                                        @if ($edit_mode && $seller->id == $contract->seller_id)
                                                                        selected="selected"
                                                                    @endif
                                                                >{{ $seller->first_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>الإستقطاع الشهري</label>
                                                        <input type="text" name="seller_commission" class="form-control" value="{{ ($edit_mode)? $contract->seller_commission : old('seller_commission') }}" onkeyup="checkNumber(this)">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    @else
                                        <div class="row">
                                            <div class="col-md-4 col-sm-6">
                                                <div class="form-group">
                                                    <label>اسم المورد</label>
                                                    <select name="supplier_id" class="form-control">
                                                        <option value=""
                                                                @if (!$edit_mode)
                                                                selected="selected"
                                                            @endif
                                                        ></option>
                                                        @foreach ($suppliers as $supplier)
                                                            <option value="{{ $supplier->id }}"
                                                                    @if ($edit_mode && $supplier->id == old('supplier_id', $contract->supplier_id))
                                                                    selected="selected"
                                                                @endif
                                                            >{{ $supplier->supplier_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <div class="form-group">
                                                    <label>نسبة الإستقطاع</label>
                                                    <input type="text" name="supplier_commission" class="form-control" value="{{ ($edit_mode)? $contract->supplier_commission : old('supplier_commission') }}" onkeyup="checkNumber(this)">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <span>البيانات</span>
                                    <button type="button" class="btn btn-success btn-sm" onclick="appendBulletin()"><i class="fa fa-plus"></i> إضافة بيان</button>

                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table id="bulletins" class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th width="40%">البيان</th>
                                            <th>العدد</th>
                                            <th>التكلفة الشهرية</th>
                                            <th>المجموع</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if($edit_mode && $bulletins)
                                            @php foreach($bulletins as $bulletin):
                                                    $amount = $bulletin->nb * $bulletin->cost;
                                                     $total_amount += $amount;
                                            @endphp
                                            <tr row_id="">
                                                <td class="table_actions">
                                                    <button type="button" class="btn_remove btn btn-danger" onclick="removeRow(this)"><i class="fa fa-times"></i></button>
                                                </td>
                                                <td class="td_label">
                                                    <input type="text" class="form-control" value="{{ $bulletin->label }}" onkeyup="calcCost(this)">
                                                </td>
                                                <td class="td_nb">
                                                    <input type="text" class="form-control currency" value="{{ $bulletin->nb }}" onkeyup="calcCost(this)">
                                                </td>
                                                <td class="td_cost">
                                                    <input type="text" class="form-control currency" value="{{ $bulletin->cost }}" onkeyup="calcCost(this)">
                                                </td>
                                                <td class="td_total">
                                                    <input type="text" class="form-control currency" value="{{ number_format($amount,2, '.', ' ') }}" readonly>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-left">المجموع: </th>
                                            <th class="currency">
                                                <label id="total_amount">{{ number_format($total_amount, 2, '.', ' ') }}</label>
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                    </div>

                                    <textarea id="bulletins_area" name="bulletins" class="form-control" style="display: none">@if($bulletins)@foreach($bulletins as $bulletin){{ $bulletin->label.';'.$bulletin->nb.';'.$bulletin->cost.'::' }}@endforeach @endif</textarea>
                                    <input type="hidden" id="contract_total" name="contract_total" value="{{ ($edit_mode)? $contract->contract_total : 0 }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <br>
                            <hr>
                            <div class="form-group text-center">
                                <button type="button" class="btn btn-primary btn-lg" onclick="checkFormAddContract(this)"><i class="fa fa-save"></i> @lang('site.save')</button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </section>
    </div>

@endsection
<script>
    import Label from "@/Jetstream/Label";
    import Input from "@/Jetstream/Input";
    import Button from "@/Jetstream/Button";
    export default {
        components: {Button, Input, Label}
    }
</script>
