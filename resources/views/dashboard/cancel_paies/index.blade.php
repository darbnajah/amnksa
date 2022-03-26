@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> إلغاء صرف الرواتب</h1>
                </div>
                <div class="col-md-6 text-left">
                    <div class="btn-group">
                        <a onclick="previewPaies(event, this)" href="{{ route('dashboard.cancel_paies.preview', $paper->id) }}" class="btn btn-default" target="_blank"><i class="fa fa-print"></i> طباعة</a>
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="caret"></span>
                            <span class="sr-only">طباعة</span>
                        </button>
                        <ul class="dropdown-menu">
                            @foreach($papers as $paper)
                                <li><a onclick="previewPaies(event, this)" href="{{ route('dashboard.cancel_paies.preview', ['paper_id' => $paper->id]) }}" target="_blank"><i class="fa fa-file-o"></i> {{ $paper->paper_name }}</a></li>
                            @endforeach

                        </ul>
                    </div>

                    <!--
                @if(auth()->user()->can('paies_accept_deny_yes'))
                    <button type="button" class="btn btn-info" onclick="showModalAcceptPaies()"><i class="fa fa-check"></i> تعميد الرواتب المختارة</button>
                    <button type="button" class="btn btn-danger" onclick="showModalDenyPaies()"><i class="fa fa-warning"></i> رفض الرواتب المختارة</button>
                    @endif
                @if(!Session::has('expiration_dt') && auth()->user()->can('paies_create'))
                    <a href="{{ route('dashboard.paies.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> إضافة مسير</a>
                    @endif
-->
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
                    <div class="table-responsive">
                        <table id="example" class="table table-hover paie_table">
                            <thead>
                            <tr>
                                <th style="display: none"></th>
                                <th></th>
                                <th>تاريخ المسير</th>
                                <th>اسم الموظف</th>
                                <th>الوظيفة</th>
                                <th>موقع العمل</th>
                                <th>المدينة</th>
                                <th>الراتب</th>
                                <th>دوام فعلي</th>
                                <th>خصم</th>
                                <th>سلفة</th>
                                <th>إضافي</th>
                                <th>الصافي</th>
                                <th>الإجمالي</th>
                                <th>الحالة</th>
                                <th>المستخدم</th>

                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $total_deductions = 0;
                                $total_advances = 0;
                                $total_net = 0;
                            @endphp
                            @if($paies)
                                @foreach($paies as $paie)
                                    @php
                                        $total_deductions += $paie->deduction;
                                        $total_advances += $paie->advance;
                                        $total_net += $paie->salary_net;
                                    @endphp
                                    <tr salary_id="{{ $paie->id }}">
                                        <td style="display: none">{{ $paie->status }}</td>
                                        <td class="text-center table_actions">
                                            @if(auth()->user()->can('paies_accept_deny_yes'))
                                                @if($paie->status == 1 && $paie->trans_status == 1)
                                                    <button type="button" onclick="cancelSalaryTransfer('{{ route('dashboard.cancel_paies.cancel_salary_transfer', $paie->id) }}')" class="btn btn-warning btn-sm"><i class="fa fa-reply"></i> إلغاء الصرف</button>
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $paie->paie_dt }}</td>
                                        <td>{{ $paie->employee_name }}</td>
                                        <td>{{ $paie->job_name }}</td>
                                        <td>{{ $paie->work_zone }}</td>
                                        <td>{{ $paie->city }}</td>
                                        <td class="currency">{{ \App\Helper\Helper::nFormat($paie->salary) }}</td>
                                        <td class="currency">{{ $paie->nb_days }}</td>
                                        <td class="currency td_deduction">{{ \App\Helper\Helper::nFormat($paie->deduction) }}</td>
                                        <td class="currency td_advance">{{ \App\Helper\Helper::nFormat($paie->advance) }}</td>
                                        <td class="currency">{{ \App\Helper\Helper::nFormat($paie->extra) }}</td>
                                        <td class="currency bold td_net">{{ \App\Helper\Helper::nFormat($paie->salary_net) }}</td>
                                        <td class="currency bold td_global">{{ \App\Helper\Helper::nFormat($paie->salary_net + $paie->advance) }}</td>
                                        <td>
                                            @if($paie->status == -1)
                                                <label class="label label-danger"><i class="fa fa-warning"></i> مرفوض</label>
                                                <small style="color: #9f3a38">{{ $paie->deny_notes }}</small>
                                            @elseif($paie->status == 0)
                                                <label class="label label-warning"><i class="fa fa-clock-o"></i> قيد التعميد</label>
                                            @elseif($paie->status == 1 && $paie->trans_status == 0)
                                                <label class="label label-info"><i class="fa fa-check"></i>  تم التعميد</label>
                                            @elseif($paie->status == 1 && $paie->trans_status == 1)
                                                <label class="label label-success"><i class="fa fa-clock-o"></i> تم الصرف</label>
                                            @endif
                                        </td>
                                        <td>{{ $paie->username }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                            <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th class="currency foot_total_deductions">{{ \App\Helper\Helper::nFormat($total_deductions) }}</th>
                                <th class="currency foot_total_advances">{{ \App\Helper\Helper::nFormat($total_advances) }}</th>
                                <th></th>
                                <th class="currency foot_total_net">{{ \App\Helper\Helper::nFormat($total_net) }}</th>
                                <th class="currency foot_total_global">{{ \App\Helper\Helper::nFormat($total_net + $total_advances) }}</th>
                                <th></th>
                                <th></th>

                            </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>
            </div>

        </section>
    </div>

@endsection

<div class="modal fade" id="modal_accept_paies" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-danger btn-sm btn-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">تعميد الرواتب</h4>
            </div>
            <div class="modal-body" style="padding: 20px">
                <div class="form-group">
                    <label>تاريخ التعميد</label>
                    <input type="date" class="form-control accept_dt">
                </div>
            </div>
            <div class="modal-footer">
                <div class="pull-right">
                    <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">إغلاق</button>
                </div>
                <div class="pull-left">
                    <button type="button" class="btn btn-info btn-lg" onclick="accept_Paies('{{ route('dashboard.paies.accept_paies') }}')"><i class="fa fa-check"></i> تعميد الرواتب المختارة</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_deny_paies" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-danger btn-sm btn-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">رفض تعميد الرواتب</h4>
            </div>
            <div class="modal-body" style="padding: 20px">
                <div class="form-group">
                    <label>ملاحظات</label>
                    <input type="text" class="form-control deny_notes">
                </div>
            </div>
            <div class="modal-footer">
                <div class="pull-right">
                    <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">إغلاق</button>
                </div>
                <div class="pull-left">
                    <button type="button" class="btn btn-danger btn-lg" onclick="deny_Paies('{{ route('dashboard.paies.deny_paies') }}')"><i class="fa fa-check"></i> رفض الرواتب المختارة</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    import Selectize
    import Button from "@/Jetstream/Button";
    export default {
        components: {Button, Selectize}
    }
</script>
