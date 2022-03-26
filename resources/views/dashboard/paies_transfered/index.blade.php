@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> الرواتب المدفوعة</h1>
                </div>
                <div class="col-md-6 text-left">

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
                                <th></th>
                                <th>تاريخ المسير</th>
                                <th>عن شهر</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($paies)
                                @foreach($paies as $paie)
                                    <tr>
                                        <td class="table_actions">
                                            <a href="{{ route('dashboard.paies_transfered.show', $paie->paie_dt) }}" class="btn btn-primary"><i class="fa fa-list-ul"></i> الرواتب</a>

                                            <div class="btn-group">
                                                <a href="{{ route('dashboard.paies_transfered.preview', $paie->paie_dt) }}" class="btn btn-default" target="_blank"><i class="fa fa-print"></i></a>
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="caret"></span>
                                                    <span class="sr-only">طباعة</span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @foreach($papers as $paper)
                                                        <li><a href="{{ route('dashboard.paies_transfered.preview', ['paie_dt' => $paie->paie_dt, 'paper_id' => $paper->id]) }}" target="_blank"><i class="fa fa-file-o"></i> {{ $paper->paper_name }}</a></li>
                                                    @endforeach

                                                </ul>
                                            </div>
                                        <td>{{ $paie->paie_dt }}</td>
                                        <td>{{ \App\Helper\Helper::monthNameAr($paie->month_id) }}</td>
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
