@php
    $edit_mode = false;
    $modal_customer_route = url()->to('dashboard/customers/modal/paie');
    if(isset($salary)){
        $edit_mode = true;
        $modal_customer_route .= '/'.$salary->id;
    }
@endphp

@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> الرواتب - {{ ($edit_mode)? $salary->employee_name : 'مسير جديد' }}</h1>
                </div>
                <div class="col-md-6 text-left">

                </div>
            </div>

        </section>
        <section class="content">
            <input type="hidden" class="form-control" id="da_totals_route" value="{{ route('dashboard.deductions_advances.total_rest_deductions_advances_by_employee') }}">
            <input type="hidden" class="form-control" id="modal_customers_route" value="{{ $modal_customer_route }}">
            <div class="box box-primary">
                <div class="box-body with-border">
                    <div class="row">
                        <div class="col-sm-6 search_inline">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>تاريخ المسير</label>
                                    <input type="date" name="dt_paie" id="dt_paie" class=""  value="{{ ($edit_mode)? $salary->paie_dt :null }}" onchange="setPaieMonth()">
                                </div>
                                <div class="col-sm-6">
                                    <label id="month_ar">{{ ($edit_mode)? \App\Helper\Helper::monthNameAr($salary->month_id) :null }}</label>
                                    <label class="label label-primary" style="display: {{ ($edit_mode)? 'inline' : 'none' }}; font-size: 14px"><span id="month_days">{{ ($edit_mode)? $salary->month_days :null }}</span> يوم</label>

                                    <input type="hidden" id="month_id" value="{{ ($edit_mode)? $salary->month_id :null }}">
                                    <input type="hidden" id="nb_days" value="{{ ($edit_mode)? $salary->paie_nb_days :null }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 search_inline">
                            <div class="row">
                                <div class="col-sm-6">

                                </div>
                                <div class="col-sm-6 nb_days_type" style="display: {{ ($edit_mode)? 'block' : 'none' }}">
                                    <label>نظام الراتب: </label>
                                    @if(!$edit_mode)
                                    <input type="radio" name="nb_days_type[]" value="monthly" checked onclick="setPaieNbDays($('#month_days').text())"><span>شهري</span>
                                    <input type="radio" name="nb_days_type[]" value="days_30" onclick="setPaieNbDays(30)"><span>30 يوم</span>
                                    @else
                                        <input type="radio" name="nb_days_type[]" value="monthly" {{ ($salary->paie_nb_days == $salary->month_days)? 'checked' : null }} onclick="setPaieNbDays($('#month_days').text())"><span>شهري</span>
                                        <input type="radio" name="nb_days_type[]" value="days_30" {{ ($salary->paie_nb_days != $salary->month_days)? 'checked' : null }} onclick="setPaieNbDays(30)"><span>30 يوم</span>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box box-primary">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover paie_table">
                            <thead>
                            <tr>
                                <th></th>
                                <th>اسم الموظف</th>
                                <th>الوظيفة</th>
                                <th>موقع العمل</th>
                                <th>المدينة</th>
                                <th>الراتب</th>
                                <th>دوام فعلي</th>
                                <th>م.الخصم</th>
                                <th>خصم</th>
                                <th>م.السلف</th>
                                <th>سلفة</th>
                                <th>إضافي</th>
                                <th>الصافي</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($edit_mode)
                                <tr row_id="{{ $salary->id }}" employee_id="{{ $salary->employee_id }}">
                                <td></td>
                                <td class="td_name">{{ $salary->employee_name }}</td>
                                <td class="td_job">{{ $salary->job_name }}</td>

                                <td class="td_work_zone">
                                    <span>{{ $salary->work_zone }}</span><br>
                                    <input type="hidden" class="form-control" value="{{ $salary->work_zone }}" readonly>
                                    <button type="button" class="btn btn-primary btn-sm" onclick="modalCustomers('{{ $modal_customer_route }}')"><i class="fa fa-search"></i> اختر العميل</button>
                                    <ul class="contracts_table"></ul>
                                </td>
                                <td class="td_city">
                                    <span>{{ $salary->city }}</span>
                                    <input type="hidden" class="form-control" value="{{ $salary->city }}" readonly>
                                </td>
                                <td class="td_salary">
                                    <input type="text" class="form-control" value="{{ $salary->salary }}" onkeyup="checkNumber(this);calcSalaryRow(this)">
                                </td>
                                <td class="td_nb_days">
                                    <input type="text" class="form-control" onkeyup="checkNumber(this);calcSalaryRow(this)" value="{{ $salary->nb_days }}">
                                </td>
                                <td class="td_total_deductions currency">{{ \App\Helper\Helper::nFormat($total_deductions) }}</td>
                                <td class="td_deduction">
                                    <input type="text" class="form-control" onkeyup="checkNumber(this);checkDeduction(this);calcSalaryRow(this)" value="{{ $salary->deduction }}">
                                </td>
                                <td class="td_total_advances currency">{{ \App\Helper\Helper::nFormat($total_advances) }}</td>
                                <td class="td_advance">
                                    <input type="text" class="form-control" onkeyup="checkNumber(this);checkAdvance(this);calcSalaryRow(this)" value="{{ $salary->advance }}">
                                </td>
                                <td class="td_extra">
                                    <input type="text" class="form-control" onkeyup="checkNumber(this);calcSalaryRow(this)" value="{{ $salary->extra }}">
                                </td>
                                <td class="td_salary_net currency bold">{{ $salary->salary_net }}</td>
                            </tr>
                            @endif
                            </tbody>
                        </table>
                        @if(!$edit_mode)
                        <p>
                            <button type="button" class="btn btn-success" onclick="modalEmployees('{{ route('dashboard.employees.modal', 'paie_list') }}', 'paie_list')"><i class="fa fa-plus"></i> إضافة راتب</button>

                        </p>
                        @endif
                    </div>
                    <div class="col-lg-12">
                        <hr>
                        <div class="form-group text-center">
                            @if(!$edit_mode)
                            <button type="button" onclick="savePaies('{{ route('dashboard.paies.store') }}', 'post')" class="btn btn-primary btn-lg"><i class="fa fa-send"></i> إرسال المسير للتعميد</button>
                            @else
                                <button type="button" onclick="savePaies('{{ route('dashboard.paies.update', $salary->id) }}', 'put')" class="btn btn-warning btn-lg"><i class="fa fa-send"></i> إرسال الراتب للتعميد</button>

                            @endif
                        </div>
                    </div>

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

<div class="modal fade" id="modal_employees" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-danger btn-sm btn-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">الموظفين</h4>
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
    import Input from "@/Jetstream/Input";
    import Label from "@/Jetstream/Label";
    export default {
        components: {Label, Input}
    }
</script>
