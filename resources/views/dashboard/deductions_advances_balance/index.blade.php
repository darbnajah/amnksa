@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-university"></i> كشف السلف والخصم </h1>
                </div>
                <div class="col-md-6 text-left">

                </div>
            </div>
        </section>

        <section class="content">
            <div class="box box-primary">
                <div class="box-body with-border">
                    <input type="text" name="search" class="form-control"  id="advances_search" placeholder="@lang('site.search')">

                </div>
            </div>
            <div class="box box-primary">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover deductions_advances_table" id="advances_table">
                            <thead>
                            <tr>
                                <th>اسم الموظف</th>
                                <th>موقع العمل</th>
                                <th>مجموع الباقي</th>
                                <th>سلفة / خصم</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($dabs)
                                <?php foreach($dabs as $dab){
                                    if($dab->rest > 0) {
                               ?>
                                <tr>
                                    <td>{{ $dab->employee_name }}</td>
                                    <td>{{ $dab->work_zone }}</td>
                                    <td class="currency">{{ \App\Helper\Helper::nFormat($dab->rest) }}</td>
                                    <td>{{ ($dab->type == 'advance')? 'سلفة' : 'خصم' }}</td>
                                </tr>
                                <?php } } ?>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </section>
    </div>

@endsection
