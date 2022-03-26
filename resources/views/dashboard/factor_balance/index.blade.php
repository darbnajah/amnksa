@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fa fa-money"></i> الارباح والخسائر </h1>
                </div>
                <div class="col-md-6 text-left">
                </div>
            </div>

        </section>
        <section class="content">
            <input type="hidden" id="route_url" class="form-control" value="{{ url('/dashboard/company_balance/') }}">
            <input type="hidden" id="preview_url" class="form-control" value="{{ $preview_url }}">

            <div class="box box-primary">
                <div class="box-body row">
                    <div class="col-sm-6 search_inline text-left">
                        <label>من تاريخ</label>
                        <input type="date" name="dt_from" id="dt_from" class=""  value="{{ $dt_from }}" onchange="goToCompanyBalance()">
                    </div>
                    <div class="col-sm-6 search_inline">
                        <label>الى تاريخ</label>
                        <input type="date" name="dt_to" id="dt_to" class=""  value="{{ $dt_to }}" onchange="goToCompanyBalance()">
                    </div>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="small-box bg-green">
                                        <div class="inner">
                                            <p>الفواتير المحصلة	</p>
                                            <h3 class="currency">{{ $obj->payed_invoices_total }}</h3>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-document-text"></i>
                                        </div>
                                    </div>
                                    <div class="small-box bg-red">
                                        <div class="inner">
                                            <p>الفواتير الغير محصلة	</p>
                                            <h3 class="currency">{{ $obj->not_payed_invoices_total }}</h3>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-document-text"></i>
                                        </div>
                                    </div>
                                    <div class="small-box bg-green">
                                        <div class="inner">
                                            <p>الايرادات الاضافية	</p>
                                            <h3 class="currency">{{ $obj->incomes_total }}</h3>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-document-text"></i>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-sm-6">
                                    <div class="small-box bg-green">
                                        <div class="inner">
                                            <p>تحصيل الموردين</p>
                                            <h3 class="currency">{{ $obj->collections_total }}</h3>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-document-text"></i>
                                        </div>
                                    </div>
                                    <div class="small-box bg-yellow">
                                        <div class="inner">
                                            <p>متبقي على الموردين	</p>
                                            <h3 class="currency">{{ $obj->suppliers_rest_total }}</h3>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-document-text"></i>
                                        </div>
                                    </div>
                                    <div class="small-box bg-lime-active">
                                        <div class="inner">
                                            <p>خصم الموظفين	</p>
                                            <h3 class="currency">{{ $obj->employees_deductions_total }}</h3>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-document-text"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="small-box bg-red">
                                        <div class="inner">
                                            <p>الرواتب المدفوعة</p>
                                            <h3 class="currency">{{ $obj->transfered_paies_total }}</h3>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-document-text"></i>
                                        </div>
                                    </div>
                                    <div class="small-box bg-red">
                                        <div class="inner">
                                            <p>سلف الموظفين	</p>
                                            <h3 class="currency">{{ $obj->employees_advances_total }}</h3>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-document-text"></i>
                                        </div>
                                    </div>
                                    <div class="small-box bg-yellow">
                                        <div class="inner">
                                            <p>رواتب غير مدفوعة</p>
                                            <h3 class="currency">{{ $obj->accepted_not_payed_paies_total }}</h3>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-document-text"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="small-box bg-red">
                                        <div class="inner">
                                            <p>المشتريات</p>
                                            <h3 class="currency">{{ $obj->purchases_total }}</h3>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-document-text"></i>
                                        </div>
                                    </div>
                                    <div class="small-box bg-red">
                                        <div class="inner">
                                            <p>المصروفات</p>
                                            <h3 class="currency">{{ $obj->expenses_total }}</h3>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-document-text"></i>
                                        </div>
                                    </div>
                                    <div class="small-box bg-primary">
                                        <div class="inner">
                                            <p>مصروفات على العملاء</p>
                                            <h3 class="currency">{{ $obj->customer_expenses_total }}</h3>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-document-text"></i>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <p>صافي الارباح والخسائر	</p>
                                    <h3 class="currency">{{ $obj->factor_balance_net }}</h3>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-document-text"></i>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </section>
    </div>

@endsection
