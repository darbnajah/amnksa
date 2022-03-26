@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <input type="hidden" id="doc_type" value="employees_balance">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> كشف حساب الموظفين</h1>
                </div>
                <div class="col-md-6 text-left">
                    <div class="btn-group">
                        <button onclick="previewSearch({{ $paper->id }})" class="btn btn-default" target="_blank"><i class="fa fa-print"></i> طباعة</button>
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="caret"></span>
                            <span class="sr-only">طباعة</span>
                        </button>
                        <ul class="dropdown-menu">
                            @foreach($papers as $paper)
                                <li><button class="btn btn-default" onclick="previewSearch({{ $paper->id }})" target="_blank"><i class="fa fa-file-o"></i> {{ $paper->paper_name }}</button></li>
                            @endforeach

                        </ul>
                    </div>
                </div>
            </div>

        </section>
        <section class="content">
            <input type="hidden" id="route_url" class="form-control" value="{{ url('/dashboard/employees_balance/') }}">
            <input type="hidden" id="preview_url" class="form-control" value="{{ $preview_url }}">
            <div class="box box-primary">
                <div class="box-body with-border">
                    <div class="row">
                        <div class="col-sm-7 search_inline">
                            <label>الموظف</label>
                            <input type="hidden" id="employee_id" value="{{ $employee_id }}" readonly>
                            <input type="text" id="employee_name" value="{{ $employee_name }}" readonly>
                            <button type="button" class="btn btn-default" onclick="modalEmployees('{{ route('dashboard.employees.modal', 'employees_balance') }}')"><i class="fa fa-search"></i></button>

                        </div>
                        <div class="col-sm-5 search_inline">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>من تاريخ</label>
                                    <input type="date" name="dt_from" id="dt_from" class=""  value="{{ $dt_from }}" onchange="goToEmployeeBalance()">
                                </div>
                                <div class="col-sm-6">
                                <label>الى تاريخ</label>
                                <input type="date" name="dt_to" id="dt_to" class=""  value="{{ $dt_to }}" onchange="goToEmployeeBalance()">
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-body">
                    @if($paies->count() > 0)
                        <div class="table-responsive">
                            <table id="example" class="table table-hover paie_table">
                                <thead>
                                <tr>
                                    <th>تاريخ المسير</th>
                                    <th>الشهر</th>
                                    <th>اسم الموظف</th>
                                    <th>الوظيفة</th>
                                    <th>المدينة</th>
                                    <th>موقع العمل</th>
                                    <th>الصافي</th>
                                    <th>الحالة</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($paies)
                                    @foreach($paies as $paie)
                                        <tr salary_id="{{ $paie->id }}">
                                            <td>{{ $paie->paie_dt }}</td>
                                            <td>{{ \App\Helper\Helper::monthNameAr($paie->month_id) }}</td>
                                            <td>{{ $paie->employee_name }}</td>
                                            <td>{{ $paie->job_name }}</td>
                                            <td>{{ $paie->city }}</td>
                                            <td>{{ $paie->work_zone }}</td>
                                            <td class="currency bold">{{ \App\Helper\Helper::nFormat($paie->salary_net) }}</td>
                                            <td>
                                                @if($paie->status == -1)
                                                    <label class="label label-danger"><i class="fa fa-warning"></i> مرفوض</label>
                                                @elseif($paie->status == 0)
                                                    <label class="label label-warning"><i class="fa fa-clock-o"></i> قيد التعميد</label>
                                                @elseif($paie->status == 1 && $paie->trans_status == 0)
                                                    <label class="label label-info"><i class="fa fa-check"></i>  تم التعميد</label>

                                                @elseif($paie->status == 1 && $paie->trans_status == 1)
                                                    <label class="label label-success"><i class="fa fa-clock-o"></i> تم الصرف</label>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    @else
                        <h2>لا توجد بيانات !</h2>
                    @endif
                </div>
            </div>

        </section>
    </div>

@endsection

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
    import Label from "@/Jetstream/Label";
    import Input from "@/Jetstream/Input";
    import Button from "@/Jetstream/Button";
    export default {
        components: {Button, Input, Label}
    }
</script>
