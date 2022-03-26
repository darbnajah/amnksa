@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> الرواتب</h1>
                </div>
                <div class="col-md-6 text-left">
                    <div class="btn-group">
                        <a onclick="previewPaies(event, this)" href="{{ route('dashboard.paies_accepted.preview', $paper->id) }}" class="btn btn-default" target="_blank"><i class="fa fa-print"></i> طباعة</a>
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="caret"></span>
                            <span class="sr-only">طباعة</span>
                        </button>
                        <ul class="dropdown-menu">
                            @foreach($papers as $paper)
                                <li><a onclick="previewPaies(event, this)" href="{{ route('dashboard.paies_accepted.preview', ['paper_id' => $paper->id]) }}" target="_blank"><i class="fa fa-file-o"></i> {{ $paper->paper_name }}</a></li>
                            @endforeach

                        </ul>
                    </div>

                @if(auth()->user()->can('paies_accepted_create'))
                    <button type="button" class="btn btn-warning" onclick="showModalTransferPaies()"><i class="fa fa-money"></i> صرف الرواتب المختارة</button>
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
                    <div class="table-responsive">
                        <table id="example" class="table table-hover paie_table">
                            <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" class="check_row" id="check_all" onchange="checkAllInvoices(this);">
                                </th>
                                <th>تاريخ المسير</th>
                                <th>اسم الموظف</th>
                                <th>المدينة</th>
                                <th>موقع العمل</th>
                                <th>اسم مالك الحساب</th>
                                <th>اسم البنك</th>
                                <th>رقم الحساب</th>
                                <th>ايبان</th>
                                <th>صافي الراتب</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($paies)
                                @foreach($paies as $paie)
                                    <tr salary_id="{{ $paie->id }}">
                                        <td class="text-center">
                                            <input type="checkbox" class="check_row" onchange="checkUncheckInvoice(this);">
                                        <td>{{ $paie->paie_dt }}</td>
                                        <td>{{ $paie->employee_name }}</td>
                                        <td>{{ $paie->city }}</td>
                                        <td>{{ $paie->work_zone }}</td>
                                        <td>{{ $paie->bank_account_name }}</td>
                                        <td>{{ $paie->bank_name }}</td>
                                        <td>{{ $paie->bank_account }}</td>
                                        <td>{{ $paie->bank_iban }}</td>
                                        <td class="currency bold">{{ \App\Helper\Helper::nFormat($paie->salary_net) }}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </section>
    </div>

@endsection

<div class="modal fade" id="modal_transfer_paies" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-danger btn-sm btn-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">صرف الرواتب</h4>
            </div>
            <div class="modal-body" style="padding: 20px">
                <div class="form-group">
                    <label>تاريخ الصرف</label>
                    <input type="date" class="form-control transfer_dt">
                </div>
                <div class="form-group">
                    <label>ملاحظات</label>
                    <textarea class="form-control transfer_notes" placeholder="ملاحظات"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <div class="pull-right">
                    <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">إغلاق</button>
                </div>
                <div class="pull-left">
                    <button type="button" class="btn btn-warning btn-lg" onclick="transfer_Paies('{{ route('dashboard.paies_accepted.transfer_paies') }}')"><i class="fa fa-check"></i> صرف الرواتب المختارة</button>
                </div>
            </div>
        </div>
    </div>
</div>
