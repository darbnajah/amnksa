@php
    $edit_mode = false;
    if(isset($customer_expense)){
        $edit_mode = true;
    }
@endphp

@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> المصروفات على العملاء -
                        @if($edit_mode)
                            @lang('site.edit')
                        @else
                            @lang('site.create')
                        @endif
                    </h1>
                </div>
                <div class="col-md-6 text-left">
                    <a href="{{ route('dashboard.customer_expenses.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('site.back')</a>
                </div>
            </div>

        </section>


        <section class="content">
            <div class="box box-primary">
                <div class="box-body">
                    @include('partials._errors')
                    <form action="{{ ($edit_mode)? route('dashboard.customer_expenses.update', $customer_expense->id) : route('dashboard.customer_expenses.store') }}" method="post" enctype="multipart/form-data" class="has_small_inputs" id="default_form">
                        {{ csrf_field() }}
                        {{ method_field(($edit_mode)? 'put' : 'post') }}
                        <input type="hidden" id="form_method" value="{{ ($edit_mode)? 'put' : 'post' }}">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <span>العميل</span>
                                        <button type="button" class="btn btn-success" onclick="modalCustomers('{{ route('dashboard.customers.modal', 'customer_expense') }}')"><i class="fa fa-search"></i></button>
                                    </div>
                                    <div class="panel-body" style="min-height: 200px;">
                                        <div class="form-group">
                                            <label>اسم العميل</label>
                                            <input type="text" id="customer_name" class="form-control"  value="{{ ($edit_mode)? $customer_expense->customer_name : null }}" readonly>
                                        </div>
                                        <input type="hidden" name="customer_id" id="customer_id" class="form-control"  value="{{ ($edit_mode)? $customer_expense->customer_id : null }}" readonly>

                                        <input type="hidden" name="contract_id" id="contract_id" class="form-control" value="{{ ($edit_mode)? $customer_expense->contract_id : null }}">
                                        <input type="hidden" value="" name="contract_obj" id="contract_obj" class="form-control"  value="" readonly>

                                        <input type="hidden" name="seller_id" id="seller_id" class="form-control" readonly>
                                        <input type="hidden" name="supplier_id" id="supplier_id" class="form-control" readonly>
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
                                                    <th>إجمالي العقد</th>
                                                    @if(!$company->factor)
                                                    <th>نسبة المسوق</th>
                                                    @else
                                                        <th>نسبة المورد</th>
                                                    @endif
                                                    <th>حالة العقد</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if($edit_mode)
                                                    <tr>
                                                        <td class="table_actions">
                                                            <button type="button" onclick="selectContractForPayment({{ json_encode($contract) }})" class="btn btn-success btn-sm"><i class="fa fa-check"></i> اختر العقد</button>
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
                        <div class="row_payment_operation_wrap" style="display: {{ ($edit_mode)? 'block' : 'none' }}">
                            <div class="row">
                                <div class="col-lg-12" style="padding-left: 15px">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">المصروف</div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <label>تاريخ العملية</label>
                                                <input type="date" name="dt" id="dt" class="form-control"  value="{{ ($edit_mode)? $customer_expense->dt : null }}" style="margin-bottom: 10px" onchange="setPaymentNumber()">

                                            </div>
                                            <div class="form-group">
                                                <label>تحميل المصروف على شهر</label>
                                                <select id="month_id" name="month_id" class="form-control">
                                                    <option value=""></option>
                                                    @foreach ($months as $month)
                                                        <option value="{{ $month['id'] }}"
                                                                @if ($edit_mode && $month['id'] == $customer_expense->month_id)
                                                                selected="selected"
                                                            @endif
                                                        >{{ $month['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>	الرقم المرجعي</label>
                                                <input type="number" id="number" name="number" class="form-control" value="{{($edit_mode)? $customer_expense->number : null }}" readonly>
                                                <input type="hidden" id="doc_id" name="doc_id" class="form-control" value="{{($edit_mode)? $customer_expense->doc_id : $doc_id }}" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label>البيان</label>
                                                <input type="text" name="label" id="label" class="form-control"  value="{{ ($edit_mode)? $customer_expense->label : null }}">
                                            </div>
                                            <div class="form-group">
                                                <label>المبلغ</label>
                                                <input type="text" name="credit" id="credit" class="form-control"  value="{{ ($edit_mode)? $customer_expense->credit : null }}" onkeyup="checkNumber(this);">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-lg-12">
                            <br>
                            <hr>
                            <div class="form-group text-center">
                                <button type="button" class="btn btn-primary btn-lg" onclick="saveCustomerExpense()"><i class="fa fa-save"></i> @lang('site.save')</button>
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
<script>
    import Label from "@/Jetstream/Label";
    export default {
        components: {Label}
    }
</script>
