@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <input type="hidden" id="route_url" class="form-control" value="{{ url('/dashboard/paies_transfered/setSearchSession') }}">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> <span> الرواتب المدفوعة</span> - <span>{{ $paie_dt }}</span></h1>
                </div>
                <div class="col-md-6 text-left">
                    <div class="btn-group">
                        <a href="{{ route('dashboard.paies_transfered.preview', $paie_dt) }}" class="btn btn-default" target="_blank"><i class="fa fa-print"></i> طباعة</a>
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="caret"></span>
                            <span class="sr-only">طباعة</span>
                        </button>
                        <ul class="dropdown-menu">
                            @foreach($papers as $paper)
                                <li><a href="{{ route('dashboard.paies_transfered.preview', ['paie_dt' => $paie_dt, 'paper_id' => $paper->id]) }}" target="_blank"><i class="fa fa-file-o"></i> {{ $paper->paper_name }}</a></li>
                            @endforeach

                        </ul>
                    </div>

                    <a href="{{ route('dashboard.paies_transfered.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> @lang('site.back')</a>

                </div>
            </div>

        </section>
        <section class="content">
            <div class="box box-primary" style="display: none">
                <div class="box-header with-border">
                    <input type="text" name="search" class="form-control"  id="dt_search" placeholder="@lang('site.search')" onkeyup="setSearchSession(this)">
                </div>
            </div>

            <div class="box box-primary">
                <div class="box-body">
                    <div class="table-responsive">
                        <style>
                            #example thead th input {
                                width: 100%;
                            }
                        </style>
                        <table id="example" class="table table-hover paie_table paies_trasfered_table">
                            <thead>
                            <tr>
                                <th name="employees.employee_name">اسم الموظف</th>
                                <th name="paie_salaries.city">المدينة</th>
                                <th name="paie_salaries.work_zone">موقع العمل</th>
                                <th name="employees.bank_account_name">اسم مالك الحساب</th>
                                <th name="employees.bank_name">اسم البنك</th>
                                <th name="employees.bank_account">رقم الحساب</th>
                                <th name="employees.bank_iban">ايبان</th>
                                <th name="paie_salaries.salary_net">صافي الراتب</th>
                                <th name="paie_salaries.trans_notes">ملاحظات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($paies)
                                @foreach($paies as $paie)
                                    <tr salary_id="{{ $paie->id }}">
                                        <td>{{ $paie->employee_name }}</td>
                                        <td>{{ $paie->city }}</td>
                                        <td>{{ $paie->work_zone }}</td>
                                        <td>{{ $paie->bank_account_name }}</td>
                                        <td>{{ $paie->bank_name }}</td>
                                        <td>{{ $paie->bank_account }}</td>
                                        <td>{{ $paie->bank_iban }}</td>
                                        <td class="currency bold">{{ \App\Helper\Helper::nFormat($paie->salary_net) }}</td>
                                        <td>@php echo nl2br($paie->trans_notes) @endphp</td>

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
