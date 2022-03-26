@php
    $edit_mode = false;

    if(isset($employee)){
        $edit_mode = true;
    }

@endphp

@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> الموظفين -
                        @if($edit_mode)
                            @lang('site.edit')
                        @else
                            @lang('site.create')
                        @endif
                    </h1>
                </div>
                <div class="col-md-6 text-left">
                    <a href="{{ route('dashboard.employees.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('site.back')</a>
                </div>
            </div>

        </section>


        <section class="content">

            <div class="box box-primary">
                <div class="box-body">
                    @include('partials._errors')
                    <form action="{{ ($edit_mode)? route('dashboard.employees.update', $employee->id) : route('dashboard.employees.store') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field(($edit_mode)? 'put' : 'post') }}

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>كود الموظف <span class="required_field_star">*</span></label>
                                <input type="text" name="code" class="form-control" value="{{ ($edit_mode)? $employee->code : (old('code')? old('code') : $employee_id) }}" required>
                            </div>
                            <div class="form-group">
                                <label>الإسم الموظف <span class="required_field_star">*</span></label>
                                <input type="text" name="employee_name" id="employee_name" class="form-control" value="{{ ($edit_mode)? $employee->employee_name : old('employee_name') }}" onkeyup="setBank_account_name()" required>
                            </div>
                            <div class="form-group">
                                <label>رقم الجوال <span class="required_field_star">*</span></label>
                                <input type="text" name="mobile_1" class="form-control" value="{{ ($edit_mode)? $employee->mobile_1 : old('mobile_1') }}" required>
                            </div>

                            <div class="form-group">
                                <label>تاريخ المباشرة</label>
                                <input type="date" name="dt_start" class="form-control" value="{{ ($edit_mode)? $employee->dt_start : old('dt_start') }}">
                            </div>
                            <div class="form-group">
                                <label>تاريخ الإيقاف</label>
                                <input type="date" name="civil_card_expire_dt" class="form-control" value="{{ ($edit_mode)? $employee->civil_card_expire_dt : old('civil_card_expire_dt') }}">
                            </div>
                            <div class="form-group">
                                <label>الراتب <span class="required_field_star">*</span></label>
                                <input type="text" name="salary" class="form-control" value="{{ ($edit_mode)? $employee->salary : old('salary') }}" onkeyup="validateNumber(this)" required>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <span>موقع العمل والمدينة</span>
                                            <button type="button" class="btn btn-success" onclick="modalCustomers('{{ route('dashboard.customers.modal', 'employee') }}')"><i class="fa fa-search"></i> اختر العميل</button>
                                        </div>
                                        <div class="panel-body" style="min-height: 200px;">
                                            <div class="row">
                                                <div class="col-sm-12 edit_employee_customer">
                                                    <div class="form-group">
                                                        <label>اسم العميل</label>
                                                        <input type="text" id="customer_name" class="form-control"  value="" readonly>
                                                    </div>
                                                    <input type="hidden" name="customer_id" id="customer_id" class="form-control"  value="" readonly>

                                                    <input type="hidden" name="contract_id" id="contract_id" class="form-control" value="">
                                                </div>
                                                <div class="col-sm-12 edit_employee_contract">
                                                    <h4><b>العقود السارية</b></h4>
                                                    <div class="table-responsive">
                                                        <table class="table table-hover" id="contracts_table">
                                                            <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>المدينة</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>موقع العمل <span class="required_field_star">*</span></label>
                                                        <input type="text" name="work_zone" id="work_zone" class="form-control" value="{{ ($edit_mode)? $employee->work_zone : old('work_zone') }}" required readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>المدينة <span class="required_field_star">*</span></label>
                                                        <input type="text" name="city" id="city" class="form-control" value="{{ ($edit_mode)? $employee->city : old('city') }}" required readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="panel panel-danger">
                                <div class="panel-heading">الوظيفة <span class="required_field_star">*</span></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <select name="job_id" class="form-control" required>
                                            <option value=""
                                                    @if (!$edit_mode)
                                                    selected="selected"
                                                @endif
                                            ></option>
                                            @foreach ($jobs as $job)
                                                <option value="{{ $job->id }}"
                                                        @if ($edit_mode && $job->id == old('job_id', $job->id))
                                                        selected="selected"
                                                    @endif
                                                >{{ $job->job_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="col-sm-6">
                            <div class="panel panel-info">
                                <div class="panel-heading">بطاقة الأحوال</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label>رقم بطاقة الأحوال</label>
                                        <input type="text" name="civil_card_number" class="form-control" value="{{ ($edit_mode)? $employee->civil_card_number : old('civil_card_number') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>مدينة الإصدار / الميلاد</label>
                                        <input type="text" name="civil_card_issue" class="form-control" value="{{ ($edit_mode)? $employee->civil_card_issue : old('civil_card_issue') }}">
                                    </div>

                                </div>
                            </div>
                            <div class="panel panel-success">
                                <div class="panel-heading">الحساب البنكي</div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label>اسم مالك الحساب</label>
                                        <input type="text" name="bank_account_name" id="bank_account_name" class="form-control" value="{{ ($edit_mode)? $employee->bank_account_name : old('bank_account_name') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>اسم البنك</label>
                                        <input type="text" name="bank_name" class="form-control" value="{{ ($edit_mode)? $employee->bank_name : old('bank_name') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>رقم الحساب</label>
                                        <input type="text" name="bank_account" class="form-control" value="{{ ($edit_mode)? $employee->bank_account : old('bank_account') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>ايبان</label>
                                        <input type="text" name="bank_iban" class="form-control" value="{{ ($edit_mode)? $employee->bank_iban : old('bank_iban') }}">
                                    </div>
                                </div>
                            </div>



                        </div>
                        <div class="col-lg-12">
                            <br>
                            <hr>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-save"></i> @lang('site.save')</button>
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
    import Input from "@/Jetstream/Input";
    import Button from "@/Jetstream/Button";
    export default {
        components: {Button, Input, Label}
    }
</script>
